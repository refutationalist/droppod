<?php
header("Content-type: text/plain");
require( '../../../wp-load.php' );
$user = wp_get_current_user();

if (in_array("administrator", $user->roles)) {
	$dp_dir = get_option('dp_dir');
	$file = $dp_dir . '/' . base64_decode(escapeshellarg($_REQUEST["f"]));
	echo unlink($file);
} else {
	echo "nope.";
}
?>
