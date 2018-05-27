<?php
/**
 * Plugin Name: Open Google Analytics Dashboard for WP (OGADWP)
 * Plugin URI: https://deconf.com
 * Description: Displays Google Analytics Reports and Real-Time Statistics in your Dashboard. Automatically inserts the tracking code in every page of your website.
 * Author: Alin Marcu
 * Version: 5.3.2
 * Author URI: https://deconf.com
 * Text Domain: open-google-analytics-dashboard-for-wp
 * Domain Path: /languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit();

// Plugin Version
if ( ! defined( 'OGADWP_CURRENT_VERSION' ) ) {
	define( 'OGADWP_CURRENT_VERSION', '5.3.3' );
}

if ( ! defined( 'OGADWP_ENDPOINT_URL' ) ) {
	define( 'OGADWP_ENDPOINT_URL', 'https://ogadwp.deconf.com/' );
}


if ( ! class_exists( 'OGADWP_Manager' ) ) {

	final class OGADWP_Manager {

		private static $instance = null;

		public $config = null;

		public $frontend_actions = null;

		public $common_actions = null;

		public $backend_actions = null;

		public $tracking = null;

		public $frontend_item_reports = null;

		public $backend_setup = null;

		public $frontend_setup = null;

		public $backend_widgets = null;

		public $backend_item_reports = null;

		public $gapi_controller = null;

		/**
		 * Construct forbidden
		 */
		private function __construct() {
			if ( null !== self::$instance ) {
				_doing_it_wrong( __FUNCTION__, __( "This is not allowed, read the documentation!", 'open-google-analytics-dashboard-for-wp' ), '4.6' );
			}
		}

		/**
		 * Clone warning
		 */
		private function __clone() {
			_doing_it_wrong( __FUNCTION__, __( "This is not allowed, read the documentation!", 'open-google-analytics-dashboard-for-wp' ), '4.6' );
		}

		/**
		 * Wakeup warning
		 */
		private function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( "This is not allowed, read the documentation!", 'open-google-analytics-dashboard-for-wp' ), '4.6' );
		}

		/**
		 * Creates a single instance for OGADWP and makes sure only one instance is present in memory.
		 *
		 * @return OGADWP_Manager
		 */
		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
				self::$instance->setup();
				self::$instance->config = new OGADWP_Config();
			}
			return self::$instance;
		}

		/**
		 * Defines constants and loads required resources
		 */
		private function setup() {

			// Plugin Path
			if ( ! defined( 'OGADWP_DIR' ) ) {
				define( 'OGADWP_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin URL
			if ( ! defined( 'OGADWP_URL' ) ) {
				define( 'OGADWP_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin main File
			if ( ! defined( 'OGADWP_FILE' ) ) {
				define( 'OGADWP_FILE', __FILE__ );
			}

			/*
			 * Load Tools class
			 */
			include_once ( OGADWP_DIR . 'tools/tools.php' );

			/*
			 * Load Config class
			 */
			include_once ( OGADWP_DIR . 'config.php' );

			/*
			 * Load GAPI Controller class
			 */
			include_once ( OGADWP_DIR . 'tools/gapi.php' );

			/*
			 * Plugin i18n
			 */
			add_action( 'init', array( self::$instance, 'load_i18n' ) );

			/*
			 * Plugin Init
			 */
			add_action( 'init', array( self::$instance, 'load' ) );

			/*
			 * Include Install
			 */
			include_once ( OGADWP_DIR . 'install/install.php' );
			register_activation_hook( OGADWP_FILE, array( 'OGADWP_Install', 'install' ) );

			/*
			 * Include Uninstall
			 */
			include_once ( OGADWP_DIR . 'install/uninstall.php' );
			register_uninstall_hook( OGADWP_FILE, array( 'OGADWP_Uninstall', 'uninstall' ) );

			/*
			 * Load Frontend Widgets
			 * (needed during ajax)
			 */
			include_once ( OGADWP_DIR . 'front/widgets.php' );

			/*
			 * Add Frontend Widgets
			 * (needed during ajax)
			 */
			add_action( 'widgets_init', array( self::$instance, 'add_frontend_widget' ) );
		}

		/**
		 * Load i18n
		 */
		public function load_i18n() {
			load_plugin_textdomain( 'open-google-analytics-dashboard-for-wp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Register Frontend Widgets
		 */
		public function add_frontend_widget() {
			register_widget( 'OGADWP_Frontend_Widget' );
		}

		/**
		 * Conditional load
		 */
		public function load() {
			if ( is_admin() ) {
				if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
					if ( OGADWP_Tools::check_roles( self::$instance->config->options['access_back'] ) ) {
						/*
						 * Load Backend ajax actions
						 */
						include_once ( OGADWP_DIR . 'admin/ajax-actions.php' );
						self::$instance->backend_actions = new OGADWP_Backend_Ajax();
					}

					/*
					 * Load Frontend ajax actions
					 */
					include_once ( OGADWP_DIR . 'front/ajax-actions.php' );
					self::$instance->frontend_actions = new OGADWP_Frontend_Ajax();

					/*
					 * Load Common ajax actions
					 */
					include_once ( OGADWP_DIR . 'common/ajax-actions.php' );
					self::$instance->common_actions = new OGADWP_Common_Ajax();

					if ( self::$instance->config->options['backend_item_reports'] ) {
						/*
						 * Load Backend Item Reports for Quick Edit
						 */
						include_once ( OGADWP_DIR . 'admin/item-reports.php' );
						self::$instance->backend_item_reports = new OGADWP_Backend_Item_Reports();
					}
				} else if ( OGADWP_Tools::check_roles( self::$instance->config->options['access_back'] ) ) {

					/*
					 * Load Backend Setup
					 */
					include_once ( OGADWP_DIR . 'admin/setup.php' );
					self::$instance->backend_setup = new OGADWP_Backend_Setup();

					if ( self::$instance->config->options['dashboard_widget'] ) {
						/*
						 * Load Backend Widget
						 */
						include_once ( OGADWP_DIR . 'admin/widgets.php' );
						self::$instance->backend_widgets = new OGADWP_Backend_Widgets();
					}

					if ( self::$instance->config->options['backend_item_reports'] ) {
						/*
						 * Load Backend Item Reports
						 */
						include_once ( OGADWP_DIR . 'admin/item-reports.php' );
						self::$instance->backend_item_reports = new OGADWP_Backend_Item_Reports();
					}
				}
			} else {
				if ( OGADWP_Tools::check_roles( self::$instance->config->options['access_front'] ) ) {
					/*
					 * Load Frontend Setup
					 */
					include_once ( OGADWP_DIR . 'front/setup.php' );
					self::$instance->frontend_setup = new OGADWP_Frontend_Setup();

					if ( self::$instance->config->options['frontend_item_reports'] ) {
						/*
						 * Load Frontend Item Reports
						 */
						include_once ( OGADWP_DIR . 'front/item-reports.php' );
						self::$instance->frontend_item_reports = new OGADWP_Frontend_Item_Reports();
					}
				}

				if ( ! OGADWP_Tools::check_roles( self::$instance->config->options['track_exclude'], true ) && 'disabled' != self::$instance->config->options['tracking_type'] ) {
					/*
					 * Load tracking class
					 */
					include_once ( OGADWP_DIR . 'front/tracking.php' );
					self::$instance->tracking = new OGADWP_Tracking();
				}
			}
		}
	}
}

/**
 * Returns a unique instance of OGADWP
 */
function OGADWP() {
	return OGADWP_Manager::instance();
}

/*
 * Start OGADWP
 */
OGADWP();
