<?php
/* picture.php
 *
 * Retrieving picture from gridfs base on mongodb
 *
 * Author: chenxin <chenxin@smapp.hk>
 * Date: 2012-12-16
 *
 * */

$filename = $_GET['f'] or die("Cannot get filename");

$mongo = new MongoClient();
$db = $mongo->pictures;
$grid = $db->getGridFS();

$picture = $grid->findOne($filename);
if ($picture == NULL) {
    header("HTTP/1.0 404 Not Found");
    die("Picture is not exists");
}

$length = $picture->getSize();

header("Content-type: " . $picture->file['filetype']);
header("Content-Length: $length");
echo $picture->getBytes();

?>
