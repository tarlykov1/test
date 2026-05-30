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
$links     = isset( $context['links'] ) ? $context['links'] : array();

$is_admin           = current_user_can( 'manage_options' );
$use_demo_programs  = empty( $programs );
$use_demo_events    = empty( $events );
$use_demo_stories   = empty( $stories );
$use_demo_faq       = empty( $faq );
$use_demo_materials = empty( $materials );
$program_items      = $use_demo_programs ? gspcp_get_demo_programs() : $programs;
$event_items        = $use_demo_events ? gspcp_get_demo_events() : $events;
$story_items        = $use_demo_stories ? gspcp_get_demo_stories() : $stories;
$faq_items          = $use_demo_faq ? gspcp_get_demo_faq() : $faq;
$material_items     = $use_demo_materials ? gspcp_get_demo_materials() : $materials;

$application_url = ! empty( $links['application_url'] ) ? $links['application_url'] : '#gsp-children-contacts';
$account_url     = ! empty( $links['account_url'] ) ? $links['account_url'] : '#gsp-children-contacts';
$all_programs    = ! empty( $links['programs_url'] ) ? $links['programs_url'] : '#gsp-children-programs';
$all_events      = ! empty( $links['events_url'] ) ? $links['events_url'] : '#gsp-children-events';

$hero_title      = $hero ? get_the_title( $hero ) : __( 'Детям сотрудников Газстройпрома', 'gsp-children-portal' );
$hero_text       = $hero ? gspcp_get_post_summary( $hero, 24 ) : __( 'Социальные, образовательные и развивающие программы для детей сотрудников группы компаний Газстройпром.', 'gsp-children-portal' );
$primary_text    = $hero ? gspcp_get_meta( $hero->ID, 'gsp_primary_button_text', __( 'Смотреть программы', 'gsp-children-portal' ) ) : __( 'Смотреть программы', 'gsp-children-portal' );
$primary_url     = $hero ? gspcp_get_meta( $hero->ID, 'gsp_primary_button_url', $all_programs ) : $all_programs;
$secondary_text  = $hero ? gspcp_get_meta( $hero->ID, 'gsp_secondary_button_text', __( 'Подать заявку', 'gsp-children-portal' ) ) : __( 'Подать заявку', 'gsp-children-portal' );
$secondary_url   = $hero ? gspcp_get_meta( $hero->ID, 'gsp_secondary_button_url', $application_url ) : $application_url;
$partner_link    = $partner ? gspcp_get_meta( $partner->ID, 'gsp_external_url', $application_url ) : $application_url;
$partner_title   = $partner ? get_the_title( $partner ) : __( 'Скидка для сотрудников Газстройпрома от Skysmart', 'gsp-children-portal' );
$partner_text    = $partner ? gspcp_get_post_summary( $partner, 32 ) : __( 'Онлайн-занятия для детей по школьным предметам, программированию и английскому языку на специальных условиях для сотрудников группы Газстройпром.', 'gsp-children-portal' );
$partner_badge   = $partner ? gspcp_get_meta( $partner->ID, 'gsp_badge', '-20%' ) : '-20%';
$partner_button  = $partner ? gspcp_get_meta( $partner->ID, 'gsp_button_text', __( 'Получить скидку', 'gsp-children-portal' ) ) : __( 'Получить скидку', 'gsp-children-portal' );
$hero_image_html = $hero ? gspcp_get_image_html( $hero->ID, 'large', 'gsp-children-hero__image', 'hero-retro-default.svg' ) : sprintf( '<img class="gsp-children-hero__image" src="%s" alt="" loading="eager" />', esc_url( gspcp_get_asset_image_url( 'hero-retro-default.svg' ) ) );
?>
<div class="gsp-children" id="gsp-children-portal">
	<header class="gsp-children-topbar">
		<a class="gsp-children-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Газстройпром', 'gsp-children-portal' ); ?>"><?php esc_html_e( 'Газстройпром', 'gsp-children-portal' ); ?></a>
		<nav class="gsp-children-nav" aria-label="<?php esc_attr_e( 'Навигация по странице', 'gsp-children-portal' ); ?>">
			<a href="#gsp-children-programs"><?php esc_html_e( 'Программы', 'gsp-children-portal' ); ?></a>
			<a href="#gsp-children-events"><?php esc_html_e( 'Мероприятия', 'gsp-children-portal' ); ?></a>
			<a href="#gsp-children-partners"><?php esc_html_e( 'Партнёры', 'gsp-children-portal' ); ?></a>
			<a href="#gsp-children-stories"><?php esc_html_e( 'Новости', 'gsp-children-portal' ); ?></a>
			<a href="#gsp-children-contacts"><?php esc_html_e( 'Вопросы и ответы', 'gsp-children-portal' ); ?></a>
		</nav>
		<a class="gsp-children-account" href="<?php echo esc_url( $account_url ); ?>"<?php echo gspcp_external_link_attrs( $account_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( 'Личный кабинет', 'gsp-children-portal' ); ?></a>
	</header>

	<section class="gsp-children-hero" aria-labelledby="gsp-children-hero-title">
		<?php echo wp_kses_post( $hero_image_html ); ?>
		<div class="gsp-children-hero__content">
			<p class="gsp-children-hero__script"><?php esc_html_e( 'Строим будущее вместе!', 'gsp-children-portal' ); ?></p>
			<h1 id="gsp-children-hero-title"><?php echo esc_html( $hero_title ); ?></h1>
			<p><?php echo esc_html( $hero_text ); ?></p>
			<div class="gsp-children-actions">
				<a class="gsp-children-button gsp-children-button--primary" href="<?php echo esc_url( $primary_url ); ?>"<?php echo gspcp_external_link_attrs( $primary_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $primary_text ); ?></a>
				<a class="gsp-children-button gsp-children-button--light" href="<?php echo esc_url( $secondary_url ); ?>"<?php echo gspcp_external_link_attrs( $secondary_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $secondary_text ); ?></a>
			</div>
			<?php if ( ! $hero && $is_admin ) : ?><p class="gsp-children-admin-note"><?php esc_html_e( 'Администратор: hero заменится записью из категории gsp-children-hero.', 'gsp-children-portal' ); ?></p><?php endif; ?>
		</div>
		<p class="gsp-children-hero__motto"><?php esc_html_e( 'Сильной стране — будущее поколение!', 'gsp-children-portal' ); ?></p>
	</section>

	<main class="gsp-children-main">
		<section class="gsp-children-section" id="gsp-children-programs" aria-labelledby="gsp-children-programs-title">
			<h2 id="gsp-children-programs-title"><?php esc_html_e( 'Программы и направления', 'gsp-children-portal' ); ?></h2>
			<div class="gsp-children-program-grid">
			<?php foreach ( $program_items as $program ) : ?>
				<?php
				if ( $use_demo_programs ) {
					$program_link = $program['url'];
					$title        = $program['title'];
					$summary      = $program['description'];
					$age          = $program['age'];
					$image_html   = sprintf( '<img class="gsp-children-card__image" src="%s" alt="" loading="lazy" />', esc_url( gspcp_get_asset_image_url( $program['image'] ) ) );
				} else {
					$program_link = gspcp_get_meta( $program->ID, 'gsp_external_url', get_permalink( $program ) );
					$title        = get_the_title( $program );
					$summary      = gspcp_get_post_summary( $program, 12 );
					$age          = gspcp_get_meta( $program->ID, 'gsp_age', __( '6–17 лет', 'gsp-children-portal' ) );
					$image_html   = gspcp_get_image_html( $program->ID, 'medium', 'gsp-children-card__image', 'placeholder.svg' );
				}
				?>
				<article class="gsp-children-card">
					<a class="gsp-children-card__media" href="<?php echo esc_url( $program_link ); ?>"<?php echo gspcp_external_link_attrs( $program_link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo wp_kses_post( $image_html ); ?></a>
					<div class="gsp-children-card__body">
						<h3><?php echo esc_html( $title ); ?></h3>
						<p><?php echo esc_html( $summary ); ?></p>
						<div><span><?php echo esc_html( $age ); ?></span><a href="<?php echo esc_url( $program_link ); ?>"<?php echo gspcp_external_link_attrs( $program_link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> aria-label="<?php echo esc_attr( $title ); ?>">→</a></div>
					</div>
				</article>
			<?php endforeach; ?>
			</div>
			<a class="gsp-children-more" href="<?php echo esc_url( $all_programs ); ?>"<?php echo gspcp_external_link_attrs( $all_programs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( 'Все программы', 'gsp-children-portal' ); ?></a>
		</section>

		<section class="gsp-children-partner" id="gsp-children-partners" aria-labelledby="gsp-children-partner-title">
			<div class="gsp-children-partner__content">
				<h2 id="gsp-children-partner-title"><?php echo esc_html( $partner_title ); ?></h2>
				<p><?php echo esc_html( $partner_text ); ?></p>
				<ul><li><?php esc_html_e( 'Индивидуальные занятия', 'gsp-children-portal' ); ?></li><li><?php esc_html_e( 'Подготовка к ОГЭ/ЕГЭ', 'gsp-children-portal' ); ?></li><li><?php esc_html_e( 'Английский язык', 'gsp-children-portal' ); ?></li><li><?php esc_html_e( 'Программирование', 'gsp-children-portal' ); ?></li></ul>
				<div class="gsp-children-partner__actions"><a class="gsp-children-button gsp-children-button--green" href="<?php echo esc_url( $partner_link ); ?>"<?php echo gspcp_external_link_attrs( $partner_link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $partner_button ); ?></a><a class="gsp-children-button gsp-children-button--outline" href="<?php echo esc_url( $partner_link ); ?>"<?php echo gspcp_external_link_attrs( $partner_link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( 'Подробнее о программе', 'gsp-children-portal' ); ?></a></div>
			</div>
			<div class="gsp-children-partner__art"><span><?php echo esc_html( $partner_badge ); ?><small><?php esc_html_e( 'для сотрудников ГСП', 'gsp-children-portal' ); ?></small></span><img src="<?php echo esc_url( gspcp_get_asset_image_url( 'skysmart-default.svg' ) ); ?>" alt="" loading="lazy" /></div>
		</section>

		<div class="gsp-children-content-grid">
			<div class="gsp-children-left">
				<section class="gsp-children-section" id="gsp-children-events" aria-labelledby="gsp-children-events-title">
					<h2 id="gsp-children-events-title"><?php esc_html_e( 'Ближайшие мероприятия', 'gsp-children-portal' ); ?></h2>
					<div class="gsp-children-event-grid">
					<?php foreach ( $event_items as $event ) : ?>
						<?php
						if ( $use_demo_events ) {
							$event_link = $event['url'];
							$event_date = $event['date'];
							$title      = $event['title'];
							$summary    = $event['deadline'];
							$image_html = sprintf( '<img class="gsp-children-event__image" src="%s" alt="" loading="lazy" />', esc_url( gspcp_get_asset_image_url( $event['image'] ) ) );
						} else {
							$event_link = gspcp_get_meta( $event->ID, 'gsp_external_url', get_permalink( $event ) );
							$date_meta  = gspcp_get_meta( $event->ID, 'gsp_event_date' );
							$event_date = $date_meta ? gspcp_format_date( $date_meta ) : date_i18n( 'j F', strtotime( $event->post_date ) );
							$title      = get_the_title( $event );
							$summary    = gspcp_get_meta( $event->ID, 'gsp_deadline', gspcp_get_post_summary( $event, 8 ) );
							$image_html = gspcp_get_image_html( $event->ID, 'thumbnail', 'gsp-children-event__image', 'event-default.svg' );
						}
						?>
						<article class="gsp-children-event"><a href="<?php echo esc_url( $event_link ); ?>"<?php echo gspcp_external_link_attrs( $event_link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo wp_kses_post( $image_html ); ?></a><time><?php echo esc_html( $event_date ); ?></time><div><h3><?php echo esc_html( $title ); ?></h3><p><?php echo esc_html( $summary ); ?></p></div><a class="gsp-children-event__arrow" href="<?php echo esc_url( $event_link ); ?>"<?php echo gspcp_external_link_attrs( $event_link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>→</a></article>
					<?php endforeach; ?>
					</div>
					<a class="gsp-children-more" href="<?php echo esc_url( $all_events ); ?>"<?php echo gspcp_external_link_attrs( $all_events ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( 'Все мероприятия', 'gsp-children-portal' ); ?></a>
				</section>

				<section class="gsp-children-section" id="gsp-children-stories" aria-labelledby="gsp-children-stories-title">
					<h2 id="gsp-children-stories-title"><?php esc_html_e( 'Истории сотрудников', 'gsp-children-portal' ); ?></h2>
					<div class="gsp-children-story-grid">
					<?php foreach ( $story_items as $story ) : ?>
						<?php
						if ( $use_demo_stories ) {
							$quote      = $story['quote'];
							$name       = $story['name'];
							$position   = $story['position'];
							$image_html = sprintf( '<img class="gsp-children-story__image" src="%s" alt="" loading="lazy" />', esc_url( gspcp_get_asset_image_url( $story['image'] ) ) );
						} else {
							$quote      = gspcp_get_post_summary( $story, 18 );
							$name       = gspcp_get_meta( $story->ID, 'gsp_person_name', get_the_title( $story ) );
							$position   = gspcp_get_meta( $story->ID, 'gsp_person_position' );
							$image_html = gspcp_get_image_html( $story->ID, 'thumbnail', 'gsp-children-story__image', 'story-default.svg' );
						}
						?>
						<article class="gsp-children-story"><?php echo wp_kses_post( $image_html ); ?><div><blockquote><?php echo esc_html( $quote ); ?></blockquote><strong><?php echo esc_html( $name ); ?></strong><span><?php echo esc_html( $position ); ?></span></div></article>
					<?php endforeach; ?>
					</div>
				</section>
			</div>

			<aside class="gsp-children-quick" id="gsp-children-contacts" aria-labelledby="gsp-children-quick-title">
				<h2 id="gsp-children-quick-title"><?php esc_html_e( 'Быстрые ссылки', 'gsp-children-portal' ); ?></h2>
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
						$summary       = gspcp_get_post_summary( $material, 9 );
						$icon          = '↗';
					}
					?>
					<li><a href="<?php echo esc_url( $material_link ); ?>"<?php echo gspcp_external_link_attrs( $material_link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><span><?php echo esc_html( $icon ); ?></span><strong><?php echo esc_html( $title ); ?></strong><small><?php echo esc_html( $summary ); ?></small></a></li>
				<?php endforeach; ?>
				</ul>
				<div class="gsp-children-faq-mini" aria-label="<?php esc_attr_e( 'Вопросы и ответы', 'gsp-children-portal' ); ?>">
					<?php foreach ( array_slice( $faq_items, 0, 3 ) as $index => $item ) : ?>
						<?php
						$question = $use_demo_faq ? $item['question'] : get_the_title( $item );
						$answer   = $use_demo_faq ? wpautop( $item['answer'] ) : apply_filters( 'the_content', $item->post_content );
						?>
						<details <?php echo 0 === $index ? 'open' : ''; ?>>
							<summary><?php echo esc_html( $question ); ?></summary>
							<div><?php echo wp_kses_post( $answer ); ?></div>
						</details>
					<?php endforeach; ?>
				</div>
				<a class="gsp-children-quick__note" href="<?php echo esc_url( $application_url ); ?>"<?php echo gspcp_external_link_attrs( $application_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( 'Забота о детях — это вклад в будущее!', 'gsp-children-portal' ); ?></a>
			</aside>
		</div>
	</main>

	<footer class="gsp-children-benefits" aria-label="<?php esc_attr_e( 'Преимущества', 'gsp-children-portal' ); ?>">
		<span><?php esc_html_e( 'Мы создаём возможности', 'gsp-children-portal' ); ?></span>
		<span><?php esc_html_e( 'Мы поддерживаем развитие', 'gsp-children-portal' ); ?></span>
		<span><?php esc_html_e( 'Мы вдохновляем на достижения', 'gsp-children-portal' ); ?></span>
		<span><?php esc_html_e( 'Мы строим будущее вместе', 'gsp-children-portal' ); ?></span>
	</footer>
</div>
