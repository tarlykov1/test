<?php
/**
 * Admin settings and demo content.
 *
 * @package GSP_Children_Portal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers admin settings page.
 */
function gspcp_admin_menu() {
	add_options_page(
		__( 'Детские программы ГСП', 'gsp-children-portal' ),
		__( 'Детские программы ГСП', 'gsp-children-portal' ),
		'manage_options',
		'gsp-children-portal',
		'gspcp_render_settings_page'
	);
}
add_action( 'admin_menu', 'gspcp_admin_menu' );

/**
 * Registers plugin settings.
 */
function gspcp_register_settings() {
	register_setting(
		'gspcp_settings',
		'gspcp_auto_demo_enabled',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'gspcp_sanitize_checkbox',
			'default'           => '0',
		)
	);
}
add_action( 'admin_init', 'gspcp_register_settings' );

/**
 * Sanitizes checkbox value.
 *
 * @param mixed $value Raw value.
 * @return string
 */
function gspcp_sanitize_checkbox( $value ) {
	return '1' === (string) $value ? '1' : '0';
}

/**
 * Handles demo content creation.
 */
function gspcp_handle_demo_action() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( ! isset( $_POST['gspcp_create_demo'], $_POST['gspcp_demo_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gspcp_demo_nonce'] ) ), 'gspcp_create_demo' ) ) {
		return;
	}

	gspcp_ensure_categories();
	$count = gspcp_create_demo_content();
	add_settings_error(
		'gspcp_messages',
		'gspcp_demo_created',
		sprintf( esc_html__( 'Демо-контент готов. Создано новых записей: %d.', 'gsp-children-portal' ), absint( $count ) ),
		'updated'
	);
}
add_action( 'admin_init', 'gspcp_handle_demo_action' );

/**
 * Renders settings page.
 */
function gspcp_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$categories = gspcp_get_category_definitions();
	$fields     = gspcp_get_meta_fields();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Детские программы ГСП', 'gsp-children-portal' ); ?></h1>
		<?php settings_errors( 'gspcp_messages' ); ?>

		<div class="card">
			<h2><?php esc_html_e( 'Шорткод', 'gsp-children-portal' ); ?></h2>
			<p><?php esc_html_e( 'Создайте обычную страницу WordPress и вставьте шорткод:', 'gsp-children-portal' ); ?></p>
			<p><code id="gspcp-shortcode">[gsp_children_portal]</code></p>
			<button type="button" class="button button-secondary" onclick="navigator.clipboard && navigator.clipboard.writeText('[gsp_children_portal]');">
				<?php esc_html_e( 'Скопировать шорткод', 'gsp-children-portal' ); ?>
			</button>
		</div>

		<form method="post" action="options.php" class="card">
			<?php settings_fields( 'gspcp_settings' ); ?>
			<h2><?php esc_html_e( 'Настройки демо-контента', 'gsp-children-portal' ); ?></h2>
			<label>
				<input type="checkbox" name="gspcp_auto_demo_enabled" value="1" <?php checked( get_option( 'gspcp_auto_demo_enabled', '0' ), '1' ); ?> />
				<?php esc_html_e( 'Автоматически создавать демо-записи при активации плагина', 'gsp-children-portal' ); ?>
			</label>
			<?php submit_button( __( 'Сохранить настройки', 'gsp-children-portal' ) ); ?>
		</form>

		<form method="post" class="card">
			<h2><?php esc_html_e( 'Демо-контент', 'gsp-children-portal' ); ?></h2>
			<p><?php esc_html_e( 'Кнопка создаёт набор тестовых записей без дублей. Повторный запуск не создаёт записи с уже существующими служебными ключами.', 'gsp-children-portal' ); ?></p>
			<?php wp_nonce_field( 'gspcp_create_demo', 'gspcp_demo_nonce' ); ?>
			<?php submit_button( __( 'Создать демо-контент', 'gsp-children-portal' ), 'primary', 'gspcp_create_demo' ); ?>
		</form>

		<div class="card">
			<h2><?php esc_html_e( 'Категории и назначение', 'gsp-children-portal' ); ?></h2>
			<table class="widefat striped">
				<thead><tr><th><?php esc_html_e( 'Slug', 'gsp-children-portal' ); ?></th><th><?php esc_html_e( 'Назначение', 'gsp-children-portal' ); ?></th></tr></thead>
				<tbody>
				<?php foreach ( $categories as $slug => $category ) : ?>
					<tr><td><code><?php echo esc_html( $slug ); ?></code></td><td><?php echo esc_html( $category['description'] ); ?></td></tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<div class="card">
			<h2><?php esc_html_e( 'Meta-поля записей', 'gsp-children-portal' ); ?></h2>
			<ul>
			<?php foreach ( $fields as $key => $field ) : ?>
				<li><code><?php echo esc_html( $key ); ?></code> — <?php echo esc_html( $field['label'] ); ?></li>
			<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<?php
}

/**
 * Creates demo post if absent.
 *
 * @param string $demo_key Demo key.
 * @param array  $post_data Post fields.
 * @param array  $meta Meta fields.
 * @param string $category_slug Category slug.
 * @return int Created count.
 */
function gspcp_create_demo_post( $demo_key, $post_data, $meta, $category_slug ) {
	$existing = get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_gspcp_demo_key',
			'meta_value'     => $demo_key,
		)
	);

	if ( ! empty( $existing ) ) {
		return 0;
	}

	$term = get_term_by( 'slug', $category_slug, 'category' );
	$post_id = wp_insert_post(
		array_merge(
			array(
				'post_type'    => 'post',
				'post_status'  => 'publish',
				'post_excerpt' => '',
			),
			$post_data
		)
	);

	if ( is_wp_error( $post_id ) || ! $post_id ) {
		return 0;
	}

	if ( $term ) {
		wp_set_post_terms( $post_id, array( (int) $term->term_id ), 'category' );
	}

	update_post_meta( $post_id, '_gspcp_demo_key', $demo_key );
	foreach ( $meta as $key => $value ) {
		update_post_meta( $post_id, $key, $value );
	}

	return 1;
}

/**
 * Creates demo content without duplicates.
 *
 * @return int Number of created posts.
 */
function gspcp_create_demo_content() {
	gspcp_ensure_categories();

	$created = 0;
	$created += gspcp_create_demo_post(
		'hero',
		array(
			'post_title'   => __( 'Детям сотрудников Газстройпрома', 'gsp-children-portal' ),
			'post_excerpt' => __( 'Социальные, образовательные и развивающие программы для детей сотрудников группы компаний Газстройпром.', 'gsp-children-portal' ),
			'post_content' => __( 'Строим будущее вместе: поддерживаем образование, спорт, творчество и профориентацию детей сотрудников.', 'gsp-children-portal' ),
		),
		array(
			'gsp_primary_button_text'   => __( 'Смотреть программы', 'gsp-children-portal' ),
			'gsp_primary_button_url'    => '#gsp-children-programs',
			'gsp_secondary_button_text' => __( 'Подать заявку', 'gsp-children-portal' ),
			'gsp_secondary_button_url'  => '#gsp-children-contacts',
		),
		'gsp-children-hero'
	);

	$programs = array(
		array( 'education', __( 'Образование', 'gsp-children-portal' ), __( 'Дополнительные занятия, подготовка к экзаменам и образовательные курсы.', 'gsp-children-portal' ), '6–17 лет', 10 ),
		array( 'sport', __( 'Спорт', 'gsp-children-portal' ), __( 'Секции, турниры и оздоровительные программы.', 'gsp-children-portal' ), '6–17 лет', 20 ),
		array( 'camp', __( 'Лагеря', 'gsp-children-portal' ), __( 'Загородные и тематические лагеря, смены и экспедиции.', 'gsp-children-portal' ), '7–17 лет', 30 ),
		array( 'olympiads', __( 'Олимпиады', 'gsp-children-portal' ), __( 'Конкурсы по разным предметам и направлениям.', 'gsp-children-portal' ), '7–17 лет', 40 ),
		array( 'creative', __( 'Развитие', 'gsp-children-portal' ), __( 'Творческие студии, технические кружки и мастер-классы.', 'gsp-children-portal' ), '6–17 лет', 50 ),
		array( 'career', __( 'Профориентация', 'gsp-children-portal' ), __( 'Профориентационные программы и экскурсии на объекты.', 'gsp-children-portal' ), '12–17 лет', 60 ),
	);
	foreach ( $programs as $item ) {
		$created += gspcp_create_demo_post(
			'program-' . $item[0],
			array( 'post_title' => $item[1], 'post_excerpt' => $item[2], 'post_content' => $item[2] ),
			array( 'gsp_age' => $item[3], 'gsp_order' => $item[4] ),
			'gsp-children-programs'
		);
	}

	$created += gspcp_create_demo_post(
		'partner-skysmart',
		array(
			'post_title'   => __( 'Скидка для сотрудников Газстройпрома от Skysmart', 'gsp-children-portal' ),
			'post_excerpt' => __( 'Онлайн-занятия для детей по школьным предметам, программированию и английскому языку на специальных условиях.', 'gsp-children-portal' ),
			'post_content' => '<ul><li>Индивидуальные занятия</li><li>Подготовка к ОГЭ/ЕГЭ</li><li>Английский язык</li><li>Программирование</li></ul>',
		),
		array( 'gsp_badge' => '-20%', 'gsp_button_text' => __( 'Получить скидку', 'gsp-children-portal' ), 'gsp_external_url' => 'https://skysmart.ru/' ),
		'gsp-children-partners'
	);

	$events = array(
		array( 'math', __( 'Олимпиада по математике', 'gsp-children-portal' ), '+14 days', '+7 days' ),
		array( 'camp', __( 'Летняя смена «Путешествие к открытиям»', 'gsp-children-portal' ), '+28 days', '+20 days' ),
		array( 'webinar', __( 'Вебинар для родителей', 'gsp-children-portal' ), '+35 days', '+30 days' ),
		array( 'tech', __( 'Технический лагерь «Инженеры будущего»', 'gsp-children-portal' ), '+45 days', '+35 days' ),
	);
	foreach ( $events as $event ) {
		$created += gspcp_create_demo_post(
			'event-' . $event[0],
			array( 'post_title' => $event[1], 'post_excerpt' => __( 'Регистрация открыта для детей сотрудников.', 'gsp-children-portal' ), 'post_content' => __( 'Подробности мероприятия и условия участия доступны у координаторов программы.', 'gsp-children-portal' ) ),
			array( 'gsp_event_date' => gmdate( 'Y-m-d', strtotime( $event[2] ) ), 'gsp_deadline' => gmdate( 'Y-m-d', strtotime( $event[3] ) ) ),
			'gsp-children-events'
		);
	}

	$created += gspcp_create_demo_post( 'story-1', array( 'post_title' => __( 'История инженера Андрея', 'gsp-children-portal' ), 'post_excerpt' => __( 'Мой сын участвует в программе профориентации и уже побывал на настоящем строительном объекте.', 'gsp-children-portal' ), 'post_content' => __( 'Это вдохновляет его на новые цели и помогает лучше понять профессию.', 'gsp-children-portal' ) ), array( 'gsp_person_name' => __( 'Андрей К.', 'gsp-children-portal' ), 'gsp_person_position' => __( 'инженер', 'gsp-children-portal' ) ), 'gsp-children-stories' );
	$created += gspcp_create_demo_post( 'story-2', array( 'post_title' => __( 'История экономиста Елены', 'gsp-children-portal' ), 'post_excerpt' => __( 'Благодаря программам дочка талантливо развивается и нашла новых друзей.', 'gsp-children-portal' ), 'post_content' => __( 'Творческая студия стала для нашей семьи настоящей поддержкой.', 'gsp-children-portal' ) ), array( 'gsp_person_name' => __( 'Елена М.', 'gsp-children-portal' ), 'gsp_person_position' => __( 'экономист', 'gsp-children-portal' ) ), 'gsp-children-stories' );

	$created += gspcp_create_demo_post( 'faq-apply', array( 'post_title' => __( 'Как подать заявку?', 'gsp-children-portal' ), 'post_content' => __( 'Выберите программу, перейдите по ссылке и заполните форму. Координатор свяжется с вами для подтверждения.', 'gsp-children-portal' ) ), array(), 'gsp-children-faq' );
	$created += gspcp_create_demo_post( 'faq-age', array( 'post_title' => __( 'Для какого возраста доступны программы?', 'gsp-children-portal' ), 'post_content' => __( 'Большинство направлений рассчитано на детей от 6 до 17 лет. Возраст указан в каждой карточке программы.', 'gsp-children-portal' ) ), array(), 'gsp-children-faq' );

	$materials = array( __( 'Как подать заявку', 'gsp-children-portal' ), __( 'Вопросы и ответы', 'gsp-children-portal' ), __( 'Полезные материалы', 'gsp-children-portal' ), __( 'Контакты', 'gsp-children-portal' ) );
	foreach ( $materials as $index => $title ) {
		$created += gspcp_create_demo_post( 'material-' . $index, array( 'post_title' => $title, 'post_excerpt' => __( 'Короткая инструкция и контактная информация для родителей.', 'gsp-children-portal' ), 'post_content' => __( 'Материал можно открыть на отдельной странице или заменить внешней ссылкой.', 'gsp-children-portal' ) ), array(), 'gsp-children-materials' );
	}

	return $created;
}
