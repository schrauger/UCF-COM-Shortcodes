<?php 

$facPage = get_the_title();

$preview = wp_get_attachment_image_src( get_field( 'photo' ), 'medium' );
$image = $preview[0];

?>

<aside class="contact-card">
        
    <a href="<?php the_permalink(); ?>" class="contact-image" <?php if ( get_field( 'photo' ) ) { ?> style="background: url(' <?php echo $image; ?>') no-repeat center center; background-size: cover;" <?php } else { ?> style="background: #fff url('<?php bloginfo( 'template_url' ); ?>/images/sprites/main-med-sprite.png') no-repeat 24px -413px;" }  <?php } ?> > </a>

    <ul class="contact-info">
        <?php
        if ($res_clinical_site = get_field( 'res_clinical_site' )){
            echo "<li><span>Clinical Site:</span>$res_clinical_site</li>";
        }
        if ($res_medical_school = get_field( 'res_medical_school' )){
            echo "<li><span>Medical School:</span>$res_medical_school</li>";
        }
        if ($res_residency = get_field( 'res_residency' )){
            echo "<li><span>Residency:</span>$res_residency</li>";
        }
        if ($res_resident_year = get_field( 'res_resident_year' )){
            echo "<li><span>Resident Year:</span>$res_resident_year</li>";
        }
        if ($res_career_interest = get_field( 'res_career_interest' )){
            echo "<li><span>Career Interest:</span>$res_career_interest</li>";
        }
        if ($res_fun_fact = get_field( 'res_fun_fact' )){
            echo "<li><span>Fun Fact:</span>$res_fun_fact</li>";
        }
        ?>
    </ul>

</aside>