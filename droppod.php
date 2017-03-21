<?php

/*
Plugin Name: DropPod
Plugin URL: http://vis.nu/droppod
Description: Quickly upload arbitrary files to an unmanaged directory, otherwise uncontrolled by WordPress.  Simple wrapper around <a href='http://www.dropzonejs.com/'>Dropzone.js</a>.   Includes delete functionality.  Part of the Interest Compound support structure, which is why it says 'contact Sam' anytime something is broken.  Outside of the Interest Compound, there's no implied support, but you're free to contact me anyway. Docs <a href="http://vis.nu/droppod">here</a>.
Author: Sam Mulvey
Author URI: http:///vis.nu
Version: 3 
License: GPL3

*/

require_once("vendor/plugin-update-checker.php");

$dp_updcheck = PucFactory::buildUpdateChecker('http://vis.nu/plugins/droppod.json', __FILE__);


if (is_admin()) {
	add_action('admin_menu', 'droppod_menus');
	add_action('admin_init', 'droppod_init');
	add_action('wp_ajax_droppod_filelist', 'droppod_filelist');
	add_action('admin_enqueue_scripts', 'droppod_enqueue');

}

function droppod_enqueue($hook) {
	if ($hook == 'toplevel_page_droppod') {
		wp_enqueue_script('droppod_dropzone', 
						  plugin_dir_url(__FILE__)."vendor/dropzone/dropzone.min.js");
		wp_enqueue_script('droppod_js',
						  plugin_dir_url(__FILE__)."droppod.js");

		wp_register_style('droppod_dropzone_style', 
						  plugin_dir_url(__FILE__)."vendor/dropzone/dropzone.min.css");
		wp_register_style('droppod_style',
						  plugin_dir_url(__FILE__)."droppod.css");

		wp_enqueue_style('droppod_dropzone_style');
		wp_enqueue_style('droppod_style');
	}
}


function droppod_init() {
	register_setting('droppod', 'dp_dir');
	register_setting('droppod', 'dp_url');


}

function droppod_menus() {
	add_options_page('DropPod Config', 
					 'DropPod Config', 
					 'manage_options', 
					 'droppod_config', 
					 'droppod_config');

	add_menu_page("DropPod File Management", 
				  "DropPod",
				  "manage_options",
				  "droppod",
				  "do_droppod",
				  "dashicons-album");
	

}



function droppod_config() {
	if (!current_user_can('manage_options'))  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	?>
<div class="wrap">
	<h1><?php screen_icon(); ?> DropPod Config</h1>
	<form method="post" action="options.php">
	<?php
		settings_fields('droppod');
		do_settings_sections('droppod');
	?>
	<p>
	<b>Directory to Manage:</b> 
		<input type="text" name="dp_dir" id="dp_dir" value="<?php
			echo esc_attr(get_option('dp_dir'));	
		?>" /> <br />
	<b>URL Correspondence:</b> 
		<input type="text" name="dp_url" id="dp_url" value="<?php
			echo esc_attr(get_option('dp_url'));	
		?>" />
	</p>

	<p>Only two settings.  More settings might come later.  Don't screw with it unless you're some kind of magical genius.  &mdash; Sam</p>

	<?php submit_button(); ?>	

</div>
<?php
}

function do_droppod() {
	require_once(dirname(__FILE__)."/functions.php");
	if (!current_user_can('manage_options'))  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	add_action('admin_head', 'droppod_css');
	
	settings_fields('droppod');
	$dp_dir = get_option('dp_dir');
	add_thickbox();
	echo "<div id='droppod' class='wrap'>\n".
		 "\t<h1>DropPod File Manager</h1>\n";

	if (!is_writeable($dp_dir)) { 
		echo "\t<div class='error notice'>\n".
			 "\t\t<p><b>TOTALLY BORKEN:</b> The dp_dir is not writeable, ".
			 "so you can't upload.  Contact Sam.</p>\n".
			 "\t</div>\n";
		return;	
	} else {
		$plugin_url = plugin_dir_url(__FILE__);
		$upload_url = $plugin_url."upload.php";
		
		echo "\t<form action='$upload_url' id='dlform' class='dropzone'></form>\n".
			 "\t<input type='hidden' id='dppu' value='$plugin_url' />\n".
			 "\t<div id='dp_list'>\n";
		droppod_filelist();
		echo "\t</div>\n";
	}

	echo "</div>\n";
}

?>
