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
	return gspcp_get_asset_image_url( 'placeholder.svg' );
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
 * Gets post image HTML or placeholder.
 *
 * @param int    $post_id Post ID.
 * @param string $size    Image size.
 * @param string $class   CSS class.
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


/**
 * Returns demo programs used when the portal has no published program posts.
 *
 * @return array<int,array<string,string>>
 */
function gspcp_get_demo_programs() {
	return array(
		array(
			'title'       => __( 'Кружки и секции', 'gsp-children-portal' ),
			'description' => __( 'Творческие студии, инженерные занятия и клубы по интересам для детей сотрудников.', 'gsp-children-portal' ),
			'age'         => __( '6–14 лет', 'gsp-children-portal' ),
			'image'       => 'program-education.svg',
			'url'         => '#gsp-children-contacts',
		),
		array(
			'title'       => __( 'Спортивные секции', 'gsp-children-portal' ),
			'description' => __( 'Командные игры, плавание, лыжи и семейные старты для активного отдыха.', 'gsp-children-portal' ),
			'age'         => __( '5–16 лет', 'gsp-children-portal' ),
			'image'       => 'program-sport.svg',
			'url'         => '#gsp-children-contacts',
		),
		array(
			'title'       => __( 'Детские лагеря', 'gsp-children-portal' ),
			'description' => __( 'Летние смены с насыщенной программой, наставниками и безопасной средой.', 'gsp-children-portal' ),
			'age'         => __( '7–15 лет', 'gsp-children-portal' ),
			'image'       => 'program-camp.svg',
			'url'         => '#gsp-children-contacts',
		),
		array(
			'title'       => __( 'Олимпиады и конкурсы', 'gsp-children-portal' ),
			'description' => __( 'Интеллектуальные соревнования, творческие задания и поддержка талантов.', 'gsp-children-portal' ),
			'age'         => __( '8–17 лет', 'gsp-children-portal' ),
			'image'       => 'program-olympiad.svg',
			'url'         => '#gsp-children-contacts',
		),
		array(
			'title'       => __( 'Профориентация', 'gsp-children-portal' ),
			'description' => __( 'Знакомство с инженерными профессиями, производством и проектной работой.', 'gsp-children-portal' ),
			'age'         => __( '12–17 лет', 'gsp-children-portal' ),
			'image'       => 'program-career.svg',
			'url'         => '#gsp-children-contacts',
		),
		array(
			'title'       => __( 'Семейные мероприятия', 'gsp-children-portal' ),
			'description' => __( 'Праздники, экскурсии и добрые встречи, где семьи становятся ближе.', 'gsp-children-portal' ),
			'age'         => __( 'Для всей семьи', 'gsp-children-portal' ),
			'image'       => 'program-family.svg',
			'url'         => '#gsp-children-contacts',
		),
	);
}

/**
 * Returns demo events used when the portal has no published event posts.
 *
 * @return array<int,array<string,string>>
 */
function gspcp_get_demo_events() {
	return array(
		array( 'date' => __( '25 мая', 'gsp-children-portal' ), 'title' => __( 'Олимпиада по математике', 'gsp-children-portal' ), 'description' => __( 'Онлайн-тур для школьников 5–8 классов.', 'gsp-children-portal' ), 'deadline' => __( 'Регистрация до 20 мая', 'gsp-children-portal' ), 'image' => 'event-default.svg', 'url' => '#gsp-children-contacts' ),
		array( 'date' => __( '7 июня', 'gsp-children-portal' ), 'title' => __( 'Летняя смена', 'gsp-children-portal' ), 'description' => __( 'Творчество, спорт и инженерные мастерские.', 'gsp-children-portal' ), 'deadline' => __( 'Заявки принимаются до 1 июня', 'gsp-children-portal' ), 'image' => 'event-default.svg', 'url' => '#gsp-children-contacts' ),
		array( 'date' => __( '15 июня', 'gsp-children-portal' ), 'title' => __( 'Вебинар для родителей', 'gsp-children-portal' ), 'description' => __( 'Как выбрать программу и подготовить заявку.', 'gsp-children-portal' ), 'deadline' => __( 'Подключение по предварительной записи', 'gsp-children-portal' ), 'image' => 'event-default.svg', 'url' => '#gsp-children-faq' ),
		array( 'date' => __( '1 июля', 'gsp-children-portal' ), 'title' => __( 'Технический лагерь', 'gsp-children-portal' ), 'description' => __( 'Проекты, робототехника и экскурсии на производство.', 'gsp-children-portal' ), 'deadline' => __( 'Количество мест ограничено', 'gsp-children-portal' ), 'image' => 'event-default.svg', 'url' => '#gsp-children-contacts' ),
	);
}

/**
 * Returns demo family stories.
 *
 * @return array<int,array<string,string>>
 */
function gspcp_get_demo_stories() {
	return array(
		array( 'quote' => __( 'Сын впервые выступил с инженерным проектом и поверил, что большие идеи начинаются с маленьких опытов.', 'gsp-children-portal' ), 'name' => __( 'Анна Морозова', 'gsp-children-portal' ), 'position' => __( 'ведущий инженер проекта', 'gsp-children-portal' ), 'image' => 'story-default.svg' ),
		array( 'quote' => __( 'Дочь нашла друзей в спортивной секции, а мы увидели, как корпоративная забота помогает семье.', 'gsp-children-portal' ), 'name' => __( 'Илья Соколов', 'gsp-children-portal' ), 'position' => __( 'специалист службы эксплуатации', 'gsp-children-portal' ), 'image' => 'story-default.svg' ),
	);
}

/**
 * Returns demo FAQ items.
 *
 * @return array<int,array<string,string>>
 */
function gspcp_get_demo_faq() {
	return array(
		array( 'question' => __( 'Кто может участвовать в программах?', 'gsp-children-portal' ), 'answer' => __( 'В программах могут участвовать дети сотрудников Газстройпрома. Подробные условия публикуются в карточке каждой программы.', 'gsp-children-portal' ) ),
		array( 'question' => __( 'Как подать заявку?', 'gsp-children-portal' ), 'answer' => __( 'Выберите направление, подготовьте необходимые данные и отправьте заявку через контактный блок или личный кабинет.', 'gsp-children-portal' ) ),
		array( 'question' => __( 'С какого возраста можно участвовать?', 'gsp-children-portal' ), 'answer' => __( 'Возраст зависит от конкретного направления: в карточках указаны рекомендуемые возрастные группы.', 'gsp-children-portal' ) ),
		array( 'question' => __( 'Где посмотреть сроки регистрации?', 'gsp-children-portal' ), 'answer' => __( 'Сроки размещаются в блоке ближайших мероприятий и в описании программ. Если сроков нет, уточните информацию у координатора.', 'gsp-children-portal' ) ),
	);
}

/**
 * Returns demo useful materials.
 *
 * @return array<int,array<string,string>>
 */
function gspcp_get_demo_materials() {
	return array(
		array( 'title' => __( 'Как подать заявку', 'gsp-children-portal' ), 'description' => __( 'Короткая инструкция для родителей.', 'gsp-children-portal' ), 'icon' => '✍', 'url' => '#gsp-children-faq' ),
		array( 'title' => __( 'Вопросы и ответы', 'gsp-children-portal' ), 'description' => __( 'Ответы на частые вопросы.', 'gsp-children-portal' ), 'icon' => '?', 'url' => '#gsp-children-faq' ),
		array( 'title' => __( 'Полезные материалы', 'gsp-children-portal' ), 'description' => __( 'Памятки, чек-листы и ссылки.', 'gsp-children-portal' ), 'icon' => '★', 'url' => '#gsp-children-programs' ),
		array( 'title' => __( 'Контакты', 'gsp-children-portal' ), 'description' => __( 'Связь с координатором программы.', 'gsp-children-portal' ), 'icon' => '☎', 'url' => 'mailto:hr@gazstroyprom.ru' ),
	);
}
