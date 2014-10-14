<?php

global $placeholder;

for ( $i = 1; $i <= 8; $i += 2 ) {
	if ( ( get_field( 'eight_image_box_' . $i . '_title' ) ) || ( get_field( 'eight_image_box_' . ( $i + 1 ) . '_title' ) ) ) {
		$image_left  = wp_get_attachment_image_src( get_field( 'eight_image_box_' . $i . '_image' ), 'large' );
		$image_left  = $image_left[ 0 ];
		$image_right = wp_get_attachment_image_src( get_field( 'eight_image_box_' . ( $i + 1 ) . '_image' ), 'large' );
		$image_right = $image_right[ 0 ];
		?>
		<div class="double-box-container" >
			<?php
			// Left Image
			if ( get_field( 'eight_image_box_' . $i . '_title' ) ) {
				?>
				<div class="half double-box grey-box" >
					<a href="<?php the_field( 'eight_image_box_' . $i . '_url' ); ?>"
					   style="background: transparent url( '<?php echo ( $image_left == null ) ? $placeholder : $image_left; ?>' ) no-repeat center center; background-size: cover;" >
						<h2 ><?php the_field( 'eight_image_box_' . $i . '_title' ); ?></h2 >
					</a >
				</div >
			<?php
			} else {
				?>
				<div class="half double-box no-box" ></div >
			<?php
			}

			// Right Image
			if ( get_field( 'eight_image_box_' . ( $i + 1 ) . '_title' ) ) {
				?>
				<div class="half double-box grey-box" >
					<a href="<?php the_field( 'eight_image_box_' . ( $i + 1 ) . '_url' ); ?>"
					   style="background: transparent url( '<?php echo ( $image_right == null ) ? $placeholder : $image_right; ?>' ) no-repeat center center; background-size: cover;" >
						<h2 ><?php the_field( 'eight_image_box_' . ( $i + 1 ) . '_title' ); ?></h2 >
					</a >
				</div >
			<?php
			} else {
				?>
				<div class="half double-box no-box" ></div >
			<?php
			}
			?>
		</div >
	<?php
	}
}

?>