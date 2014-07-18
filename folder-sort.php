<?php
if (isset($_POST['folder-sort'])) {

						$dir = "./data/output/";
						$csv_array = parseCsv($csv_path);
						

						foreach ($csv_array as $file) {

							if (isset($file['agency'])) {
								$folder = $file['agency'];
							}

							if (!is_dir($dir . $folder)) {
								mkdir ($dir . $folder);
							}

							if (file_exists ($dir . $file['SEO file name']) && $file['SEO file name'] !== '') {
								rename ($dir . $new_filename , $dir . $folder . '/' . $new_filename);
								array_push($success_log, $file);
							}

							





							if (isset($file['Gallery Name'])) {
								$folder = $file['Gallery Name'];
							}

							if (isset($file['type'])) {
								$folder = $file['type'];
							}
							else {
								$folder = $file['town'];
							}

							if (isset($file['agency'])) {
								$folder = $file['agency'];
							}


							$new_filename = $file['SEO file name'];
							$file_ok = 0;

							if ($file['SEO file name'] !== '') {
								if (file_exists($dir . $new_filename)) {
									$file_ok = 1;
								}
								if (is_dir($dir . $folder)) {
									$folder_ok = 1;
								}
								else {
									mkdir($dir . $folder);
									$folder_ok =1;
								}

								if ($file_ok == 1 && $folder_ok == 1) {

									rename($dir . $new_filename , $dir . $folder . '/' . $new_filename);

									$image_attr = getimagesize($dir . $folder . '/' . $new_filename);
									$width = $image_attr[0];
									$height = $image_attr[1];
									$new_width = 320; 
									$new_height = floor (($height / $width) * $new_width) + 60;
									$target_file_name = str_replace($dir, '', $file);

									echo '<div class="group clear">';

									echo '<div class="left">';
									echo '<img src="./assets/ok.png" alt="Success"><strong>'.$new_filename.'</strong> moved into <strong>' . $folder . '</strong><br /><br />';
									foreach ($file as $key => $value) {
										// echo '<strong>' . ucfirst($key) . ':</strong> ' . $value;
										br();
									}

									echo '<img src="./data/output/' . $folder . '/' . $new_filename . '" width="'.$new_width.'" class="right">';
									echo '</div>';

									echo '</div>';
								}
							}
						}

						echo '<div class="group clear">';
						echo '<h3>Folder sorter</h3>';
						echo '</div>';
					}
?>