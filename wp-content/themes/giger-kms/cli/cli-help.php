<?php
/**
 * Temp Utilities
 *
 **/

set_time_limit (0);
ini_set('memory_limit','256M');

try {
	$time_start = microtime(true);
	include('cli_common.php');
	echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

	global $wpdb;

	//Upload test
	$path = WP_CONTENT_DIR.'/themes/giger-kms/cli/sideload/forum-patient.jpg';


	$file = file_get_contents($path);

	if($file){


		$filename = basename($path);
		$upload_file = wp_upload_bits($filename, null, $file);

		if (!$upload_file['error']) {
			$wp_filetype = wp_check_filetype($filename, null );

			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_parent' => 0,
				'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
				'post_content' => '',
				'post_status' => 'inherit'
			);

			$attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], 0 );

			if (!is_wp_error($attachment_id)) {
				require_once(ABSPATH . "wp-admin" . '/includes/image.php');
				$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
				wp_update_attachment_metadata( $attachment_id,  $attachment_data );
			}
		}

		var_dump($attachment_id);
	}

	//Final
	echo 'Memory '.memory_get_usage(true).chr(10);
	echo 'Total execution time in seconds: ' . (microtime(true) - $time_start).chr(10).chr(10);
}
catch (TstNotCLIRunException $ex) {
	echo $ex->getMessage() . "\n";
}
catch (TstCLIHostNotSetException $ex) {
	echo $ex->getMessage() . "\n";
}
catch (Exception $ex) {
	echo $ex;
}
