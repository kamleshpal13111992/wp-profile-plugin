<?php
/*
Plugin Name: Wp Kamlesh Pal
Description: Custom WordPress plugin for managing coupons.
Version: 1.0
Author: Kamlesh Pal
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function wp_kamlesh_pal_load_textdomain() {
    load_plugin_textdomain('wp-kamlesh-pal', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'wp_kamlesh_pal_load_textdomain');

// Activation Hook
register_activation_hook(__FILE__, 'wp_kamlesh_pal_activate');
register_uninstall_hook(__FILE__, 'wp_kamlesh_pal_uninstall');

function wp_kamlesh_pal_activate() {

	global $wpdb;
    // Create custom table name
    $table_name = $wpdb->prefix . 'coupons_kamlesh_pal';

    // SQL to create the custom table
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        amount VARCHAR(255) NOT NULL,
        image VARCHAR(255),
        category VARCHAR(50),
        availability TEXT,
        PRIMARY KEY (id)
    ) {$wpdb->get_charset_collate()};";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Create Products menu
    add_menu_page('Products', 'Products', 'manage_options', 'wp-kamlesh-pal-products', 'wp_kamlesh_pal_products_page');

    // Create Coupons submenu
    add_submenu_page('wp-kamlesh-pal-products', 'Coupons', 'Coupons', 'manage_options', 'wp-kamlesh-pal-products', 'wp_kamlesh_pal_products_page');

    // Create Add Coupon submenu
    add_submenu_page('wp-kamlesh-pal-products', 'Add Coupon', 'Add Coupon', 'manage_options', 'wp-kamlesh-pal-add-coupon', 'wp_kamlesh_pal_add_coupon_page');
}

// Uninstall callback
function wp_kamlesh_pal_uninstall() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'coupons_kamlesh_pal';
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}

add_action( 'admin_menu', 'wp_kamlesh_pal_activate' );

require plugin_dir_path( __FILE__ ) . 'includes/class-list-table.php';

function wp_kamlesh_pal_products_page() {

    global $wpdb;
    // Custom table name
    $table_name = $wpdb->prefix . 'coupons_kamlesh_pal';

    // Check for success message
    if (isset($_GET['success']) && $_GET['success'] == 1) {
        echo '<div class="notice notice-success is-dismissible"><p>Coupon updated successfully.</p></div>';
    }

    if (isset($_GET['success']) && $_GET['success'] == 2) {
        echo '<div class="notice notice-success is-dismissible"><p>Coupon Added successfully.</p></div>';
    }

    $coupon_list_table = new Coupon_List_Table();
    $coupon_list_table->prepare_items();
    ?>

    <div class="wrap">
        <h1 class="wp-heading-inline">Coupons</h1>
        <hr class="wp-header-end">
        <form method="post">
            <?php $coupon_list_table->display(); ?>
        </form>
    </div>

    <?php
}

function wp_kamlesh_pal_add_coupon_page() {
    global $wpdb;
    // Check if an ID is provided for editing an existing coupon
    $coupon_id = isset($_GET['edit_coupon']) ? absint($_GET['edit_coupon']) : 0;

    // Retrieve coupon data for editing
    $coupon = array(
        'title' 		=> '',
        'description' 	=> '',
        'amount' 		=> '',
        'image' 		=> '',
        'category' 		=> '',
        'availability' 	=> array(),
    );

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $title 			= sanitize_text_field($_POST['coupon_title']);
        $description 	= sanitize_textarea_field($_POST['coupon_description']);
        $amount 		= sanitize_text_field($_POST['coupon_amount']);
        $image 			= sanitize_text_field($_POST['coupon_image']);
        $category 		= sanitize_text_field($_POST['coupon_category']);
        $availability 	= serialize($_POST['availability']);

        $validation_errors = array();

        if (empty($title)) {
            $validation_errors[] = 'Title is required.';
        }

        if (empty($amount)) {
            $validation_errors[] = 'Amount is required.';
        }

        // Check if there are validation errors
        if (!empty($validation_errors)) {
            // Validation failed, display errors and exit
            wp_kamlesh_pal_display_errors($validation_errors);
        }

        // Custom table name
        $table_name = $wpdb->prefix . 'coupons_kamlesh_pal';

        if ($coupon_id > 0) {
            // Update existing coupon
            $wpdb->update(
                $table_name,
                array(
                    'title' 		=> $title,
                    'description' 	=> $description,
                    'amount' 		=> $amount,
                    'image' 		=> $image,
                    'category' 		=> $category,
                    'availability' 	=> $availability,
                ),
                array('id' => $coupon_id)
            );
//wp_redirect(admin_url('admin.php?page=wp-mayur-upadhyay-products&success=1'));
            ?>
            <script> location.replace("admin.php?page=wp-kamlesh-pal-products&success=1"); </script>
            <?php


        } else {
            // Insert new coupon
            $wpdb->insert(
                $table_name,
                array(
                    'title' 		=> $title,
                    'description' 	=> $description,
                    'amount' 		=> $amount,
                    'image' 		=> $image,
                    'category'		=> $category,
                    'availability' 	=> $availability,
                )
            );

            ?>
            <script> location.replace("admin.php?page=wp-kamlesh-pal-products&success=2"); </script>
            <?php
        }
    }

    // Retrieve coupon data for editing
    if ($coupon_id > 0) {
        $table_name 	= $wpdb->prefix . 'coupons_kamlesh_pal';
        $coupon_data 	= $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $coupon_id), ARRAY_A);

        if ($coupon_data) {
            $coupon['title'] 		= $coupon_data['title'];
            $coupon['description'] 	= $coupon_data['description'];
            $coupon['amount'] 		= $coupon_data['amount'];
            $coupon['image'] 		= $coupon_data['image'];
            $coupon['category'] 	= $coupon_data['category'];
            $coupon['availability'] = unserialize($coupon_data['availability']);
        }
    }

    ?>
    <div class="wrap">
        <h1><?php echo ($coupon_id > 0) ? esc_html_e('Edit Coupon', 'wp-mayur-upadhyay') : esc_html_e('Add Coupon', 'wp-mayur-upadhyay'); ?></h1>
        
        <form method="post" action="">
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="coupon_title"><?php esc_html_e('Title', 'wp-kamlesh-pal'); ?><span style="color:red;">*</span></label></th>
                    <td><input type="text" name="coupon_title" id="coupon_title" value="<?php echo esc_attr($coupon['title']); ?>"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="coupon_description"><?php esc_html_e('Description', 'wp-kamlesh-pal'); ?></label></th>
                    <td><textarea name="coupon_description" id="coupon_description" rows="4"><?php echo esc_textarea($coupon['description']); ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><label for="coupon_amount"><?php esc_html_e('Coupon Amount', 'wp-kamlesh-pal'); ?><span style="color:red;">*</span></label></th>
                    <td><input type="text" name="coupon_amount" id="coupon_amount" value="<?php echo esc_attr($coupon['amount']); ?>"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="coupon_image"><?php esc_html_e('Image', 'wp-kamlesh-pal'); ?></label></th>
                    <td><input type="button" id="upload_image_button" value="Upload Image"></td>
                    <input type="hidden" id="coupon_image" name="coupon_image" value="<?php echo esc_attr($coupon['image']); ?>">
                </tr>
                <tr>
                    <th scope="row"><label for="coupon_category"><?php esc_html_e('Category', 'wp-kamlesh-pal'); ?></label></th>
                    <td>
                        <select name="coupon_category" id="coupon_category">
                            <option value="category1" <?php selected($coupon['category'], 'category1'); ?>>Category 1</option>
                            <option value="category2" <?php selected($coupon['category'], 'category2'); ?>>Category 2</option>
                            <option value="category3" <?php selected($coupon['category'], 'category3'); ?>>Category 3</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="coupon_availability"><?php esc_html_e('Availability', 'wp-kamlesh-pal'); ?></label></th>
                    <td>
                        <input type="checkbox" name="availability[]" value="option1" <?php checked(in_array('option1', $coupon['availability'])); ?>> Option 1<br>
                        <input type="checkbox" name="availability[]" value="option2" <?php checked(in_array('option2', $coupon['availability'])); ?>> Option 2<br>
                        <input type="checkbox" name="availability[]" value="option3" <?php checked(in_array('option3', $coupon['availability'])); ?>> Option 3<br>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="coupon_id" value="<?php echo esc_attr($coupon_id); ?>">
            <?php submit_button(($coupon_id > 0) ? 'Update Coupon' : 'Add Coupon'); ?>
        </form>
    </div>

    <script>
        jQuery(document).ready(function($) {
		    var mediaUploader;

		    $('#upload_image_button').on('click', function(e) {
		        e.preventDefault();

		        if (mediaUploader) {
		            mediaUploader.open();
		            return;
		        }

		        mediaUploader = wp.media.frames.file_frame = wp.media({
		            title: 'Choose Image',
		            button: {
		                text: 'Choose Image'
		            },
		            multiple: false
		        });

		        mediaUploader.on('select', function() {
		            var attachment = mediaUploader.state().get('selection').first().toJSON();
		            //console.log(attachment.url);
		            jQuery("#coupon_image").val(attachment.url);
		        });

		        mediaUploader.open();
		    });
		});

    </script>

    <?php
}

function load_media_files() {
    wp_enqueue_media();
}

add_action( 'admin_enqueue_scripts', 'load_media_files' );

function wp_kamlesh_pal_display_errors($errors) {
    $error_message = implode('<br>', $errors);
    wp_die($error_message, 'Validation Error');
}
