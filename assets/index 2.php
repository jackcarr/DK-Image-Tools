<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CSV image renamer</title>

	<!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/bootstrap-theme.css" rel="stylesheet">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
    </head>
    <body>
     <div class="container">
      <h1></h1>

      <?php


      ini_set('display_errors',1);
		//error_reporting(-1);
      $handle = "./data/csv/pois.csv";
      $filename = $handle;



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





   ?>


   <div class="left">
     <h1>Spread files</h1>
     <?php

// function rename_if_free($newPath, $oldPath) {
//     if (file_exists($newPath)) return false;
//     else {
//         rename($oldPath, $newPath);
//         return true;
//     }
// }


     $spread_file_list = array();
     $dir = "./data/images/";
     $image_count = 0;
     foreach (parseCsv($handle) as $entry) {

      $spread_number = $entry['number'];
      $spread_number = preg_replace('/[^\da-z]/i', '_', $spread_number);
  			//$spread_number = preg_replace("", "_", $spread_number);

      if ($spread_number)
      {
       $spread_file_name = $entry ['SEO file name'];
  					// echo 'this>> ' . $spread_file_name;
  					// echo $spread_number;
       array_push($spread_file_list, $spread_number);
       // for ($i=0;$i<count($spread_file_list);i++;)
       // {
       //  echo $spread_file_list[0];
       // }
  					// echo '<br />';
  					// Here, find the corresponding local file and feed the full name string to it.
  					//renameImage ($spread_file_name,$spread_number);


  					//iterates through the local files
       if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
         while (($image_file = readdir($dh)) !== false) {
  								// makes sure we're only looking at images
          if ($image_file !== '.' && $image_file !== '..' && $image_file !== '.DS_Store') {


           $image_file = explode ('_',$image_file);

           $array_length = count($image_file);
           // echo 'array length: ' . $array_length. '<br />';
  									// echo '<br />';
  									// echo 'first part = ' . $image_file [$array_length-2];
  									// echo '<br />';
  									// echo 'last part = ' . $image_file [$array_length-1];
  									// echo '<br />';

  					//checks the second from last part of the file name is numeric and hence part of a serial number
           if ($array_length != 1){
             if (is_numeric($image_file [$array_length-2]) && defined($image_file [$array_length-2]))
             {
              $file_number = $image_file [$array_length-2] . "-" . $image_file [$array_length-1];
            }
  									// if not, just use the final part of the name
            else $file_number = $image_file [$array_length-1];
          }
          else
          {
            $file_number = $image_file [0];
          }
		  									// echo $image_file [$array_length-2];
		  									// echo '<br/ >';
		  									// echo 'file_number == ' . $file_number;
		  									// echo '<br/ >';echo '<br/ >';

          $file_number = str_replace('.jpg', '', $file_number);
          // echo 'file_number: ' . $file_number . '<br />';

  									// compares the spreadsheet string to the actual file name
          if ($file_number == $spread_number)
          {
            // echo 'file number: '. $file_number;
            // echo '<br />';
            // echo 'spread number: ' . $spread_number;
            // echo '<br />';
            // echo 'spread_file_name: ' . $spread_file_name;
            // echo '<br />';
            // echo '<br />';
            // echo 'found it <br />';
            // echo 'spread number > ' . $spread_number;
            // echo '<br />';
            // echo 'file number > ' . $file_number;
            // echo '<br />';
            // echo 'image count: ' . $image_count;
            // echo '<br />';

            $image_file = implode('_', $image_file);

            $new_name = $entry['SEO file name'];
            $target_file = $image_file;

            echo 'spread name: ' . $entry['SEO file name'] . '<br />';
            echo 'target image: ' . $image_file . '<br />';
            echo 'target path: ' . $dir . $image_file;






            if (file_exists($dir.$target_file))
            {
              echo '<br />' . $target_file . '<br />';
              // if (rename ($dir.$target_file, $new_name))
              // {
              // echo '<br />renamed' . '<br />';;
              // }
              // else
              // {
              //   '<br />not renamed' . '<br />';;
              // }
            }




            echo '<br />';
            echo '<br />';
  			
										// include in final success/failure report
            $image_count++;
          }
  									// array_push($file_file_list, $file_number);
        }
      }
      closedir($dh);
    }
  }  					

}	
  				//print_r ($spread_file_list);

}
?>
</div>

<div class="right">
 <h1>Local files</h1>
 <?php
  			// function renameImage($spread_file_name, $spread_number) {
  			// 	//Put all the crap below into this so the function can take the end-file name (array) and image number (number), find the file and rename it accordingly.
  			// 	$dir = "./data/images/";
  			// 	if (is_dir($dir)) {
  			// 		if ($dh = opendir($dir)) {
  			// 			while (($image_file = readdir($dh)) !== false) {
  			// 				if ($image_file !== '.' && $image_file !== '..' && $image_file !== '.DS_Store') {

  			// 		//echo $image_file;
  			// 					$image_file = explode ('_',$image_file);
  			// 		// echo "filename: .".$image_file."<br />";
  			// 		//echo '<br / >count ' . count($image_file) . '<br />';
  			// 					$array_length = count($image_file);
  			// 					echo 'first part = ' . $image_file [$array_length-2];
  			// 					echo '<br />';
  			// 					echo 'last part = ' . $image_file [$array_length-1];


  			// 					if (is_numeric($image_file [$array_length-2]))
  			// 					{
  			// 						$file_number = $image_file [$array_length-2] . "-" . $image_file [$array_length-1];
  			// 					}
  			// 					else $file_number = $image_file [$array_length-1];
  			// 		// echo $image_file [$array_length-2];
  			// 					echo '<br/ >';
  			// 					echo 'file_number == ' . $file_number;
  			// 					echo '<br/ >';echo '<br/ >';
  			// 					$file_number = str_replace('.jpg', '', $file_number);
  			// 					array_push($file_file_list, $file_number);
  			// 				}
  			// 			}
  			// 			closedir($dh);
  			// 		}
  			// 	}
  			// }





 ?>




</div>


<br /><br />

<div class="clear"<>
 Script to do:

 php takes csv into array
 find number from column

 run similar split/regex against all file names
 for each item in array, find local file
 rename according to original filename

 test on existing spread with source files
</div>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>












Use this to remove the first 5 characters of files for testing
for f in *; do mv "$f" "${f:5}"; done
