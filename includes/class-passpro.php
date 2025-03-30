<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    PassPro
 * @subpackage PassPro/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    PassPro
 * @subpackage PassPro/includes
 * @author     Your Name <email@example.com>
 */
class PassPro {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      PassPro_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		if ( defined( 'PASSPRO_VERSION' ) ) {
			$this->version = PASSPRO_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'passpro';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - PassPro_Loader. Orchestrates the hooks of the plugin.
	 * - PassPro_i18n. Defines internationalization functionality.
	 * - PassPro_Db. Defines database operations.
	 * - PassPro_Admin. Defines all hooks for the admin area.
	 * - PassPro_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once PASSPRO_PLUGIN_DIR . 'includes/class-passpro-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once PASSPRO_PLUGIN_DIR . 'includes/class-passpro-i18n.php';

		/**
		 * The class responsible for database operations.
		 */
		require_once PASSPRO_PLUGIN_DIR . 'includes/class-passpro-db.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once PASSPRO_PLUGIN_DIR . 'admin/class-passpro-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once PASSPRO_PLUGIN_DIR . 'public/class-passpro-public.php';

		$this->loader = new PassPro_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the PassPro_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new PassPro_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new PassPro_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );
        // Add password management actions
        $this->loader->add_action( 'admin_init', $plugin_admin, 'handle_password_actions' );
        // Add action link to plugin page
        $plugin_basename = plugin_basename( PASSPRO_PLUGIN_DIR . 'passpro.php' );
        $this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new PassPro_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'template_redirect', $plugin_public, 'check_password_protection', 1 ); // Priority 1 to run early
        $this->loader->add_action( 'login_form_passpro', $plugin_public, 'render_login_form' ); // Custom action for login form
        $this->loader->add_action( 'wp_head', $plugin_public, 'output_custom_login_styles' ); // Action to output custom CSS
        $this->loader->add_action( 'login_head', $plugin_public, 'output_custom_login_styles' ); // Also hook to login_head for login pages
        $this->loader->add_action( 'wp_footer', $plugin_public, 'add_logout_button' ); // Add action for logout button
        $this->loader->add_action( 'admin_post_passpro_logout', $plugin_public, 'process_logout' ); // Handle logout action
        $this->loader->add_action( 'admin_post_nopriv_passpro_logout', $plugin_public, 'process_logout' ); // Handle logout for non-admin users

	}

	/**
	 * Check if the plugin needs to run any migrations
	 * 
	 * @since    1.0.0
	 */
	public function check_for_updates() {
		$db_version = get_option('passpro_db_version', '0');
		$current_version = PASSPRO_VERSION;
		
		// Only run migrations if the stored version is older than current version
		if (version_compare($db_version, $current_version, '<')) {
			
			// Connect to the database class
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-passpro-db.php';
			$db = new PassPro_DB();
			
			// Run migration to password hashes
			$db->migrate_to_password_hashes();
			
			// Update stored version
			update_option('passpro_db_version', $current_version);
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		// Check for updates first
		$this->check_for_updates();
		
		// Then run normal plugin operations
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    PassPro_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

} 