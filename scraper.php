		<!-- Reads, analyses and conform EXIF data -->


		<form action="index.php" method="post">
			

			<input type="hidden" name="go-exif">
			
			<!--
			Use this once web-scraping capability is developed

			<input type="checkbox" id="select-all">&nbsp;<label for="select-all">Select all</label><br />
			<input type="checkbox" name="scrape_superstock" id="scrape_superstock">&nbsp;<label for="scrape_superstock">Scrape SuperStock</label><br />
			<input type="checkbox" name="scrape_getty" id="scrape_getty">&nbsp;<label for="scrape_getty">Scrape Getty</label><br />
			<input type="checkbox" name="scrape_alamy" id="scrape_alamy">&nbsp;<label for="scrape_alamy">Scrape Alamy</label><br />
			<input type="checkbox" name="scrape_123rf" id="scrape_123rf">&nbsp;<label for="scrape_123rf">Scrape 123rf</label><br />
			<input type="checkbox" name="scrape_corbis" id="scrape_corbis">&nbsp;<label for="scrape_corbis">Scrape Corbis</label><br />
			<input type="checkbox" name="scrape_glow" id="scrape_glow">&nbsp;<label for="scrape_glow">Scrape Glow</label><br />
			<input type="checkbox" name="scrape_robert_harding" id="scrape_robert_harding">&nbsp;<label for="scrape_robert_harding">Scrape Robert Harding</label><br />
			<input type="checkbox" name="scrape_dreamstime" id="scrape_dreamstime">&nbsp;<label for="scrape_dreamstime">Scrape Dreamstime</label>
		-->

		<?php
		if (isset($_POST['go'])) {
				// include ('./assets/functions.php');
				// include ('./assets/exif/IPTC.php');
				// include ('./assets/exif/JPEG.php');
				// include ('./assets/exif/EXIF.php');
				// include ('./assets/exif/Photoshop_File_Info.php');
				// include './assets/exif/Toolkit_Version.php';

			$dir = "./data/output/";
			$csv_path = "./data/csv/pois.csv";
			$spread = parseCsv($csv_path);
			
			$tally = 0;

			br();
			foreach ($_POST as $key => $value) {
					// echo $key  . ' -> ' . $value;
					// br();
			}

            // go through each file in folder 
			if (is_dir($dir)) {
				if ($dh = opendir($dir)) {
					while (($file = readdir($dh)) !== false) {
						if ($file !== '.' && $file !== '..' && $file !== '.DS_Store' && $file !== '.BridgeSort' && $file !== '' && $file !== '.jpg') {
							$file = str_replace('.jpg', '', $file);
							foreach ($spread as $key => $value) {
								if ($value['SEO file name'] == $file) {
									// Make array of files which also exist in the spreadsheet. Each image is an array containing filename, agency and photo ID
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


					$jpeg_header = get_jpeg_header_data ($dir . $file['filename'] . '.jpg');
					$irb_data = get_Photoshop_IRB ($jpeg_header);
					$iptc_data = get_Photoshop_IPTC ($irb_data);

					for ($i=0; $i < count($iptc_data); $i++) { 
						$iptc_data[$i]['RecData'] = preg_replace("/[^A-Za-z0-9,_\-\/ ]/", "", $iptc_data[$i]['RecData']);
					}

						// if (isset($))

					$new_ps_file_info_array = array (
						'title'                 => $file['number'],
						'author'                => $file['photographer'],
						'creator'               => $file['photographer'],
						'authorsposition'       => "",
						'caption'               => $file['caption'],
						'description'           => $file['caption'],
						'captionwriter'         => "",
						'jobname'               => "",
						'copyrightstatus'       => "Copyrighted Work",
						'copyrightnotice'       => $file['agency'],
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
						'headline'              => $file['description'],
						'instructions'          => "",
						'transmissionreference' => "",
						'urgency'               => "");



						/*
						Filename 			->	Filename (File Properties)
						Asset Name/Number 	->	Title (IPTC Core)
						Source 				->	Copyright Notice (IPTC Core)
						Photographer 		->	Creator (IPTC Core)
						Agency Description 	->	Headline (IPTC Core)
							Caption 			->	Description (IPTC Core) – optional
						*/



							$file = $dir . $file ['filename'] . '.jpg';

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
							$new_ps_file_info_array[ 'supplementalcategories' ] = explode( "\n", trim( $new_ps_file_info_array[ 'supplementalcategories' ] ) );


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
									
									$image_attr = getimagesize($file);
									$width = $image_attr[0];
									$height = $image_attr[1];
									$new_width = 640; 
									$new_height = floor (($height / $width) * $new_width) + 60;
									$target_file_name = str_replace($dir, '', $file);

									echo '<div class="clear image-panel" style="height:'. $new_height .'px">';

									echo '<h3>' . $target_file_name . '</h3>';


									echo '<div class="left image-thumbnail-panel">';
									echo '<img src=" ' . $file . '" width="' . $new_width . '"/>';
									echo '</div>';


									echo '<div class="image-info-panel">';

									echo '<a href="' . $file . '" download="' . $target_file_name . '" class="button">Download image</a>';

									echo '<h4>New metadata</h4>';
									echo '<table class="image-info-panel-table">';



									echo '<tr><td class="descriptor">New title:</td>';
									echo '<td class="content';
									if ($new_ps_file_info_array['title'] == '') {
										echo ' empty-field';
									}
									echo '">'.$new_ps_file_info_array['title'].'</td></tr>';



									echo '<tr><td class="descriptor">New author:</td>';
									echo '<td class="content';
									if ($new_ps_file_info_array['author'] == '') {
										echo ' empty-field';
									}
									echo '">'.$new_ps_file_info_array['author'].'</td></tr>';



									echo '<tr><td class="descriptor">New caption:</td>';
									echo '<td class="content';
									if ($new_ps_file_info_array['caption'] == '') {
										echo ' empty-field';
									}
									echo '">'.$new_ps_file_info_array['caption'].'</td></tr>';



									echo '<tr><td class="descriptor">New copyright notice:</td>';
									echo '<td class="content';
									if ($new_ps_file_info_array['copyrightnotice'] == '') {
										echo ' empty-field';
									}
									echo '">&#169;'.$new_ps_file_info_array['copyrightnotice'].'</td></tr>';



									echo '<tr><td class="descriptor">Width:</td>';
									echo '<td class="content">'.$width.'px</td></tr>';

									echo '<tr><td class="descriptor">Height:</td>';
									echo '<td class="content">'.$height.'px</td></tr>';



									echo '</table>';
									echo '</div>';
									echo '</div>';
									echo '<div class="clear">&nbsp;</div>';


									
								}
							}
						}
					}
					
				}

				if (isset($processed_files) && is_array($processed_files)) {

					foreach ($processed_files as $value) {
						pre();
						print_r($value);
						post();
						echo  '<br /><img src="./assets/ok.png">&nbsp;Metadata applied to <strong>' . $value . '</strong><br />';
					}
				}
            // for each in array, find agency name
            // if it's an agency which can be scraped, fetch and save the html 
            // pull out the metadata and put it into array 
            // apply metadata array to file
            // generate output for files which couldn't be processed 

				?>
				<br />
			</form>
			<!-- /Reads, analyses and conform EXIF data -->











