<?php

//Creating Main Class for Projects handing 
class WP_Projects_Loader {
    

    protected $actions;
    protected $filters;

    public function __construct() {
        $this->actions = array();
        $this->filters = array();

        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    private function load_dependencies() {
        // Load any additional dependencies here
    }

    private function define_admin_hooks() {
        $plugin_admin = new WP_Projects_Admin();

        $this->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->add_action('admin_menu', $plugin_admin, 'add_options_page');
        $this->add_action('admin_init', $plugin_admin, 'register_settings');
    }

    private function define_public_hooks() {

        $plugin_public = new WP_Projects_Shortcode();

        $this->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->add_shortcode('wp_projects', $plugin_public, 'render_shortcode');
    }

    public function add_action($hook, $component, $callback, $priority = 10, $accepted_args = 1) {


        $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $accepted_args);
    }

    public function add_filter($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $accepted_args);
    }

    public function add_shortcode($tag, $component, $callback) {
        add_shortcode($tag, array($component, $callback));
    

    }


    private function add($hooks, $hook, $component, $callback, $priority, $accepted_args) {
        

        $hooks[] = array(
            'hook'          => $hook,
            'component'     => $component,
            'callback'      => $callback,
            'priority'      => $priority,
            'accepted_args' => $accepted_args
        );

        return $hooks;
    }

    public function run() {
        foreach ($this->filters as $hook) {
            
            add_filter($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority'], $hook['accepted_args']);
        }

        foreach ($this->actions as $hook) {
            add_action($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority'], $hook['accepted_args']);
        }
    }
}