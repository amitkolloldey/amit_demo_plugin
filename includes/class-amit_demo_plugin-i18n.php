<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://amitkolloldey.me
 * @since      1.0.0
 *
 * @package    Amit_demo_plugin
 * @subpackage Amit_demo_plugin/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Amit_demo_plugin
 * @subpackage Amit_demo_plugin/includes
 * @author     Amit Kollol Dey <amitkolloldey@gmail.com>
 */
class Amit_demo_plugin_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'amit_demo_plugin',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
