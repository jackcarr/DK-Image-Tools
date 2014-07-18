<?php
if (isset($zip_array)) {

	$zip = new ZipArchive;
	$zip_file = './data/output/' . date('D-M-Y',time()) . '-' . time() . '.zip';

	if ($zip->open($zip_file, ZipArchive::CREATE)) {
		foreach ($zip_array as $key => $value) {
			$zip->addFile($value, str_replace('./data/output/', '', $value));
		}

	$zip->close();
	
	$message .= 'ZIP Creation successful!';
	} 
	else {
		$message .= 'ZIP creation failed';
	}
}
?>