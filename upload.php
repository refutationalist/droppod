<?php
header("Content-type: text/plain");
require( '../../../wp-load.php' );
$user = wp_get_current_user();

if (in_array("administrator", $user->roles)) {
	$dp_dir = get_option('dp_dir');

	if (!empty($_FILES)) {
		$file_name = preg_replace("/[^a-zA-Z0-9_\.\- ]/", '', $_FILES['file']['name']);
		$file_name = preg_replace("/[ -]+/", '-', $file_name);

		$temp_file = $_FILES['file']['tmp_name'];
		$target_file = $dp_dir.'/'.$file_name;
		move_uploaded_file($temp_file, $target_file);
	}

} else {
	echo "nope.";
}
?>
