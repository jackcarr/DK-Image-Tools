<?php
if (isset($_POST['rename-images'])){
					if(file_exists($csv_path))
					{
						echo '<div class="group clear">';
						
						$dir = "./data/images/";



						foreach (parseCsv($csv_path) as $entry) {
						// foreach ($csv_array as $entry) {
						// $process = $_POST['use_file'];

						//
						// $process replaces the normal csv parser with the input from the file list form
						//

						// foreach ($process as $entry) {



							if (isset($entry['number']) && $entry['number'] !== 0 && isset($entry ['SEO file name']) && $entry ['SEO file name'] !== '')
							{
								$spread_file_name = $entry ['SEO file name'];
								if ($entry['number']) {
									if (is_dir($dir)) {
										if ($dh = opendir($dir)) {
											while (($image_file = readdir($dh)) !== false) {
												if ($image_file !== '.' && $image_file !== '..' && $image_file !== '.DS_Store') {

													$processed = 0;

													if (strpos($image_file, $entry['number']) !== false) {
														if (rename($dir . $image_file, './data/output/' . $entry['SEO file name'])) {
															array_push($success_log, array('image_file'=>$image_file, 'spread_file'=>$spread_file_name));
															// echo '<p><img src="./assets/ok.png" alt="Success"><strong>' . $image_file . '</strong> was  renamed to <strong>'. $entry['SEO file name'] .'</strong></p><br />';
															$processed = 1;

															// rename ($dir . $image_file, './data/output/' . $image_file);
														}
													}

													if (strpos($entry['SEO file name'],'.jpg') !== false) {
														// $entry['SEO file name'] == $entry['SEO file name'] . 'jpg';
													}


													if ((($image_file == $entry['SEO file name'] . '.jpg') || $image_file == $entry['number']) && $processed == 0) {
													// echo 'match<br />';
														if (rename($dir . $image_file, './data/output/' . $image_file)) {
															array_push($success_log, array('image_file'=>$image_file, 'spread_file'=>$spread_file_name));
															// echo '<p><img src="./assets/ok.png" alt="Success"><strong>' . $image_file . '</strong> was successfully renamed to <strong>'. $entry['SEO file name'] .'</strong></p><br />';
															$processed = 1;
														}
													}            									

													else {
														$file_array = explode('_', $image_file);
														$file_array = array_reverse($file_array);
														if (str_replace('.jpg', '', $file_array[0]) == $entry['number'] && $processed == 0) {
														// echo 'another match';
															if (rename($dir . $image_file, './data/output/' . $entry['SEO file name'])) {
																array_push($success_log, array('image_file'=>$image_file, 'spread_file'=>$spread_file_name));
																// echo '<p><img src="./assets/ok.png" alt="Success"><strong>' . $file_array[0] . '</strong> was successfully renamed to <strong>'. $entry['SEO file name'] .'</strong></p><br />';
																$processed = 1;
															}
														}
														elseif (isset ($file_array[1]) && str_replace('.jpg', '', ($file_array[1] . '-' . $file_array[0]) == $entry['number'])) {
														// echo 'another match';
															if (rename($dir . $image_file, './data/output/' . $entry['SEO file name'])) {
																array_push($success_log, array('image_file'=>$image_file, 'spread_file'=>$spread_file_name));
																// echo '<p><img src="./assets/ok.png" alt="Success"><strong>' . $file_array[1] . '</strong> was successfully renamed to <strong>'. $entry['SEO file name'] .'</strong></p><br />';	
																$processed = 1;
															}
														}
													}

													if ($processed !== 1) {

													}

													if (strpos ($entry['number'], $image_file) && $processed !== 1)
													{
														if (file_exists($dir . $image_file) && $processed !== 1)
														{
															if (rename ($dir . $image_file, 'data/output/' . $spread_file_name . '.jpg'))
															{
																array_push($success_log, array('image_file'=>$image_file, 'spread_file'=>$spread_file_name));
																// echo '<p><img src="./assets/ok.png" alt="Success"><strong>' . $image_file . '</strong> is now:<br /><strong>' . $spread_file_name . '</strong></p><br />';
																// array_push($image_count, $image_file);
																$processed = 1;
															}
															else
															{
																array_push($error_log, array($image_file,$spread_file_name));
																// echo '<p><img src="./assets/not-ok.png" alt="Error">Failed to rename <strong>' . $image_file . '</strong>.</p>';
															}
														}


													}

													else {
														$filename_array = explode('_', $image_file);
														if (in_array($entry['number'], $filename_array) && $processed !== 1) {
															if (file_exists($dir.$image_file))
															{
															// echo 'should be ok';
																echo 'spread_file_name: ' . $spread_file_name;
																if (rename ($dir . $image_file, 'data/output/' . $spread_file_name))
																{
																	array_push($success_log, array('image_file'=>$image_file, 'spread_file'=>$spread_file_name));
																	// echo '<p><img src="./assets/ok.png" alt="Success"><strong>' . $image_file . '</strong> is now:<br /><strong>' . $spread_file_name . '</strong></p><br />';
																	// array_push($image_count, $image_file);
																	$processed = 1;
																}
																else
																{
																	array_push($error_log, array($image_file,$spread_file_name));
																	// echo '<p><img src="./assets/not-ok.png" alt="Error">Failed to rename <strong>' . $image_file . '</strong>.</p>';
																}
															}
														}

													}


													if (strpos ($image_file,(str_replace('_','-',$entry['number'])) && $processed !== 1))



												// elseif (strpos(str_replace('_','-',$entry['number']), $image_file) && $processed == 0)
												// elseif (strpos($image_file,str_replace('_','-',$entry['number'])) && $processed == 0)
													{
														$entry['number'] = str_replace('_', '-', $entry['number']);
														if (file_exists($dir.$image_file) && $processed !== 1)
														{
															echo 'should be ok';
															echo 'spread_file_name: ' . $spread_file_name;
															if (rename ($dir . $image_file, 'data/output/' . $spread_file_name))
															{
																array_push($success_log, array('image_file'=>$image_file, 'spread_file'=>$spread_file_name));
																// echo '<p><img src="./assets/ok.png" alt="Success"><strong>' . $image_file . '</strong> is now:<br /><strong>' . $spread_file_name . '</strong></p><br />';
																// array_push($image_count, $image_file);
																$processed = 1;
															}
															else
															{
																array_push($error_log, array($image_file,$spread_file_name));
																// echo '<p><img src="./assets/not-ok.png" alt="Error">Failed to rename <strong>' . $image_file . '</strong>.</p>';
															}
														}

														$processed = 1;

													}
													else {

														if (preg_match ('/.*?Penguin/', $image_file) && $processed !== 1)
														{
															$image_file = explode('_', $image_file);

															$file_number = substr($image_file[4],0,-4);
															$file_number = $file_number;

															$image_file = implode('_', $image_file);

															if (trim($file_number) == trim($entry['number']))
															{	
																if (rename ($dir . $image_file, 'data/output/' . $spread_file_name))
																{
																	// echo '<p><img src="./assets/ok.png" alt="Success"><strong>' . $image_file . '</strong> is now:<br /><strong>' . $spread_file_name . '</strong></p><br />';
																	array_push($success_log, array('image_file'=>$image_file, 'spread_file'=>$spread_file_name));
																	// array_push($image_count, $image_file);
																	$processed = 1;
																}
																else
																{
																	array_push($error_log, array($image_file,$spread_file_name));
																	// echo '<p><img src="./assets/not-ok.png" alt="Error">Failed to rename <strong>' . $image_file . '</strong>.</p>';
																}
															}
															$processed = 1;
														}
													}

												}
												if ($processed == 0)
												{
													$file_number = explode('_', $image_file);
													$file_number = array_reverse($file_number);
													$file_number = $file_number[0];
													$spread_number = $entry['number'];

													if ($spread_number == $file_number) {
														if (rename ($dir . $image_file, 'data/output/' . $spread_file_name))
														{
															array_push($success_log, array('image_file'=>$image_file, 'spread_file'=>$spread_file_name));
															
															array_push($image_count, $image_file);
														}
														else
														{
															array_push($error_log, array($image_file,$spread_file_name));
															// echo '<p><img src="./assets/not-ok.png" alt="Error">Failed to rename <strong>' . $image_file . '</strong>.</p>';
														}
													}

												}

											}

											closedir($dh);

										}
									}
								}  					
							}	
						}

						$time_post = microtime(true);
						$exec_time = $time_post - $time_pre;


						if (count($image_count)>0)
						{
							echo '</p>Processed ' . count($image_count) . ' images in ' . $exec_time . ' seconds.</p>';
						}


						if (is_dir($dir)) {
							if ($dh = opendir($dir)) {
								while (($image_file = readdir($dh)) !== false) {
									if ($image_file !== '.' && $image_file !== '..' && $image_file !== '.DS_Store') {
										array_push($error_log, $image_file);
								// echo '<p class="alert-danger"><strong>' . $image_file . '</strong></p>';
									}
								}
							}
							if ($error_log)
							{	
								// echo '<div class="group clear">';
								echo 'The following images failed to process:<br /><br />';
								foreach ($error_log as $value) {
									echo '<p><img src="./assets/not-ok.png" alt="Error"><strong>' . $value . '</strong> failed to process.</p>';
								}
								// echo '</div>';
							}
							if ($success_log) {
								foreach ($success_log as $value) {
									echo '<p><img src="./assets/ok.png" alt="Success"><strong>' . $value['image_file'] . '</strong> is now <strong>' . $value['spread_file'] . '</strong></p><br />';
								}
								
							}
						}
					}
					else
					{
						echo '<h1>Error</h1>';
						echo '<p>pois.csv is not present in the data folder</p>';
					}
					echo '</div>';
				} ?>
				<!-- /File renamer -->
