<?php if (isset($_POST['asset-numbers'])) { ?>
<div class="clear">
	<!-- Asset Library String Generator -->
	<div class="group">

		<!-- Generates a 'number OR number OR number' string to enable easy downloading of assets from AL -->
		<h3>Asset Library string generator</h3>

		<?php
			$al_string = '';
				$spread_array = parseCsv($csv_path);
				foreach ($spread_array as $key => $value) {
					if (substr($value['number'],0,2) == 'AL' && $value['source'] == 'dk') {
						$al_string .= $value['number'] . ' OR ';
					}
				}

			echo '<textarea cols="182" rows="10" onclick="this.select();">';
			if (isset($_POST['asset-numbers'])) {
				echo preg_replace("/OR\z/", '', trim($al_string));
			}
			echo '</textarea>';
		?><br />
		<!-- <button type="submit">Generate string</button> -->
	</div>
	<!-- /Asset Library String Generator -->
</div>
<?php } ?>