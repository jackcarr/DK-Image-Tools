<?php if (isset($_POST['asset-numbers'])) { ?>
<div class="clear">
	<!-- Asset Library String Generator -->
	<div class="group">

		<!-- Generates a 'number OR number OR number' string to enable easy downloading of assets from AL -->
		<h3>Asset Library string generator</h3>

		<?php

		if (is_array($csv) && count ($csv) > 0) {
			$al_string = '';
				$csv = parseCsv($csv_path);
				foreach ($csv as $key => $value) {
					//
					// Check if the CSV is a proper POI Images file
					//
					if (!isset($value['number'])) {
						$output .= '<div class="error">';
						$output .= 'This CSV does not contain a <strong>number</strong> field.';
						$output .= '</div>';
						break;
					}
					elseif (substr($value['number'],0,2) == 'AL' && $value['source'] == 'dk') {
						$al_string .= $value['number'] . ' OR ';
					}
				}

			$output .= '<textarea cols="182" rows="10" onclick="this.select();">';
			if (isset($_POST['asset-numbers'])) {
				$output .= preg_replace("/OR\z/", '', trim($al_string));
			}
			$output .= '</textarea>';
		}
		echo $output;
		$output = '';
		?><br />
		<!-- <button type="submit">Generate string</button> -->
	</div>
	<!-- /Asset Library String Generator -->
</div>
<?php } ?>