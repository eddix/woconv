<?php
/* video.php
 *
 * Retrieving video from gridfs base on mongodb
 *
 * Author: chenxin <chenxin@smapp.hk>
 * Date: 2012-12-16
 *
 * */

$filename = $_GET['f'] or die("Cannot get filename");

$mongo = new MongoClient();
$db = $mongo->videos;
$grid = $db->getGridFS();

$video = $grid->findOne($filename);

if ($video == NULL) {
    header("HTTP/1.0 404 Not Found");
    die("Video is not exists");
}

$size = $video->getSize();
$length = $size;
$start = 0;
$end = $size - 1;

$stream = $video->getResource();

header("Accept-Ranges: 0-$length");

if(isset($_SERVER['HTTP_RANGE'])) {
    $c_start = $start;
    $c_end = $end;
    list(, $range) = explode("=", $_SERVER['HTTP_RANGE'], 2);
    // Make sure the client hasn't sent us a multibyte range
    if (strpos($range, ',') !== false) {
        header('HTTP/1.1 416 Requested Range Not Satisfiable');
        header("Content-Range: bytes $start-$end/$size");
        exit;
    }
    if ($range[0] == '-') {
        $c_start = $size - substr($range, 1);
    } else {
        $range = explode('-', $range);
        $c_start = $range[0];
        $c_end   = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $size;
    }
    /* Check the range and make sure it's treated according to the specs.
     * http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html
     **/
    // End bytes can not be larger than $end.
    $c_end = ($c_end > $end) ? $end : $c_end;

    if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {
        header('HTTP/1.1 416 Requested Range Not Satisfiable');
        header("Content-Range: bytes $start-$end/$size");
        exit;
    }
    $start = $c_start;
    $end   = $c_end;
    $length = $end - $start + 1;
    fseek($stream, $start);
    header("HTTP/1.1 206 Partial Content");
    header("Content-Range: bytes $start-$end/$size");
    header("Content-Length: $length");
}else{
    header("Content-Length: $length");
}

header("Content-type: video/mp4");
while (!feof($stream) && ($p = ftell($stream)) <= $end) {
    echo fread($stream, 8192);
    flush();
}

fclose($stream);
?>
