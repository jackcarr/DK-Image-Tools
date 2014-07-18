<?php
foreach ($_POST as $key => $value) {
	if (is_array($value)) {
		foreach ($value as $entry) {
			$message .= $entry . '<br/ >';
		}
	}
	else {
		$message .= '<div class="item"><strong>$_POST:</strong> '.$key . ' : ' . $value . '</div>';
	}
}

foreach ($_GET as $key => $value) {
	
	if (is_array($value)) {
		foreach ($value as $entry) {
			$message .= $entry . '<br/ >';
		}
	}
	else {
		$message .= '<div class="item"><strong>$_GET:</strong> '.$key . ' : ' . $value . '</div>';
	}
	br();br();
}

if ($message !== '') {
	echo '<div class="message">';
	echo '<h3>Status</h3>';
	echo $message;

	pre();
	print_r($_POST);
	post();

	echo '</div>';
}
?>