<?php
class WP_Projects_Admin {
    public function enqueue_styles() {
        wp_enqueue_style('wp-projects-admin', WP_PROJECTS_PLUGIN_URL . 'admin/css/wp-projects-admin.css', array(), WP_PROJECTS_VERSION, 'all');
    }

    public function enqueue_scripts() {
        wp_enqueue_script('wp-projects-admin', WP_PROJECTS_PLUGIN_URL . 'admin/js/wp-projects-admin.js', array('jquery'), WP_PROJECTS_VERSION, false);
    }

    public function add_options_page() {
        add_options_page(
            __('WP Projects Settings', 'wp-projects'),
            __('WP Projects', 'wp-projects'),
            'manage_options',
            'wp-projects-settings',
            array($this, 'render_options_page')
        );
    }

    public function render_options_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
            <?php
                settings_fields('wp_projects_options');
                do_settings_sections('wp-projects-settings');
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    public function register_settings() {
        register_setting('wp_projects_options', 'wp_projects_settings', array($this, 'sanitize_settings'));

        add_settings_section(
            'wp_projects_general_section',
            __('General Settings', 'wp-projects'),
            array($this, 'render_general_section'),
            'wp-projects-settings'
        );

        add_settings_field(
            'wp_projects_default_count',
            __('Default Project Count', 'wp-projects'),
            array($this, 'render_default_count_field'),
            'wp-projects-settings',
            'wp_projects_general_section'
        );

        add_settings_field(
            'wp_projects_autoplay',
            __('Enable Autoplay', 'wp-projects'),
            array($this, 'render_autoplay_field'),
            'wp-projects-settings',
            'wp_projects_general_section'
        );

        add_settings_field(
            'wp_projects_autoplay_speed',
            __('Autoplay Speed (ms)', 'wp-projects'),
            array($this, 'render_autoplay_speed_field'),
            'wp-projects-settings',
            'wp_projects_general_section'
        );

        add_settings_field(
            'wp_projects_show_pagination',
            __('Show Pagination', 'wp-projects'),
            array($this, 'render_show_pagination_field'),
            'wp-projects-settings',
            'wp_projects_general_section'
        );
    }

    public function sanitize_settings($input) {
        $sanitized_input = array();

        if (isset($input['default_count'])) {
            $sanitized_input['default_count'] = absint($input['default_count']);
        }

        if (isset($input['autoplay'])) {
            $sanitized_input['autoplay'] = (bool) $input['autoplay'];
        }

        if (isset($input['autoplay_speed'])) {
            $sanitized_input['autoplay_speed'] = absint($input['autoplay_speed']);
        }

        if (isset($input['show_pagination'])) {
            $sanitized_input['show_pagination'] = (bool) $input['show_pagination'];
        }

        return $sanitized_input;
    }

    public function render_general_section() {
        echo '<p>' . __('Configure the general settings for the WP Projects plugin.', 'wp-projects') . '</p>';
    }

    public function render_default_count_field() {
        $options = 

 get_option('wp_projects_settings');
        $value = isset($options['default_count']) ? $options['default_count'] : 4;
        echo '<input type="number" name="wp_projects_settings[default_count]" value="' . esc_attr($value) . '" min="1" max="20" />';
    }

    public function render_autoplay_field() {
        $options = get_option('wp_projects_settings');
        $checked = isset($options['autoplay']) && $options['autoplay'] ? 'checked' : '';
        echo '<input type="checkbox" name="wp_projects_settings[autoplay]" value="1" ' . $checked . ' />';
    }

    public function render_autoplay_speed_field() {
        $options = get_option('wp_projects_settings');
        $value = isset($options['autoplay_speed']) ? $options['autoplay_speed'] : 2000;
        echo '<input type="number" name="wp_projects_settings[autoplay_speed]" value="' . esc_attr($value) . '" min="1000" step="100" />';
    }

    public function render_show_pagination_field() {
        $options = get_option('wp_projects_settings');
        $checked = isset($options['show_pagination']) && $options['show_pagination'] ? 'checked' : '';
        echo '<input type="checkbox" name="wp_projects_settings[show_pagination]" value="1" ' . $checked . ' />';
    }
}