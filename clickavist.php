<?php
/** Plugin Name: Click a Vist Social API
 * Plugin URI: http://google.com
 * Author: Tek Connect Inc
 * Author URI: http://google.com
 * Version: 1.0
 * Description: Click a vist Social API to send tweets, send emails, donate funds, etc...
*/

// Make sure plugin do nothing if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

/* define plugin directory and url */
define( 'CLICKAVIST__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CLICKAVIST__PLUGIN_URL', plugin_dir_url(__FILE__) );

/* define plugin activation hook and deactivation hook */
register_activation_hook( __FILE__, array( 'Clickavist', 'clickavist_activation' ) );
register_deactivation_hook( __FILE__, array( 'Clickavist', 'clickavist_deactivation' ) );

/* main class of ClickAvist */
require_once(CLICKAVIST__PLUGIN_DIR . '/_classes/clickavist-class.php');

/* widget class  start */
require_once(CLICKAVIST__PLUGIN_DIR . '/_classes/clickavist-shortcode-class.php');
require_once(CLICKAVIST__PLUGIN_DIR . '/_classes/clickavist-widget.php');

/* static variable and api auth code weitten in this file*/

add_action( 'plugins_loaded', array( 'clickAvist', 'get_instance' ) );