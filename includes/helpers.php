<?php
/**
 * Shared helpers and runtime fallback data.
 *
 * @package GSP_Children_Portal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gets plugin asset image URL.
 *
 * @param string $filename Asset filename in assets/img.
 * @return string
 */
function gspcp_get_asset_image_url( $filename ) {
	return GSPCP_PLUGIN_URL . 'assets/img/' . ltrim( $filename, '/' );
}

/**
 * Gets post image HTML or fallback.
 *
 * @param int    $post_id  Post ID.
 * @param string $size     Image size.
 * @param string $class    CSS class.
 * @param string $fallback Fallback SVG filename.
 * @return string
 */
function gspcp_get_image_html( $post_id, $size = 'large', $class = 'gspcp-card__image', $fallback = 'placeholder.svg' ) {
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
		esc_url( gspcp_get_asset_image_url( $fallback ) )
	);
}

/**
 * Returns safe excerpt or trimmed content.
 *
 * @param WP_Post $post  Post object.
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
 * Returns whether URL points outside current host.
 *
 * @param string $url URL.
 * @return bool
 */
function gspcp_is_external_url( $url ) {
	if ( '' === $url || 0 === strpos( $url, '#' ) || 0 === strpos( $url, 'mailto:' ) || 0 === strpos( $url, 'tel:' ) ) {
		return false;
	}

	$home_host = wp_parse_url( home_url(), PHP_URL_HOST );
	$url_host  = wp_parse_url( $url, PHP_URL_HOST );

	return $url_host && $home_host && strtolower( $url_host ) !== strtolower( $home_host );
}

/**
 * Returns escaped external link attrs string.
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

/**
 * Formats compact date parts for event badges.
 *
 * @param string $date Date string.
 * @return array{day:string,month:string}
 */
function gspcp_get_event_date_parts( $date ) {
	$timestamp = strtotime( $date );
	if ( ! $timestamp ) {
		$parts = preg_split( '/\s+/', trim( $date ) );
		return array(
			'day'   => isset( $parts[0] ) ? $parts[0] : '',
			'month' => isset( $parts[1] ) ? $parts[1] : '',
		);
	}

	return array(
		'day'   => date_i18n( 'd', $timestamp ),
		'month' => date_i18n( 'M', $timestamp ),
	);
}

/**
 * Returns runtime fallback hero.
 *
 * @return array<string,string>
 */
function gspcp_get_demo_hero() {
	return array(
		'title'                 => __( 'Детям сотрудников Газстройпрома', 'gsp-children-portal' ),
		'eyebrow'               => __( 'Строим будущее вместе!', 'gsp-children-portal' ),
		'description'           => __( 'Социальные, образовательные и развивающие программы для детей сотрудников группы компаний Газстройпром.', 'gsp-children-portal' ),
		'image'                 => 'hero-retro-default.svg',
		'primary_button_text'   => __( 'Смотреть программы', 'gsp-children-portal' ),
		'primary_button_url'    => '#gspcp-programs',
		'secondary_button_text' => __( 'Подать заявку', 'gsp-children-portal' ),
		'secondary_button_url'  => '#gspcp-contacts',
	);
}

/**
 * Returns runtime fallback programs.
 *
 * @return array<int,array<string,string>>
 */
function gspcp_get_demo_programs() {
	return array(
		array( 'title' => __( 'Образование', 'gsp-children-portal' ), 'description' => __( 'Дополнительные занятия, подготовка к экзаменам, образовательные курсы.', 'gsp-children-portal' ), 'age' => __( '6–17 лет', 'gsp-children-portal' ), 'image' => 'program-education.svg', 'url' => '#gspcp-contacts' ),
		array( 'title' => __( 'Спорт', 'gsp-children-portal' ), 'description' => __( 'Спортивные секции, турниры и оздоровительные программы.', 'gsp-children-portal' ), 'age' => __( '6–17 лет', 'gsp-children-portal' ), 'image' => 'program-sport.svg', 'url' => '#gspcp-contacts' ),
		array( 'title' => __( 'Лагеря', 'gsp-children-portal' ), 'description' => __( 'Загородные и тематические лагеря, смены и экспедиции.', 'gsp-children-portal' ), 'age' => __( '7–17 лет', 'gsp-children-portal' ), 'image' => 'program-camp.svg', 'url' => '#gspcp-contacts' ),
		array( 'title' => __( 'Олимпиады', 'gsp-children-portal' ), 'description' => __( 'Олимпиады и конкурсы по различным предметам и направлениям.', 'gsp-children-portal' ), 'age' => __( '7–17 лет', 'gsp-children-portal' ), 'image' => 'program-olympiad.svg', 'url' => '#gspcp-contacts' ),
		array( 'title' => __( 'Развитие', 'gsp-children-portal' ), 'description' => __( 'Творческие студии, технические кружки и мастер-классы.', 'gsp-children-portal' ), 'age' => __( '6–17 лет', 'gsp-children-portal' ), 'image' => 'program-development.svg', 'url' => '#gspcp-contacts' ),
		array( 'title' => __( 'Профориентация', 'gsp-children-portal' ), 'description' => __( 'Профориентационные программы и экскурсии на объекты.', 'gsp-children-portal' ), 'age' => __( '12–17 лет', 'gsp-children-portal' ), 'image' => 'program-career.svg', 'url' => '#gspcp-contacts' ),
	);
}

/**
 * Returns runtime fallback partner offer.
 *
 * @return array<string,mixed>
 */
function gspcp_get_demo_partner() {
	return array(
		'title'       => __( 'Скидка для сотрудников Газстройпрома от Skysmart', 'gsp-children-portal' ),
		'description' => __( 'Онлайн-занятия для детей по школьным предметам, программированию и английскому языку на специальных условиях для сотрудников группы Газстройпром.', 'gsp-children-portal' ),
		'items'       => array( __( 'Индивидуальные занятия', 'gsp-children-portal' ), __( 'Подготовка к ОГЭ/ЕГЭ', 'gsp-children-portal' ), __( 'Английский язык', 'gsp-children-portal' ), __( 'Программирование', 'gsp-children-portal' ) ),
		'button'      => __( 'Получить скидку', 'gsp-children-portal' ),
		'url'         => 'https://go.skysmart.ru/gsprom_skysmart',
		'badge'       => '-20%',
		'image'       => 'skysmart-default.svg',
	);
}

/**
 * Returns runtime fallback events.
 *
 * @return array<int,array<string,string>>
 */
function gspcp_get_demo_events() {
	return array(
		array( 'date' => __( '25 мая', 'gsp-children-portal' ), 'title' => __( 'Олимпиада по математике', 'gsp-children-portal' ), 'deadline' => __( 'Регистрация до 15 мая', 'gsp-children-portal' ), 'image' => 'event-default.svg', 'url' => '#gspcp-contacts' ),
		array( 'date' => __( '7 июня', 'gsp-children-portal' ), 'title' => __( 'Летняя смена «Путешествие к открытиям»', 'gsp-children-portal' ), 'deadline' => __( 'Регистрация до 20 мая', 'gsp-children-portal' ), 'image' => 'program-camp.svg', 'url' => '#gspcp-contacts' ),
		array( 'date' => __( '15 июня', 'gsp-children-portal' ), 'title' => __( 'Вебинар для родителей', 'gsp-children-portal' ), 'deadline' => __( 'Онлайн', 'gsp-children-portal' ), 'image' => 'program-development.svg', 'url' => '#gspcp-contacts' ),
		array( 'date' => __( '1 июля', 'gsp-children-portal' ), 'title' => __( 'Технический лагерь «Инженеры будущего»', 'gsp-children-portal' ), 'deadline' => __( 'Регистрация до 25 июня', 'gsp-children-portal' ), 'image' => 'program-career.svg', 'url' => '#gspcp-contacts' ),
	);
}

/**
 * Returns runtime fallback stories.
 *
 * @return array<int,array<string,string>>
 */
function gspcp_get_demo_stories() {
	return array(
		array( 'quote' => __( 'Мой сын участвует в программе профориентации и уже побывал на настоящем строительном объекте. Это вдохновляет его на новые цели!', 'gsp-children-portal' ), 'name' => __( 'Андрей К.', 'gsp-children-portal' ), 'position' => __( 'инженер', 'gsp-children-portal' ), 'image' => 'story-default.svg' ),
		array( 'quote' => __( 'Благодаря программам Газстройпрома дочь раскрыла свои таланты и нашла новых друзей!', 'gsp-children-portal' ), 'name' => __( 'Елена М.', 'gsp-children-portal' ), 'position' => __( 'экономист', 'gsp-children-portal' ), 'image' => 'story-default.svg' ),
	);
}

/**
 * Returns runtime fallback FAQ.
 *
 * @return array<int,array<string,string>>
 */
function gspcp_get_demo_faq() {
	return array(
		array( 'question' => __( 'Как подать заявку?', 'gsp-children-portal' ), 'answer' => __( 'Выберите программу и отправьте заявку через корпоративную форму или координатору направления.', 'gsp-children-portal' ) ),
		array( 'question' => __( 'Кто может участвовать?', 'gsp-children-portal' ), 'answer' => __( 'Дети сотрудников группы компаний Газстройпром. Возраст указан в каждой карточке программы.', 'gsp-children-portal' ) ),
		array( 'question' => __( 'Где узнать сроки регистрации?', 'gsp-children-portal' ), 'answer' => __( 'Сроки указаны в блоке мероприятий и в описании конкретной программы.', 'gsp-children-portal' ) ),
	);
}

/**
 * Returns runtime fallback quick links.
 *
 * @return array<int,array<string,string>>
 */
function gspcp_get_demo_materials() {
	return array(
		array( 'title' => __( 'Как подать заявку', 'gsp-children-portal' ), 'description' => __( 'Пошаговая инструкция', 'gsp-children-portal' ), 'icon' => '↗', 'url' => '#gspcp-contacts' ),
		array( 'title' => __( 'Вопросы и ответы', 'gsp-children-portal' ), 'description' => __( 'Ответы на частые вопросы', 'gsp-children-portal' ), 'icon' => '?', 'url' => '#gspcp-contacts' ),
		array( 'title' => __( 'Полезные материалы', 'gsp-children-portal' ), 'description' => __( 'Советы для родителей', 'gsp-children-portal' ), 'icon' => '☰', 'url' => '#gspcp-programs' ),
		array( 'title' => __( 'Контакты', 'gsp-children-portal' ), 'description' => __( 'Мы всегда на связи', 'gsp-children-portal' ), 'icon' => '☎', 'url' => 'mailto:hr@gazstroyprom.ru' ),
	);
}
