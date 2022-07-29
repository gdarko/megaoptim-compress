<?php

function megaoptim_compress_daily_quota_reached() {
	$ip    = megaoptim_get_request_ip();
	$today = date( 'Y-m-d', time() );
	$key   = md5( $today . '-' . $ip );
}
add_action( 'megaoptim_compress_before_optimization', 'megaoptim_compress_daily_quota_reached' );