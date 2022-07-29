<?php
/**
 * Enqueues the required megaoptim scripts and styles.
 */
function megaoptim_compress_enqueue_scripts() {

	// Dropzone
	wp_register_style( 'toastr', MEGAOPTIM_COMPRESS_URL . '/assets/toastr/toastr.min.css' );
	wp_register_script( 'toastr', MEGAOPTIM_COMPRESS_URL . '/assets/toastr/toastr.min.js', array( 'jquery' ) );

	// Dropzone
	wp_register_style( 'dropzone', MEGAOPTIM_COMPRESS_URL . '/assets/dropzone/dropzone.min.css' );
	wp_register_script( 'dropzone', MEGAOPTIM_COMPRESS_URL . '/assets/dropzone/dropzone.min.js', array( 'jquery' ) );

	// Compress.css
	$time = filemtime( MEGAOPTIM_COMPRESS_PATH . '/assets/compress.css' );
	wp_register_style( 'compress', MEGAOPTIM_COMPRESS_URL . '/assets/compress.css', null, $time );

	// Compress.js
	$time = filemtime( MEGAOPTIM_COMPRESS_PATH . '/assets/compress.js' );
	wp_register_script( 'compress', MEGAOPTIM_COMPRESS_URL . '/assets/compress.js', array( 'jquery' ), $time, true );
	wp_localize_script( 'compress', 'MGOCompress', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'compress' ),
		'limit'    => MEGAOPTIM_COMPRESS_MAX_PER_DAY,
	) );


	/*global $post;
	if(megaoptim_compress_str_contains('[megaoptim_compress', $post->post_content)) {
		wp_enqueue_script('dropzone');
		wp_enqueue_script('dropzone');
	}*/

}
add_action( 'wp_enqueue_scripts', 'megaoptim_compress_enqueue_scripts' );

/**
 * Ajax handler for compression
 */
function megaoptim_compress_handle() {
	// Perform security checks
	if ( ! check_ajax_referer( 'compress', '_ajax_nonce', false ) ) {
		wp_send_json_error( array(
			'error'   => 'permission_denied',
			'message' => 'Permission denied to this resource.',
		) );
		exit;
	}

	$limiter = new \MegaOptim\Compress\Limiter(MEGAOPTIM_COMPRESS_MAX_PER_DAY);
	if($limiter->is_limit_exceeded()) {
		wp_send_json_error( array(
			'error'   => 'quota_exceeded',
			'message' => 'Sorry. Your daily limit is exceeded! Using this interface you can only optimize up to '.MEGAOPTIM_COMPRESS_MAX_PER_DAY.' images per day. Please check our documentation to see how you can use MegaOptim to optimize more images or come back tomorrow for more.',
		) );
		exit;
	}


	// Move uploaded file and obtain paramters
	$opt_args = megaoptim_compress_array_only( $_POST, array( 'keep_exif', 'compression', 'webp', 'max_width' ) );
	$opt_file = megaoptim_move_uploaded_file('file');

	// Sanitize max width param
	if(isset($opt_args['max_width']) ) {
		if(!is_numeric($opt_args['max_width']) || $opt_args['max_width'] <= 0) {
			unset($opt_args['max_width']);
		}
	}

	// Validate if upload is successful.
	if(!file_exists($opt_file)) {
		wp_send_json_error( array(
			'error'   => 'internal_server_error',
			'message' => 'Error saving uploaded file. Please make sure you upload valid image file.',
		) );
		exit;
	}

	// Try to optimize.
	try {
		$response = megaoptim_compress( $opt_file, $opt_args );
		$raw = $response->getRawResponse();
		$raw = json_decode($raw, true);
		$files = isset($raw['result']) ? $raw['result'] : array();
		$files = count($files) > 0 ? $files[0] : array();
		wp_send_json_success(array(
			'result' => $files,
			'message' => 'Image '.basename($opt_file).' optimized successfully!'
		));
	} catch ( Exception $e ) {
		wp_send_json_error( array(
			'error'   => 'runtime_error',
			'message' => $e->getMessage(),
		) );
	}
	exit;

}
add_action( 'wp_ajax_megaoptim_compress', 'megaoptim_compress_handle' );
add_action( 'wp_ajax_nopriv_megaoptim_compress', 'megaoptim_compress_handle' );
