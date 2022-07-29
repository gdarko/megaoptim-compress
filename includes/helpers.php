<?php
/**
 * String contains?
 * @param $substring
 * @param $string
 *
 * @return bool
 */
function megaoptim_compress_str_contains($substring, $string) {
	return strpos($string, $substring) !== false;
}

/**
 * Returns array items
 * @param $arr
 * @param $allowed
 *
 * @return array
 */
function megaoptim_compress_array_only($arr, $allowed) {
	$items = array();
	foreach($arr as $key => $value) {
		if(in_array($key, $allowed)) {
			$items[$key] = $value;
		}
	}
	return $items;
}

/**
 * Checks if a folder exist and return canonicalized absolute pathname (sort version)
 *
 * @param string $folder the path being checked.
 *
 * @return mixed returns the canonicalized absolute pathname on success otherwise FALSE is returned
 */
function megaoptim_dir_exist( $folder ) {
	// Get canonicalized absolute pathname
	$path = realpath( $folder );

	// If it exist, check if it's a directory
	return ( $path !== false AND is_dir( $path ) ) ? $path : false;
}

/**
 * Returns the temporary dir.
 * @return bool
 */
function megaoptim_get_temporary_dir() {
	$wp_uploads = wp_upload_dir();
	$dir        = $wp_uploads['basedir'] . DIRECTORY_SEPARATOR . 'megaoptim_compress';
	if ( ! megaoptim_dir_exist( $dir ) ) {
		@wp_mkdir_p( $dir );
	}
	if ( megaoptim_dir_exist( $dir ) ) {
		return $dir;
	}

	return false;
}

/**
 * Compress image
 *
 * @param $file_path
 * @param $args
 *
 * @return \MegaOptim\Responses\Response
 * @throws Exception
 */
function megaoptim_compress( $file_path, $args ) {
	$optimizer = new MegaOptim\Optimizer( MEGAOPTIM_COMPRESS_API_KEY );
	$resource = $optimizer->run( $file_path, $args );
	return $resource;
}


/**
 * Handle file upload
 *
 * @param string $handler
 *
 * @return bool|string
 */
function megaoptim_move_uploaded_file( $handler = 'file' ) {

	if ( ! file_exists( $_FILES[ $handler ]['tmp_name'] ) || ! is_uploaded_file( $_FILES[ $handler ]['tmp_name'] ) ) {
		return false;
	}
	$uploads_d = megaoptim_get_temporary_dir();
	if ( false === $uploads_d ) {
		return false;
	}
	$extension = pathinfo( $_FILES[ $handler ]['name'], PATHINFO_EXTENSION );
	$file_name = pathinfo( $_FILES[ $handler ]['name'], PATHINFO_FILENAME );
	$file_name = "{$file_name}.{$extension}";
	$file_path = $uploads_d . DIRECTORY_SEPARATOR . $file_name;
	if ( file_exists( $file_path ) ) {
		@unlink( $file_path );
	}
	if ( move_uploaded_file( $_FILES[ $handler ]['tmp_name'], $file_path ) ) {
		return $file_path;
	} else {
		return false;
	}
}

/////////////////// PREVENT REQUESTS ///////////////////////
