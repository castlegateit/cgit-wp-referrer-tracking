<?php

/*

Plugin Name: Castlegate IT Referrer Tracking
Plugin URI: http://github.com/castlegateit/cgit-wp-obfuscator
Description: Track referrers from target third parties to target pages.
Version: 1.0
Author: Castlegate IT
Author URI: http://www.castlegateit.co.uk/
License: MIT

*/

if (!defined('ABSPATH')) {
    wp_die('Access denied');
}

require_once __DIR__ . '/classes/autoload.php';

add_action('wp', function() {
    $plugin = new \Cgit\Referral\Plugin();
    
    do_action('cgit_referral_tracker_loaded');
});
