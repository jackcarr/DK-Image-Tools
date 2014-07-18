<?php

function parseCsv($file) {
	$handle = fopen($file, "r");
	$fields = fgetcsv($handle, 5000, ",");

	$y = 0;
	while($data = fgetcsv($handle, 5000, ",")) {
		$x = 0;
		foreach($data as $value) {
			$csv[$y][$fields[$x]] = $value;
			$x++;
		}
		$y++;
	}
	return $csv;
}


function is_in_dir($file, $directory, $recursive = true, $limit = 1000) {
	$directory = realpath($directory);
	$parent = realpath($file);
	$i = 0;
	while ($parent) {
		if ($directory == $parent) return true;
		if ($parent == dirname($parent) || !$recursive) break;
		$parent = dirname($parent);
	}
	return false;
}


function pre() {
	echo '<pre>';
}

function post () {
	echo '</pre>';
}

function output ($var) {
	pre();
	print_r($var);
	post();
}

function br () {
	echo '<br />';
}

function outputImageOverview ($file, $folder) {

	$image = exif_thumbnail ($file, $thumb_width, $thumb_height, $thumb_type);

	if (!$image) {
		$thumbnail = '<div class="no-thumbnail">';
		$thumbnail .= '<img src="./assets/sort-of-ok.png"><br />';
		$thumbnail .= '<strong>There is no thumbnail embedded in this image.</strong>';
		$thumbnail .= '</div>';
	}
	else {
		$thumbnail = "<img width='100%' src='data:image/gif;base64,".base64_encode($image)."'>";
	}


	$jpeg_header_data = get_jpeg_header_data ($file);
	$Exif_array = get_EXIF_JPEG ($jpeg_header_data);
	$XMP_array = read_XMP_array_from_text (get_XMP_text ($jpeg_header_data));
	$IRB_array = get_Photoshop_IRB ($jpeg_header_data);

	$file_info = get_photoshop_file_info ($Exif_array, $XMP_array, $IRB_array);

	$image_attr = getimagesize($file);
	$width = $image_attr[0];
	$height = $image_attr[1];
    // $new_width = 640; 
    // $new_height = floor (($height / $width) * $new_width) + 60;

	$file_title = array_reverse(explode('/', $file));

	$output = '<div class="image-panel clear block">';

	$output .= '<h3>' . $file_title[0] . '</h3>';

	$output .= '<div class="image-thumbnail-panel left">';
	$output .= '<div class="clear">' . $thumbnail . '</div>';
	
	$output .= '<br/>';

	$output .= '<table><tr><td class="descriptor';
	if ($width < 2048) {
		$output .= ' error';
	}
	$output .= '">Width:</td>';
	$output .= '<td class="content">'.$width.'px</td></tr>';

	$output .= '<tr><td class="descriptor">Height:</td>';
	$output .= '<td class="content">'.$height.'px</td></tr></table>';

	$output .= '</div>';

	$output .= '<div class="metadata-table right">';

	$output .= '<div class="image-buttons">';

	$output .= '<a href="assets/download.php?file=' . $file . '" target="_blank" class="button image-button">Download image</a>';
	$output .= '<a href="assets/download-thumbnail.php?file=' . $file . '" target="_blank" class="button image-button">Download thumbnail</a>';
	$output .= '<a href="' . $file . '" class="button image-button" target="_blank">View image</a>';
	$output .= '<a href="delete-confirm.php?file=' . $file . '&folder='.$folder.'&action=delete" class="button image-button delete">Delete image</a><br />';
	
	$output .= '</div>';

	$output .= '<table class="split-table">';
	$output .= '<tr><td class="descriptor">Title</td><td class="content">' . $file_info['title'] . '</td>';
	$output .= '<td class="descriptor">Author</td><td class="content">' . $file_info['author'] . '</td></tr>';

	$output .= '<tr><td class="descriptor">Caption</td><td class="content">' . $file_info['caption'] . '</td>';
	$output .= '<td class="descriptor">Copyright</td><td class="content">' . $file_info['copyrightnotice'] . '</td></tr>';

	$output .= '<tr><td class="descriptor">URL</td><td class="content"><a href="http://' . $file_info['ownerurl'] . '" target="_blank">' . $file_info['ownerurl'] . '</a></td>';
	$output .= '<td class="descriptor">City</td><td class="content">' . $file_info['city'] . '</td></tr>';

	$output .= '<tr><td class="descriptor">Credit</td><td class="content">' . $file_info['credit'] . '</td>';
	$output .= '<td class="descriptor">Source</td><td class="content">' . $file_info['source'] . '</td></tr>';

	$output .= '<tr><td class="descriptor">Headline</td><td class="content">' . $file_info['headline'] . '</td>';
	$output .= '<td class="descriptor">ID</td><td class="content">' . $file_info['transmissionreference'] . '</td></tr>';
	$output .= '</table>';

	$output .= '</div>';

	

	$output .= '<div class="clear"></div>';

	$output .= '</div>';

	return $output;
}


// Function to format the new IPTC text
// (thanks to Thies C. Arntzen)
function iptc_maketag($rec,$dat,$val){
	$len = strlen($val);
	if ($len < 0x8000)
		return chr(0x1c).chr($rec).chr($dat).
	chr($len >> 8).
	chr($len & 0xff).
	$val;
	else
		return chr(0x1c).chr($rec).chr($dat).
	chr(0x80).chr(0x04).
	chr(($len >> 24) & 0xff).
	chr(($len >> 16) & 0xff).
	chr(($len >> 8 ) & 0xff).
	chr(($len ) & 0xff).
	$val;
}


function in_array_r ($needle, $haystack, $strict = true) 
{
	foreach ($haystack as $needle => $value ) 
	{
		if (( $strict ? $needle === $needle : $needle == $needle ) || ( is_array ( $needle ) && in_array_r ( $needle, $needle, $strict ))) 
		{
			return true;
		}
	}

	return false;
}



function get_image_info ($iptc_data, $file) {

	$agency_name = $photographer = $collection_name = $agency_description = $copyright_notice = $photo_id = '';

	for ($i=0; $i < count($iptc_data); $i++) { 
		$iptc_data[$i]['RecData'] = preg_replace("/[^A-Za-z0-9,_\-\/ ]/", "", $iptc_data[$i]['RecData']);
                                            // echo '<pre>' . strpos($iptc_data[2]['RecData'],'Superstock') . '</pre>';
	}

	for ($i=0; $i < count($iptc_data); $i++) {
		if (strpos ($iptc_data[$i]['RecData'],'Getty') || strpos(strtolower($file), 'getty')) {
			$agency_name = 'Getty Images';
		}
		if (strpos ($iptc_data[$i]['RecData'],'Alamy') || strpos(strtolower($file), 'alamy')) {
			$agency_name = 'Alamy Images';
		}
		if (strpos ($iptc_data[$i]['RecData'],'Corbis') || strpos(strtolower($file), 'corbis')) {
			$agency_name = 'Corbis';
		}
		if (strpos ($iptc_data[$i]['RecData'],'Robert Harding') || strpos(strtolower($file), 'robert_harding')) {
			$agency_name = 'Robert Harding Picture Library';
		}
		if (strpos ($iptc_data[$i]['RecData'],'Dreamstime') || strpos(strtolower($file), 'dreamstime')) {
			$agency_name = 'Dreamstime.com';
		}
		if (strpos ($iptc_data[$i]['RecData'],'123rf') || strpos(strtolower($file), '123rf')) {
			$agency_name = '123RF.com';
		}
		if (strpos ($iptc_data[$i]['RecData'],'Superstock') || strpos(strtolower($file), 'superstock')) {
			$agency_name = 'Superstock';
		}
		if (strpos ($iptc_data[$i]['RecData'],'Glow') || strpos(strtolower($file), 'glow')) {
			$agency_name = 'Glow Images';
		}
		if (strpos ($iptc_data[$i]['RecData'],'Dorling Kindersley') || strpos(strtolower($file), 'dctm_penguin')) {
			$agency_name = 'DK';
		}
	}


	if ($agency_name == 'Getty Images') {

		for ($i=0; $i < count($iptc_data); $i++) {

			if ($iptc_data[$i]['IPTC_Type'] == '2:120') {
				$agency_description = $iptc_data[$i]['RecData'];
			}

			if ($iptc_data[$i]['IPTC_Type'] == '2:80') {
				$photographer = $iptc_data[$i]['RecData'];
			}

			if ($iptc_data[$i]['IPTC_Type'] == '2:103') {
				$photo_id = trim($iptc_data[$i]['RecData']);
			}
			if ($photo_id == '') {
				$photo_id = explode('_', $file);
				$photo_id = array_reverse($photo_id);
				$photo_id[0] = substr($photo_id[0],0,-4);
				foreach ($photo_id as $key => $value) {
					echo $key . ' ' . $value . '<br />';
				}
				if (is_numeric($photo_id[1])) {
					$photo_id = $photo_id[1] . '-' . $photo_id[0];
				}
				else {
					$photo_id = $photo_id[0];
				}

			}

			if ($photographer !== '') {
				$copyright_notice = $agency_name . '/' . $photographer;
			}
			else {
				$copyright_notice = $agency_name;
			}

		}
	}

	elseif ($agency_name == 'Alamy Images') {

		for ($i=0; $i < count($iptc_data); $i++) {

			if ($iptc_data[$i]['IPTC_Type'] == '2:116') {
				$copyright_notice = str_replace('Alamy', '', trim($iptc_data[$i]['RecData']));
				$photographer = $copyright_notice;
				$copyright_notice = 'Alamy Images/' . $photographer;
			}

			if ($iptc_data[$i]['IPTC_Type'] == '2:105') {
				$agency_description = $iptc_data[$i]['RecData'];
			}

			if ($iptc_data[$i]['IPTC_Type'] == '2:103') {
				$photo_id = trim($iptc_data[$i]['RecData']);
			}

			if ($photo_id == '') {
				$photo_id = explode('_', $file);
				$photo_id = array_reverse($photo_id);
				$photo_id = substr($photo_id[0],0,-4);
			}
		}
	}
	elseif ($agency_name == 'Corbis') {

		for ($i=0; $i < count($iptc_data); $i++) {

			if ($iptc_data[$i]['IPTC_Type'] == '2:110') {
				$copyright_notice = str_replace('Corbis', '', trim($iptc_data[$i]['RecData']));
				$photographer = $copyright_notice;
				$copyright_notice = 'Corbis/' . $photographer;
			}

			if ($iptc_data[$i]['IPTC_Type'] == '2:120') {
				$agency_description = $iptc_data[$i]['RecData'];
			}

			if ($iptc_data[$i]['IPTC_Type'] == '2:103') {
				$photo_id = trim($iptc_data[$i]['RecData']);
			}
		}
	}

	elseif ($agency_name == 'Robert Harding Picture Library') {

		for ($i=0; $i < count($iptc_data); $i++) {

			if ($iptc_data[$i]['IPTC_Type'] == '2:110') {
				$copyright_notice = str_replace('Corbis', '', trim($iptc_data[$i]['RecData']));
				$photographer = $copyright_notice;
				$copyright_notice = explode('/', $copyright_notice);
				$copyright_notice = array_reverse($copyright_notice);
				$copyright_notice = implode('/', $copyright_notice);
				$copyright_notice = str_replace('Robert Harding', 'Robert Harding Picture Library', $copyright_notice);
			}

			if ($iptc_data[$i]['IPTC_Type'] == '2:120') {
				$agency_description = $iptc_data[$i]['RecData'];
			}

			if ($iptc_data[$i]['IPTC_Type'] == '2:103') {
				$photo_id = trim($iptc_data[$i]['RecData']);
			}
		}
	}


	elseif ($agency_name == 'DK') {
		$copyright_notice = 'dkimages';
		for ($i=0; $i < count($iptc_data); $i++) {

			if ($iptc_data[$i]['IPTC_Type'] == '2:05') {
				$photo_id = $iptc_data[$i]['RecData'];
			}

			if ($iptc_data[$i]['IPTC_Type'] == '2:105') {
				$agency_description = $iptc_data[$i]['RecData'];
			}
		}
	}


	elseif ($agency_name == 'Glow Images') {
		for ($i=0; $i < count($iptc_data); $i++) {

			if ($iptc_data[$i]['IPTC_Type'] == '2:05') {
				$photo_id = trim(str_replace('Glowimages ', '', $iptc_data[$i]['RecData']));
			}

			if ($iptc_data[$i]['IPTC_Type'] == '2:120') {
				$agency_description = $iptc_data[$i]['RecData'];
			}
			if ($iptc_data[$i]['IPTC_Type'] == '2:116') {
				$copyright_notice = $iptc_data[$i]['RecData'];
				$copyright_notice = str_replace('Glowimages', 'Glow Images', $copyright_notice);
				$copyright_notice = str_replace(' / ', '/', $copyright_notice);
			}
		}
	}

	elseif ($agency_name == 'Superstock') {
		for ($i=0; $i < count($iptc_data); $i++) {

			if ($photo_id == '') {
				$photo_id = preg_replace('/[^\da-z]/i', ' ', $file);
				$photo_id = explode(' ', $photo_id);
				$photo_id = array_reverse($photo_id);
				$photo_id = $photo_id[2] . '-' . $photo_id[1];

				if (isset($_POST['scrape_superstock'])) {

					$superstock_path = 'http://www.superstock.co.uk/stock-photos-images/' . $photo_id;
					$superstock_html = file_get_contents($superstock_path);
					$title = '';
					$dom = new DOMDocument();
                    // Grab the <title> from Superstock's site, which matches the image description
					if ($dom->loadHTMLFile($superstock_path)) {
						$list = $dom->getElementsByTagName("title");
						if ($list->length > 0) {
							$agency_description = $list->item(0)->textContent;
						}
					}

                    // Grabs the photographer name from the page body where class=credit
					$classname = 'credit';
					$dom = new DOMDocument;
					$dom->loadHTML($superstock_html);
					$xpath = new DOMXPath($dom);
					$results = $xpath->query("//*[@class='" . $classname . "']");
					if ($results->length > 0) {
						$copyright_notice = $results->item(0)->nodeValue;
					}
                    // Remove 'Credit: ' from the front of the string
					$copyright_notice = substr($copyright_notice, 12);

					$copyright_notice = explode('/', $copyright_notice);
					$collection_name = trim($copyright_notice[1]);
					$copyright_notice = array_reverse($copyright_notice);
					$value = '';
					while ($i <= count($copyright_notice)-1) {
						$value .= trim($copyright_notice[$i]) . '/';
						$i++;
					}
					$copyright_notice = $value;
				}
			}
		}
	}
	$values = array (
		'agency'=>$agency_name,
		'description'=>$agency_description,
		'photo_id'=>$photo_id,
		'copyright'=>$copyright_notice
		);
	return $values;
}

function div ($class) {
	echo '<div class="' . $class . '">';
}

function divclose () {
	echo '</div>';
}



function make_thumb($src, $dest, $desired_width) {

	/* read the source image */
	$source_image = imagecreatefromjpeg($src);
	$width = imagesx($source_image);
	$height = imagesy($source_image);

	/* find the "desired height" of this thumbnail, relative to the desired width  */
	$desired_height = floor($height * ($desired_width / $width));

	/* create a new, "virtual" image */
	$virtual_image = imagecreatetruecolor($desired_width, $desired_height);

	/* copy source image at a resized size */
	imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

	/* create the physical thumbnail image to its destination */
	imagejpeg($virtual_image, $dest);
}


function csv_selector () {
	$csv_scan_dir = './data/csv';
	$output = '<select id="poi-file" name="poi-file" onChange="this.form.submit();"';

	if (!isset($_POST['poi-file'])) {
		$output .= ' class="selector-animation"';
	}
	else {
		$output .= ' class="selector"';
	}
	$output .= '>';
	$output .= '<option value="">Select a csv file or use default pois.csv</option>';
	if ($csv_handle = opendir ($csv_scan_dir)) {
		while (($file = readdir($csv_handle)) !== false) {
			if (!in_array($file, array('.', '..','.DS_Store','.BridgeSort'))) {
				$output .= '<option value="'.$file.'"';
				if (isset($_POST['poi-file'])) {
					if ($_POST['poi-file'] == $file) {
						$output .= ' selected';
					}
				}
				$output .= '>'.$file.'</option>';
			}
		}
	}
	$output .= '</select>';
	return $output;
}



function folder_selector ($glow) {
	$scan_dir = './data/';
	$output = '<select id="folder" name="folder" onChange="this.form.submit();"';

	if ($glow == 'glow') {
		if (isset($_POST['folder'])) {
		}
		else {
			$output .= ' class="glow';
		}
	}
	else {
		$output .= '';
	}

	if ($glow == 'no glow') {
		$output .= ' border1px';
	}

	$output .= '">';
	$output .= '<option value="">Select a folder</option>';
	if ($csv_handle = opendir ($scan_dir)) {
		while (($file = readdir($csv_handle)) !== false) {
			if (!in_array($file, array('.', '..','.DS_Store','.BridgeSort')) && !is_file($scan_dir.$file)) {
				$output .= '<option value="'.$file.'"';
				if (isset($_POST['folder'])) {
					if ($_POST['folder'] == $file) {
						$output .= ' selected';
					}
				}
				$output .= '>'.$file.'</option>';
			}
		}
	}
	$output .= '</select>';
	return $output;
}







/* creates a compressed zip file */
function create_zip($files = array(), $destination = '', $overwrite = false) {
    //if the zip file already exists and overwrite is false, return false
	if (file_exists($destination) && !$overwrite) { return false; }
    //vars
	$valid_files = array();
    //if files were passed in...
	if (is_array($files)) {
        //cycle through each file
		foreach($files as $file) {
            //make sure the file exists
			if (file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
    //if we have good files...
	if (count($valid_files)) {
        //create the archive
		$zip = new ZipArchive();
		if ($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
        //add the files
		foreach($valid_files as $file) {
			$zip->addFile($file,$file);
		}
        //debug
        //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;

        //close the zip -- done!
		$zip->close();

        //check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		return false;
	}
}











?>