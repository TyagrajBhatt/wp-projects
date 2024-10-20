<?php
class WP_Projects_Shortcode {
    public function enqueue_styles() {
        wp_enqueue_style('slick', WP_PROJECTS_PLUGIN_URL . 'public/css/slick.css', array(), '1.8.1', 'all');
        wp_enqueue_style('wp-projects-public', WP_PROJECTS_PLUGIN_URL . 'public/css/wp-projects-public.css', array(), WP_PROJECTS_VERSION, 'all');

        wp_enqueue_style('slick-theme.min.css', WP_PROJECTS_PLUGIN_URL . 'public/css/slick-theme.min.css', array(), WP_PROJECTS_VERSION, 'all');
    }

    public function enqueue_scripts() {
        wp_enqueue_script('slick', WP_PROJECTS_PLUGIN_URL . 'public/js/slick.min.js', array('jquery'), '1.8.1', true);
        wp_enqueue_script('wp-projects-public', WP_PROJECTS_PLUGIN_URL . 'public/js/wp-projects-public.js', array('jquery', 'slick'), WP_PROJECTS_VERSION, true);

        $options = get_option('wp_projects_settings');
        wp_localize_script('wp-projects-public', 'wpProjectsSettings', array(
            'autoplay' => isset($options['autoplay']) ? (bool) $options['autoplay'] : false,
            'autoplaySpeed' => isset($options['autoplay_speed']) ? absint($options['autoplay_speed']) : 2000,
            'showPagination' => isset($options['show_pagination']) ? (bool) $options['show_pagination'] : true,
        ));
    }

    public function render_shortcode($atts) {
        $atts = shortcode_atts(array(
            'count' => 4,
            'category' => '',
        ), $atts, 'wp_projects');

        $options = get_option('wp_projects_settings');
        $default_count = isset($options['default_count']) ? absint($options['default_count']) : 4;

        $args = array(
            'post_type' => 'wp_project',
            'posts_per_page' => isset($atts['count']) ? absint($atts['count']) : $default_count,
        );

        if (!empty($atts['category'])) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'wp_project_category',
                    'field' => 'slug',
                    'terms' => sanitize_text_field($atts['category']),
                ),
            );
        }

        $projects = new WP_Query($args);

        ob_start();

        if ($projects->have_posts()) :




            echo '<div class="wp-projects-slider modern-slider">';
                    while ($projects->have_posts()) : $projects->the_post();
        $location = get_post_meta(get_the_ID(), '_wp_project_location', true);
        $completion_date = get_post_meta(get_the_ID(), '_wp_project_completion_date', true);
        ?>
        <!-- Project Item -->
        <div class="project-item">
            <?php if (has_post_thumbnail()) : ?>
                <div class="project-image">
                    <?php the_post_thumbnail('full'); ?>
                    <div class="overlay"></div>
                    <div class="project-info">
                        <h3 class="project-title"><?php the_title(); ?></h3>
                        <?php if ($location) : ?>
                            <p class="project-location"><i class="fas fa-map-marker-alt"></i> <?php echo esc_html($location); ?></p>
                        <?php endif; ?>
                        <?php if ($completion_date) : ?>
                            <p class="project-date"><i class="fas fa-calendar-alt"></i> <?php echo esc_html($completion_date); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <!-- // Project Item -->
    <?php endwhile;
            echo '</div>';
        else :
            echo '<p>' . __('No projects found.', 'wp-projects') . '</p>';
        endif;

        wp_reset_postdata();

        return ob_get_clean();
    }
}