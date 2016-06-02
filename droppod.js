Dropzone.autoDiscover = false;
jQuery(document).ready(function() {

	var url = jQuery("#dppu").val();
	var dz = new Dropzone("#dlform");

	var refresh_table = function() {
		var full_url = url + "table.php";
		console.log("full url", full_url);
		jQuery.ajax(full_url, {
					success: function(new_list) {
						console.log("file list reloaded");
						jQuery("#dp_list").html(new_list);
						bind_table();
					}
		});



	}


	var bind_table = function() {
		jQuery(".dp_delete").on("click", function() {
			var file_code = jQuery(this).attr("dpfile");
			var conf_txt  = jQuery(this).attr("title");
			var do_i = confirm("Are you sure you want to "+conf_txt+'?');

			if (do_i == true) {

				jQuery.ajax(url+"delete.php",
							{
								data: "f="+file_code,
								success: function(out) {
									console.log("delete function return", out);
									refresh_table();
								}
							});

			}



		});
	}


	dz.on("success", function(file) {
		console.log("File Succeeded!", file);
		refresh_table();
	});
	bind_table();



});
