<?php 

$output = $message = $images_folder = $csv_path = $dir = $last_image = $processed = '';

$present_files = 
$missing_files = 
$unneeded_files = 
$spread_array = 
$zip_array = 
$error_log = 
$success_log = 
$spread_file_list = 
$image_count = 
$files = 
$processed_files = 
$success_log = 
$problem_files = 
array();



$un_file_array = array('.', '..','.DS_Store','.BridgeSort');

if (isset($_POST['folder-path'])) {
	$dir = $_POST['folder-path'];
}
else {
	$dir = "./data/output/";
}

					//
					// Check if a csv has been selected
					// If it hasn't, use pois.csv, if it exists
					//

if (isset($_POST['poi-file']) && $_POST['poi-file'] !== '') {
	$csv_path = './data/csv/' . $_POST['poi-file'];

	$message .= '<div class="clear item">';
	$message .= '<img src="./assets/ok.png" alt="Success"> Using ' . $_POST['poi-file'];
	$message .= '</div>';
}
else {
	if (file_exists("./data/csv/pois.csv")) {
		$csv_path = "./data/csv/pois.csv";

		$message .= '<div class="clear item">';
		$message .= '<img src="./assets/ok.png" alt="Success"> Using <strong>pois.csv</strong>';
		$message .= '</div>';

	}
	else {
		$message .= '<div class="clear item">';
		$message .= '<img src="./assets/not-ok.png" alt="Error"> <strong>Error:</strong> Please place <strong>pois.csv</strong> file in <strong>./data/csv/</strong> or select a csv file from the top right of the navigation menu.';
		$message .= '</div>';
		$csv_path = '';
	}

}

if ($csv_path !== '') {
	$csv_array = parseCsv($csv_path);
}


					//
					// Check that there are images in ./data/images
					//

$dir = './data/images/';
$images_folder = scandir($dir);
$count = 0;
foreach ($images_folder as $key => $value) {
	if ($value !=='.' && $value !== '..' && $value !=='.DS_Store' && $value !=='.BridgeSort' && is_file($dir.$value)) {
		$count++;
	}
}
if ($count == 0) {
	$message .= '<div class="clear item">';
	$message .= '<img src="./assets/not-ok.png" alt="Error"> <strong>Error:</strong> There are no images in <strong>'.$dir.'</strong>';
	$message .= '</div>';
}
else {
	$message .= '<div class="clear item">';
	$message .= '<img src="./assets/ok.png" alt="Success"> There are images present in <strong>'.$dir.'</strong>';
	$message .= '</div>';
}


					//
					// Check that there are images in ./data/output
					//

$dir = './data/output/';
$images_folder = scandir($dir);

$count = 0;
foreach ($images_folder as $key => $value) {
	if ($value !=='.' && $value !== '..' && $value !=='.DS_Store' && $value !=='.BridgeSort' && is_file($dir.$value)) {
		$count++;
	}
}

if ($count == 0) {
	$message .= '<div class="clear">';
	$message .= '<img src="./assets/not-ok.png" alt="Error"> <strong>Error:</strong> There are no images in <strong>'.$dir.'</strong>';
	$message .= '</div>';
}
else {
	$message .= '<div class="clear">';
	$message .= '<img src="./assets/ok.png" alt="Success"> There are ' . $count .' images present in <strong>'.$dir.'</strong>';
	$message .= '</div>';
}
?>

<form action="index.php" method="post" id="tools" class="tools">