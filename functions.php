<?php 

function time_scandir($dir) {
	$times = array();
	$files = scandir($dir);

	foreach ($files as $f) {
		if (substr($f, 0, 1) == '.') continue;
		$times[$f] =  filemtime($dir.'/'.$f);
	}

	asort($times, SORT_NUMERIC);
	return array_reverse(array_keys($times));
}

function droppod_filelist() { 

	$dp_dir = get_option('dp_dir');
	$dp_url = get_option('dp_url');
	
	
	?>

	
	<table class="wp-list-table widefat fixed posts striped">

		<thead>
			<tr>
				<th class="column-title">Filename</th>
				<th>Unix Owner</th>
				<th>Size</th>
				<th>Date</th>
				<th>Actions</th>
			</tr>
		</thead>

		<tbody>

		<?php
			$dir = time_scandir($dp_dir);

			foreach($dir as $file) {

				$ffile = $dp_dir.'/'.$file;
				$furl  = $dp_url.'/'.$file;	

				$fpwuid = posix_getpwuid(fileowner($ffile));
				$fgrgid = posix_getgrgid(filegroup($ffile));

				$owner = $fpwuid["name"];
				$group = $fgrgid["name"];
				$size  = size_format(filesize($ffile), 2);
				$date  = date("M d Y H:i:s", filemtime($ffile));

				$display_file = htmlspecialchars($file);


				echo "\t\t\t<tr>\n".
					 "\t\t\t\t<td>$display_file</td>\n".
					 "\t\t\t\t<td title='User: $owner, Group: $group'>$owner/$group</td>\n".
					 "\t\t\t\t<td>$size</td>\n".
					 "\t\t\t\t<td>$date</td>\n".
					 "\t\t\t\t<td><a title='Download $display_file' ".
					 "target='_blank' href='$furl'>".
					 '<span class="dashicons dashicons-admin-links"></span></a>&nbsp;'.
					 "<a title='delete $display_file' class='dp_delete' dpfile='".base64_encode($file)."'>".
					 '<span class="dashicons dashicons-trash"></span></a>'.
					 "</td>\n".
					 "\t\t\t</tr>\n";

			}

		?>

	</table>







<?php }

