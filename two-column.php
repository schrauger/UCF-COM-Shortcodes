<?php
if ( ( null != get_field( 'left_column' ) ) || ( null != get_field( 'right_column' ) ) ) {
	?>
	<div class="half" >
		<?php the_field( 'left_column' ); ?>
	</div >

	<div class="half" >
		<?php the_field( 'right_column' ); ?>
	</div >
<?php
}
