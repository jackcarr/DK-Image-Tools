<!-- Folder browser -->

<?php
// if (!isset($folder)) {
if (isset($_POST['folder']) && $_POST['folder'] !== '') {
	$folder = './data/' . $_POST['folder'];
}
else {
	$folder = './data/output/';
}

?>
<div class="group clear">
<h3>Browsing: <strong><?php echo realpath ($folder);?></strong></h3>
<input type="hidden" name="browse-folder" value="true">
<?php

if ($handle = opendir($folder)) {
	
			// echo '<select name="dir" onChange="this.form.submit();">';
	/* This is the correct way to loop over the directory. */
	while (false !== ($entry = readdir($handle))) {
		if (!in_array($entry, array('.','..','.DS_Store','.BridgeSort')) && !is_dir($folder . '/' . $entry)) {
			echo outputImageOverview ($folder . '/' . $entry, $folder);
			array_push($zip_array, $folder . '/' . $entry);
		}
	}	
}

if ($zip_array !== '') {
	echo '<input type="hidden" id="zip-array" name="zip-array" value="' . htmlentities(serialize($zip_array)) . '">';
	echo '<p>There are no images in <strong>' . realpath ($folder) . '</strong>.<br />Please add some or select a different folder.</p>';
}
else {
	echo '<button id="zip" name="zip" class="full-width">Download all images as ZIP file.</button>';
}



closedir($handle);
divclose();
	// }



	//
	// Prints accumulated info messages (not errors) into div with class=message
	//






?>


	<!-- /Folder browser -->