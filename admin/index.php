<?php
add_action('admin_menu',    'kurocoedge_test_plugin_setup_menu');
add_action('admin_init',    'kurocoedge_settings_data_setup');
add_action('rest_api_init', 'kurocoedge_add_sync_setting_endpoint');
add_action('rest_api_init', 'kurocoedge_clear_cache_endpoint');
 
function kurocoedge_test_plugin_setup_menu(){
    add_options_page('KurocoEdge Settings', 'KurocoEdge', 'manage_options', 'kurocoedge_settings', 'kurocoedge_settings_init' );
}

function kurocoedge_settings_init() {
    require_once plugin_dir_path(__FILE__).'pages/settings.php';
    add_filter('admin_footer_text', 'add_kuroco_message');

    $data = array(
        'api_url'  => get_option('kurocoedge_connection_api_url'),
        'edge_key' => get_option('kurocoedge_connection_edge_key'),
        'enable'   => get_option('kurocoedge_general_enable'),
        'base_url' => get_site_url(),
        'version'  => kurocoedge_get_version()
    );

    wp_enqueue_script('settings-js',   plugin_dir_url( __FILE__ ).'/js/settings.js');
    wp_localize_script('settings-js',  'kurocoedge_script_obj', $data); 
    wp_enqueue_style('settings-css',   plugin_dir_url( __FILE__ ).'/css/settings.css'); 
    echo kurocoedge_settings_render();
}

function kurocoedge_settings_data_setup() {
    require_once plugin_dir_path(__FILE__).'pages/settings.php';
    kurocoedge_settings_configure();
}

function add_kuroco_message() {
    $version = kurocoedge_get_version();
    echo '<span id="footer-thankyou">Thank you for using <a rel="noreferrer" href="http://kuroco.app" target="_blank">Kuroco.</a> &#124; Copyright &copy; 2022 Diverta Inc. All rights reserved &#124; KurocoEdge version v' .esc_attr($version). '</span>';
}

function kurocoedge_add_sync_setting_endpoint() {
    $version = kurocoedge_get_version();
    register_rest_route('/kurocoedge/v' .esc_attr($version), 'sync', array('methods' => 'GET', 'callback' => 'kurocoedge_update_saas'));
}

function kurocoedge_clear_cache_endpoint() {
    $version = kurocoedge_get_version();
    register_rest_route('/kurocoedge/v' .esc_attr($version), 'clearcache', array('methods' => 'GET', 'callback' => 'kurocoedge_clear_cache'));
}