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

$hero         = ! empty( $context['hero'] ) ? $context['hero'] : null;
$programs     = ! empty( $context['programs'] ) ? $context['programs'] : gspcp_get_demo_programs();
$partner      = ! empty( $context['partner'] ) ? $context['partner'] : null;
$events       = ! empty( $context['events'] ) ? $context['events'] : gspcp_get_demo_events();
$stories      = ! empty( $context['stories'] ) ? $context['stories'] : gspcp_get_demo_stories();
$materials    = ! empty( $context['materials'] ) ? $context['materials'] : gspcp_get_demo_materials();
$footer       = ! empty( $context['footer'] ) ? $context['footer'] : array();
$demo_hero    = gspcp_get_demo_hero();
$demo_partner = gspcp_get_demo_partner();
$hero_image_url = ( $hero && has_post_thumbnail( $hero->ID ) ) ? get_the_post_thumbnail_url( $hero->ID, 'full' ) : gspcp_get_asset_image_url( $demo_hero['image'] );
$hero_title     = $hero ? get_the_title( $hero ) : $demo_hero['title'];
$hero_title_html = esc_html( $hero_title );
if ( preg_match( '/^(.+?\s+сотрудников)\s+(.+)$/iu', $hero_title, $matches ) ) {
	$hero_title_html = '<span class="gspcp-nowrap">' . esc_html( $matches[1] ) . '</span><br>' . esc_html( $matches[2] );
}
?>
<div class="gspcp-root">
	<header class="gspcp-wrap gspcp-header">
		<a class="gspcp-logo" href="#gspcp-hero" aria-label="<?php esc_attr_e( 'Газстройпром', 'gsp-children-portal' ); ?>"><?php esc_html_e( 'Газстройпром', 'gsp-children-portal' ); ?></a>
		<nav class="gspcp-nav" aria-label="<?php esc_attr_e( 'Навигация по детским программам', 'gsp-children-portal' ); ?>">
			<a href="#gspcp-programs"><?php esc_html_e( 'Программы', 'gsp-children-portal' ); ?></a>
			<a href="#gspcp-events"><?php esc_html_e( 'Мероприятия', 'gsp-children-portal' ); ?></a>
			<a href="#gspcp-partners"><?php esc_html_e( 'Партнёры', 'gsp-children-portal' ); ?></a>
			<a href="#gspcp-stories"><?php esc_html_e( 'Новости', 'gsp-children-portal' ); ?></a>
			<a href="#gspcp-contacts"><?php esc_html_e( 'Вопросы и ответы', 'gsp-children-portal' ); ?></a>
		</nav>
		<a class="gspcp-account" href="<?php echo esc_url( $account_url ); ?>"<?php echo gspcp_external_link_attrs( $account_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( 'Личный кабинет', 'gsp-children-portal' ); ?></a>
	</header>

	<main class="gspcp-wrap">
		<section class="gspcp-hero" id="gspcp-hero" aria-labelledby="gspcp-hero-title">
			<div class="gspcp-hero-art" aria-hidden="true">
				<img src="<?php echo esc_url( $hero_image_url ); ?>" alt="" loading="eager" decoding="async" />
			</div>

			<div class="gspcp-hero-content">
				<div class="gspcp-star" aria-hidden="true">★</div>
				<p class="gspcp-hero-slogan"><?php echo wp_kses_post( nl2br( esc_html( $hero ? wp_strip_all_tags( $hero->post_content ) : $demo_hero['eyebrow'] ) ) ); ?></p>
				<h1 id="gspcp-hero-title"><?php echo wp_kses( $hero_title_html, array( 'br' => array(), 'span' => array( 'class' => array() ) ) ); ?></h1>
				<p class="gspcp-hero-lead"><?php echo esc_html( $hero ? gspcp_get_post_summary( $hero, 24 ) : $demo_hero['description'] ); ?></p>
				<div class="gspcp-actions">
					<?php $primary_url = $hero ? gspcp_get_meta( $hero->ID, 'gsp_primary_button_url', $programs_url ) : $demo_hero['primary_button_url']; ?>
					<a class="gspcp-btn gspcp-btn--primary" href="<?php echo esc_url( $primary_url ); ?>"<?php echo gspcp_external_link_attrs( $primary_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $hero ? gspcp_get_meta( $hero->ID, 'gsp_primary_button_text', __( 'Смотреть программы', 'gsp-children-portal' ) ) : $demo_hero['primary_button_text'] ); ?></a>
					<?php $secondary_url = $hero ? gspcp_get_meta( $hero->ID, 'gsp_secondary_button_url', $application_url ) : $demo_hero['secondary_button_url']; ?>
					<a class="gspcp-btn" href="<?php echo esc_url( $secondary_url ); ?>"<?php echo gspcp_external_link_attrs( $secondary_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $hero ? gspcp_get_meta( $hero->ID, 'gsp_secondary_button_text', __( 'Подать заявку', 'gsp-children-portal' ) ) : $demo_hero['secondary_button_text'] ); ?></a>
				</div>
			</div>
		</section>

		<section class="gspcp-showcase" id="gspcp-programs" aria-labelledby="gspcp-programs-title">
			<div>
				<h2 class="gspcp-section-title" id="gspcp-programs-title"><?php esc_html_e( 'Программы и направления', 'gsp-children-portal' ); ?></h2>
				<div class="gspcp-program-grid">
				<?php foreach ( $programs as $index => $program ) : ?>
					<?php
					$is_demo = is_array( $program );
					$title   = $is_demo ? $program['title'] : get_the_title( $program );
					$desc    = $is_demo ? $program['description'] : gspcp_get_post_summary( $program, 12 );
					$age     = $is_demo ? $program['age'] : gspcp_get_meta( $program->ID, 'gsp_age' );
					$url     = $is_demo ? $program['url'] : gspcp_get_meta( $program->ID, 'gsp_external_url', $application_url );
					$image   = $is_demo ? $program['image'] : gspcp_get_meta( $program->ID, 'gsp_fallback_image', array( 'program-education.svg', 'program-sport.svg', 'program-camp.svg', 'program-olympiad.svg', 'program-development.svg', 'program-career.svg' )[ $index % 6 ] );
					?>
					<article class="gspcp-program-card"><a href="<?php echo esc_url( $url ); ?>"<?php echo gspcp_external_link_attrs( $url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><span class="gspcp-program-image"><?php echo $is_demo ? '<img src="' . esc_url( gspcp_get_asset_image_url( $image ) ) . '" alt="" loading="lazy" decoding="async" />' : gspcp_get_image_html( $program->ID, 'medium_large', 'gspcp-program-img', $image ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><span class="gspcp-program-body"><strong><?php echo esc_html( $title ); ?></strong><em><?php echo esc_html( $desc ); ?></em><span class="gspcp-meta"><small><?php echo esc_html( $age ); ?></small><b aria-hidden="true">→</b></span></span></a></article>
				<?php endforeach; ?>
				</div>
				<div class="gspcp-center"><a class="gspcp-btn" href="<?php echo esc_url( $programs_url ); ?>"<?php echo gspcp_external_link_attrs( $programs_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( 'Все программы', 'gsp-children-portal' ); ?></a></div>
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
				<h2><?php echo esc_html( $partner_title ); ?></h2>
				<p><?php echo esc_html( $partner_desc ); ?></p>
				<ul><?php foreach ( array_slice( $partner_items, 0, 4 ) as $item ) : ?><li><?php echo esc_html( $item ); ?></li><?php endforeach; ?></ul>
				<div class="gspcp-actions"><a class="gspcp-btn gspcp-btn--green" href="<?php echo esc_url( $partner_url ); ?>"<?php echo gspcp_external_link_attrs( $partner_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $partner_btn ); ?></a><a class="gspcp-btn gspcp-btn--white" href="<?php echo esc_url( $partner_url ); ?>"<?php echo gspcp_external_link_attrs( $partner_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( 'Подробнее', 'gsp-children-portal' ); ?></a></div>
				<div class="gspcp-partner-badge"><b><?php echo esc_html( $partner_badge ); ?></b><span><?php esc_html_e( 'для сотрудников ГСП', 'gsp-children-portal' ); ?></span></div>
				<div class="gspcp-partner-art" aria-hidden="true"><?php echo $partner ? gspcp_get_image_html( $partner->ID, 'medium_large', 'gspcp-partner-img', $demo_partner['image'] ) : '<img src="' . esc_url( gspcp_get_asset_image_url( $demo_partner['image'] ) ) . '" alt="" loading="lazy" decoding="async" />'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div><div class="gspcp-partner-logo" aria-hidden="true">skysmart</div>
			</aside>
		</section>

		<section class="gspcp-lower" id="gspcp-events">
			<div>
				<h2 class="gspcp-section-title"><?php esc_html_e( 'Ближайшие мероприятия', 'gsp-children-portal' ); ?></h2>
				<div class="gspcp-events">
				<?php foreach ( $events as $index => $event ) : ?>
					<?php
					$is_demo = is_array( $event );
					$date    = $is_demo ? $event['date'] : gspcp_get_meta( $event->ID, 'gsp_event_date' );
					$parts   = gspcp_get_event_date_parts( $date );
					$title   = $is_demo ? $event['title'] : get_the_title( $event );
					$summary = $is_demo ? $event['deadline'] : gspcp_get_meta( $event->ID, 'gsp_deadline', gspcp_get_post_summary( $event, 8 ) );
					$url     = $is_demo ? $event['url'] : gspcp_get_meta( $event->ID, 'gsp_external_url', $application_url );
					$image   = $is_demo ? $event['image'] : gspcp_get_meta( $event->ID, 'gsp_fallback_image', array( 'event-math.svg', 'event-camp.svg', 'event-webinar.svg', 'event-tech.svg' )[ $index % 4 ] );
					?>
					<article class="gspcp-event"><a href="<?php echo esc_url( $url ); ?>"<?php echo gspcp_external_link_attrs( $url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><span class="gspcp-event-thumb"><?php echo $is_demo ? '<img src="' . esc_url( gspcp_get_asset_image_url( $image ) ) . '" alt="" loading="lazy" decoding="async" />' : gspcp_get_image_html( $event->ID, 'thumbnail', 'gspcp-event-img', $image ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><time><b><?php echo esc_html( $parts['day'] ); ?></b><span><?php echo esc_html( $parts['month'] ); ?></span></time><span><strong><?php echo esc_html( $title ); ?></strong><small><?php echo esc_html( $summary ); ?></small></span><em aria-hidden="true">→</em></a></article>
				<?php endforeach; ?>
				</div>
				<div class="gspcp-center"><a class="gspcp-btn" href="<?php echo esc_url( $events_url ); ?>"<?php echo gspcp_external_link_attrs( $events_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( 'Все мероприятия', 'gsp-children-portal' ); ?></a></div>
			</div>

			<div class="gspcp-side">
				<section id="gspcp-stories">
					<h2 class="gspcp-section-title"><?php esc_html_e( 'Истории сотрудников', 'gsp-children-portal' ); ?></h2>
					<div class="gspcp-stories">
					<?php foreach ( $stories as $index => $story ) : ?>
						<?php $is_demo = is_array( $story ); $story_image = $is_demo && ! empty( $story['image'] ) ? $story['image'] : gspcp_get_meta( $story->ID, 'gsp_fallback_image', array( 'story-boy.svg', 'story-girl.svg' )[ $index % 2 ] ); ?>
						<article class="gspcp-story"><span class="gspcp-photo"><?php echo $is_demo ? '<img src="' . esc_url( gspcp_get_asset_image_url( $story_image ) ) . '" alt="" loading="lazy" decoding="async" />' : gspcp_get_image_html( $story->ID, 'thumbnail', 'gspcp-story-img', $story_image ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><div><blockquote><?php echo esc_html( $is_demo ? $story['quote'] : gspcp_get_post_summary( $story, 20 ) ); ?></blockquote><cite><?php echo esc_html( $is_demo ? $story['name'] : gspcp_get_meta( $story->ID, 'gsp_person_name', get_the_title( $story ) ) ); ?><small><?php echo esc_html( $is_demo ? $story['position'] : gspcp_get_meta( $story->ID, 'gsp_person_position' ) ); ?></small></cite></div></article>
					<?php endforeach; ?>
					</div>
					<div class="gspcp-center"><a class="gspcp-btn" href="#gspcp-stories"><?php esc_html_e( 'Все истории', 'gsp-children-portal' ); ?></a></div>
				</section>

				<aside class="gspcp-quick" id="gspcp-contacts">
					<div class="gspcp-quick-list">
					<?php foreach ( $materials as $material ) : ?>
						<?php $is_demo = is_array( $material ); $url = $is_demo ? $material['url'] : gspcp_get_meta( $material->ID, 'gsp_external_url', $application_url ); ?>
						<a class="gspcp-quick-item" href="<?php echo esc_url( $url ); ?>"<?php echo gspcp_external_link_attrs( $url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><span class="gspcp-quick-icon"><?php echo esc_html( $is_demo ? $material['icon'] : '↗' ); ?></span><span><b><?php echo esc_html( $is_demo ? $material['title'] : get_the_title( $material ) ); ?></b><span><?php echo esc_html( $is_demo ? $material['description'] : gspcp_get_post_summary( $material, 8 ) ); ?></span></span></a>
					<?php endforeach; ?>
					</div>
					<a class="gspcp-postcard" href="<?php echo esc_url( $application_url ); ?>"<?php echo gspcp_external_link_attrs( $application_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( 'Забота о детях — это вклад в будущее!', 'gsp-children-portal' ); ?></a>
				</aside>
			</div>
		</section>
	</main>

	<footer class="gspcp-footer"><div class="gspcp-wrap gspcp-footer-inner">
		<?php if ( $footer ) : ?>
			<?php foreach ( $footer as $item ) : ?><div class="gspcp-benefit"><i>✦</i><span><?php echo esc_html( get_the_title( $item ) ); ?></span></div><?php endforeach; ?>
		<?php else : ?>
			<div class="gspcp-benefit"><i>✦</i><span><?php esc_html_e( 'Мы создаём возможности', 'gsp-children-portal' ); ?></span></div><div class="gspcp-benefit"><i>♡</i><span><?php esc_html_e( 'Мы поддерживаем развитие', 'gsp-children-portal' ); ?></span></div><div class="gspcp-benefit"><i>✧</i><span><?php esc_html_e( 'Мы вдохновляем на достижения', 'gsp-children-portal' ); ?></span></div><div class="gspcp-benefit"><i>⌂</i><span><?php esc_html_e( 'Мы строим будущее вместе', 'gsp-children-portal' ); ?></span></div>
		<?php endif; ?>
		<div class="gspcp-plant-line" aria-hidden="true"></div>
	</div></footer>
</div>
