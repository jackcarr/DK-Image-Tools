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

	<script type="text/javascript" src="./js/jquery-2.1.1.min.js" ></script>
	<script type='text/javascript' src='./js/select.js'></script>
	<script type='text/javascript' src='./js/jquery-ui.js'></script>

	<!-- Bootstrap -->
	<link href="./css/bootstrap.min.css" rel="stylesheet">
	<link href="./css/bootstrap-theme.css" rel="stylesheet">
	<link href="./css/jquery-ui.structure.css" rel="stylesheet">
	<link href="./css/jquery-ui.min.css" rel="stylesheet">
	

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
 <!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->


	<script type="text/javascript">

	$(document).ready(function () {

	$("#nav button[title]").tooltip(
	{
		tooltipClass: "custom-tooltip",
		position: {
			my: "top center",
			at: "center top+75"
		}
	});


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
<body>

	<form action="index.php" method="post" id="tools" class="tools">

		<div id="nav">
			<div id="logo">
				<a href="">
					<img src="./assets/dk-image-tools.png" alt="DK Image Tools">
				</a>
			</div>
			<div id="nav-buttons">
				<!-- <input type="hidden" name="compare-files"> -->
				<button name="home" title="Browse the chosen folder">Home</button>
				<button name="compare-file" title="Compares chosen CSV file to chosen folder">Compare files</button>
				<button name="folder-sort" title="Moves images into FindOutMore, Gallery, etc.">Sort into folders</button>
				<button name="copy-metadata" title="Embeds metadata from CSV file into images" value="copy-metadata">Copy metadata</button>
				<button name="rename-images" title="Rename images in chosen folder based on file name in CSV file">Rename images</button>
				<button name="asset-numbers" title="Generates string of DK asset numbers for use in Asset Library">Generate string</button>


				<div class="white-box">
					<?php
					echo csv_selector();
					?>
				</div>

				<div class="white-box">
					<?php
					$glow = 'glow';
					echo folder_selector('glow');
					?>
				</div>
			</div>
		</div>

		<div class="container">
		</form>