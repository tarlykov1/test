<?php
/**
 * Plugin Name: GSP Children Portal
 * Plugin URI: https://example.com/gsp-children-portal
 * Description: Страница внутрикорпоративного портала «Детские программы Газстройпрома» через шорткод [gsp_children_portal].
 * Version: 1.0.0
 * Author: Gazstroyprom
 * Text Domain: gsp-children-portal
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * License: GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GSPCP_VERSION', '1.0.0' );
define( 'GSPCP_PLUGIN_FILE', __FILE__ );
define( 'GSPCP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'GSPCP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once GSPCP_PLUGIN_DIR . 'includes/helpers.php';
require_once GSPCP_PLUGIN_DIR . 'includes/queries.php';
require_once GSPCP_PLUGIN_DIR . 'includes/meta-boxes.php';
require_once GSPCP_PLUGIN_DIR . 'includes/admin.php';

/**
 * Loads plugin text domain.
 */
function gspcp_load_textdomain() {
	load_plugin_textdomain( 'gsp-children-portal', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'gspcp_load_textdomain' );

/**
 * Creates required categories on activation.
 */
function gspcp_activate() {
	gspcp_ensure_categories();

	if ( get_option( 'gspcp_auto_demo_enabled', '0' ) === '1' ) {
		gspcp_create_demo_content();
	}
}
register_activation_hook( __FILE__, 'gspcp_activate' );

/**
 * Registers front assets.
 */
function gspcp_register_assets() {
	wp_register_style(
		'gsp-children-portal',
		GSPCP_PLUGIN_URL . 'assets/css/gsp-children-portal.css',
		array(),
		GSPCP_VERSION
	);

	wp_register_script(
		'gsp-children-portal',
		GSPCP_PLUGIN_URL . 'assets/js/gsp-children-portal.js',
		array(),
		GSPCP_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'gspcp_register_assets' );

/**
 * Enqueues assets only during shortcode rendering.
 */
function gspcp_enqueue_shortcode_assets() {
	wp_enqueue_style( 'gsp-children-portal' );
	wp_enqueue_script( 'gsp-children-portal' );
}

/**
 * Renders children portal shortcode.
 *
 * @return string
 */
function gspcp_render_shortcode( $atts = array() ) {
	gspcp_enqueue_shortcode_assets();

	$atts = shortcode_atts(
		array(
			'application_url' => '#gsp-children-contacts',
			'account_url'     => '#gsp-children-contacts',
			'programs_url'    => '#gsp-children-programs',
			'events_url'      => '#gsp-children-events',
		),
		(array) $atts,
		'gsp_children_portal'
	);

	$links = array();
	foreach ( $atts as $key => $value ) {
		$links[ $key ] = sanitize_text_field( $value );
	}

	$context = array(
		'hero'      => gspcp_get_single_post_by_category( 'gsp-children-hero' ),
		'programs'  => gspcp_get_program_posts( 6 ),
		'partner'   => gspcp_get_single_post_by_category( 'gsp-children-partners' ),
		'events'    => gspcp_get_event_posts( 4 ),
		'stories'   => gspcp_get_category_posts( 'gsp-children-stories', 3 ),
		'faq'       => gspcp_get_category_posts( 'gsp-children-faq', 20, 'menu_order', 'ASC' ),
		'materials' => gspcp_get_category_posts( 'gsp-children-materials', 8 ),
		'links'     => $links,
	);

	ob_start();
	include GSPCP_PLUGIN_DIR . 'templates/portal-page.php';
	return ob_get_clean();
}
add_shortcode( 'gsp_children_portal', 'gspcp_render_shortcode' );
