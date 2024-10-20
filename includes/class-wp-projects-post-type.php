<?php
//Class intilisations for creating CPT named Projects


class WP_Projects_Post_Type {
    public function __construct() {
        add_action('init', array($this, 'register_post_type'));
        add_action('init', array($this, 'register_taxonomy'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_box_data'));
    }

    public function register_post_type() {
        $labels = array(
            'name'               => _x('Projects', 'post type general name', 'wp-projects'),
            'singular_name'      => _x('Project', 'post type singular name', 'wp-projects'),
            'menu_name'          => _x('Projects', 'admin menu', 'wp-projects'),
            'name_admin_bar'     => _x('Project', 'add new on admin bar', 'wp-projects'),
            'add_new'            => _x('Add New', 'project', 'wp-projects'),
            'add_new_item'       => __('Add New Project', 'wp-projects'),
            'new_item'           => __('New Project', 'wp-projects'),
            'edit_item'          => __('Edit Project', 'wp-projects'),
            'view_item'          => __('View Project', 'wp-projects'),
            'all_items'          => __('All Projects', 'wp-projects'),
            'search_items'       => __('Search Projects', 'wp-projects'),
            'parent_item_colon'  => __('Parent Projects:', 'wp-projects'),
            'not_found'          => __('No projects found.', 'wp-projects'),
            'not_found_in_trash' => __('No projects found in Trash.', 'wp-projects')
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'project'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments')
        );

        register_post_type('wp_project', $args);
    }

    public function register_taxonomy() {
        $labels = array(
            'name'              => _x('Project Categories', 'taxonomy general name', 'wp-projects'),
            'singular_name'     => _x('Project Category', 'taxonomy singular name', 'wp-projects'),
            'search_items'      => __('Search Project Categories', 'wp-projects'),
            'all_items'         => __('All Project Categories', 'wp-projects'),
            'parent_item'       => __('Parent Project Category', 'wp-projects'),
            'parent_item_colon' => __('Parent Project Category:', 'wp-projects'),
            'edit_item'         => __('Edit Project Category', 'wp-projects'),
            'update_item'       => __('Update Project Category', 'wp-projects'),
            'add_new_item'      => __('Add New Project Category', 'wp-projects'),
            'new_item_name'     => __('New Project Category Name', 'wp-projects'),
            'menu_name'         => __('Project Categories', 'wp-projects'),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'project-category'),
        );

        register_taxonomy('wp_project_category', array('wp_project'), $args);
    }

    public function add_meta_boxes() {
        add_meta_box(
            'wp_project_details',
            __('Project Details', 'wp-projects'),
            array($this, 'render_meta_box'),
            'wp_project',
            'normal',
            'default'
        );
    }

    public function render_meta_box($post) {
        wp_nonce_field('wp_project_meta_box', 'wp_project_meta_box_nonce');

        $location = get_post_meta($post->ID, '_wp_project_location', true);
        $completion_date = get_post_meta($post->ID, '_wp_project_completion_date', true);

        echo '<p>';
        echo '<label for="wp_project_location">' . __('Project Location', 'wp-projects') . '</label><br>';
        echo '<input type="text" id="wp_project_location" name="wp_project_location" value="' . esc_attr($location) . '" size="25" />';
        echo '</p>';

        echo '<p>';
        echo '<label for="wp_project_completion_date">' . __('Completion Date', 'wp-projects') . '</label><br>';
        echo '<input type="date" id="wp_project_completion_date" name="wp_project_completion_date" value="' . esc_attr($completion_date) . '" />';
        echo '</p>';
    }

    public function save_meta_box_data($post_id) {
        if (!isset($_POST['wp_project_meta_box_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['wp_project_meta_box_nonce'], 'wp_project_meta_box')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST['wp_project_location'])) {
            $location = sanitize_text_field($_POST['wp_project_location']);
            update_post_meta($post_id, '_wp_project_location', $location);
        }

        if (isset($_POST['wp_project_completion_date'])) {
            $completion_date = sanitize_text_field($_POST['wp_project_completion_date']);
            update_post_meta($post_id, '_wp_project_completion_date', $completion_date);
        }
    }
}

new WP_Projects_Post_Type();