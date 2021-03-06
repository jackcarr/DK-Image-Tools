<?php 
session_start();

ini_set("memory_limit","1000M");

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
$time_pre = microtime(true);

if (isset($_POST['zip-array'])) {
	if (isset($_POST['zip'])) {

		// Takes the list of files to zip and puts them into an array
		$zip_array = unserialize ($_POST['zip-array']);

		// Include external file to avoid header conflict
		include ('./assets/download-zip.php');
	}
}


include ('./assets/functions.php');
include ('./assets/exif/IPTC.php');
include ('./assets/exif/JPEG.php');
include ('./assets/exif/EXIF.php');
include ('./assets/exif/Photoshop_File_Info.php');
include ('./assets/exif/Toolkit_Version.php');
include ('./assets/exif/get_ps_thumb.php');


?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Image and CSV tools</title>

	<script type="text/javascript" src="js/jquery-2.1.1.min.js" ></script>
	<script type='text/javascript' src='js/select.js'></script>
	<script type='text/javascript' src='js/jquery-ui.js'></script>

	<!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/bootstrap-theme.css" rel="stylesheet">
	<link href="css/jquery-ui.structure.css" rel="stylesheet">
	<link href="css/jquery-ui.min.css" rel="stylesheet">
	

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
 <!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->


	<script type="text/javascript">

	$(document).ready(function () {

	// Provides tooltips for #nav buttons
	$("#nav button[title]").tooltip();

	// Enables selecting of all checkboxs which share a class 
	$('#selectall').click(function () {
		$('.use_file').prop('checked', this.checked);
	});

	// WHat does this do?
	$('.use_file').change(function () {
		var check = ($('.use_file').filter(":checked").length == $('.use_file').length);
		$('#selectall').prop("checked", check);
	});
});


	</script>	
</head>