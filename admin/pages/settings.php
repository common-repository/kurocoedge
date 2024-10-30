<?php

function kurocoedge_settings_render() {
    //Wordpress uses echo approach instead of returning the template strings. Therefore need to parse the buffer.
    ob_start();
    require_once plugin_dir_path(__FILE__).'../templates/settings.php';
    $html = ob_get_contents();
    ob_end_clean();

    return $html;
}

//Note: Naming convention: kurocoedge_settings_<section_id>_<field_id>
function kurocoedge_settings_configure() {
    // All the settings that can be set on this Plugin
    register_setting('kurocoedge_settings', 'kurocoedge_connection_site_key',      array('sanitize_callback' => 'kurocoedge_settings_connection_site_key_validator',      'default' => ''  ));
    register_setting('kurocoedge_settings', 'kurocoedge_connection_api_url',       array('sanitize_callback' => 'kurocoedge_settings_connection_api_url_validator',       'default' => ''  ));
    register_setting('kurocoedge_settings', 'kurocoedge_connection_edge_key',      array('sanitize_callback' => 'kurocoedge_settings_connection_edge_key_validator',      'default' => ''  ));
    register_setting('kurocoedge_settings', 'kurocoedge_general_enable',           array('sanitize_callback' => 'kurocoedge_settings_general_enable_validator',           'default' => '0' ));
    register_setting('kurocoedge_settings', 'kurocoedge_general_auto_clear_cache', array('sanitize_callback' => 'kurocoedge_settings_general_auto_clear_cache_validator', 'default' => '0' ));

    //Secions for better UI/UX
    add_settings_section('kurocoedge_settings_connection', 'Connection Settings', 'kurocoedge_settings_connection_subtext_render', 'kurocoedge_settings');
    add_settings_section('kurocoedge_settings_general',    'General Settings',    'kurocoedge_settings_general_subtext_render',    'kurocoedge_settings');
    
    //Configuration fields per section
    //Section 1 - Connection Settings
    add_settings_field('kurocoedge_settings_connection_site_key', 'Site Key', 'kurocoedge_settings_connection_site_key_render', 'kurocoedge_settings', 'kurocoedge_settings_connection');
    add_settings_field('kurocoedge_settings_connection_api_url',  'API URL',  'kurocoedge_settings_connection_api_url_render',  'kurocoedge_settings', 'kurocoedge_settings_connection');
    add_settings_field('kurocoedge_settings_connection_edge_key', 'Edge Key', 'kurocoedge_settings_connection_edge_key_render', 'kurocoedge_settings', 'kurocoedge_settings_connection');
    //Add Site Key (String one), not the ID

    //Section 2 - General Settings
    add_settings_field('kurogo_edge_settings_general_enable',           'Enable KurocoEdge',  'kurocoedge_settings_general_enable_render',           'kurocoedge_settings', 'kurocoedge_settings_general');
    add_settings_field('kurogo_edge_settings_general_auto_clear_cache', 'Auto Clear Cache',   'kurocoedge_settings_general_auto_clear_cache_render', 'kurocoedge_settings', 'kurocoedge_settings_general');
}


//Code block for Connection Settings Start
function kurocoedge_settings_connection_subtext_render() {
    echo '<p>Here you can set all the options for using the API Connection</p>';
}

function kurocoedge_settings_connection_site_key_render() {
    $value = get_option('kurocoedge_connection_site_key');
    echo    '<input class="regular-text" name="kurocoedge_connection_site_key" type="text" value="' .esc_attr($value). '" />'       .
            '<p class="description" id="home-description">'                                                                         .
                'You can read more about site key '                                                                                 . 
                '<a target="_blank" rel="noreferrer" href="https://kuroco.app/docs/management/account/">here</a>.'                  .
            '</p>';
}

function kurocoedge_settings_connection_api_url_render() {
    $value = get_option('kurocoedge_connection_api_url');
    echo '<input class="regular-text" name="kurocoedge_connection_api_url" type="text" value="' .esc_url($value). '" />';
}

function kurocoedge_settings_connection_edge_key_render() {
    $value = get_option('kurocoedge_connection_edge_key');
    echo '<input class="regular-text" name="kurocoedge_connection_edge_key" type="text" value="' .esc_attr($value). '" />';
}


function kurocoedge_settings_connection_api_url_validator($api_url) {
    $enabled = sanitize_text_field($_REQUEST['kurocoedge_general_enable']);
    if($enabled == '1') {
        if(strlen($api_url) == 0) {
            add_settings_error('kurocoedge_setting', 'kurocoedge_connection_api_url_empty', 'The Value of API URL cannot be empty', 'error');
            $disable = true;
        } elseif(strpos($api_url, sanitize_text_field($_REQUEST['kurocoedge_connection_site_key'])) !== 8) {
            add_settings_error('kurocoedge_setting', 'kurocoedge_connection_api_url_or_site_key_invalid', 'The Value of API URL or Site Key is invalid', 'error');
            $disable = true;
        } elseif(wp_remote_get("{$api_url}/direct/menu/ping/")['body'] !== 'ping ok') {
            add_settings_error('kurocoedge_setting', 'kurocoedge_connection_invalid_connection', 'Not able to connect to Kuroco Environment', 'error');
            $disable = true;
        }
    }

    if($disable) {
        add_settings_error('kurocoedge_setting', 'kurocoedge_connection_cannot_enable', 'Cannot enable the plugin, please fix the errors first', 'error');
        $_REQUEST['kurocoedge_general_enable'] = '0';
        $_POST['kurocoedge_general_enable']    = '0';
    }

    return $api_url;
}

function kurocoedge_settings_connection_site_key_validator($site_key) {
    $site_key = trim($site_key, '/');
    $enabled = sanitize_text_field($_REQUEST['kurocoedge_general_enable']);
    $disable = false;
    
    if($enabled == '1') {
        if(strlen($site_key) == 0) {
            add_settings_error('kurocoedge_setting', 'kurocoedge_connection_site_key_empty', 'The Value of Site Key cannot be empty', 'error');
            $disable = true;
        }
    }

    if($disable) {
        add_settings_error('kurocoedge_setting', 'kurocoedge_connection_cannot_enable', 'Cannot enable the plugin, please fix the errors first', 'error');
        $_REQUEST['kurocoedge_general_enable'] = '0';
        $_POST['kurocoedge_general_enable']    = '0';
    }

    return $site_key;
}

function kurocoedge_settings_connection_edge_key_validator($edge_key) {
    $enabled = sanitize_text_field($_REQUEST['kurocoedge_general_enable']);
    $disable = false;
    
    if($enabled == "1") {
        if(strlen($edge_key) == 0) {
            add_settings_error('kurocoedge_setting', 'kurocoedge_connection_edge_key_empty', 'The Value of Edge Key cannot be empty', 'error');
            $disable = true;
        }
    }

    if($disable) {
        add_settings_error('kurocoedge_setting', 'kurocoedge_connection_cannot_enable', 'Cannot enable the plugin, please fix the errors first', 'error');
        $_REQUEST['kurocoedge_general_enable'] = '0';
        $_POST['kurocoedge_general_enable']    = '0';
    }

    return $edge_key;
}

//Code block for Connection Settings End




//Code block for General Settings Start

function kurocoedge_settings_general_subtext_render() {
    echo '<p>Here you can set all the options for using the API</p>';
}

function kurocoedge_settings_general_enable_render() {
    $value       = get_option('kurocoedge_general_enable');
    $value       = esc_attr($value);
    $checked     = '';
    $disbaled    = 'disabled';
    if($value    == '1') {
        $checked  = 'checked';
        $disbaled = '';
    }
    echo    '<input id="kurocoedge_general_enable" class="regular-text" name="kurocoedge_general_enable" value="1" type="checkbox" ' .esc_attr($checked). ' />'     .
            '<label for="kurocoedge_general_enable">Enable connection to KurocoEdge.</label>'                                                                       .
            '<br />'                                                                                                                                                .
            '<br />'                                                                                                                                                .
            '<button id="kurocoedge_general_sync_settings_now" class="button button-secondary ' .esc_attr($disbaled). '">Sync Settings now</button>';
}

function kurocoedge_settings_general_auto_clear_cache_render() {
    $value       = get_option('kurocoedge_general_auto_clear_cache');
    $enabled     = get_option('kurocoedge_general_enable');
    $value       = esc_attr($value);
    $enabled     = esc_attr($enabled);
    $disbaled    = 'disabled';
    $checked     = '';
    if($value    == '1') { $checked  = 'checked'; }
    if($enabled  == '1') { $disbaled = '';        }
    echo    '<input id="kurocoedge_general_auto_clear_cache" class="regular-text" name="kurocoedge_general_auto_clear_cache" value="1" type="checkbox" ' .esc_attr($checked). ' />'     .
            '<label for="kurocoedge_general_auto_clear_cache">Auto Clear the CDN Cache when any post data is updated.</label>'                                                          .
            '<br />'                                                                                                                                                                    .
            '<br />'                                                                                                                                                                    .
            '<button id="kurocoedge_general_clear_cache_now" class="button button-secondary ' .esc_attr($disbaled). '">Clear Cache now</button>';
}

function kurocoedge_settings_general_enable_validator($enable) {
    if(!in_array($enable, array(0, 1))) {
        $enable = '0';
        add_settings_error('kurocoedge_setting', 'kurocoedge_general_enable_invalid', 'The Value of Enable Flag is invalid', 'error');
    }
    return $enable;
}

function kurocoedge_settings_general_auto_clear_cache_validator($auto_clear_cache) {
    if(!in_array($auto_clear_cache, array(0, 1))) {
        $auto_clear_cache = '0';
        add_settings_error('kurocoedge_setting', 'kurocoedge_general_auto_clear_cache_invalid', 'The Value of Auto Clear Cache is invalid', 'error');
    }
    return $auto_clear_cache;
}

//Code block for General Settings end