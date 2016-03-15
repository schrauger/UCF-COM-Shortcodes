<?php 

$facPage = get_the_title();

$preview = wp_get_attachment_image_src( get_field( 'photo' ), 'medium' );
$image = $preview[0];

?>

<aside class="contact-card">
        
    <a href="<?php the_permalink(); ?>" class="contact-image" <?php if ( get_field( 'photo' ) ) { ?> style="background: url(' <?php echo $image; ?>') no-repeat center center; background-size: cover;" <?php } else { ?> style="background: #fff url('<?php bloginfo( 'template_url' ); ?>/images/sprites/main-med-sprite.png') no-repeat 24px -413px;" }  <?php } ?> > </a>

    <ul class="contact-info">
        <li><span>Medical School:</span> <?php the_field( 'res_medical_school' ); ?></li>
        <li><span>Resident Year:</span> <?php the_field( 'res_resident_year' ); ?></li>
        <li><span>Career Interest:</span> <?php the_field( 'res_career_interest' ); ?></li>
        <li><span>Fun Fact:</span> <?php echo the_field( 'res_fun_fact' ); ?></li>
    </ul>

</aside>