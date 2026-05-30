<?php
/**
 * Meta boxes for GSP Children Portal CPT.
 *
 * @package GSP_Children_Portal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns configured meta fields.
 *
 * @return array<string,array<string,string>>
 */
function gspcp_get_meta_fields() {
	return array(
		'gsp_age'                   => array( 'label' => __( 'Возраст', 'gsp-children-portal' ), 'type' => 'text' ),
		'gsp_event_date'            => array( 'label' => __( 'Дата события', 'gsp-children-portal' ), 'type' => 'date' ),
		'gsp_deadline'              => array( 'label' => __( 'Дедлайн регистрации', 'gsp-children-portal' ), 'type' => 'date' ),
		'gsp_button_text'           => array( 'label' => __( 'Текст кнопки', 'gsp-children-portal' ), 'type' => 'text' ),
		'gsp_external_url'          => array( 'label' => __( 'Внешняя ссылка', 'gsp-children-portal' ), 'type' => 'url' ),
		'gsp_badge'                 => array( 'label' => __( 'Бейдж', 'gsp-children-portal' ), 'type' => 'text' ),
		'gsp_order'                 => array( 'label' => __( 'Порядок сортировки', 'gsp-children-portal' ), 'type' => 'number' ),
		'gsp_format'                => array( 'label' => __( 'Формат', 'gsp-children-portal' ), 'type' => 'text' ),
		'gsp_person_name'           => array( 'label' => __( 'Имя сотрудника', 'gsp-children-portal' ), 'type' => 'text' ),
		'gsp_person_position'       => array( 'label' => __( 'Должность/подразделение', 'gsp-children-portal' ), 'type' => 'text' ),
		'gsp_primary_button_text'   => array( 'label' => __( 'Hero: текст первой кнопки', 'gsp-children-portal' ), 'type' => 'text' ),
		'gsp_primary_button_url'    => array( 'label' => __( 'Hero: ссылка первой кнопки', 'gsp-children-portal' ), 'type' => 'url' ),
		'gsp_secondary_button_text' => array( 'label' => __( 'Hero: текст второй кнопки', 'gsp-children-portal' ), 'type' => 'text' ),
		'gsp_secondary_button_url'  => array( 'label' => __( 'Hero: ссылка второй кнопки', 'gsp-children-portal' ), 'type' => 'url' ),
	);
}

/**
 * Adds meta box for CPT only.
 */
function gspcp_add_meta_box() {
	add_meta_box(
		'gspcp_post_meta',
		__( 'Поля детской программы', 'gsp-children-portal' ),
		'gspcp_render_meta_box',
		GSPCP_POST_TYPE,
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'gspcp_add_meta_box' );

/**
 * Renders meta box.
 *
 * @param WP_Post $post Post object.
 */
function gspcp_render_meta_box( $post ) {
	wp_nonce_field( 'gspcp_save_meta', 'gspcp_meta_nonce' );
	?>
	<div class="gspcp-meta-box">
		<p class="description">
			<?php esc_html_e( 'Поля используются только элементами CPT gsp_child_item на странице [gsp_children_portal]. Выберите раздел страницы в таксономии «Разделы страницы».', 'gsp-children-portal' ); ?>
		</p>
		<table class="form-table" role="presentation">
			<tbody>
			<?php foreach ( gspcp_get_meta_fields() as $key => $field ) : ?>
				<tr>
					<th scope="row"><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ); ?></label></th>
					<td>
						<input class="regular-text" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" type="<?php echo esc_attr( $field['type'] ); ?>" value="<?php echo esc_attr( get_post_meta( $post->ID, $key, true ) ); ?>" />
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php
}

/**
 * Saves meta fields safely.
 *
 * @param int $post_id Post ID.
 */
function gspcp_save_post_meta( $post_id ) {
	if ( ! isset( $_POST['gspcp_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gspcp_meta_nonce'] ) ), 'gspcp_save_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( gspcp_get_meta_fields() as $key => $field ) {
		if ( ! isset( $_POST[ $key ] ) ) {
			delete_post_meta( $post_id, $key );
			continue;
		}

		$raw = wp_unslash( $_POST[ $key ] );
		if ( is_array( $raw ) || '' === trim( $raw ) ) {
			delete_post_meta( $post_id, $key );
			continue;
		}

		if ( 'url' === $field['type'] ) {
			$value = esc_url_raw( $raw );
		} elseif ( 'number' === $field['type'] ) {
			$value = (string) absint( $raw );
		} else {
			$value = sanitize_text_field( $raw );
		}

		if ( '' === $value ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $value );
		}
	}
}
add_action( 'save_post_' . GSPCP_POST_TYPE, 'gspcp_save_post_meta' );
