<?php
if (isset($_POST['compare-file'])) {
	if ($csv_path) {
		$csv = parseCsv ($csv_path);

		foreach ($csv as $key => $value) {
			if (isset ($value['SEO file name'])) {
				array_push($spread_array, $value['SEO file name']);
			}
		}

						// Get array of local files
		$local_files = scandir($dir);
		$output .= '<div class="group clear">';

		foreach ($local_files as $key => $local_file) {
			if ($local_file !== '.' && $local_file !== '..' && $local_file !== '.DS_Store' && $local_file !== '.BridgeSort' && !is_dir($dir.$local_file)) {
				if (!in_array($local_file, $spread_array)) {
					array_push($files_to_remove, $local_file);
					$errors++;
				}
				else {
									// is present
					array_push($success_log, $local_file);
				}
			}
		}

						// Check is file is in the spread but not in the file system
		foreach ($spread_array as $key => $value) {
			if ($value !== '' && !file_exists($dir . $value)) {
				array_push($error_log, $value);
				$errors++;
			}
		}

		$success_log = array_unique($success_log);
		$error_log = array_unique($error_log);
		$files_to_remove = array_unique($files_to_remove);

		if ($errors > 0) {
			$output .=  '<h3><img src="./assets/not-ok.png" alt="Error">There are errors.</h3>';
		}
		else {
			$output .=  '<h3><img src="./assets/ok.png" alt="Success">Everything\'s present and correct.</h3>';
		}

		if (isset($error_message)) {
			$output .= '<p>' . $error_message . '</p>';
		}

		foreach ($error_log as $value) {
			$output .= '<img src="./assets/not-ok.png" alt="Error"><strong>' . $value . '</strong> is missing from the filesystem<br />';
		}

		foreach ($files_to_remove as $value) {
			$output .= '<img src="./assets/sort-of-ok.png"><strong>' . $value . '</strong> is not needed and has been moved to ./data/remove<br />';
			rename('./data/output/'.$value, './data/remove/'.$value);
		}

		foreach ($success_log as $value) {
			$output .= '<img src="./assets/ok.png" alt="Success"><strong>' . $value . '</strong> is present in the filesystem and spreadsheet.<br />';
		}

	}
	else {
		$message .= 'There is no csv';
	}
}
?>