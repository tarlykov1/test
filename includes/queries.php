<?php
/**
 * Query helpers for GSP Children Portal CPT content.
 *
 * @package GSP_Children_Portal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns portal items from one section only.
 *
 * @param string $section        Section term slug.
 * @param int    $posts_per_page Number of items.
 * @param array  $extra          Extra query args.
 * @return WP_Post[]
 */
function gspcp_get_section_items( $section, $posts_per_page = 6, $extra = array() ) {
	$args = array_merge(
		array(
			'post_type'           => GSPCP_POST_TYPE,
			'post_status'         => 'publish',
			'posts_per_page'      => $posts_per_page,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'tax_query'           => array(
				array(
					'taxonomy' => GSPCP_TAXONOMY,
					'field'    => 'slug',
					'terms'    => $section,
				),
			),
		),
		$extra
	);

	$query = new WP_Query( $args );
	$items = $query->posts;
	wp_reset_postdata();

	return $items;
}

/**
 * Returns one portal item from section.
 *
 * @param string $section Section term slug.
 * @return WP_Post|null
 */
function gspcp_get_single_section_item( $section ) {
	$items = gspcp_get_section_items(
		$section,
		1,
		array(
			'orderby' => array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			),
		)
	);

	return ! empty( $items ) ? $items[0] : null;
}

/**
 * Returns program items sorted by meta order, menu order and date.
 *
 * @param int $limit Limit.
 * @return WP_Post[]
 */
function gspcp_get_program_items( $limit = 6 ) {
	return gspcp_get_section_items(
		'programs',
		$limit,
		array(
			'meta_query' => array(
				'relation'         => 'OR',
				'gsp_order_clause' => array(
					'key'     => 'gsp_order',
					'compare' => 'EXISTS',
					'type'    => 'NUMERIC',
				),
				array(
					'key'     => 'gsp_order',
					'compare' => 'NOT EXISTS',
				),
			),
			'orderby'    => array(
				'gsp_order_clause' => 'ASC',
				'menu_order'       => 'ASC',
				'date'             => 'DESC',
			),
		)
	);
}

/**
 * Returns event items sorted by event date and publication date.
 *
 * @param int $limit Limit.
 * @return WP_Post[]
 */
function gspcp_get_event_items( $limit = 4 ) {
	return gspcp_get_section_items(
		'events',
		$limit,
		array(
			'meta_query' => array(
				'relation'              => 'OR',
				'gsp_event_date_clause' => array(
					'key'     => 'gsp_event_date',
					'compare' => 'EXISTS',
					'type'    => 'DATE',
				),
				array(
					'key'     => 'gsp_event_date',
					'compare' => 'NOT EXISTS',
				),
			),
			'orderby'    => array(
				'gsp_event_date_clause' => 'ASC',
				'date'                  => 'DESC',
			),
		)
	);
}

/**
 * Returns all portal content for shortcode template.
 *
 * @return array<string,mixed>
 */
function gspcp_get_portal_context() {
	return array(
		'hero'      => gspcp_get_single_section_item( 'hero' ),
		'programs'  => gspcp_get_program_items( 6 ),
		'partner'   => gspcp_get_single_section_item( 'partners' ),
		'events'    => gspcp_get_event_items( 4 ),
		'stories'   => gspcp_get_section_items( 'stories', 2, array( 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'DESC' ) ) ),
		'faq'       => gspcp_get_section_items( 'faq', 20, array( 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'DESC' ) ) ),
		'materials' => gspcp_get_section_items( 'materials', 8, array( 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'DESC' ) ) ),
		'footer'    => gspcp_get_section_items( 'footer', 4, array( 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'DESC' ) ) ),
	);
}
