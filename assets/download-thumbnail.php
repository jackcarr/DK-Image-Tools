<?php

chdir('../');
$file = $_GET['file'];


// make sure it's a file before doing anything
if(is_file($file)) {
	
	// Set a maximum height and width
	$width = 640;
	$height = 640;

	// Content type
	header('Content-Type: image/jpeg');

	// Get new dimensions
	list($width_orig, $height_orig) = getimagesize($file);

	$ratio_orig = $width_orig/$height_orig;

	if ($width/$height > $ratio_orig) {
		$width = $height*$ratio_orig;
	} else {
		$height = $width/$ratio_orig;
	}

	// Resample
	$image_p = imagecreatetruecolor($width, $height);
	$image = imagecreatefromjpeg($file);
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

	// Output
	imagejpeg($image_p, null, 100);
	}
?>