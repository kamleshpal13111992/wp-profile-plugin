<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://https://profiles.wordpress.org/kamleshpal1311/
 * @since      1.0.0
 *
 * @package    Wp_Profile_Listing
 * @subpackage Wp_Profile_Listing/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Profile_Listing
 * @subpackage Wp_Profile_Listing/public
 * @author     kamlesh Pal <kamleshpal39@gmail.com>
 */
class Wp_Profile_Listing_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Profile_Listing_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Profile_Listing_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-profile-listing-public.css', array(), $this->version, 'all' );

		wp_enqueue_style('select2','https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css', array(),'4.0.13');

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Profile_Listing_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Profile_Listing_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array('jquery'), '4.0.13', true);

		wp_enqueue_script('custom-profile-scripts', plugin_dir_url(__FILE__) . 'js/wp-profile-listing-public.js', array('jquery'), $this->version, false);

		wp_localize_script('custom-profile-scripts', 'custom_profile_vars', array(
	        'ajaxurl' 	=> admin_url('admin-ajax.php'),
	        'nonce' 	=> wp_create_nonce('custom-profile-nonce'),
	    ));
	}

	// Ajax handler for profile search and profile listing
	public function custom_profile_search() {

		check_ajax_referer('custom-profile-nonce', 'security');

		$page 						= isset($_POST['page']) ? intval($_POST['page']) : 1;
	    $title_filter 				= isset($_POST['title_filter']) ? sanitize_text_field($_POST['title_filter']) : '';
	    $skills_filter 				= isset($_POST['skills_filter']) ? $_POST['skills_filter'] : array();
    	$education_filter 			= isset($_POST['education_filter']) ? $_POST['education_filter'] : array();
    	$age_range 					= isset($_POST['age_range']) ? sanitize_text_field($_POST['age_range']) : '';
    	$rating_filter 				= isset($_POST['rating_filter']) ? intval($_POST['rating_filter']) : '';
	    $jobs_completed_filter 		= isset($_POST['jobs_completed_filter']) ? intval($_POST['jobs_completed_filter']) : '';
    	$years_of_experience_filter = isset($_POST['years_of_experience_filter']) ? intval($_POST['years_of_experience_filter']) : '';
    	$order 						= isset($_POST['order']) ? sanitize_text_field($_POST['order']) : 'asc';
    	$tax_query 					= array('relation' => 'OR');
    	$tax_query 					= array();
		$meta_query 				= array();

		if (!empty($skills_filter)) {
		    $tax_query[] = array(
		        'taxonomy' 	=> 'skills',
		        'field' 	=> 'id',
		        'terms' 	=> $skills_filter,
		        'operator' 	=> 'IN',
		    );
		}

		if (!empty($education_filter)) {
		    $tax_query[] = array(
		        'taxonomy' 	=> 'education',
		        'field' 	=> 'id',
		        'terms' 	=> $education_filter,
		        'operator' 	=> 'IN',
		    );
		}

		if (!empty($rating_filter)) {
		    $meta_query[] = array(
		        'key' 		=> 'ratings',
		        'value' 	=> $rating_filter,
		        'compare' 	=> '=',
		        'type' 		=> 'NUMERIC',
		    );
		} 

		if (!empty($age_range)) {
		    $age_values = array_map('intval', explode('-', $age_range));
		    $meta_query[] = array(
		        'key' 		=> 'age',
		        'value' 	=> $age_values,
		        'compare' 	=> 'BETWEEN',
		        'type' 		=> 'NUMERIC',
		    );
		}

		$tax_query 	= !empty($tax_query) ? $tax_query : array();
		$meta_query = !empty($meta_query) ? $meta_query : array();

		if (!empty($jobs_completed_filter)) {
		    $meta_query[] = array(
		        'key' 		=> 'jobs_completed',
		        'value' 	=> $jobs_completed_filter,
		        'compare' 	=> '=',
		        'type' 		=> 'NUMERIC',
		    );
		}

		if (!empty($years_of_experience_filter)) {
		    $meta_query[] = array(
		        'key' 		=> 'years_of_experience',
		        'value' 	=> $years_of_experience_filter,
		        'compare' 	=> '=',
		        'type' 		=> 'NUMERIC',
		    );
		}

		$args = array(
	        'post_type' 		=> 'profile',
	        'post_status' 		=> 'publish',
	        'posts_per_page' 	=> 5,
	        'orderby' 			=> 'title',
	        's' 				=> $title_filter,
    		'order' 			=> $order,
	        'paged' 			=> $page,
	        'meta_query'		=> $meta_query,
	        'tax_query' 		=> $tax_query,
	    );


    	if($order == "asc"){
    		$arrow = "&#x25B2;";
    	}else{
    		$arrow = "&#x25BC;";
    	}

	    $query 		= new WP_Query($args);
		$results 	= $query->posts;

		$output 	= '<div id="custom-profile-listing">';
		$output 	.= '<table>';
		$output 	.= '<thead><tr><th>No</th><th>Profile Name <span class="sort-arrow" data-column="profile_name">'.$arrow.'</span> </th><th>Age</th><th>No of Jobs Completed</th><th>Years of Experience</th><th>Ratings</th></tr></thead>';
		$output 	.= '<tbody>';

		if(!empty($results)){
			foreach ($results as $profile) {
			    $serialNo 			= $profile->ID;
			    $title 				= esc_html($profile->post_title);
			    $age_no 			= esc_html(get_post_meta($profile->ID, 'age', true));
			    $yearsOfExperience 	= esc_html(get_post_meta($profile->ID, 'years_of_experience', true));
			    $jobsCompleted 		= esc_html(get_post_meta($profile->ID, 'jobs_completed', true));
			    $ratings 			= esc_html(get_post_meta($profile->ID, 'ratings', true));
			    $args 				= array('rating' => $ratings,'type'	=> 'rating','number' => 12345);

			    $output .= '<tr>';
			    $output .= '<td>' . $serialNo . '</td>';
			    $output .= '<td>' . '<a target="_blank" href="'.get_permalink($profile->ID).'">'.$title . '</a></td>';
			    $output .= '<td>' . $age_no . '</td>';
			    $output .= '<td>' . $jobsCompleted . '</td>';
			    $output .= '<td>' . $yearsOfExperience . '</td>';
			    $output .= '<td>' . wp_star_rating( $args ) . '</td>';
			    $output .= '</tr>';
			}
		}else{
			$output .= '<tr><td>No Data Found!!</td></tr>';
		}
		

		$output .= '</tbody>';
		$output .= '</table>';

		$output .=  '<div class="pagination">';

		if($query->max_num_pages > 1){
			for ($i = 1; $i <= $query->max_num_pages; $i++) {
		        $class 	= ($i == $page) ? 'current' : '';
		        $output .=  '<a href="#" class="' . $class . '" data-page="' . $i . '">' . $i . '</a>';
		    }
		}
	    
	    $output .=  '</div>';

	    echo $output;

	    wp_die();
	}

}
