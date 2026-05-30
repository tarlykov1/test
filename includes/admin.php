<?php
/**
 * Admin pages and list table customization.
 *
 * @package GSP_Children_Portal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds settings and demo pages inside CPT menu.
 */
function gspcp_admin_menu() {
	add_submenu_page(
		'edit.php?post_type=' . GSPCP_POST_TYPE,
		__( 'Настройки', 'gsp-children-portal' ),
		__( 'Настройки', 'gsp-children-portal' ),
		'manage_options',
		'gsp-children-portal-settings',
		'gspcp_render_settings_page'
	);

	add_submenu_page(
		'edit.php?post_type=' . GSPCP_POST_TYPE,
		__( 'Демо-контент', 'gsp-children-portal' ),
		__( 'Демо-контент', 'gsp-children-portal' ),
		'manage_options',
		'gsp-children-portal-demo',
		'gspcp_render_demo_page'
	);
}
add_action( 'admin_menu', 'gspcp_admin_menu' );

/**
 * Handles admin action buttons.
 */
function gspcp_handle_admin_actions() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( isset( $_POST['gspcp_create_demo'], $_POST['gspcp_demo_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gspcp_demo_nonce'] ) ), 'gspcp_demo_action' ) ) {
		$count = gspcp_create_demo_content();
		add_settings_error( 'gspcp_messages', 'gspcp_demo_created', sprintf( esc_html__( 'Демо-контент готов. Создано новых элементов: %d.', 'gsp-children-portal' ), absint( $count ) ), 'updated' );
	}

	if ( isset( $_POST['gspcp_delete_demo'], $_POST['gspcp_demo_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gspcp_demo_nonce'] ) ), 'gspcp_demo_action' ) ) {
		$count = gspcp_delete_demo_content();
		add_settings_error( 'gspcp_messages', 'gspcp_demo_deleted', sprintf( esc_html__( 'Удалено демо-элементов: %d.', 'gsp-children-portal' ), absint( $count ) ), 'updated' );
	}

	if ( isset( $_POST['gspcp_migrate_legacy'], $_POST['gspcp_settings_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gspcp_settings_nonce'] ) ), 'gspcp_settings_action' ) ) {
		$count = gspcp_migrate_legacy_posts();
		add_settings_error( 'gspcp_messages', 'gspcp_migrated', sprintf( esc_html__( 'Миграция завершена. Создано элементов CPT: %d.', 'gsp-children-portal' ), absint( $count ) ), 'updated' );
	}
}
add_action( 'admin_init', 'gspcp_handle_admin_actions' );

/**
 * Renders settings page.
 */
function gspcp_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Настройки детских программ ГСП', 'gsp-children-portal' ); ?></h1>
		<?php settings_errors( 'gspcp_messages' ); ?>
		<div class="card">
			<h2><?php esc_html_e( 'Шорткод', 'gsp-children-portal' ); ?></h2>
			<p><?php esc_html_e( 'Создайте страницу WordPress, например /dp/, и вставьте шорткод:', 'gsp-children-portal' ); ?></p>
			<p><code>[gsp_children_portal]</code></p>
		</div>
		<div class="card">
			<h2><?php esc_html_e( 'Архитектура контента', 'gsp-children-portal' ); ?></h2>
			<p><?php esc_html_e( 'Контент хранится только в CPT gsp_child_item и таксономии gsp_child_section. Стандартные записи и категории WordPress на фронтенде не используются.', 'gsp-children-portal' ); ?></p>
			<ul style="list-style:disc;padding-left:20px;">
				<li><?php esc_html_e( 'Главный экран: term hero', 'gsp-children-portal' ); ?></li>
				<li><?php esc_html_e( 'Программы: term programs', 'gsp-children-portal' ); ?></li>
				<li><?php esc_html_e( 'Мероприятия: term events', 'gsp-children-portal' ); ?></li>
				<li><?php esc_html_e( 'Истории: term stories', 'gsp-children-portal' ); ?></li>
				<li><?php esc_html_e( 'Партнёры: term partners', 'gsp-children-portal' ); ?></li>
				<li><?php esc_html_e( 'FAQ: term faq', 'gsp-children-portal' ); ?></li>
				<li><?php esc_html_e( 'Материалы: term materials', 'gsp-children-portal' ); ?></li>
				<li><?php esc_html_e( 'Нижняя плашка: term footer', 'gsp-children-portal' ); ?></li>
			</ul>
		</div>
		<div class="card">
			<h2><?php esc_html_e( 'Миграция старых записей', 'gsp-children-portal' ); ?></h2>
			<p><?php esc_html_e( 'Кнопка вручную копирует старые записи из категорий gsp-children-* в новый CPT. Автоматически миграция не запускается.', 'gsp-children-portal' ); ?></p>
			<form method="post">
				<?php wp_nonce_field( 'gspcp_settings_action', 'gspcp_settings_nonce' ); ?>
				<?php submit_button( __( 'Мигрировать старые записи', 'gsp-children-portal' ), 'secondary', 'gspcp_migrate_legacy', false ); ?>
			</form>
		</div>
	</div>
	<?php
}

/**
 * Renders demo page.
 */
function gspcp_render_demo_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Демо-контент', 'gsp-children-portal' ); ?></h1>
		<?php settings_errors( 'gspcp_messages' ); ?>
		<div class="card">
			<p><?php esc_html_e( 'Демо-элементы создаются в CPT gsp_child_item и помечаются meta gsp_is_demo = 1. Дубли не создаются.', 'gsp-children-portal' ); ?></p>
			<form method="post" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
				<?php wp_nonce_field( 'gspcp_demo_action', 'gspcp_demo_nonce' ); ?>
				<?php submit_button( __( 'Создать демо-контент', 'gsp-children-portal' ), 'primary', 'gspcp_create_demo', false ); ?>
				<?php submit_button( __( 'Удалить демо-контент', 'gsp-children-portal' ), 'delete', 'gspcp_delete_demo', false ); ?>
			</form>
		</div>
	</div>
	<?php
}

/**
 * Adds custom columns.
 *
 * @param array $columns Columns.
 * @return array
 */
function gspcp_manage_columns( $columns ) {
	$new = array();
	foreach ( $columns as $key => $label ) {
		if ( 'cb' === $key || 'title' === $key ) {
			$new[ $key ] = $label;
		}
		if ( 'title' === $key ) {
			$new['gspcp_thumb']        = __( 'Миниатюра', 'gsp-children-portal' );
			$new['gspcp_section']      = __( 'Раздел страницы', 'gsp-children-portal' );
			$new['gspcp_age']          = __( 'Возраст', 'gsp-children-portal' );
			$new['gspcp_event_date']   = __( 'Дата события', 'gsp-children-portal' );
			$new['gspcp_order']        = __( 'Порядок', 'gsp-children-portal' );
			$new['gspcp_external_url'] = __( 'Внешняя ссылка', 'gsp-children-portal' );
		}
	}
	$new['date'] = isset( $columns['date'] ) ? $columns['date'] : __( 'Дата', 'gsp-children-portal' );
	return $new;
}
add_filter( 'manage_' . GSPCP_POST_TYPE . '_posts_columns', 'gspcp_manage_columns' );

/**
 * Renders custom column content.
 *
 * @param string $column  Column name.
 * @param int    $post_id Post ID.
 */
function gspcp_render_column( $column, $post_id ) {
	switch ( $column ) {
		case 'gspcp_thumb':
			echo get_the_post_thumbnail( $post_id, array( 54, 54 ) ) ?: '&mdash;';
			break;
		case 'gspcp_section':
			$terms = get_the_terms( $post_id, GSPCP_TAXONOMY );
			if ( empty( $terms ) || is_wp_error( $terms ) ) {
				echo '&mdash;';
				break;
			}
			echo esc_html( implode( ', ', wp_list_pluck( $terms, 'name' ) ) );
			break;
		case 'gspcp_age':
			echo esc_html( gspcp_get_meta( $post_id, 'gsp_age', '—' ) );
			break;
		case 'gspcp_event_date':
			echo esc_html( gspcp_get_meta( $post_id, 'gsp_event_date', '—' ) );
			break;
		case 'gspcp_order':
			echo esc_html( gspcp_get_meta( $post_id, 'gsp_order', (string) get_post_field( 'menu_order', $post_id ) ) );
			break;
		case 'gspcp_external_url':
			$url = gspcp_get_meta( $post_id, 'gsp_external_url' );
			if ( $url ) {
				printf( '<a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a>', esc_url( $url ), esc_html( wp_parse_url( $url, PHP_URL_HOST ) ?: $url ) );
			} else {
				echo '&mdash;';
			}
			break;
	}
}
add_action( 'manage_' . GSPCP_POST_TYPE . '_posts_custom_column', 'gspcp_render_column', 10, 2 );

/**
 * Makes selected columns sortable.
 *
 * @param array $columns Sortable columns.
 * @return array
 */
function gspcp_sortable_columns( $columns ) {
	$columns['gspcp_event_date'] = 'gsp_event_date';
	$columns['gspcp_order']      = 'gsp_order';
	return $columns;
}
add_filter( 'manage_edit-' . GSPCP_POST_TYPE . '_sortable_columns', 'gspcp_sortable_columns' );

/**
 * Adds taxonomy dropdown filter.
 *
 * @param string $post_type Post type.
 */
function gspcp_filter_by_section( $post_type ) {
	if ( GSPCP_POST_TYPE !== $post_type ) {
		return;
	}

	$selected = isset( $_GET[ GSPCP_TAXONOMY ] ) ? sanitize_text_field( wp_unslash( $_GET[ GSPCP_TAXONOMY ] ) ) : '';
	wp_dropdown_categories(
		array(
			'show_option_all' => __( 'Все разделы страницы', 'gsp-children-portal' ),
			'taxonomy'        => GSPCP_TAXONOMY,
			'name'            => GSPCP_TAXONOMY,
			'orderby'         => 'name',
			'value_field'     => 'slug',
			'hide_empty'      => false,
			'selected'        => $selected,
		)
	);
}
add_action( 'restrict_manage_posts', 'gspcp_filter_by_section' );

/**
 * Applies taxonomy and sortable filters.
 *
 * @param WP_Query $query Admin query.
 */
function gspcp_admin_query_filters( $query ) {
	global $pagenow;

	if ( ! is_admin() || 'edit.php' !== $pagenow || ! $query->is_main_query() || GSPCP_POST_TYPE !== $query->get( 'post_type' ) ) {
		return;
	}

	$section = isset( $_GET[ GSPCP_TAXONOMY ] ) ? sanitize_text_field( wp_unslash( $_GET[ GSPCP_TAXONOMY ] ) ) : '';
	if ( '' !== $section && '0' !== $section ) {
		$query->set(
			'tax_query',
			array(
				array(
					'taxonomy' => GSPCP_TAXONOMY,
					'field'    => 'slug',
					'terms'    => $section,
				),
			)
		);
	}

	$orderby = $query->get( 'orderby' );
	if ( 'gsp_event_date' === $orderby ) {
		$query->set( 'meta_key', 'gsp_event_date' );
		$query->set( 'orderby', 'meta_value' );
	} elseif ( 'gsp_order' === $orderby ) {
		$query->set( 'meta_key', 'gsp_order' );
		$query->set( 'orderby', 'meta_value_num' );
	}
}
add_action( 'pre_get_posts', 'gspcp_admin_query_filters' );
