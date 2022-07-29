<?php
function _megaoptim_compress( $atts ) {
	$atts = shortcode_atts( array(), $atts );
	wp_enqueue_style('toastr');
	wp_enqueue_script('toastr');
	wp_enqueue_style( 'dropzone' );
	wp_enqueue_script( 'dropzone' );
	wp_enqueue_style( 'compress' );
	wp_enqueue_script( 'compress' );
	ob_start();
	include( MEGAOPTIM_COMPRESS_PATH . '/views/compress.php' );
	return ob_get_clean();
}

add_shortcode( 'megaoptim_compress', '_megaoptim_compress' );