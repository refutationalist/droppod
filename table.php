<?php
header("Content-type: text/plain");
require( '../../../wp-load.php' );
$user = wp_get_current_user();


if (in_array("administrator", $user->roles)) {
	require_once(dirname(__FILE__)."/functions.php");
	droppod_filelist();
} else {
	echo "nope.";
}
