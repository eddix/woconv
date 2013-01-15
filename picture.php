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
$db = $mongo->pictures;
$grid = $db->getGridFS();

$video = $grid->findOne($filename);
if ($video == NULL) {
    header("HTTP/1.0 404 Not Found");
    die("Picture is not exists");
}

$length = $video->getSize();
$stream = $video->getResource();

header("Content-type: image/jpeg");
header("Content-Length: $length");

while (!feof($stream) && ($p = ftell($stream)) <= $end) {
    echo fread($stream, 8192);
    flush();
}

fclose($stream);
?>
