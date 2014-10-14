<?php $previewh1 = wp_get_attachment_image_src( get_field( 'eight_image_box_1_image' ), 'large' );
$previewh2 = wp_get_attachment_image_src( get_field( 'eight_image_box_2_image' ), 'large' );
$previewh3 = wp_get_attachment_image_src( get_field( 'eight_image_box_3_image' ), 'large' );
$previewh4 = wp_get_attachment_image_src( get_field( 'eight_image_box_4_image' ), 'large' );
$previewh5 = wp_get_attachment_image_src( get_field( 'eight_image_box_5_image' ), 'large' );
$previewh6 = wp_get_attachment_image_src( get_field( 'eight_image_box_6_image' ), 'large' );
$previewh7 = wp_get_attachment_image_src( get_field( 'eight_image_box_7_image' ), 'large' );
$previewh8 = wp_get_attachment_image_src( get_field( 'eight_image_box_8_image' ), 'large' );

$imageh1 = $previewh1[0]; 
$imageh2 = $previewh2[0]; 
$imageh3 = $previewh3[0]; 
$imageh4 = $previewh4[0]; 
$imageh5 = $previewh5[0]; 
$imageh6 = $previewh6[0]; 
$imageh7 = $previewh7[0];
$imageh8 = $previewh8[0]; 

global $placeholder;?>


<div class="double-box-container">

    <div class="half double-box grey-box">
            <a href="<?php the_field( 'eight_image_box_1_url' ); ?>" style="background: transparent url( '<?php $i1 = ( $imageh1 == NULL ) ? $placeholder : $imageh1; echo $i1; ?>' ) no-repeat center center; background-size: cover;"><h2><?php the_field( 'eight_image_box_1_title' ); ?></h2></a>
    </div>

    <div class="half double-box grey-box">
            <a href="<?php the_field( 'eight_image_box_2_url' ); ?>" style="background: transparent url( '<?php $i2 = ( $imageh2 == NULL ) ? $placeholder : $imageh2; echo $i2; ?>' ) no-repeat center center; background-size: cover;"><h2><?php the_field( 'eight_image_box_2_title' ); ?></h2></a>
    </div>

</div>

<?php  if ( get_field( 'eight_image_box_3_title' ) != NULL ) { ?>

<div class="double-box-container">

    <div class="half double-box grey-box">
            <a href="<?php the_field( 'eight_image_box_3_url' ); ?>" style="background: transparent url( '<?php $i3 = ( $imageh3 == NULL ) ? $placeholder : $imageh3; echo $i3; ?>' ) no-repeat center center; background-size: cover;"><h2><?php the_field( 'eight_image_box_3_title' ); ?></h2></a>
    </div>

    <div class="half double-box grey-box">
            <a href="<?php the_field( 'eight_image_box_4_url' ); ?>" style="background: transparent url( '<?php $i4 = ( $imageh4 == NULL ) ? $placeholder : $imageh4; echo $i4; ?>' ) no-repeat center center; background-size: cover;"><h2><?php the_field( 'eight_image_box_4_title' ); ?></h2></a>
    </div>

</div>

<?php } if ( get_field( 'eight_image_box_5_title' ) != NULL ) { ?>

<div class="double-box-container">

    <div class="half double-box grey-box">
            <a href="<?php the_field( 'eight_image_box_5_url' ); ?>" style="background: transparent url( '<?php $i5 = ( $imageh5 == NULL ) ? $placeholder : $imageh5; echo $i5; ?>' ) no-repeat center center; background-size: cover;"><h2><?php the_field( 'eight_image_box_5_title' ); ?></h2></a>
    </div>

    <?php if ( get_field( 'eight_image_box_6_title' ) != NULL ) { ?>

    <div class="half double-box grey-box">
            <a href="<?php the_field( 'eight_image_box_6_url' ); ?>" style="background: transparent url( '<?php $i6 = ( $imageh6 == NULL ) ? $placeholder : $imageh6; echo $i6; ?>' ) no-repeat center center; background-size: cover;"><h2><?php the_field( 'eight_image_box_6_title' ); ?></h2></a>
    </div>

    <?php } ?>

</div>

<?php } if ( get_field( 'eight_image_box_7_title' ) != NULL ) { ?>

<div class="double-box-container">

    <div class="half double-box grey-box">
            <a href="<?php the_field( 'eight_image_box_7_url' ); ?>" style="background: transparent url( '<?php $i7 = ( $imageh7 == NULL ) ? $placeholder : $imageh7; echo $i7; ?>' ) no-repeat center center; background-size: cover;"><h2><?php the_field( 'eight_image_box_7_title' ); ?></h2></a>
    </div>

    <?php if ( get_field( 'eight_image_box_8_title' ) != NULL ) { ?>

    <div class="half double-box grey-box">
            <a href="<?php the_field( 'eight_image_box_8_url' ); ?>" style="background: transparent url( '<?php $i8 = ( $imageh8 == NULL ) ? $placeholder : $imageh8; echo $i8; ?>' ) no-repeat center center; background-size: cover;"><h2><?php the_field( 'eight_image_box_8_title' ); ?></h2></a>
    </div>

    <?php } ?>

</div>

<?php } ?>