<?php
if (isset($_POST['compare-file'])) {
	$errors = $count = 0;

	

	if ($csv_path) {
		$csv_array = parseCsv ($csv_path);


		foreach ($csv_array as $key => $value) {
			if (isset ($value['SEO file name'])) {
				array_push($spread_array, $value['SEO file name']);
			}
		}

						// Get array of local files
		$local_files = scandir($dir);
		echo '<div class="group clear">';

		foreach ($local_files as $key => $local_file) {
			if ($local_file !== '.' && $local_file !== '..' && $local_file !== '.DS_Store' && $local_file !== '.BridgeSort' && !is_dir($dir.$local_file)) {
								// $local_file = './data/output/' . $local_file;
				if (!in_array($local_file, $spread_array)) {
									// is unneeded
							// echo $local_file;
					array_push($unneeded_files, $local_file);
					$errors++;
				}
				else {
									// is present
					array_push($present_files, $local_file);
				}
			}
		}

						// Check is file is in the spread but not in the file system
		foreach ($spread_array as $key => $value) {
			if ($value !== '' && !file_exists($dir . $value)) {
				array_push($missing_files, $value);
				$errors++;
			}
		}

		$present_files = array_unique($present_files);
		$missing_files = array_unique($missing_files);
		$unneeded_files = array_unique($unneeded_files);

		if ($errors > 0) {
			echo  '<h3><img src="./assets/not-ok.png" alt="Error">There are errors.</h3>';
		}
		else {
			echo  '<h3><img src="./assets/ok.png" alt="Success">Everything\'s present and correct.</h3>';
		}

		if (isset($error_message)) {
			echo '<p>' . $error_message . '</p>';
		}

		foreach ($missing_files as $value) {
			echo '<img src="./assets/not-ok.png" alt="Error"><strong>' . $value . '</strong> is missing from the filesystem<br />';
		}

		foreach ($unneeded_files as $value) {
			echo '<img src="./assets/sort-of-ok.png"><strong>' . $value . '</strong> is not needed and has been moved to ./data/remove<br />';
			rename('./data/output/'.$value, './data/remove/'.$value);
		}

		foreach ($present_files as $value) {
			echo '<img src="./assets/ok.png" alt="Success"><strong>' . $value . '</strong> is present in the filesystem and spreadsheet.<br />';
		}

	}
	else {
		$message .= 'There is no csv';
	}
}
?>