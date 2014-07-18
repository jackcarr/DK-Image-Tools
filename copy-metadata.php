<?php
if (isset($_POST['copy-metadata'])) {

						$dir = "./data/output/";
						// $csv_path = "./data/csv/pois.csv";
						$spread = parseCsv($csv_path);
						
						$tally = 0;


	           			 	// go through each file in folder 
						if (is_dir($dir)) {
							if ($dh = opendir($dir)) {
								while (($file = readdir($dh)) !== false) {
									if ($file !== '.' && $file !== '..' && $file !== '.DS_Store' && $file !== '.BridgeSort' && $file !== '' && $file !== '.jpg') {
									// $file = str_replace('.jpg', '', $file);
										foreach ($spread as $key => $value) {
											if (isset($value['SEO file name']) && $value['SEO file name'] == $file) {
									// Make array of files which also exist in the spreadsheet. Each image is an array containing filename, agency, photo ID, credit, photographer and caption.
												array_push ($files, array(
													'filename' => $file, 
													'description' => $value['agency description'], 
													'photographer' => $value['photographer'], 
													'agency' => $value['source'], 
													'number' => $value['number'],
													'credit' => $value['credit'],
													'caption' => $value['caption']
													));
											}
										}
									}
								}
							}
						}



				// Preliminary structure for scraping metadata from web
						foreach ($files as $file) {
							if (is_array($file) && (count(array_flip(array_flip($file))) !== 1)) {

								if (isset($_POST['scrape_superstock']) && $_POST['scrape_superstock'] == 'on') {
						// uses format:
						// http://www.superstock.co.uk/preview.asp?image=1815R-103683#sthash.J6QX355v.dpbs
						// for urls.
						// Should be EASY
								}

								if (isset($_POST['scrape_getty']) && $_POST['scrape_getty'] == 'on') {
						// uses format:
						// http://www.gettyimages.co.uk/Search/Search.aspx?contractUrl=2&language=en-GB&assetType=image&p=IMAGE ID GOES HERE
						// Should be EASY
								}

								if (isset($_POST['scrape_alamy']) && $_POST['scrape_alamy'] == 'on') {
						// Uses format:
						//http://www.alamy.com/search/Imageresults.aspx?CreativeOn=1&adv=1&ag=0&all=1&creative=&et=0x000000000000000000000&vp=0&loc=0&qt=IMAGE ID GOES HERE
						//&qn=&archive=1&dtfr=&dtto=&hc=&selectdate=&size=0xFF&aqt=&epqt=&oqt=&nqt=&gtype=0
						// Should be FAIRLY EASY, though look out for scraping among multiple results
								}

								if (isset($_POST['scrape_123rf']) && $_POST['scrape_123rf'] == 'on') {
					// Uses format:
					//http://www.123rf.com/search.php?word=PHOTO ID GOES HERE&imgtype=0&t_word=&t_lang=en&oriSearch=
					//but could be tricky scraping photographer. Everything else will be accessed via DOM
								}


								if (isset($_POST['scrape_corbis']) && $_POST['scrape_corbis'] == 'on') {
						// uses format:
						// http://www.corbisimages.com/stock-photo/rights-managed/42-23825229/
						// Should be VERY EASY
								}

								if (isset($_POST['scrape_glow']) && $_POST['scrape_glow'] == 'on') {
						// Uses unique keys to generate file urls. Where's it from? DIFFICULT
								}

								if (isset($_POST['scrape_robert_harding']) && $_POST['scrape_robert_harding'] == 'on') {
					// FIND OUT
								}

								if (isset($_POST['scrape_dreamstime']) && $_POST['scrape_dreamstime'] == 'on') {
					//	FIND OUT
								}


								$jpeg_header = get_jpeg_header_data ($dir . $file['filename']);
								$irb_data = get_Photoshop_IRB ($jpeg_header);
								$iptc_data = get_Photoshop_IPTC ($irb_data);

								for ($i=0; $i < count($iptc_data); $i++) { 
									$iptc_data[$i]['RecData'] = preg_replace("/[^A-Za-z0-9,_\-\/ ]/", "", $iptc_data[$i]['RecData']);
								}



								if (empty($file['number'])) {
									$file_number = '00000000';
								}
								else {
									$file_number = $file['number'];
								}

								if ($file['photographer'] == '') {
									$photographer = 'XX';
								}
								else {
									$photographer = $file['photographer'];
								}




								if ($file['caption'] == '') {
									$caption = 'XX';
								}
								else {
									$caption = $file['caption'];
								}

								if (empty($file['agency'])) {
									$agency = 'XX';
								}
								else {
									$agency = $file['agency'];
								}



								if (empty($file['credit'])) {
									$credit = 'XX';
								}
								else {
									$credit = $file['credit'];
								}


								if (empty($file['description'])) {
									$description = 'XX';
								}
								else {
									$description = $file['description'];
								}





								$new_ps_file_info_array = array (
									'title'                 => $file_number,
									'author'                => $photographer,
									'creator'               => $photographer,
									'authorsposition'       => "",
									'caption'               => $caption,
									'captionwriter'         => "",
									'jobname'               => "",
									'copyrightstatus'       => "Copyrighted Work",
									'copyrightnotice'       => $credit,
									'ownerurl'              => "",
									'keywords'              => "",
									'category'              => "",
									'supplementalcategories'=> "",
									'date'                  => date('Y-m-d'),
									'city'                  => "",
									'state'                 => "",
									'country'               => "",
									'credit'                => "",
									'source'                => "",
									'headline'              => $description,
									'instructions'          => "",
									'transmissionreference' => "",
									'urgency'               => "",
									'description'			=> $description
									);

								/*
								Filename 			->	Filename (File Properties)
								Asset Name/Number 	->	Title (IPTC Core)
								Source 				->	Copyright Notice (IPTC Core)
								Photographer 		->	Creator (IPTC Core)
								Agency Description 	->	Headline (IPTC Core)
								Caption 			->	Description (IPTC Core) – optional
								*/


								$file = $dir . $file ['filename'];

							// Some characters are escaped with backslashes in posted variable
        		            // Cycle through each of the HTML Posted variables, and strip out the slashes
								foreach ($new_ps_file_info_array as $var_key => $var_val)
								{
									if (!is_array($var_val)) {
										$new_ps_file_info_array [$var_key] = stripslashes ($var_val);
									}
								}

							// Keywords should be an array - explode it on newline boundarys
								$new_ps_file_info_array[ 'keywords' ] = explode( "\n", trim( $new_ps_file_info_array[ 'keywords' ] ) );

                    		// Supplemental Categories should be an array - explode it on newline boundarys
								$new_ps_file_info_array ['supplementalcategories'] = explode ("\n", trim ($new_ps_file_info_array ['supplementalcategories']));


								$jpeg_header_data = get_jpeg_header_data ($file);

		                    // Retreive the EXIF, XMP and Photoshop IRB information from
		                    // the existing file, so that it can be updated

								$Exif_array = get_EXIF_JPEG ($file);
								$XMP_array = read_XMP_array_from_text (get_XMP_text ($jpeg_header_data));
								$IRB_array = get_Photoshop_IRB ($jpeg_header_data);

							// Update the JPEG header information with the new Photoshop File Info
								$jpeg_header_data = put_photoshop_file_info ($jpeg_header_data, $new_ps_file_info_array, $Exif_array, $XMP_array, $IRB_array);

								if (file_exists($file)) {
									if (put_jpeg_header_data ($file, $file, $jpeg_header_data)) {
										echo outputImageOverview ($file, $dir);
									}
								}
							}
						}

					}

					if (isset($processed_files) && is_array($processed_files)) {

						foreach ($processed_files as $value) {
							echo  '<br /><img src="./assets/ok.png" alt="Success">&nbsp;Metadata applied to <strong>' . $value . '</strong><br />';
						}
					}
				?>