<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

// chdir('../');

// $folder . $file = str_replace('./data/output/', '', $_GET['file']);
$file = $_GET['file'];
$folder = $_GET['folder'];

// echo $file;
print_r($_REQUEST);

print_r ($_GET);
// echo 'this';

// echo 'file: ' . $file;
// echo '<br />';
// echo 'fodler: ' . $folder;



// if ($_GET['cookie'] == $_SERVER['HTTP_COOKIE']) {
	if (file_exists($folder . $file) && unlink($folder . $file)) {

		if (!empty($_SERVER['HTTP_REFERER'])) {
			header('Location: index.php?folder=' . $folder . '&message=Success');
		}
		else {
			header('Location: index.php?message=Failed');
		}
		die();
	}
// }
?>