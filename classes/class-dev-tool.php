<?php
/**
 * Developer Tools options.
 *
 * @package Developer Tool
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Dev_Tool' ) ) {

	class Dev_Tool {

		/**
		 *  Constructor
		 */
		function __construct() {

			// Redirection on plugin activation.
			add_action( 'admin_init', __CLASS__ . '::dev_tool_redirect_on_activation', 1 );

			// Add required actions.
			add_action( 'admin_bar_init', array( $this, 'init' ) );
			add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
		}

		/**
		 * Redirect on activation hook.
		 *
		 * @since 1.0.0
		 */
		public static function dev_tool_redirect_on_activation() {

			if ( true === get_option( 'dev_tool_plugin_redirect' ) || '1' === get_option( 'dev_tool_plugin_redirect' ) ) {

				update_option( 'dev_tool_plugin_redirect', false );

				if ( ! is_multisite() ) :
					wp_redirect( admin_url( 'options-general.php?page=dev_tool_settings' ) );
				endif;
			}
		}

		/**
		 * Initialization required actions.
		 *
		 * @since 1.0.0
		 */
		function init() {

			// Load the appropriate text-domain.
			self::load_plugin_textdomain();

			// Add required action for admin menu.
			add_action( 'admin_enqueue_scripts', array( $this, 'dev_tool_enqueue_styles' ) );
			add_action( 'admin_bar_menu', array( $this, 'admin_bar_menu' ), 1000 );

			if ( isset( $_POST['download_config_file'] ) ) {
			    add_action('admin_init', 'developer_tool_config');
			}
		}

		/**
		 * Load developer-tool Text Domain.
		 * This will load the translation textdomain depending on the file priorities.
		 *      1. Global Languages /wp-content/languages/developer-tool/ folder
		 *      2. Local dorectory /wp-content/plugins/developer-tool/languages/ folder
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public static function load_plugin_textdomain() {

			// Traditional WordPress plugin locale filter.
			$locale = apply_filters( 'plugin_locale', get_locale(), 'developer-tool' );

			// Setup paths to current locale file.
			$mofile_global = trailingslashit( WP_LANG_DIR ) . 'plugins/developer-tool/' . $locale . '.mo';
			$mofile_local  = trailingslashit( WE_CS_BASE_DIR ) . 'languages/' . $locale . '.mo';

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/plugins/developer-tool/ folder.
				return load_textdomain( 'developer-tool', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/developer-tool/languages/ folder.
				return load_textdomain( 'developer-tool', $mofile_local );
			}

			// Nothing found.
			return false;
		}

		/**
		 * Add submenu to options-general.php.
		 *
		 * @since 1.0.0
		 */
		function admin_menu() {

			add_submenu_page( 'options-general.php', 'Developer Tool', 'Developer Tool', 'manage_options', 'dev_tool_settings', array( &$this, 'dev_settings_page' ) );
		}

		/**
		 * Enqueue required styles/scripts.
		 *
		 * @since 1.0.0
		 */
		function dev_tool_enqueue_styles() {

			// Enqueue required styles.
			wp_enqueue_style( 'developer-tool-style', DEV_TOOL_PLUGIN_URL . 'assets/style.css' );
		}

		function dev_settings_page() {

			// Load options page.
			require_once DEV_TOOL_PLUGIN_DIR . 'includes/functions.php';
			require_once DEV_TOOL_PLUGIN_DIR . 'includes/settings.php';
		}

		/**
		 * Adds the admin nav menu to wp_admin_bar
		 *
		 * @since 1.0.0
		 * @return void
		 */
		function admin_bar_menu( $admin_bar ) {

			$classes = apply_filters( 'developer_tool_classes', array() );

			/* Add the main siteadmin menu item */
			$admin_bar->add_menu( array(
				'id'     => 'developer-tool',
				'title'  => '<div class="devIcon dashicons-before dashicons-dashboard"></div>',
				'meta'   => array( 'class' => $classes ),
			));

			$admin_bar->add_menu( array(
				'id'     => 'dev-tool-first-item',
				'parent' => 'developer-tool',
				'href'	 => 'options-general.php?page=dev_tool_settings',
				'title'  => apply_filters( 'dev_tool_wp_bar_title', __( 'Error Log', 'developer-tool' ) ),
				'meta'   => array( 'class' => $classes ),
			));

			$admin_bar->add_menu( array(
				'id'     => 'dev-tool-second-item',
				'parent' => 'developer-tool',
				'href'	 => 'options-general.php?page=dev_tool_settings',
				'title'  => apply_filters( 'dev_tool_wp_bar_title', __( 'Debug Settings', 'developer-tool' ) ),
				'meta'   => array( 'class' => $classes ),
			));
		}	
	}
	
	$GLOBALS['dev_tool'] = new Dev_Tool();
}
