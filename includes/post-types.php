<?php
/**
 * Custom post type registration.
 *
 * @package GSP_Children_Portal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Plugin CPT slug. */
const GSPCP_POST_TYPE = 'gsp_child_item';

/**
 * Registers children portal content item CPT.
 */
function gspcp_register_post_type() {
	$labels = array(
		'name'                  => __( 'Детские программы ГСП', 'gsp-children-portal' ),
		'singular_name'         => __( 'Элемент страницы', 'gsp-children-portal' ),
		'menu_name'             => __( 'Детские программы ГСП', 'gsp-children-portal' ),
		'name_admin_bar'        => __( 'Элемент детских программ', 'gsp-children-portal' ),
		'add_new'               => __( 'Добавить элемент', 'gsp-children-portal' ),
		'add_new_item'          => __( 'Добавить элемент', 'gsp-children-portal' ),
		'new_item'              => __( 'Новый элемент', 'gsp-children-portal' ),
		'edit_item'             => __( 'Редактировать элемент', 'gsp-children-portal' ),
		'view_item'             => __( 'Просмотреть элемент', 'gsp-children-portal' ),
		'all_items'             => __( 'Все элементы', 'gsp-children-portal' ),
		'search_items'          => __( 'Искать элементы', 'gsp-children-portal' ),
		'not_found'             => __( 'Элементы не найдены.', 'gsp-children-portal' ),
		'not_found_in_trash'    => __( 'В корзине элементы не найдены.', 'gsp-children-portal' ),
		'featured_image'        => __( 'Иллюстрация', 'gsp-children-portal' ),
		'set_featured_image'    => __( 'Задать иллюстрацию', 'gsp-children-portal' ),
		'remove_featured_image' => __( 'Удалить иллюстрацию', 'gsp-children-portal' ),
	);

	register_post_type(
		GSPCP_POST_TYPE,
		array(
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_rest'       => true,
			'query_var'          => false,
			'rewrite'            => false,
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 25,
			'menu_icon'          => 'dashicons-heart',
			'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
		)
	);
}
add_action( 'init', 'gspcp_register_post_type' );
