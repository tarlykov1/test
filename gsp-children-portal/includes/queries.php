<?php
/**
 * Query helpers for GSP Children Portal.
 *
 * @package GSP_Children_Portal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns posts by category slug.
 *
 * @param string $slug           Category slug.
 * @param int    $posts_per_page Number of posts.
 * @param string $orderby        Order by.
 * @param string $order          Order.
 * @param array  $extra          Extra query args.
 * @return WP_Post[]
 */
function gspcp_get_category_posts( $slug, $posts_per_page = 6, $orderby = 'date', $order = 'DESC', $extra = array() ) {
	$args = array_merge(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => $posts_per_page,
			'category_name'       => $slug,
			'orderby'             => $orderby,
			'order'               => $order,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		),
		$extra
	);

	$query = new WP_Query( $args );
	$posts = $query->posts;
	wp_reset_postdata();

	return $posts;
}

/**
 * Returns latest single post by category.
 *
 * @param string $slug Category slug.
 * @return WP_Post|null
 */
function gspcp_get_single_post_by_category( $slug ) {
	$posts = gspcp_get_category_posts( $slug, 1 );
	return ! empty( $posts ) ? $posts[0] : null;
}

/**
 * Returns program posts sorted by order then date.
 *
 * @param int $limit Limit.
 * @return WP_Post[]
 */
function gspcp_get_program_posts( $limit = 6 ) {
	return gspcp_get_category_posts(
		'gsp-children-programs',
		$limit,
		array(
			'gsp_order_clause' => 'ASC',
			'date'             => 'DESC',
		),
		'DESC',
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
		)
	);
}

/**
 * Returns event posts sorted by event date then publication date.
 *
 * @param int $limit Limit.
 * @return WP_Post[]
 */
function gspcp_get_event_posts( $limit = 4 ) {
	return gspcp_get_category_posts(
		'gsp-children-events',
		$limit,
		array(
			'gsp_event_date_clause' => 'ASC',
			'date'                  => 'DESC',
		),
		'DESC',
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
		)
	);
}
