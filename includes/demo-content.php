<?php
/**
 * Demo content and legacy migration actions.
 *
 * @package GSP_Children_Portal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Finds existing demo item by demo key.
 *
 * @param string $demo_key Demo key.
 * @return int
 */
function gspcp_find_demo_item_id( $demo_key ) {
	$items = get_posts(
		array(
			'post_type'      => GSPCP_POST_TYPE,
			'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'meta_query'     => array(
				array(
					'key'   => '_gspcp_demo_key',
					'value' => $demo_key,
				),
			),
		)
	);

	return ! empty( $items ) ? (int) $items[0] : 0;
}

/**
 * Creates one demo item without duplicates.
 *
 * @param string $demo_key Demo key.
 * @param string $section  Section slug.
 * @param array  $data     Post data.
 * @param array  $meta     Meta values.
 * @return int 1 when created, 0 when skipped.
 */
function gspcp_create_demo_item( $demo_key, $section, $data, $meta = array() ) {
	if ( gspcp_find_demo_item_id( $demo_key ) ) {
		return 0;
	}

	$post_id = wp_insert_post(
		array_merge(
			array(
				'post_type'   => GSPCP_POST_TYPE,
				'post_status' => 'publish',
				'post_name'   => 'gsp-demo-' . sanitize_title( $demo_key ),
			),
			$data
		)
	);

	if ( is_wp_error( $post_id ) || ! $post_id ) {
		return 0;
	}

	wp_set_object_terms( $post_id, $section, GSPCP_TAXONOMY );
	update_post_meta( $post_id, 'gsp_is_demo', '1' );
	update_post_meta( $post_id, '_gspcp_demo_key', $demo_key );

	foreach ( $meta as $key => $value ) {
		if ( '' !== (string) $value ) {
			update_post_meta( $post_id, $key, $value );
		}
	}

	return 1;
}

/**
 * Creates demo CPT content.
 *
 * @return int Number of created items.
 */
function gspcp_create_demo_content() {
	gspcp_ensure_section_terms();

	$created = 0;
	$created += gspcp_create_demo_item(
		'hero',
		'hero',
		array(
			'post_title'   => __( 'Детям сотрудников Газстройпрома', 'gsp-children-portal' ),
			'post_excerpt' => __( 'Социальные, образовательные и развивающие программы для детей сотрудников группы компаний Газстройпром.', 'gsp-children-portal' ),
			'post_content' => __( 'Строим будущее вместе!', 'gsp-children-portal' ),
			'menu_order'   => 10,
		),
		array(
			'gsp_primary_button_text'   => __( 'Смотреть программы', 'gsp-children-portal' ),
			'gsp_primary_button_url'    => '#gspcp-programs',
			'gsp_secondary_button_text' => __( 'Подать заявку', 'gsp-children-portal' ),
			'gsp_secondary_button_url'  => '#gspcp-contacts',
			'gsp_fallback_image'       => gspcp_get_demo_hero()['image'],
		)
	);

	foreach ( gspcp_get_demo_programs() as $index => $program ) {
		$created += gspcp_create_demo_item(
			'program-' . sanitize_title( $program['title'] ),
			'programs',
			array(
				'post_title'   => $program['title'],
				'post_excerpt' => $program['description'],
				'post_content' => $program['description'],
				'menu_order'   => ( $index + 1 ) * 10,
			),
			array(
				'gsp_age'          => $program['age'],
				'gsp_order'        => ( $index + 1 ) * 10,
				'gsp_external_url'   => $program['url'],
				'gsp_fallback_image' => $program['image'],
			)
		);
	}

	$partner = gspcp_get_demo_partner();
	$created += gspcp_create_demo_item(
		'partner-skysmart',
		'partners',
		array(
			'post_title'   => $partner['title'],
			'post_excerpt' => $partner['description'],
			'post_content' => implode( "\n", $partner['items'] ),
			'menu_order'   => 10,
		),
		array(
			'gsp_button_text'  => $partner['button'],
			'gsp_external_url' => $partner['url'],
			'gsp_badge'          => $partner['badge'],
			'gsp_fallback_image' => $partner['image'],
		)
	);

	$demo_event_dates = array( '2026-05-25', '2026-06-07', '2026-06-15', '2026-07-01' );
	foreach ( gspcp_get_demo_events() as $index => $event ) {
		$created += gspcp_create_demo_item(
			'event-' . sanitize_title( $event['title'] ),
			'events',
			array(
				'post_title'   => $event['title'],
				'post_excerpt' => $event['deadline'],
				'post_content' => $event['deadline'],
				'menu_order'   => ( $index + 1 ) * 10,
			),
			array(
				'gsp_event_date'   => isset( $demo_event_dates[ $index ] ) ? $demo_event_dates[ $index ] : '',
				'gsp_deadline'     => $event['deadline'],
				'gsp_external_url'   => $event['url'],
				'gsp_fallback_image' => $event['image'],
			)
		);
	}

	foreach ( gspcp_get_demo_stories() as $index => $story ) {
		$created += gspcp_create_demo_item(
			'story-' . sanitize_title( $story['name'] ),
			'stories',
			array(
				'post_title'   => $story['name'],
				'post_excerpt' => $story['quote'],
				'post_content' => $story['quote'],
				'menu_order'   => ( $index + 1 ) * 10,
			),
			array(
				'gsp_person_name'     => $story['name'],
				'gsp_person_position' => $story['position'],
				'gsp_fallback_image'    => $story['image'],
			)
		);
	}

	foreach ( gspcp_get_demo_faq() as $index => $faq ) {
		$created += gspcp_create_demo_item(
			'faq-' . sanitize_title( $faq['question'] ),
			'faq',
			array(
				'post_title'   => $faq['question'],
				'post_content' => $faq['answer'],
				'menu_order'   => ( $index + 1 ) * 10,
			)
		);
	}

	foreach ( gspcp_get_demo_materials() as $index => $material ) {
		$created += gspcp_create_demo_item(
			'material-' . sanitize_title( $material['title'] ),
			'materials',
			array(
				'post_title'   => $material['title'],
				'post_excerpt' => $material['description'],
				'post_content' => $material['description'],
				'menu_order'   => ( $index + 1 ) * 10,
			),
			array( 'gsp_external_url' => $material['url'] )
		);
	}

	$footer_items = array(
		__( 'Мы создаём возможности', 'gsp-children-portal' ),
		__( 'Мы поддерживаем развитие', 'gsp-children-portal' ),
		__( 'Мы вдохновляем на достижения', 'gsp-children-portal' ),
		__( 'Мы строим будущее вместе', 'gsp-children-portal' ),
	);
	foreach ( $footer_items as $index => $title ) {
		$created += gspcp_create_demo_item(
			'footer-' . $index,
			'footer',
			array(
				'post_title' => $title,
				'menu_order' => ( $index + 1 ) * 10,
			)
		);
	}

	return $created;
}

/**
 * Deletes demo CPT items.
 *
 * @return int Number of deleted items.
 */
function gspcp_delete_demo_content() {
	$items = get_posts(
		array(
			'post_type'      => GSPCP_POST_TYPE,
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'meta_query'     => array(
				array(
					'key'   => 'gsp_is_demo',
					'value' => '1',
				),
			),
		)
	);

	$deleted = 0;
	foreach ( $items as $item_id ) {
		if ( wp_delete_post( (int) $item_id, true ) ) {
			$deleted++;
		}
	}

	return $deleted;
}

/**
 * Migrates legacy category posts into CPT on explicit admin action.
 *
 * @return int Number of migrated items.
 */
function gspcp_migrate_legacy_posts() {
	$map = array(
		'gsp-children-hero'      => 'hero',
		'gsp-children-programs'  => 'programs',
		'gsp-children-events'    => 'events',
		'gsp-children-stories'   => 'stories',
		'gsp-children-partners'  => 'partners',
		'gsp-children-faq'       => 'faq',
		'gsp-children-materials' => 'materials',
	);
	$meta_keys = array_keys( gspcp_get_meta_fields() );
	$migrated  = 0;

	foreach ( $map as $category_slug => $section ) {
		$posts = get_posts(
			array(
				'post_type'      => 'post',
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'category_name'  => $category_slug,
				'no_found_rows'  => true,
			)
		);

		foreach ( $posts as $post ) {
			$existing = get_posts(
				array(
					'post_type'      => GSPCP_POST_TYPE,
					'post_status'    => 'any',
					'posts_per_page' => 1,
					'fields'         => 'ids',
					'meta_key'       => '_gspcp_legacy_post_id',
					'meta_value'     => (string) $post->ID,
				)
			);
			if ( ! empty( $existing ) ) {
				continue;
			}

			$new_id = wp_insert_post(
				array(
					'post_type'    => GSPCP_POST_TYPE,
					'post_status'  => $post->post_status,
					'post_title'   => $post->post_title,
					'post_content' => $post->post_content,
					'post_excerpt' => $post->post_excerpt,
					'post_name'    => $post->post_name,
					'menu_order'   => $post->menu_order,
				)
			);

			if ( is_wp_error( $new_id ) || ! $new_id ) {
				continue;
			}

			wp_set_object_terms( $new_id, $section, GSPCP_TAXONOMY );
			update_post_meta( $new_id, '_gspcp_legacy_post_id', (string) $post->ID );
			foreach ( $meta_keys as $meta_key ) {
				$value = get_post_meta( $post->ID, $meta_key, true );
				if ( '' !== $value ) {
					update_post_meta( $new_id, $meta_key, $value );
				}
			}

			$thumbnail_id = get_post_thumbnail_id( $post->ID );
			if ( $thumbnail_id ) {
				set_post_thumbnail( $new_id, $thumbnail_id );
			}

			$migrated++;
		}
	}

	return $migrated;
}
