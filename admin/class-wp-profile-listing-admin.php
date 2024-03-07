<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://https://profiles.wordpress.org/kamleshpal1311/
 * @since      1.0.0
 *
 * @package    Wp_Profile_Listing
 * @subpackage Wp_Profile_Listing/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Profile_Listing
 * @subpackage Wp_Profile_Listing/admin
 * @author     kamlesh Pal <kamleshpal39@gmail.com>
 */
class Wp_Profile_Listing_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-profile-listing-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-profile-listing-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the custom post type and custom taxonomy for the admin area.
	 *
	 * @since    1.0.0
	 */
	public  function create_custom_post_type() {

        register_post_type('profile',
	        array(
	            'labels' => array(
	                'name'               => __('Profiles'),
	                'singular_name'      => __('Profile'),
	                'menu_name'          => __('Profiles'),
	                'add_new'            => __('Add New'),
	                'add_new_item'       => __('Add New Profile'),
	                'edit'               => __('Edit'),
	                'edit_item'          => __('Edit Profile'),
	                'new_item'           => __('New Profile'),
	                'view'               => __('View'),
	                'view_item'          => __('View Profile'),
	                'search_items'       => __('Search Profiles'),
	                'not_found'          => __('No profiles found'),
	                'not_found_in_trash' => __('No profiles found in Trash'),
	            ),
	            'public'             => true,
	            'publicly_queryable' => true,
	            'show_ui'            => true,
	            'show_in_menu'       => true,
	            'query_var'          => true,
	            'rewrite'            => array('slug' => 'profile'),
	            'capability_type'    => 'post',
	            'has_archive'        => true,
	            'hierarchical'       => false,
	            'menu_position'      => null,
	            'supports'           => array('title'),
	            'taxonomies'         => array('skills', 'education'),
	        )
	    );

	    // Register Taxonomy "Skills"
	    register_taxonomy('skills', 'profile',
	        array(
	            'label' 		=> __('Skills'),
	            'hierarchical' 	=> true,
	            'public' 		=> true,
	            'rewrite' 		=> array('slug' => 'skills'),
	        )
	    );

	    // Register Taxonomy "Education"
	    register_taxonomy('education', 'profile',
	        array(
	            'label' 		=> __('Education'),
	            'hierarchical' 	=> true,
	            'public' 		=> true,
	            'rewrite' 		=> array('slug' => 'education'),
	        )
	    );

	    // Register Metadata Fields
	    register_post_meta('profile', 'dob', array('type' => 'date', 'label' => 'Date of Birth'));
	    register_post_meta('profile', 'hobbies', array('type' => 'text', 'label' => 'Hobbies'));
	    register_post_meta('profile', 'interests', array('type' => 'text', 'label' => 'Interests'));
	    register_post_meta('profile', 'years_of_experience', array('type' => 'number', 'label' => 'Years of Experience'));
	    register_post_meta('profile', 'ratings', array('type' => 'number', 'label' => 'Ratings'));
	    register_post_meta('profile', 'jobs_completed', array('type' => 'number', 'label' => 'No Jobs Completed'));
	}

	// Add custom meta boxes for metadata fields
	public function add_custom_meta_boxes() {

		add_meta_box('profile_meta_box', 'Profile Metadata', array($this ,  'render_profile_meta_box'), 'profile', 'normal', 'default');
	}

	// Function to render the custom meta box
	public function render_profile_meta_box($post) {

	    // Fetch existing values for the metadata fields
	    $dob 					= get_post_meta($post->ID, 'dob', true);
	    $hobbies 				= get_post_meta($post->ID, 'hobbies', true);
	    $interests 				= get_post_meta($post->ID, 'interests', true);
	    $years_of_experience 	= get_post_meta($post->ID, 'years_of_experience', true);
	    $ratings 				= get_post_meta($post->ID, 'ratings', true);
	    $jobs_completed 		= get_post_meta($post->ID, 'jobs_completed', true);

	    // Output HTML for the meta box
	    ?>
	    
	    <label for="dob" class="dob-meta-box-label">Date of Birth</label>
	    <input type="date" id="dob" name="dob" value="<?php echo esc_attr($dob); ?>" class="dob-meta-box-input" required>

	    <label for="hobbies">Hobbies</label>
	    <input type="text" id="hobbies" name="hobbies" value="<?php echo esc_attr($hobbies); ?>">

	    <label for="interests">Interests</label>
	    <input type="text" id="interests" name="interests" value="<?php echo esc_attr($interests); ?>">

	    <label for="years_of_experience">Years of Experience</label>
	    <input type="number" id="years_of_experience" name="years_of_experience" min="1" max="30" value="<?php echo esc_attr($years_of_experience); ?>">

	    <label for="ratings">Ratings</label>
	    <input type="number" id="ratings" name="ratings" min="1" max="5" value="<?php echo esc_attr($ratings); ?>">

	    <label for="jobs_completed">No Jobs Completed</label>
	    <input type="number" id="jobs_completed" name="jobs_completed" min="1" max="100" value="<?php echo esc_attr($jobs_completed); ?>">
	    <?php
	}

	// Save post meta data when saving the post
	public function save_profile_meta_data($post_id) {
	    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
	        return;
	    }

	    if(!empty($_POST['dob'])){
	    	update_post_meta($post_id, 'dob', sanitize_text_field($_POST['dob']));	
	    }
	    
	    // Save metadata fields
	    if(!empty($_POST['dob'])){
	        $dob_timestamp = strtotime($_POST['dob']);
	        $now = current_time('timestamp');
	        $age = date('Y', $now) - date('Y', $dob_timestamp);
	        update_post_meta($post_id, 'age', $age);
	    }
	    
	    if(!empty($_POST['hobbies'])){
	    	update_post_meta($post_id, 'hobbies', sanitize_text_field($_POST['hobbies']));
		}

		if(!empty($_POST['interests'])){
	    	update_post_meta($post_id, 'interests', sanitize_text_field($_POST['interests']));
		}

		if(!empty($_POST['years_of_experience'])){
	    	update_post_meta($post_id, 'years_of_experience', absint($_POST['years_of_experience']));
		}

		if(!empty($_POST['ratings'])){
	    	update_post_meta($post_id, 'ratings', absint($_POST['ratings']));
		}

		if(!empty($_POST['jobs_completed'])){
	    	update_post_meta($post_id, 'jobs_completed', absint($_POST['jobs_completed']));
		}
	}

}
