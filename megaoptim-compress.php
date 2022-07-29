<?php
/**
 * Plugin Name: MegaOptim Compress
 * Plugin URI:  https://megaoptim.com
 * Description: Provides the megaoptim image compression interface as a plugin.
 * Version:     1.0.0
 * Author:      Darko Gjorgjijoski
 * Author URI:  https://darkog.com
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

if(!defined('ABSPATH')) {
	exit('Not allowed');
}

define( 'MEGAOPTIM_COMPRESS_MAX_PER_DAY', 30 );
define( 'MEGAOPTIM_COMPRESS_PATH', plugin_dir_path( __FILE__ ) );
define( 'MEGAOPTIM_COMPRESS_URL', plugin_dir_url( __FILE__ ) );
define( 'MEGAOPTIM_COMPRESS_API_KEY','WqDPpugDAC01HrkwYMKpk6liBgRnbLor' );

require_once 'vendor/autoload.php';
require_once 'includes/classes/Limiter.php';
require_once 'includes/helpers.php';
require_once 'includes/hooks.php';
require_once 'includes/shortcodes.php';
