<!-- Folder browser -->
<div class="group clear">
<h3>Browsing: <strong><?php echo realpath ($dir);?></strong>, or <?php echo folder_selector('no glow'); ?></h3>
<input type="hidden" name="browse-folder" value="true">
<?php

if ($handle = opendir($dir)) {

			// echo '<select name="dir" onChange="this.form.submit();">';
	/* This is the correct way to loop over the directory. */
	while (false !== ($entry = readdir($handle))) {
		if (!in_array($entry, array('.','..','.DS_Store','.BridgeSort')) && !is_dir($dir . '/' . $entry)) {
			echo outputImageOverview ($dir . '/' . $entry, $dir);
			array_push($zip_array, $dir . '/' . $entry);
		}
	}	
}

if ($zip_array == '') {
	echo '<input type="hidden" id="zip-array" name="zip-array" value="' . htmlentities(serialize($zip_array)) . '">';
	echo '<p>There are no images in <strong>' . realpath ($dir) . '</strong>.<br />Please add some or select a different folder.</p>';
}
else {
	echo '<button id="zip" name="zip" class="full-width">Download all images as ZIP file.</button>';
}

closedir($handle);
divclose();

?>


	<!-- /Folder browser -->