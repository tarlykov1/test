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
			<a href="#gsp-children-faq"><?php esc_html_e( 'Вопросы и ответы', 'gsp-children-portal' ); ?></a>
			<a href="#gsp-children-contacts"><?php esc_html_e( 'Контакты', 'gsp-children-portal' ); ?></a>
		</nav>
		<a class="gspcp-account" href="#gsp-children-contacts"><?php esc_html_e( 'Личный кабинет', 'gsp-children-portal' ); ?></a>
	</header>

	<section class="gspcp-hero" aria-labelledby="gspcp-hero-title">
		<div class="gspcp-hero__content">
			<div class="gspcp-hero__ribbons"><span><?php esc_html_e( 'Строим будущее вместе!', 'gsp-children-portal' ); ?></span><span><?php esc_html_e( 'Сильной стране — будущее поколение!', 'gsp-children-portal' ); ?></span></div>
			<?php if ( $hero ) : ?>
				<h1 id="gspcp-hero-title"><?php echo esc_html( get_the_title( $hero ) ); ?></h1>
				<p class="gspcp-hero__lead"><?php echo esc_html( gspcp_get_post_summary( $hero, 34 ) ); ?></p>
				<?php
				$primary_text   = gspcp_get_meta( $hero->ID, 'gsp_primary_button_text', __( 'Смотреть программы', 'gsp-children-portal' ) );
				$primary_url    = gspcp_get_meta( $hero->ID, 'gsp_primary_button_url', '#gsp-children-programs' );
				$secondary_text = gspcp_get_meta( $hero->ID, 'gsp_secondary_button_text', __( 'Подать заявку', 'gsp-children-portal' ) );
				$secondary_url  = gspcp_get_meta( $hero->ID, 'gsp_secondary_button_url', '#gsp-children-contacts' );
				?>
			<?php else : ?>
				<h1 id="gspcp-hero-title"><?php esc_html_e( 'Детям сотрудников Газстройпрома', 'gsp-children-portal' ); ?></h1>
				<p class="gspcp-hero__lead"><?php esc_html_e( 'Добавьте запись в категорию gsp-children-hero, чтобы заполнить главный экран портала.', 'gsp-children-portal' ); ?></p>
				<?php
				$primary_text   = __( 'Смотреть программы', 'gsp-children-portal' );
				$primary_url    = '#gsp-children-programs';
				$secondary_text = __( 'Подать заявку', 'gsp-children-portal' );
				$secondary_url  = '#gsp-children-contacts';
				?>
			<?php endif; ?>
			<div class="gspcp-hero__actions">
				<a class="gspcp-button gspcp-button--primary" href="<?php echo esc_url( $primary_url ); ?>"<?php echo gspcp_external_link_attrs( $primary_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $primary_text ); ?></a>
				<a class="gspcp-button gspcp-button--ghost" href="<?php echo esc_url( $secondary_url ); ?>"<?php echo gspcp_external_link_attrs( $secondary_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $secondary_text ); ?></a>
			</div>
		</div>
		<div class="gspcp-hero__media">
			<?php echo wp_kses_post( $hero ? gspcp_get_image_html( $hero->ID, 'large', 'gspcp-hero__image' ) : sprintf( '<img class="gspcp-hero__image" src="%s" alt="" />', esc_url( gspcp_get_placeholder_url() ) ) ); ?>
		</div>
	</section>

	<div class="gspcp-main-grid">
		<section class="gspcp-section gspcp-programs" id="gsp-children-programs" aria-labelledby="gspcp-programs-title">
			<div class="gspcp-section__head">
				<h2 id="gspcp-programs-title"><?php esc_html_e( 'Программы и направления', 'gsp-children-portal' ); ?></h2>
			</div>
			<?php if ( ! empty( $programs ) ) : ?>
				<div class="gspcp-program-grid">
					<?php foreach ( $programs as $program ) : ?>
						<?php
						$link = gspcp_get_meta( $program->ID, 'gsp_external_url', get_permalink( $program ) );
						$age  = gspcp_get_meta( $program->ID, 'gsp_age' );
						?>
						<article class="gspcp-card gspcp-program-card">
							<a class="gspcp-card__media" href="<?php echo esc_url( $link ); ?>"<?php echo gspcp_external_link_attrs( $link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo wp_kses_post( gspcp_get_image_html( $program->ID, 'medium_large' ) ); ?></a>
							<div class="gspcp-card__body">
								<h3><a href="<?php echo esc_url( $link ); ?>"<?php echo gspcp_external_link_attrs( $link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( get_the_title( $program ) ); ?></a></h3>
								<p><?php echo esc_html( gspcp_get_post_summary( $program, 16 ) ); ?></p>
								<div class="gspcp-card__meta"><span><?php echo esc_html( $age ? $age : __( 'Для детей сотрудников', 'gsp-children-portal' ) ); ?></span><span class="gspcp-card__arrow" aria-hidden="true">→</span></div>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
				<?php
				$programs_category = get_category_by_slug( 'gsp-children-programs' );
				$programs_url      = $programs_category ? get_category_link( $programs_category ) : '#gsp-children-programs';
				?>
				<a class="gspcp-button gspcp-button--small" href="<?php echo esc_url( $programs_url ); ?>"><?php esc_html_e( 'Все программы', 'gsp-children-portal' ); ?></a>
			<?php else : ?>
				<p class="gspcp-empty"><?php esc_html_e( 'Программы скоро появятся. Добавьте записи в категорию gsp-children-programs.', 'gsp-children-portal' ); ?></p>
			<?php endif; ?>
		</section>

		<section class="gspcp-partner" id="gsp-children-partners" aria-labelledby="gspcp-partner-title">
			<?php if ( $partner ) : ?>
				<?php
				$partner_link = gspcp_get_meta( $partner->ID, 'gsp_external_url', get_permalink( $partner ) );
				$badge        = gspcp_get_meta( $partner->ID, 'gsp_badge' );
				$button_text  = gspcp_get_meta( $partner->ID, 'gsp_button_text', __( 'Получить скидку', 'gsp-children-portal' ) );
				?>
				<div class="gspcp-partner__content">
					<?php if ( $badge ) : ?><span class="gspcp-badge"><?php echo esc_html( $badge ); ?></span><?php endif; ?>
					<h2 id="gspcp-partner-title"><?php echo esc_html( get_the_title( $partner ) ); ?></h2>
					<div class="gspcp-partner__text"><?php echo wp_kses_post( has_excerpt( $partner ) ? wpautop( $partner->post_excerpt ) : apply_filters( 'the_content', $partner->post_content ) ); ?></div>
					<a class="gspcp-button gspcp-button--green gspcp-partner__cta" href="<?php echo esc_url( $partner_link ); ?>"<?php echo gspcp_external_link_attrs( $partner_link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $button_text ); ?></a>
				</div>
				<div class="gspcp-partner__media"><?php echo wp_kses_post( gspcp_get_image_html( $partner->ID, 'large', 'gspcp-partner__image' ) ); ?></div>
			<?php else : ?>
				<h2 id="gspcp-partner-title"><?php esc_html_e( 'Партнёрские предложения', 'gsp-children-portal' ); ?></h2>
				<p><?php esc_html_e( 'Добавьте запись в категорию gsp-children-partners, чтобы показать Skysmart или другое предложение.', 'gsp-children-portal' ); ?></p>
			<?php endif; ?>
		</section>
	</div>

	<div class="gspcp-lower-grid">
		<section class="gspcp-section" id="gsp-children-events" aria-labelledby="gspcp-events-title">
			<div class="gspcp-section__head"><h2 id="gspcp-events-title"><?php esc_html_e( 'Ближайшие мероприятия', 'gsp-children-portal' ); ?></h2></div>
			<?php if ( ! empty( $events ) ) : ?>
				<div class="gspcp-event-grid">
				<?php foreach ( $events as $event ) : ?>
					<?php
					$event_link = gspcp_get_meta( $event->ID, 'gsp_external_url', get_permalink( $event ) );
					$event_date = gspcp_get_meta( $event->ID, 'gsp_event_date' );
					$deadline   = gspcp_get_meta( $event->ID, 'gsp_deadline' );
					?>
					<article class="gspcp-event-card">
						<div class="gspcp-event-card__image"><?php echo wp_kses_post( gspcp_get_image_html( $event->ID, 'medium', 'gspcp-event-card__img' ) ); ?></div>
						<div class="gspcp-event-card__date"><strong><?php echo esc_html( $event_date ? gspcp_format_date( $event_date ) : date_i18n( 'j F', strtotime( $event->post_date ) ) ); ?></strong></div>
						<div class="gspcp-event-card__body">
							<h3><a href="<?php echo esc_url( $event_link ); ?>"<?php echo gspcp_external_link_attrs( $event_link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( get_the_title( $event ) ); ?></a></h3>
							<p><?php echo esc_html( gspcp_get_post_summary( $event, 14 ) ); ?></p>
							<?php if ( $deadline ) : ?><span><?php printf( esc_html__( 'Регистрация до %s', 'gsp-children-portal' ), esc_html( gspcp_format_date( $deadline ) ) ); ?></span><?php endif; ?>
						</div>
					</article>
				<?php endforeach; ?>
				</div>
			<?php else : ?>
				<p class="gspcp-empty"><?php esc_html_e( 'Ближайшие мероприятия будут опубликованы позже.', 'gsp-children-portal' ); ?></p>
			<?php endif; ?>
		</section>

		<section class="gspcp-section" aria-labelledby="gspcp-stories-title">
			<div class="gspcp-section__head"><h2 id="gspcp-stories-title"><?php esc_html_e( 'Истории сотрудников', 'gsp-children-portal' ); ?></h2></div>
			<?php if ( ! empty( $stories ) ) : ?>
				<div class="gspcp-story-grid">
				<?php foreach ( $stories as $story ) : ?>
					<article class="gspcp-story-card">
						<?php echo wp_kses_post( gspcp_get_image_html( $story->ID, 'medium', 'gspcp-story-card__image' ) ); ?>
						<blockquote>«<?php echo esc_html( gspcp_get_post_summary( $story, 24 ) ); ?>»</blockquote>
						<strong><?php echo esc_html( gspcp_get_meta( $story->ID, 'gsp_person_name', get_the_title( $story ) ) ); ?></strong>
						<span><?php echo esc_html( gspcp_get_meta( $story->ID, 'gsp_person_position' ) ); ?></span>
					</article>
				<?php endforeach; ?>
				</div>
			<?php else : ?>
				<p class="gspcp-empty"><?php esc_html_e( 'Истории сотрудников появятся после публикации записей.', 'gsp-children-portal' ); ?></p>
			<?php endif; ?>
		</section>
	</div>

	<div class="gspcp-bottom-grid">
		<section class="gspcp-section gspcp-faq" id="gsp-children-faq" aria-labelledby="gspcp-faq-title">
			<div class="gspcp-section__head"><h2 id="gspcp-faq-title"><?php esc_html_e( 'Вопросы и ответы', 'gsp-children-portal' ); ?></h2></div>
			<?php if ( ! empty( $faq ) ) : ?>
				<div class="gspcp-accordion" data-gspcp-accordion>
				<?php foreach ( $faq as $index => $item ) : ?>
					<div class="gspcp-accordion__item">
						<button class="gspcp-accordion__button" type="button" aria-expanded="<?php echo 0 === $index ? 'true' : 'false'; ?>">
							<span><?php echo esc_html( get_the_title( $item ) ); ?></span><span aria-hidden="true">+</span>
						</button>
						<div class="gspcp-accordion__panel" <?php echo 0 === $index ? '' : 'hidden'; ?>><?php echo wp_kses_post( apply_filters( 'the_content', $item->post_content ) ); ?></div>
					</div>
				<?php endforeach; ?>
				</div>
			<?php else : ?>
				<p class="gspcp-empty"><?php esc_html_e( 'FAQ пока пуст. Добавьте записи в категорию gsp-children-faq.', 'gsp-children-portal' ); ?></p>
			<?php endif; ?>
		</section>

		<aside class="gspcp-materials" id="gsp-children-contacts" aria-labelledby="gspcp-materials-title">
			<h2 id="gspcp-materials-title"><?php esc_html_e( 'Полезные материалы', 'gsp-children-portal' ); ?></h2>
			<?php if ( ! empty( $materials ) ) : ?>
				<ul>
				<?php foreach ( $materials as $material ) : ?>
					<?php $material_link = gspcp_get_meta( $material->ID, 'gsp_external_url', get_permalink( $material ) ); ?>
					<li>
						<a href="<?php echo esc_url( $material_link ); ?>"<?php echo gspcp_external_link_attrs( $material_link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
							<strong><?php echo esc_html( get_the_title( $material ) ); ?></strong>
							<span><?php echo esc_html( gspcp_get_post_summary( $material, 10 ) ); ?></span>
						</a>
					</li>
				<?php endforeach; ?>
				</ul>
			<?php else : ?>
				<p><?php esc_html_e( 'Добавьте инструкции и контакты в категорию gsp-children-materials.', 'gsp-children-portal' ); ?></p>
			<?php endif; ?>
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
