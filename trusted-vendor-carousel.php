<?php
/**
 * Plugin Name: Trusted Vendor Carousel
 * Plugin URI: https://github.com/forhadulislamshuvo/trusted-vendor-carousel
 * Description: Infinite seamless vendor logo carousel with speed, alignment, and display controls. A Straw Digital product.
 * Version: 2.5.1
 * Author: Md Forhadul Islam Shuvo
 * Author URI: http://digital.strawbd.com/
 * License: GPLv2 or later
 * Text Domain: trusted-vendor-carousel
 */

if ( ! defined( 'ABSPATH' ) ) exit;

require_once plugin_dir_path( __FILE__ ) . 'includes/class-tvc-post-type.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-tvc-shortcode.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-tvc-scripts.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-tvc-settings.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-tvc-updater.php';
