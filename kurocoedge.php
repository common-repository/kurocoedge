<?php
/**
 * Plugin Name:        KurocoEdge
 * Plugin URI:         https://kuroco.app/
 * Description:        Use KurocoEdge on your WordPress website.
 * Plugin Logo:        https://rcms.g.kuroco-front.app/blog/assets/static/favicon.7b22250.bb13803b8bb59d10a02291df4288e1c0.png
 * Requires at least:  5.2
 * Requires PHP:       7.2
 * Version:            1.0.2
 * Author:             Diverta Inc.
 * Text Domain:        kurocoedge
 * Author URI:         https://www.diverta.co.jp/
 * License:            Expat License
 * License URI:        https://directory.fsf.org/wiki/License:Expat	
 */


// If this file is called directly, abort.
if (!defined('WPINC')) {
	die('Not running in a Wordpress environment');
}

// Current Plugin Version. Better to have version wise functionality later.
define('KUROCOEDGE_VERSION', '1.0.2');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_kurocoedge() {
	require_once plugin_dir_path(__FILE__).'includes/activator.php';
	kurocoedge_activate();
}

function deactivate_kurocoedge() {
	require_once plugin_dir_path(__FILE__).'includes/deactivator.php';
	kurocoedge_deactivate();
}

register_activation_hook(   __FILE__, 'activate_kurocoedge'   );
register_deactivation_hook( __FILE__, 'deactivate_kurocoedge' );


require plugin_dir_path(__FILE__).'includes/common.php';
require plugin_dir_path(__FILE__).'admin/index.php';

kurocoedge_init();