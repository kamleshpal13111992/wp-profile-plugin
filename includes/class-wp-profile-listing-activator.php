<?php

/**
 * Fired during plugin activation
 *
 * @link       https://https://profiles.wordpress.org/kamleshpal1311/
 * @since      1.0.0
 *
 * @package    Wp_Profile_Listing
 * @subpackage Wp_Profile_Listing/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Profile_Listing
 * @subpackage Wp_Profile_Listing/includes
 * @author     kamlesh Pal <kamleshpal39@gmail.com>
 */
class Wp_Profile_Listing_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */

	public static function activate() {
	    // Check if the page already exists
	    $page = get_page_by_title('Profile Listing');

	    // Create the page if it doesn't exist
	    if (!$page) {
	        $page_data = array(
	            'post_title'    => 'Profile Listing',
	            'post_content'  => '',
	            'post_status'   => 'publish',
	            'post_type'     => 'page',
	            'page_template' => plugin_dir_url( __FILE__ ) . 'public/custom-page-template.php',
	        );

	        wp_insert_post($page_data);
	    }
	}
}
