<?php

namespace wp_whise\view\admin\project;

use wp_whise\model\Project;

global $post;

$project = new Project();

$project->set_post( $post->ID );

?>
    <div id="product_images_container">
        <ul class="product_images">
			<?php
			$attachments         = $project->get_gallery_image_ids();
			$update_meta         = false;
			$updated_gallery_ids = array();

			if ( ! empty( $attachments ) ) {
				foreach ( $attachments as $attachment_id ) {
					$attachment = wp_get_attachment_image( $attachment_id, 'thumbnail' );

					// if attachment is empty skip.
					if ( empty( $attachment ) ) {
						$update_meta = true;
						continue;
					}

					echo '<li class="image" data-attachment_id="' . esc_attr( $attachment_id ) . '">
								' . $attachment . '
								<ul class="actions">
									<li><a href="#" class="delete tips" data-tip="' . esc_attr__( 'Delete image', 'woocommerce' ) . '">' . __( 'Delete', 'woocommerce' ) . '</a></li>
								</ul>
							</li>';

					// rebuild ids to be saved.
					$updated_gallery_ids[] = $attachment_id;
				}

				// need to update product meta to set new gallery ids
				if ( $update_meta ) {
					update_post_meta( $post->ID, '_product_image_gallery', implode( ',', $updated_gallery_ids ) );
				}
			}
			?>
        </ul>

        <input type="hidden" id="product_image_gallery" name="product_image_gallery"
               value="<?php echo esc_attr( implode( ',', $updated_gallery_ids ) ); ?>"/>

    </div>
    <p class="add_product_images hide-if-no-js">
        <a href="#" data-choose="<?php esc_attr_e( 'Add images to gallery', 'wp_whise' ); ?>"
           data-update="<?php esc_attr_e( 'Add to gallery', 'woocommerce' ); ?>"
           data-delete="<?php esc_attr_e( 'Delete image', 'woocommerce' ); ?>"
           data-text="<?php esc_attr_e( 'Delete', 'woocommerce' ); ?>"><?php _e( 'Add gallery images', 'wp_whise' ); ?></a>
    </p>