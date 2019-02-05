<?php 
/*
	Plugin Name: Developer Tool
	Description: Developer Tool helps you to <strong> debug your working environment</strong>. It shows you notices / warnings / errors which may cause during development or beacuse of conflicts.
	Version: 1.0.0
	Author: Navanath Bhosale
	Author URI: https://profiles.wordpress.org/navanathbhosale/profile/
	License: GPLv2
	Text Domain: developer-tool
 */

if ( ! defined( 'DEV_TOOL_PLUGIN_FILE' ) ) {
	define( 'DEV_TOOL_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'DEV_TOOL_PLUGIN_VERSION' ) ) {
	define( 'DEV_TOOL_PLUGIN_VERSION', '1.0.0' );
}

if ( ! defined( 'DEV_TOOL_PLUGIN_BASENAME' ) ) {
	define( 'DEV_TOOL_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'DEV_TOOL_PLUGIN_DIR' ) ) {
	define( 'DEV_TOOL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'DEV_TOOL_PLUGIN_URL' ) ) {
	define( 'DEV_TOOL_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
}

if ( is_admin() ) {
	register_activation_hook( __FILE__, 'on_dev_tool_plug_activate' );
}

/**
 * Function for activation hook.
 *
 * @since 1.0.0
 */
function on_dev_tool_plug_activate() {

	update_option( 'dev_tool_plugin_redirect', true );
}

if ( ! function_exists( 'settings_navigation_link' ) ) :

	/**
	 * Astra Filters navigation to customizer
	 *
	 * @since 1.0.0
	 */
	function settings_navigation_link( $links ) {

		$links = array_merge( array( '<a href="'. admin_url() . 'options-general.php?page=dev_tool_settings' .'">' . __( 'Settings' ) . '</a>' ), $links );
		return $links;
	}

	add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'settings_navigation_link' );

endif;

// Load required file.
require_once( DEV_TOOL_PLUGIN_DIR . 'classes/class-dev-tool.php' );
