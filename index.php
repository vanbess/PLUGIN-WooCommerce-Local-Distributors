<?php

/**
 * Plugin Name: SBWC Local Distributors
 * Description: Add and edit local product distributors. Display on any page by shortcode.
 * Version: 1.0.0
 * Author: WC Bessinger
 * Text Domain: sbwc-dists
 */

if (!defined('ABSPATH')) :
    exit();
endif;

// define path constant
if (!defined('LOC_DIST_PATH')) :
    define('LOC_DIST_PATH', plugin_dir_path(__FILE__));
endif;

// define uri constant
if (!defined('LOC_DIST_URI')) :
    define('LOC_DIST_URI', plugin_dir_url(__FILE__));
endif;


// init
add_action('plugins_loaded', 'sbwc_loc_dist_init');

function sbwc_loc_dist_init()
{
    // check if WC is active
    if (!class_exists('WooCommerce')) :
        return;
    endif;
    
    // cpt
    include LOC_DIST_PATH . 'functions/cpt.php';

    // cpt metabox
    include LOC_DIST_PATH . 'functions/cpt-mbox.php';

    // shortcode to display distributors
    include LOC_DIST_PATH . 'functions/shortcode.php';
}
