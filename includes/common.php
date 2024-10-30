<?php

function kurocoedge_get_version() {
    return KUROCOEDGE_VERSION;
}

function kurocoedge_clear_cache($post_ID = -1, $post_after = '', $post_before = '') {
    /**
     * !Note
     * With these parameters, we can change when to call the Clear Cache API or not.
     * 
     * Example:
     * Currently, it will call the clear cache API even when a comment is added to the post,
     * That is because the post (meta) data is changed. So, post_updated hook is fired.
     * Because of that, the clear cache api will be called. We can change that logic here.
     */

    $enabled    = get_option('kurocoedge_general_enable');
    $api_url    = get_option('kurocoedge_connection_api_url');
    $edge_key   = get_option('kurocoedge_connection_edge_key');
    if($enabled == '1') {
        //TODO: Ideally this should be a POST API with the edge_key as a header.
        $response = wp_remote_post(esc_url($api_url). '/direct/edge/clearcache/?nonce=' .wp_create_nonce('kurocoedge_clear_cache'). '&edge_key=' .esc_attr($edge_key));
        if($response['response']['code'] == '200') {
            return json_decode($response['body'], true);
        }
    }
    return new WP_REST_Response(null, 500);
}

function kurocoedge_init() {
    $enabled    = get_option('kurocoedge_general_enable');
    $auto_clear = get_option('kurocoedge_general_auto_clear_cache');

    if($enabled == '1' && $auto_clear == '1') {
        add_action('post_updated',             'kurocoedge_clear_cache',                 10, 3);
        add_filter('update_option_siteurl',    'kurocoedge_site_configuration_changed',  10, 3);
        add_action('update_option_home',       'kurocoedge_site_configuration_changed',  10, 3);
    } else {
        remove_action('post_updated',          'kurocoedge_clear_cache',                 10);
        remove_filter('update_option_siteurl', 'kurocoedge_site_configuration_changed',  10);
        remove_filter('update_option_home',    'kurocoedge_site_configuration_changed',  10);
    }
}

function kurocoedge_site_configuration_changed($old_url, $new_url, $change_type) {
    kurocoedge_update_saas();
}

function kurocoedge_update_saas() {
    $enabled  = get_option('kurocoedge_general_enable');
    $api_url  = get_option('kurocoedge_connection_api_url');
    $edge_key = get_option('kurocoedge_connection_edge_key');
    if($enabled == '1') {
        $data = [
            'home_url'          => get_home_url()               ,
            'site_url'          => get_site_url()               ,
            'content_url'       => content_url()                ,
            'plugins_url'       => plugins_url()                ,
            'is_multisite'      => is_multisite()               ,
            'rest_url'          => get_rest_url()               ,
            'admin_url'         => get_admin_url()              ,
            'includes_url'      => includes_url()               ,
            'guess_url'         => wp_guess_url()               ,
            'login_url'         => wp_login_url()               ,
            'register_url'      => wp_registration_url()        ,
            'lostpassword_url'  => wp_lostpassword_url()        ,
            'site_icon_url'     => site_icon_url()              ,
            'admin_url'         => get_admin_url()              ,
            'sitemap_url'       => get_sitemap_url('index')     ,
            'is_home_https'     => wp_is_home_url_using_https() ,
            'is_site_https'     => wp_is_site_url_using_https() ,
            'upload_dir'        => wp_upload_dir()['url']
        ];

        $response = wp_remote_post(esc_url($api_url). '/direct/edge/wp_plugin_sync/?nonce=' .wp_create_nonce('kurocoedge_update_saas'). '&edge_key=' .esc_attr($edge_key), array(
            'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
            'body'        => json_encode($data),
            'method'      => 'POST',
            'data_format' => 'body',
        ));
        if($response['response']['code'] == '200') {
            return json_decode($response['body'], true);
        }
    }
    return new WP_REST_Response(null, 500);
}