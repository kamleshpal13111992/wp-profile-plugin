<?php

if(!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/screen.php' );
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Coupon_List_Table extends WP_List_Table {
    public function __construct() {
        parent::__construct(array(
            'singular' => 'coupon',
            'plural'   => 'coupons',
            'ajax'     => false,
        ));
    }

    public function column_title($item) {
        $edit_link = '<div class="row-actions"><span class="edit"><a href="' . esc_url(admin_url('admin.php?page=wp-kamlesh-pal-add-coupon&edit_coupon=' . $item['id'])) . '">Edit</a></span></div>';
        return sprintf('%1$s %2$s', $item['title'], $this->row_actions(array('edit' => $edit_link)));
    }

    public function column_availability($item) {
        $availability = unserialize($item['availability']);
        if(!empty($availability)){
            return implode(', ', $availability);
        }
    }

    public function get_columns() {
        return array(
            'image'         => 'Image',
            'title'         => 'Title',
            'amount'        => 'Coupon Amount',
            'category'      => 'Category',
            'availability'  => 'Availability',
        );
    }

    public function prepare_items() {
        global $wpdb;
        // Custom table name
        $table_name     = $wpdb->prefix . 'coupons_kamlesh_pal';
        $per_page       = 10;
        $current_page   = $this->get_pagenum();
        $total_items    = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
        ));

        $columns    = $this->get_columns();
        $hidden     = array();
        $sortable   = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->items = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $table_name ORDER BY id DESC LIMIT %d OFFSET %d", $per_page, ($current_page - 1) * $per_page),
            ARRAY_A
        );
    }

    public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'image':
                $image_html = '<img src="' . esc_url($item['image']) . '" style="max-width: 50px; max-height: 50px;" alt="Image">';
                return $image_html;
            default:
                return isset($item[$column_name]) ? esc_html($item[$column_name]) : '';
        }
    }
}