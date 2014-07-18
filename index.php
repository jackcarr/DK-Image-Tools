	<?php include ('header.php');?>


	

		<div class="returned">
			<?php
			if (isset($_GET['message'])) {
				if ($_GET['message'] == 'Success' && isset($_GET['folder']) && $_GET['folder'] !=='' && isset($_GET['file'])) {
					echo '<img src="./assets/ok.png" alt="Success">Successfully deleted <strong>' . $_GET['file'] . '</strong> from <strong>'.$_GET['folder'].'</strong>.';
					$folder = $_GET['folder'];
				}
				if ($_GET['message'] == 'Failed' && isset($_GET['folder']) && isset($_GET['file'])) {
					echo '<img src="./assets/not-ok.png" alt="Error">Failed to delete <strong>' . $_GET['file'] . '</strong> from <strong>'.$_GET['folder'].'</strong>.';
					$folder = $_GET['folder'];
				}
			}
			?>
		</div>


		<?php
		include ('initialise.php');

			// If csv file is set, list all local files which are eligible for processing
			// This should replace each tool's built-in csv parser?

		// if (isset($_POST['csv_path']) && $_POST['csv_path']!== '') {
			$csv_array = parseCsv ($csv_path);

			$output .= '<table><tr><td><button name="process">Process</button></td><td><label for="selectall">Select all&nbsp;</label><input type="checkbox" id="selectall"></td></tr>';

			foreach ($csv_array as $key => $value) {

				if (isset($value['SEO file name']) && $value['SEO file name'] !== '') {
					$output .= '<tr><td>';
					$output .= '<label for="' . $value['SEO file name'] . '">' . $value['SEO file name'] . '</label>';
					$output .= '</td><td>';
					$output .= '<input type="checkbox" name="use_file[]" class="use_file" value="' . $value['SEO file name'] . '" id="' . $value['SEO file name'] . '"';

					if (isset($_POST['use_file'])){
						if (in_array($value['SEO file name'], $_POST['use_file'])) {
							$output .= ' checked';
						}
					}
					$output .= '></td></tr>';
				}
			}

			$output .= '</table>';
			$output .= '<br /><button name="process">Process</button>';

				// echo $output;
		// }


		include 'string-generator.php';
		include 'compare-files.php';
		include 'folder-sort.php';
		include 'copy-metadata.php';
		include 'rename-files.php';
		include 'browse-folder.php';
		include 'messages.php';



		?>

		<!--Folder path: <input type="text" size="75" name="folder-path" value="<?php if (isset($_POST['folder-path'])){ echo $_POST['folder-path'];}?>">-->

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

    </form>
</body>
</html>