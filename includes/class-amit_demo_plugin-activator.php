<?php

/**
 * Fired during plugin activation
 *
 * @link       https://amitkolloldey.me
 * @since      1.0.0
 *
 * @package    Amit_demo_plugin
 * @subpackage Amit_demo_plugin/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Amit_demo_plugin
 * @subpackage Amit_demo_plugin/includes
 * @author     Amit Kollol Dey <amitkolloldey@gmail.com>
 */
class Amit_demo_plugin_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		global $wpdb;

		$table_name = $wpdb->prefix . 'event_guest_directory';
		$charset_collate = $wpdb->get_charset_collate();

		// SQL query to create the table with the 'created_at' field
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			name varchar(255) NOT NULL,
			bio text NOT NULL,
			age int NOT NULL,
			type text NOT NULL,
			status tinyint(1) NOT NULL,
			avatar varchar(255) DEFAULT NULL,
			zone varchar(255) NOT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id)
		) $charset_collate;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}


}
