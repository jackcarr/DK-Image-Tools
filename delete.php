<?php

$file = $_POST['file'];
$dir = $_POST['folder'];
// echo $file;
// echo '<br />';
// echo $folder;
	if (file_exists($file) && unlink($file)) {
		// echo 'ok';
		header('Location: index.php?file='.$file.'&folder='.$dir.'&message=Success');
	}
	else {
		// echo 'not ok';
		header('Location: index.php?file='.$file.'&folder='.$dir.'&message=Failed');
	}
	die();
	
	?>