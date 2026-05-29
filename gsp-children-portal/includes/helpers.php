<?php
/**
 * Shared helpers for GSP Children Portal.
 *
 * @package GSP_Children_Portal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns required category definitions.
 *
 * @return array<string,array<string,string>>
 */
function gspcp_get_category_definitions() {
	return array(
		'gsp-children'           => array(
			'name'        => __( 'Детские программы ГСП', 'gsp-children-portal' ),
			'description' => __( 'Родительская категория портала детских программ.', 'gsp-children-portal' ),
		),
		'gsp-children-hero'      => array(
			'name'        => __( 'ГСП: hero-блок', 'gsp-children-portal' ),
			'description' => __( 'Главный экран страницы.', 'gsp-children-portal' ),
		),
		'gsp-children-programs'  => array(
			'name'        => __( 'ГСП: программы и направления', 'gsp-children-portal' ),
			'description' => __( 'Карточки программ для детей сотрудников.', 'gsp-children-portal' ),
		),
		'gsp-children-events'    => array(
			'name'        => __( 'ГСП: мероприятия', 'gsp-children-portal' ),
			'description' => __( 'Ближайшие мероприятия и даты регистрации.', 'gsp-children-portal' ),
		),
		'gsp-children-stories'   => array(
			'name'        => __( 'ГСП: истории сотрудников', 'gsp-children-portal' ),
			'description' => __( 'Отзывы, цитаты и истории семей сотрудников.', 'gsp-children-portal' ),
		),
		'gsp-children-partners'  => array(
			'name'        => __( 'ГСП: партнёры', 'gsp-children-portal' ),
			'description' => __( 'Партнёрские предложения, включая Skysmart.', 'gsp-children-portal' ),
		),
		'gsp-children-faq'       => array(
			'name'        => __( 'ГСП: вопросы и ответы', 'gsp-children-portal' ),
			'description' => __( 'FAQ для аккордеона.', 'gsp-children-portal' ),
		),
		'gsp-children-materials' => array(
			'name'        => __( 'ГСП: полезные материалы', 'gsp-children-portal' ),
			'description' => __( 'Инструкции, контакты и полезные ссылки.', 'gsp-children-portal' ),
		),
	);
}

/**
 * Ensures plugin categories exist.
 *
 * @return void
 */
function gspcp_ensure_categories() {
	$definitions = gspcp_get_category_definitions();
	$parent_id   = 0;

	foreach ( $definitions as $slug => $definition ) {
		$term = get_term_by( 'slug', $slug, 'category' );

		$args = array(
			'description' => $definition['description'],
		);

		if ( 'gsp-children' !== $slug ) {
			$args['parent'] = $parent_id;
		}

		if ( ! $term ) {
			$result = wp_insert_term( $definition['name'], 'category', array_merge( array( 'slug' => $slug ), $args ) );
			if ( ! is_wp_error( $result ) && 'gsp-children' === $slug ) {
				$parent_id = (int) $result['term_id'];
			}
		} else {
			wp_update_term( (int) $term->term_id, 'category', $args );
			if ( 'gsp-children' === $slug ) {
				$parent_id = (int) $term->term_id;
			}
		}
	}
}

/**
 * Gets plugin placeholder image URL.
 *
 * @return string
 */
function gspcp_get_placeholder_url() {
	return GSPCP_PLUGIN_URL . 'assets/img/placeholder.svg';
}

/**
 * Gets post image HTML or placeholder.
 *
 * @param int    $post_id Post ID.
 * @param string $size    Image size.
 * @param string $class   CSS class.
 * @return string
 */
function gspcp_get_image_html( $post_id, $size = 'large', $class = 'gspcp-card__image' ) {
	if ( has_post_thumbnail( $post_id ) ) {
		return get_the_post_thumbnail(
			$post_id,
			$size,
			array(
				'class'   => esc_attr( $class ),
				'loading' => 'lazy',
			)
		);
	}

	return sprintf(
		'<img class="%1$s" src="%2$s" alt="" loading="lazy" />',
		esc_attr( $class ),
		esc_url( gspcp_get_placeholder_url() )
	);
}

/**
 * Returns safe excerpt or trimmed content.
 *
 * @param WP_Post $post Post object.
 * @param int     $words Words count.
 * @return string
 */
function gspcp_get_post_summary( $post, $words = 28 ) {
	$text = has_excerpt( $post ) ? $post->post_excerpt : wp_strip_all_tags( strip_shortcodes( $post->post_content ) );
	return wp_trim_words( $text, $words, '…' );
}

/**
 * Gets post meta string.
 *
 * @param int    $post_id Post ID.
 * @param string $key     Meta key.
 * @param string $default Default value.
 * @return string
 */
function gspcp_get_meta( $post_id, $key, $default = '' ) {
	$value = get_post_meta( $post_id, $key, true );
	return '' === $value ? $default : (string) $value;
}

/**
 * Returns a link target based on URL host.
 *
 * @param string $url URL.
 * @return bool
 */
function gspcp_is_external_url( $url ) {
	if ( '' === $url ) {
		return false;
	}

	$home_host = wp_parse_url( home_url(), PHP_URL_HOST );
	$url_host  = wp_parse_url( $url, PHP_URL_HOST );

	return $url_host && $home_host && strtolower( $url_host ) !== strtolower( $home_host );
}

/**
 * Prints link attributes for external links.
 *
 * @param string $url URL.
 * @return string
 */
function gspcp_external_link_attrs( $url ) {
	if ( ! gspcp_is_external_url( $url ) ) {
		return '';
	}

	return ' target="_blank" rel="noopener noreferrer"';
}

/**
 * Formats a date from Y-m-d or any parseable string.
 *
 * @param string $date Date value.
 * @return string
 */
function gspcp_format_date( $date ) {
	if ( '' === $date ) {
		return '';
	}

	$timestamp = strtotime( $date );
	if ( ! $timestamp ) {
		return $date;
	}

	return date_i18n( 'j F', $timestamp );
}
