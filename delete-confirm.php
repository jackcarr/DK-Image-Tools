<?php
include 'header.php';

$file = $_GET['file'];
$dir = $_GET['folder'];

?>
<form action="delete.php" method="post">
	<input type="hidden" name="file" value="<?php echo $file;?>">
	<input type="hidden" name="folder" value="<?php echo $dir;?>">
	Are you sure you wish to delete <strong><?php echo str_replace($dir . '/', '', $file);?></strong><br />
	<a href="#" onclick="$(this).closest('form').submit()" class="button image-button delete">Yes, delete the file</a>
</form>
