<?php
$thumbnail = exif_thumbnail($_REQUEST['image'],$width, $height, $type);
// header('Content-type: ' . image_type_to_mime_type($type));
echo($thumbnail);
?>