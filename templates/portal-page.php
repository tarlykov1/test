<?php
/**
 * Portal landing template.
 *
 * @package GSP_Children_Portal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$links           = isset( $context['links'] ) ? $context['links'] : array();
$application_url = isset( $links['application_url'] ) ? $links['application_url'] : '#gspcp-contacts';
$account_url     = isset( $links['account_url'] ) ? $links['account_url'] : '#gspcp-contacts';
$programs_url    = isset( $links['programs_url'] ) ? $links['programs_url'] : '#gspcp-programs';
$events_url      = isset( $links['events_url'] ) ? $links['events_url'] : '#gspcp-events';

$hero     = ! empty( $context['hero'] ) ? $context['hero'] : null;
$programs = ! empty( $context['programs'] ) ? $context['programs'] : gspcp_get_demo_programs();
$partner  = ! empty( $context['partner'] ) ? $context['partner'] : null;
$events   = ! empty( $context['events'] ) ? $context['events'] : gspcp_get_demo_events();
$stories  = ! empty( $context['stories'] ) ? $context['stories'] : gspcp_get_demo_stories();
$faq      = ! empty( $context['faq'] ) ? $context['faq'] : gspcp_get_demo_faq();
$materials= ! empty( $context['materials'] ) ? $context['materials'] : gspcp_get_demo_materials();
$footer   = ! empty( $context['footer'] ) ? $context['footer'] : array();
$demo_hero= gspcp_get_demo_hero();
$demo_partner = gspcp_get_demo_partner();
?>
<div class="gspcp-root">
	<header class="gspcp-header">
		<div class="gspcp-container gspcp-header__inner">
			<a class="gspcp-logo" href="#gspcp-hero" aria-label="<?php esc_attr_e( 'Газстройпром', 'gsp-children-portal' ); ?>"><?php esc_html_e( 'Газстройпром', 'gsp-children-portal' ); ?></a>
			<nav class="gspcp-nav" aria-label="<?php esc_attr_e( 'Навигация по детским программам', 'gsp-children-portal' ); ?>">
				<a href="#gspcp-programs"><?php esc_html_e( 'Программы', 'gsp-children-portal' ); ?></a>
				<a href="#gspcp-events"><?php esc_html_e( 'Мероприятия', 'gsp-children-portal' ); ?></a>
				<a href="#gspcp-partners"><?php esc_html_e( 'Партнёры', 'gsp-children-portal' ); ?></a>
				<a href="#gspcp-stories"><?php esc_html_e( 'Истории', 'gsp-children-portal' ); ?></a>
				<a href="#gspcp-faq"><?php esc_html_e( 'Вопросы и ответы', 'gsp-children-portal' ); ?></a>
			</nav>
			<a class="gspcp-account" href="<?php echo esc_url( $account_url ); ?>"<?php echo gspcp_external_link_attrs( $account_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( 'Личный кабинет', 'gsp-children-portal' ); ?></a>
		</div>
	</header>

	<main class="gspcp-container">
		<section class="gspcp-hero" id="gspcp-hero" aria-labelledby="gspcp-hero-title">
			<div class="gspcp-hero__content">
				<p class="gspcp-hero__eyebrow"><?php echo esc_html( $hero ? wp_strip_all_tags( $hero->post_content ) : $demo_hero['eyebrow'] ); ?></p>
				<h1 id="gspcp-hero-title"><?php echo esc_html( $hero ? get_the_title( $hero ) : $demo_hero['title'] ); ?></h1>
				<p class="gspcp-hero__lead"><?php echo esc_html( $hero ? gspcp_get_post_summary( $hero, 24 ) : $demo_hero['description'] ); ?></p>
				<div class="gspcp-actions">
					<?php $primary_url = $hero ? gspcp_get_meta( $hero->ID, 'gsp_primary_button_url', $programs_url ) : $demo_hero['primary_button_url']; ?>
					<a class="gspcp-btn gspcp-btn--primary" href="<?php echo esc_url( $primary_url ); ?>"<?php echo gspcp_external_link_attrs( $primary_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $hero ? gspcp_get_meta( $hero->ID, 'gsp_primary_button_text', __( 'Смотреть программы', 'gsp-children-portal' ) ) : $demo_hero['primary_button_text'] ); ?></a>
					<?php $secondary_url = $hero ? gspcp_get_meta( $hero->ID, 'gsp_secondary_button_url', $application_url ) : $demo_hero['secondary_button_url']; ?>
					<a class="gspcp-btn gspcp-btn--ghost" href="<?php echo esc_url( $secondary_url ); ?>"<?php echo gspcp_external_link_attrs( $secondary_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $hero ? gspcp_get_meta( $hero->ID, 'gsp_secondary_button_text', __( 'Подать заявку', 'gsp-children-portal' ) ) : $demo_hero['secondary_button_text'] ); ?></a>
				</div>
			</div>
			<div class="gspcp-hero__visual">
				<?php echo $hero ? wp_kses_post( gspcp_get_image_html( $hero->ID, 'full', 'gspcp-hero__image', 'hero-retro-default.svg' ) ) : '<img class="gspcp-hero__image" src="' . esc_url( gspcp_get_asset_image_url( $demo_hero['image'] ) ) . '" alt="" loading="eager" />'; ?>
			</div>
		</section>

		<section class="gspcp-programs-layout" id="gspcp-programs" aria-labelledby="gspcp-programs-title">
			<div>
				<div class="gspcp-section-head"><h2 id="gspcp-programs-title"><?php esc_html_e( 'Программы и направления', 'gsp-children-portal' ); ?></h2></div>
				<div class="gspcp-program-grid">
				<?php foreach ( $programs as $index => $program ) : ?>
					<?php
					$is_demo = is_array( $program );
					$title   = $is_demo ? $program['title'] : get_the_title( $program );
					$desc    = $is_demo ? $program['description'] : gspcp_get_post_summary( $program, 12 );
					$age     = $is_demo ? $program['age'] : gspcp_get_meta( $program->ID, 'gsp_age' );
					$url     = $is_demo ? $program['url'] : gspcp_get_meta( $program->ID, 'gsp_external_url', $application_url );
					$image   = $is_demo ? '<img class="gspcp-program-card__image" src="' . esc_url( gspcp_get_asset_image_url( $program['image'] ) ) . '" alt="" loading="lazy" />' : gspcp_get_image_html( $program->ID, 'medium_large', 'gspcp-program-card__image', gspcp_get_demo_programs()[ $index % 6 ]['image'] );
					?>
					<article class="gspcp-program-card"><a href="<?php echo esc_url( $url ); ?>"<?php echo gspcp_external_link_attrs( $url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo wp_kses_post( $image ); ?><span class="gspcp-program-card__body"><strong><?php echo esc_html( $title ); ?></strong><em><?php echo esc_html( $desc ); ?></em><small><?php echo esc_html( $age ); ?></small><b aria-hidden="true">→</b></span></a></article>
				<?php endforeach; ?>
				</div>
				<a class="gspcp-more" href="<?php echo esc_url( $programs_url ); ?>"<?php echo gspcp_external_link_attrs( $programs_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( 'Все программы', 'gsp-children-portal' ); ?></a>
			</div>

			<aside class="gspcp-partner" id="gspcp-partners">
				<?php
				$partner_title = $partner ? get_the_title( $partner ) : $demo_partner['title'];
				$partner_desc  = $partner ? gspcp_get_post_summary( $partner, 24 ) : $demo_partner['description'];
				$partner_url   = $partner ? gspcp_get_meta( $partner->ID, 'gsp_external_url', $demo_partner['url'] ) : $demo_partner['url'];
				$partner_btn   = $partner ? gspcp_get_meta( $partner->ID, 'gsp_button_text', $demo_partner['button'] ) : $demo_partner['button'];
				$partner_badge = $partner ? gspcp_get_meta( $partner->ID, 'gsp_badge', $demo_partner['badge'] ) : $demo_partner['badge'];
				$partner_items = $partner ? array_filter( array_map( 'trim', explode( "\n", wp_strip_all_tags( $partner->post_content ) ) ) ) : $demo_partner['items'];
				?>
				<span class="gspcp-partner__badge"><?php echo esc_html( $partner_badge ); ?></span>
				<div class="gspcp-partner__copy"><h2><?php echo esc_html( $partner_title ); ?></h2><p><?php echo esc_html( $partner_desc ); ?></p><ul><?php foreach ( array_slice( $partner_items, 0, 4 ) as $item ) : ?><li><?php echo esc_html( $item ); ?></li><?php endforeach; ?></ul><a class="gspcp-btn gspcp-btn--green" href="<?php echo esc_url( $partner_url ); ?>"<?php echo gspcp_external_link_attrs( $partner_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $partner_btn ); ?></a></div>
				<div class="gspcp-partner__visual"><?php echo $partner ? wp_kses_post( gspcp_get_image_html( $partner->ID, 'medium_large', 'gspcp-partner__image', 'skysmart-default.svg' ) ) : '<img class="gspcp-partner__image" src="' . esc_url( gspcp_get_asset_image_url( 'skysmart-default.svg' ) ) . '" alt="" loading="lazy" />'; ?></div>
			</aside>
		</section>

		<div class="gspcp-lower-grid">
			<section class="gspcp-section" id="gspcp-events" aria-labelledby="gspcp-events-title">
				<div class="gspcp-section-head"><h2 id="gspcp-events-title"><?php esc_html_e( 'Ближайшие мероприятия', 'gsp-children-portal' ); ?></h2></div>
				<div class="gspcp-event-grid">
				<?php foreach ( $events as $event ) : ?>
					<?php
					$is_demo = is_array( $event );
					$date    = $is_demo ? $event['date'] : gspcp_get_meta( $event->ID, 'gsp_event_date' );
					$parts   = gspcp_get_event_date_parts( $date );
					$title   = $is_demo ? $event['title'] : get_the_title( $event );
					$summary = $is_demo ? $event['deadline'] : gspcp_get_meta( $event->ID, 'gsp_deadline', gspcp_get_post_summary( $event, 8 ) );
					$url     = $is_demo ? $event['url'] : gspcp_get_meta( $event->ID, 'gsp_external_url', $application_url );
					$image   = $is_demo ? '<img src="' . esc_url( gspcp_get_asset_image_url( $event['image'] ) ) . '" alt="" loading="lazy" />' : gspcp_get_image_html( $event->ID, 'thumbnail', '', 'event-default.svg' );
					?>
					<article class="gspcp-event"><a href="<?php echo esc_url( $url ); ?>"<?php echo gspcp_external_link_attrs( $url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo wp_kses_post( $image ); ?><time><b><?php echo esc_html( $parts['day'] ); ?></b><span><?php echo esc_html( $parts['month'] ); ?></span></time><span><strong><?php echo esc_html( $title ); ?></strong><small><?php echo esc_html( $summary ); ?></small></span><em>→</em></a></article>
				<?php endforeach; ?>
				</div>
				<a class="gspcp-more" href="<?php echo esc_url( $events_url ); ?>"<?php echo gspcp_external_link_attrs( $events_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( 'Все мероприятия', 'gsp-children-portal' ); ?></a>
			</section>

			<section class="gspcp-section" id="gspcp-stories" aria-labelledby="gspcp-stories-title">
				<div class="gspcp-section-head"><h2 id="gspcp-stories-title"><?php esc_html_e( 'Истории сотрудников', 'gsp-children-portal' ); ?></h2></div>
				<div class="gspcp-story-grid">
				<?php foreach ( $stories as $story ) : ?>
					<?php $is_demo = is_array( $story ); ?>
					<article class="gspcp-story"><?php echo $is_demo ? '<img src="' . esc_url( gspcp_get_asset_image_url( $story['image'] ) ) . '" alt="" loading="lazy" />' : wp_kses_post( gspcp_get_image_html( $story->ID, 'thumbnail', '', 'story-default.svg' ) ); ?><div><blockquote>«<?php echo esc_html( $is_demo ? $story['quote'] : gspcp_get_post_summary( $story, 20 ) ); ?>»</blockquote><strong><?php echo esc_html( $is_demo ? $story['name'] : gspcp_get_meta( $story->ID, 'gsp_person_name', get_the_title( $story ) ) ); ?></strong><small><?php echo esc_html( $is_demo ? $story['position'] : gspcp_get_meta( $story->ID, 'gsp_person_position' ) ); ?></small></div></article>
				<?php endforeach; ?>
				</div>
			</section>
		</div>

		<section class="gspcp-info-grid" id="gspcp-faq" aria-labelledby="gspcp-faq-title">
			<div class="gspcp-faq"><h2 id="gspcp-faq-title"><?php esc_html_e( 'Вопросы и ответы', 'gsp-children-portal' ); ?></h2><?php foreach ( $faq as $index => $item ) : ?><?php $is_demo = is_array( $item ); ?><details <?php echo 0 === $index ? 'open' : ''; ?>><summary><?php echo esc_html( $is_demo ? $item['question'] : get_the_title( $item ) ); ?></summary><div><?php echo wp_kses_post( wpautop( $is_demo ? $item['answer'] : $item->post_content ) ); ?></div></details><?php endforeach; ?></div>
			<aside class="gspcp-quick" id="gspcp-contacts"><h2><?php esc_html_e( 'Быстрые ссылки', 'gsp-children-portal' ); ?></h2><ul><?php foreach ( $materials as $material ) : ?><?php $is_demo = is_array( $material ); $url = $is_demo ? $material['url'] : gspcp_get_meta( $material->ID, 'gsp_external_url', $application_url ); ?><li><a href="<?php echo esc_url( $url ); ?>"<?php echo gspcp_external_link_attrs( $url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><span><?php echo esc_html( $is_demo ? $material['icon'] : '↗' ); ?></span><strong><?php echo esc_html( $is_demo ? $material['title'] : get_the_title( $material ) ); ?></strong><small><?php echo esc_html( $is_demo ? $material['description'] : gspcp_get_post_summary( $material, 8 ) ); ?></small></a></li><?php endforeach; ?></ul><a class="gspcp-postcard" href="<?php echo esc_url( $application_url ); ?>"<?php echo gspcp_external_link_attrs( $application_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( 'Забота о детях — это вклад в будущее!', 'gsp-children-portal' ); ?></a></aside>
		</section>
	</main>

	<footer class="gspcp-footer"><div class="gspcp-container gspcp-footer__inner">
		<?php if ( $footer ) : ?><?php foreach ( $footer as $item ) : ?><span><?php echo esc_html( get_the_title( $item ) ); ?></span><?php endforeach; ?><?php else : ?><span><?php esc_html_e( 'Мы создаём возможности', 'gsp-children-portal' ); ?></span><span><?php esc_html_e( 'Мы поддерживаем развитие', 'gsp-children-portal' ); ?></span><span><?php esc_html_e( 'Мы вдохновляем на достижения', 'gsp-children-portal' ); ?></span><span><?php esc_html_e( 'Мы строим будущее вместе', 'gsp-children-portal' ); ?></span><?php endif; ?>
	</div></footer>
</div>
