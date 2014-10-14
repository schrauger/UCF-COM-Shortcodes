<?php
if ( ( NULL != the_field( 'left_item_title' ) ) || ( NULL != the_field( 'center_item_title' ) ) || ( NULL != the_field( 'right_item_title' ) ) ) {
	$preview_l = wp_get_attachment_image_src( get_field( 'left_item_image' ), 'medium' );
	$preview_c = wp_get_attachment_image_src( get_field( 'center_item_image' ), 'medium' );
	$preview_r = wp_get_attachment_image_src( get_field( 'right_item_image' ), 'medium' );

	$image_l = $preview_l[ 0 ];
	$image_c = $preview_c[ 0 ];
	$image_r = $preview_r[ 0 ];

	global $placeholder;?>

	<div class="triple-box-container" >

		<div class="triple-box white-box" >
			<a href="<?php the_field( 'left_item_link' ); ?>"
			   style="background: transparent url( '<?php $il = ( $image_l == null ) ? $placeholder : $image_l;
			   echo $il; ?>' ) no-repeat center center; background-size: cover;" >
				<h2 ><?php the_field( 'left_item_title' ); ?></h2 ></a >
		</div >

		<div class="triple-box white-box" >
			<a href="<?php the_field( 'center_item_link' ); ?>"
			   style="background: transparent url( '<?php $ic = ( $image_c == null ) ? $placeholder : $image_c;
			   echo $ic; ?>' ) no-repeat center center; background-size: cover;" >
				<h2 ><?php the_field( 'center_item_title' ); ?></h2 ></a >
		</div >

		<div class="triple-box white-box" >
			<a href="<?php the_field( 'right_item_link' ); ?>"
			   style="background: transparent url( '<?php $ir = ( $image_r == null ) ? $placeholder : $image_r;
			   echo $ir; ?>' ) no-repeat center center; background-size: cover;" >
				<h2 ><?php the_field( 'right_item_title' ); ?></h2 ></a >
		</div >

	</div >

<?php

}
?>