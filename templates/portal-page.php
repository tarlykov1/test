<?php
/**
 * Portal page template.
 *
 * @package GSP_Children_Portal
 *
 * @var array $context Template context.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hero      = $context['hero'];
$programs  = $context['programs'];
$partner   = $context['partner'];
$events    = $context['events'];
$stories   = $context['stories'];
$faq       = $context['faq'];
$materials = $context['materials'];

$is_admin              = current_user_can( 'manage_options' );
$use_demo_programs     = empty( $programs );
$use_demo_events       = empty( $events );
$use_demo_stories      = empty( $stories );
$use_demo_faq          = empty( $faq );
$use_demo_materials    = empty( $materials );
$program_items         = $use_demo_programs ? gspcp_get_demo_programs() : $programs;
$event_items           = $use_demo_events ? gspcp_get_demo_events() : $events;
$story_items           = $use_demo_stories ? gspcp_get_demo_stories() : $stories;
$faq_items             = $use_demo_faq ? gspcp_get_demo_faq() : $faq;
$material_items        = $use_demo_materials ? gspcp_get_demo_materials() : $materials;
$hero_title            = $hero ? get_the_title( $hero ) : __( 'Детям сотрудников Газстройпрома', 'gsp-children-portal' );
$hero_text             = $hero ? gspcp_get_post_summary( $hero, 32 ) : __( 'Добрые программы, события и партнёрские предложения для детей сотрудников — в тёплой атмосфере общей заботы о будущем.', 'gsp-children-portal' );
$primary_text          = $hero ? gspcp_get_meta( $hero->ID, 'gsp_primary_button_text', __( 'Смотреть программы', 'gsp-children-portal' ) ) : __( 'Смотреть программы', 'gsp-children-portal' );
$primary_url           = $hero ? gspcp_get_meta( $hero->ID, 'gsp_primary_button_url', '#gsp-children-programs' ) : '#gsp-children-programs';
$secondary_text        = $hero ? gspcp_get_meta( $hero->ID, 'gsp_secondary_button_text', __( 'Подать заявку', 'gsp-children-portal' ) ) : __( 'Подать заявку', 'gsp-children-portal' );
$secondary_url         = $hero ? gspcp_get_meta( $hero->ID, 'gsp_secondary_button_url', '#gsp-children-contacts' ) : '#gsp-children-contacts';
$partner_link          = $partner ? gspcp_get_meta( $partner->ID, 'gsp_external_url', get_permalink( $partner ) ) : 'https://go.skysmart.ru/gsprom_skysmart';
$partner_title         = $partner ? get_the_title( $partner ) : __( 'Скидка на онлайн-занятия для детей', 'gsp-children-portal' );
$partner_text          = $partner ? gspcp_get_post_summary( $partner, 34 ) : __( 'Партнёрское предложение Skysmart помогает школьникам уверенно учиться, готовиться к экзаменам и пробовать новые цифровые направления.', 'gsp-children-portal' );
$partner_badge         = $partner ? gspcp_get_meta( $partner->ID, 'gsp_badge', '-20%' ) : '-20%';
$partner_button_text   = $partner ? gspcp_get_meta( $partner->ID, 'gsp_button_text', __( 'Получить скидку', 'gsp-children-portal' ) ) : __( 'Получить скидку', 'gsp-children-portal' );
$programs_category     = get_category_by_slug( 'gsp-children-programs' );
$programs_url          = $programs_category ? get_category_link( $programs_category ) : '#gsp-children-programs';
?>
<div class="gspcp-portal" id="gsp-children-portal">
	<header class="gspcp-topbar">
		<a class="gspcp-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Газстройпром', 'gsp-children-portal' ); ?>">
			<?php esc_html_e( 'Газстройпром', 'gsp-children-portal' ); ?>
		</a>
		<nav class="gspcp-nav" aria-label="<?php esc_attr_e( 'Навигация по детским программам', 'gsp-children-portal' ); ?>">
			<a href="#gsp-children-programs"><?php esc_html_e( 'Программы', 'gsp-children-portal' ); ?></a>
			<a href="#gsp-children-events"><?php esc_html_e( 'Мероприятия', 'gsp-children-portal' ); ?></a>
			<a href="#gsp-children-partners"><?php esc_html_e( 'Партнёры', 'gsp-children-portal' ); ?></a>
			<a href="#gsp-children-faq"><?php esc_html_e( 'FAQ', 'gsp-children-portal' ); ?></a>
			<a href="#gsp-children-contacts"><?php esc_html_e( 'Контакты', 'gsp-children-portal' ); ?></a>
		</nav>
		<a class="gspcp-account" href="#gsp-children-contacts"><?php esc_html_e( 'Личный кабинет', 'gsp-children-portal' ); ?></a>
	</header>

	<section class="gspcp-hero" aria-labelledby="gspcp-hero-title">
		<div class="gspcp-hero__content">
			<div class="gspcp-hero__kicker"><?php esc_html_e( 'Строим будущее вместе!', 'gsp-children-portal' ); ?></div>
			<h1 class="gspcp-hero__title" id="gspcp-hero-title"><?php echo esc_html( $hero_title ); ?></h1>
			<div class="gspcp-hero__text"><?php echo esc_html( $hero_text ); ?></div>
			<?php if ( ! $hero && $is_admin ) : ?>
				<p class="gspcp-admin-note"><?php esc_html_e( 'Администратор: для замены demo-hero добавьте запись в категорию gsp-children-hero.', 'gsp-children-portal' ); ?></p>
			<?php endif; ?>
			<div class="gspcp-hero__actions">
				<a class="gspcp-button gspcp-button--primary" href="<?php echo esc_url( $primary_url ); ?>"<?php echo gspcp_external_link_attrs( $primary_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $primary_text ); ?></a>
				<a class="gspcp-button gspcp-button--ghost" href="<?php echo esc_url( $secondary_url ); ?>"<?php echo gspcp_external_link_attrs( $secondary_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $secondary_text ); ?></a>
			</div>
		</div>
		<div class="gspcp-hero__visual">
			<span class="gspcp-hero__slogan"><?php esc_html_e( 'Сильной стране — будущее поколение!', 'gsp-children-portal' ); ?></span>
			<?php echo wp_kses_post( $hero ? gspcp_get_image_html( $hero->ID, 'large', 'gspcp-hero__image', 'hero-retro-default.svg' ) : sprintf( '<img class="gspcp-hero__image" src="%s" alt="" loading="lazy" />', esc_url( gspcp_get_asset_image_url( 'hero-retro-default.svg' ) ) ) ); ?>
		</div>
	</section>

	<div class="gspcp-main-grid">
		<section class="gspcp-section gspcp-programs" id="gsp-children-programs" aria-labelledby="gspcp-programs-title">
			<div class="gspcp-section__head">
				<p><?php esc_html_e( 'Выберите направление', 'gsp-children-portal' ); ?></p>
				<h2 id="gspcp-programs-title"><?php esc_html_e( 'Программы и направления', 'gsp-children-portal' ); ?></h2>
			</div>
			<div class="gspcp-program-grid">
				<?php foreach ( $program_items as $program ) : ?>
					<?php
					if ( $use_demo_programs ) {
						$link        = $program['url'];
						$title       = $program['title'];
						$summary     = $program['description'];
						$age         = $program['age'];
						$image_html  = sprintf( '<img class="gspcp-card__image" src="%s" alt="" loading="lazy" />', esc_url( gspcp_get_asset_image_url( $program['image'] ) ) );
					} else {
						$link        = gspcp_get_meta( $program->ID, 'gsp_external_url', get_permalink( $program ) );
						$title       = get_the_title( $program );
						$summary     = gspcp_get_post_summary( $program, 16 );
						$age         = gspcp_get_meta( $program->ID, 'gsp_age', __( 'Для детей сотрудников', 'gsp-children-portal' ) );
						$image_html  = gspcp_get_image_html( $program->ID, 'medium_large', 'gspcp-card__image', 'program-education.svg' );
					}
					?>
					<article class="gspcp-card gspcp-program-card">
						<a class="gspcp-card__media" href="<?php echo esc_url( $link ); ?>"<?php echo gspcp_external_link_attrs( $link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo wp_kses_post( $image_html ); ?></a>
						<div class="gspcp-card__body">
							<h3><a href="<?php echo esc_url( $link ); ?>"<?php echo gspcp_external_link_attrs( $link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $title ); ?></a></h3>
							<p><?php echo esc_html( $summary ); ?></p>
							<div class="gspcp-card__meta"><span><?php echo esc_html( $age ); ?></span><span class="gspcp-card__arrow" aria-hidden="true">→</span></div>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
			<?php if ( $use_demo_programs && $is_admin ) : ?>
				<p class="gspcp-admin-note"><?php esc_html_e( 'Администратор: demo-карточки заменятся записями из категории gsp-children-programs.', 'gsp-children-portal' ); ?></p>
			<?php endif; ?>
			<a class="gspcp-button gspcp-button--small" href="<?php echo esc_url( $programs_url ); ?>"><?php esc_html_e( 'Все программы', 'gsp-children-portal' ); ?></a>
		</section>

		<section class="gspcp-partner" id="gsp-children-partners" aria-labelledby="gspcp-partner-title">
			<div class="gspcp-partner__content">
				<span class="gspcp-badge"><?php echo esc_html( $partner_badge ); ?></span>
				<p class="gspcp-partner__eyebrow"><?php esc_html_e( 'Партнёрское предложение', 'gsp-children-portal' ); ?></p>
				<h2 id="gspcp-partner-title"><?php echo esc_html( $partner_title ); ?></h2>
				<p class="gspcp-partner__text"><?php echo esc_html( $partner_text ); ?></p>
				<ul class="gspcp-partner__features">
					<li><?php esc_html_e( 'Индивидуальные занятия', 'gsp-children-portal' ); ?></li>
					<li><?php esc_html_e( 'Подготовка к ОГЭ/ЕГЭ', 'gsp-children-portal' ); ?></li>
					<li><?php esc_html_e( 'Английский язык', 'gsp-children-portal' ); ?></li>
					<li><?php esc_html_e( 'Программирование', 'gsp-children-portal' ); ?></li>
				</ul>
				<div class="gspcp-partner__actions">
					<a class="gspcp-button gspcp-button--green" href="<?php echo esc_url( $partner_link ); ?>"<?php echo gspcp_external_link_attrs( $partner_link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $partner_button_text ); ?></a>
					<a class="gspcp-button gspcp-button--light" href="<?php echo esc_url( $partner_link ); ?>"<?php echo gspcp_external_link_attrs( $partner_link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( 'Подробнее', 'gsp-children-portal' ); ?></a>
				</div>
				<?php if ( ! $partner && $is_admin ) : ?>
					<p class="gspcp-admin-note gspcp-admin-note--dark"><?php esc_html_e( 'Администратор: demo-блок заменится записью из категории gsp-children-partners.', 'gsp-children-portal' ); ?></p>
				<?php endif; ?>
			</div>
			<div class="gspcp-partner__media"><?php echo wp_kses_post( $partner ? gspcp_get_image_html( $partner->ID, 'large', 'gspcp-partner__image', 'skysmart-default.svg' ) : sprintf( '<img class="gspcp-partner__image" src="%s" alt="" loading="lazy" />', esc_url( gspcp_get_asset_image_url( 'skysmart-default.svg' ) ) ) ); ?></div>
		</section>
	</div>

	<div class="gspcp-lower-grid">
		<section class="gspcp-section" id="gsp-children-events" aria-labelledby="gspcp-events-title">
			<div class="gspcp-section__head"><p><?php esc_html_e( 'Календарь', 'gsp-children-portal' ); ?></p><h2 id="gspcp-events-title"><?php esc_html_e( 'Ближайшие мероприятия', 'gsp-children-portal' ); ?></h2></div>
			<div class="gspcp-event-grid">
			<?php foreach ( $event_items as $event ) : ?>
				<?php
				if ( $use_demo_events ) {
					$event_link  = $event['url'];
					$event_date  = $event['date'];
					$title       = $event['title'];
					$summary     = $event['description'];
					$deadline    = $event['deadline'];
					$image_html  = sprintf( '<img class="gspcp-event-card__img" src="%s" alt="" loading="lazy" />', esc_url( gspcp_get_asset_image_url( $event['image'] ) ) );
				} else {
					$event_link  = gspcp_get_meta( $event->ID, 'gsp_external_url', get_permalink( $event ) );
					$event_date  = gspcp_get_meta( $event->ID, 'gsp_event_date' );
					$title       = get_the_title( $event );
					$summary     = gspcp_get_post_summary( $event, 14 );
					$deadline    = gspcp_get_meta( $event->ID, 'gsp_deadline' );
					$event_date  = $event_date ? gspcp_format_date( $event_date ) : date_i18n( 'j F', strtotime( $event->post_date ) );
					$deadline    = $deadline ? sprintf( __( 'Регистрация до %s', 'gsp-children-portal' ), gspcp_format_date( $deadline ) ) : '';
					$image_html  = gspcp_get_image_html( $event->ID, 'medium', 'gspcp-event-card__img', 'event-default.svg' );
				}
				?>
				<article class="gspcp-event-card">
					<a class="gspcp-event-card__image" href="<?php echo esc_url( $event_link ); ?>"<?php echo gspcp_external_link_attrs( $event_link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo wp_kses_post( $image_html ); ?></a>
					<div class="gspcp-event-card__date"><strong><?php echo esc_html( $event_date ); ?></strong></div>
					<div class="gspcp-event-card__body">
						<h3><a href="<?php echo esc_url( $event_link ); ?>"<?php echo gspcp_external_link_attrs( $event_link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $title ); ?></a></h3>
						<p><?php echo esc_html( $summary ); ?></p>
						<?php if ( $deadline ) : ?><span><?php echo esc_html( $deadline ); ?></span><?php endif; ?>
					</div>
					<span class="gspcp-event-card__arrow" aria-hidden="true">→</span>
				</article>
			<?php endforeach; ?>
			</div>
			<?php if ( $use_demo_events && $is_admin ) : ?><p class="gspcp-admin-note"><?php esc_html_e( 'Администратор: demo-мероприятия заменятся записями из категории gsp-children-events.', 'gsp-children-portal' ); ?></p><?php endif; ?>
		</section>

		<section class="gspcp-section" aria-labelledby="gspcp-stories-title">
			<div class="gspcp-section__head"><p><?php esc_html_e( 'Семьи сотрудников', 'gsp-children-portal' ); ?></p><h2 id="gspcp-stories-title"><?php esc_html_e( 'Истории сотрудников', 'gsp-children-portal' ); ?></h2></div>
			<div class="gspcp-story-grid">
			<?php foreach ( $story_items as $story ) : ?>
				<?php
				if ( $use_demo_stories ) {
					$quote      = $story['quote'];
					$name       = $story['name'];
					$position   = $story['position'];
					$image_html = sprintf( '<img class="gspcp-story-card__image" src="%s" alt="" loading="lazy" />', esc_url( gspcp_get_asset_image_url( $story['image'] ) ) );
				} else {
					$quote      = gspcp_get_post_summary( $story, 24 );
					$name       = gspcp_get_meta( $story->ID, 'gsp_person_name', get_the_title( $story ) );
					$position   = gspcp_get_meta( $story->ID, 'gsp_person_position' );
					$image_html = gspcp_get_image_html( $story->ID, 'medium', 'gspcp-story-card__image', 'story-default.svg' );
				}
				?>
				<article class="gspcp-story-card">
					<?php echo wp_kses_post( $image_html ); ?>
					<div class="gspcp-story-card__content">
						<blockquote>«<?php echo esc_html( $quote ); ?>»</blockquote>
						<strong><?php echo esc_html( $name ); ?></strong>
						<span><?php echo esc_html( $position ); ?></span>
					</div>
				</article>
			<?php endforeach; ?>
			</div>
			<?php if ( $use_demo_stories && $is_admin ) : ?><p class="gspcp-admin-note"><?php esc_html_e( 'Администратор: demo-истории заменятся записями из категории gsp-children-stories.', 'gsp-children-portal' ); ?></p><?php endif; ?>
		</section>
	</div>

	<div class="gspcp-bottom-grid">
		<section class="gspcp-section gspcp-faq" id="gsp-children-faq" aria-labelledby="gspcp-faq-title">
			<div class="gspcp-section__head"><p><?php esc_html_e( 'Помощь родителям', 'gsp-children-portal' ); ?></p><h2 id="gspcp-faq-title"><?php esc_html_e( 'Вопросы и ответы', 'gsp-children-portal' ); ?></h2></div>
			<div class="gspcp-accordion" data-gspcp-accordion>
			<?php foreach ( $faq_items as $index => $item ) : ?>
				<?php
				$question = $use_demo_faq ? $item['question'] : get_the_title( $item );
				$answer   = $use_demo_faq ? wpautop( $item['answer'] ) : apply_filters( 'the_content', $item->post_content );
				?>
				<div class="gspcp-accordion__item">
					<button class="gspcp-accordion__button" type="button" aria-expanded="<?php echo 0 === $index ? 'true' : 'false'; ?>">
						<span><?php echo esc_html( $question ); ?></span><span aria-hidden="true">+</span>
					</button>
					<div class="gspcp-accordion__panel" <?php echo 0 === $index ? '' : 'hidden'; ?>><?php echo wp_kses_post( $answer ); ?></div>
				</div>
			<?php endforeach; ?>
			</div>
			<?php if ( $use_demo_faq && $is_admin ) : ?><p class="gspcp-admin-note"><?php esc_html_e( 'Администратор: demo-FAQ заменится записями из категории gsp-children-faq.', 'gsp-children-portal' ); ?></p><?php endif; ?>
		</section>

		<aside class="gspcp-materials" id="gsp-children-contacts" aria-labelledby="gspcp-materials-title">
			<p class="gspcp-materials__eyebrow"><?php esc_html_e( 'Навигация', 'gsp-children-portal' ); ?></p>
			<h2 id="gspcp-materials-title"><?php esc_html_e( 'Полезные материалы', 'gsp-children-portal' ); ?></h2>
			<ul>
			<?php foreach ( $material_items as $material ) : ?>
				<?php
				if ( $use_demo_materials ) {
					$material_link = $material['url'];
					$title         = $material['title'];
					$summary       = $material['description'];
					$icon          = $material['icon'];
				} else {
					$material_link = gspcp_get_meta( $material->ID, 'gsp_external_url', get_permalink( $material ) );
					$title         = get_the_title( $material );
					$summary       = gspcp_get_post_summary( $material, 10 );
					$icon          = '↗';
				}
				?>
				<li>
					<a href="<?php echo esc_url( $material_link ); ?>"<?php echo gspcp_external_link_attrs( $material_link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<span class="gspcp-materials__icon" aria-hidden="true"><?php echo esc_html( $icon ); ?></span>
						<strong><?php echo esc_html( $title ); ?></strong>
						<span><?php echo esc_html( $summary ); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
			</ul>
			<?php if ( $use_demo_materials && $is_admin ) : ?><p class="gspcp-admin-note"><?php esc_html_e( 'Администратор: demo-материалы заменятся записями из категории gsp-children-materials.', 'gsp-children-portal' ); ?></p><?php endif; ?>
			<div class="gspcp-note"><?php esc_html_e( 'Забота о детях — это вклад в будущее!', 'gsp-children-portal' ); ?></div>
		</aside>
	</div>

	<footer class="gspcp-footer" id="gspcp-benefits" aria-label="<?php esc_attr_e( 'Ценности программы', 'gsp-children-portal' ); ?>">
		<span><?php esc_html_e( 'Мы создаём возможности', 'gsp-children-portal' ); ?></span>
		<span><?php esc_html_e( 'Мы поддерживаем развитие', 'gsp-children-portal' ); ?></span>
		<span><?php esc_html_e( 'Мы вдохновляем на достижения', 'gsp-children-portal' ); ?></span>
		<span><?php esc_html_e( 'Мы строим будущее вместе', 'gsp-children-portal' ); ?></span>
	</footer>
</div>
