<?php
/**
 * Custom taxonomy registration and default terms.
 *
 * @package GSP_Children_Portal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Plugin taxonomy slug. */
const GSPCP_TAXONOMY = 'gsp_child_section';

/**
 * Registers portal section taxonomy.
 */
function gspcp_register_taxonomy() {
	$labels = array(
		'name'              => __( 'Разделы страницы', 'gsp-children-portal' ),
		'singular_name'     => __( 'Раздел страницы', 'gsp-children-portal' ),
		'search_items'      => __( 'Искать разделы', 'gsp-children-portal' ),
		'all_items'         => __( 'Все разделы страницы', 'gsp-children-portal' ),
		'edit_item'         => __( 'Редактировать раздел', 'gsp-children-portal' ),
		'update_item'       => __( 'Обновить раздел', 'gsp-children-portal' ),
		'add_new_item'      => __( 'Добавить раздел', 'gsp-children-portal' ),
		'new_item_name'     => __( 'Название раздела', 'gsp-children-portal' ),
		'menu_name'         => __( 'Разделы страницы', 'gsp-children-portal' ),
		'not_found'         => __( 'Разделы не найдены.', 'gsp-children-portal' ),
		'back_to_items'     => __( '← Назад к разделам', 'gsp-children-portal' ),
	);

	register_taxonomy(
		GSPCP_TAXONOMY,
		array( GSPCP_POST_TYPE ),
		array(
			'labels'            => $labels,
			'public'            => false,
			'publicly_queryable'=> false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'hierarchical'      => false,
			'rewrite'           => false,
		)
	);
}
add_action( 'init', 'gspcp_register_taxonomy' );

/**
 * Returns required portal section terms.
 *
 * @return array<string,string>
 */
function gspcp_get_section_terms() {
	return array(
		'hero'      => __( 'Главный экран', 'gsp-children-portal' ),
		'programs'  => __( 'Программы и направления', 'gsp-children-portal' ),
		'events'    => __( 'Ближайшие мероприятия', 'gsp-children-portal' ),
		'stories'   => __( 'Истории сотрудников', 'gsp-children-portal' ),
		'partners'  => __( 'Партнёрские предложения', 'gsp-children-portal' ),
		'faq'       => __( 'Вопросы и ответы', 'gsp-children-portal' ),
		'materials' => __( 'Полезные материалы', 'gsp-children-portal' ),
		'footer'    => __( 'Нижняя плашка', 'gsp-children-portal' ),
	);
}

/**
 * Ensures default portal section terms exist.
 */
function gspcp_ensure_section_terms() {
	foreach ( gspcp_get_section_terms() as $slug => $name ) {
		$term = get_term_by( 'slug', $slug, GSPCP_TAXONOMY );
		if ( ! $term ) {
			wp_insert_term( $name, GSPCP_TAXONOMY, array( 'slug' => $slug ) );
		} else {
			wp_update_term( (int) $term->term_id, GSPCP_TAXONOMY, array( 'name' => $name ) );
		}
	}
}
