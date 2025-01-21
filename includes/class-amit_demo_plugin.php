<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://amitkolloldey.me
 * @since      1.0.0
 *
 * @package    Amit_demo_plugin
 * @subpackage Amit_demo_plugin/includes
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
 * @package    Amit_demo_plugin
 * @subpackage Amit_demo_plugin/includes
 * @author     Amit Kollol Dey <amitkolloldey@gmail.com>
 */
class Amit_demo_plugin
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Amit_demo_plugin_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	public function __construct()
	{
		if (defined('AMIT_DEMO_PLUGIN_VERSION')) {
			$this->version = AMIT_DEMO_PLUGIN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'amit_demo_plugin';

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
	 * - Amit_demo_plugin_Loader. Orchestrates the hooks of the plugin.
	 * - Amit_demo_plugin_i18n. Defines internationalization functionality.
	 * - Amit_demo_plugin_Admin. Defines all hooks for the admin area.
	 * - Amit_demo_plugin_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-amit_demo_plugin-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-amit_demo_plugin-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-amit_demo_plugin-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-amit_demo_plugin-public.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-amit_demo_plugin-post-type-and-taxonomies-events.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-amit_demo_plugin-filter-events-shortcode.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-amit_demo_plugin-settings.php';

		$this->loader = new Amit_demo_plugin_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Amit_demo_plugin_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale()
	{

		$plugin_i18n = new Amit_demo_plugin_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{

		$plugin_admin = new Amit_demo_plugin_Admin($this->get_plugin_name(), $this->get_version());
		$post_type_events = new Amit_demo_plugin_Post_Type_And_Taxonomies_Events();
		$plugin_settings = new Amit_demo_plugin_Settings_Page();

		$this->loader->add_action('admin_menu', $plugin_settings, 'add_settings_page');
		$this->loader->add_action('admin_init', $plugin_settings, 'register_settings');
		$this->loader->add_action('init', $post_type_events, 'register_events_cpt');
		$this->loader->add_action('init', $post_type_events, 'register_event_taxonomies');
		$this->loader->add_action('add_meta_boxes', $post_type_events, 'add_event_meta_boxes');
		$this->loader->add_action('save_post', $post_type_events, 'save_event_meta_fields');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks()
	{
		$plugin_public = new Amit_demo_plugin_Public($this->get_plugin_name(), $this->get_version());
		$filter_events_shortcode = new Amit_demo_plugin_Filter_Events_Shortcode();

		$this->loader->add_action('wp_ajax_filter_events', $filter_events_shortcode, 'filter_events_ajax');
		$this->loader->add_action('wp_ajax_nopriv_filter_events', $filter_events_shortcode, 'filter_events_ajax');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
		$this->loader->add_shortcode('filter_events', $filter_events_shortcode, 'render_filter_events_shortcode');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Amit_demo_plugin_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}

}
