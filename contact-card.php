<?php 

$facPage = get_the_title();

$preview = wp_get_attachment_image_src( get_field( 'photo' ), 'medium' );
$image = $preview[0]; ?>

<aside class="contact-card">
        
    <a href="<?php the_permalink(); ?>" class="contact-image" <?php if ( get_field( 'photo' ) ) { ?> style="background: url(' <?php echo $image; ?>') no-repeat center center; background-size: cover;" <?php } else { ?> style="background: #fff url('<?php bloginfo( 'template_url' ); ?>/images/sprites/main-med-sprite.png') no-repeat 24px -413px;" }  <?php } ?> > </a>

    <ul class="contact-info">

        <li><span>Department:</span> <?php the_field( 'department' ); ?></li>
        <li><span>Title:</span> <?php the_field( 'position' ); ?></li>
        <?php if ( get_field( 'secondary_position' ) ) { ?> <li><span>Other Title(s):</span> <?php the_field( 'secondary_position' ); ?></li> <?php } ?>

        <?php if ( !get_field('division_tagline') ) { ?>

        <li><span>Office:</span> <?php the_field( 'office_address'); ?></li>
        <li><span>Phone:</span> <?php the_field( 'phone' ); ?></li>
        <?php if ( get_field( 'fax' ) ) { ?> <li><span>Fax:</span> <?php the_field( 'fax' ); ?></li> <?php } ?>
        <li><span>Email:</span> <a href="mailto:<?php the_field( 'email' ); ?>"><?php the_field( 'email' ); ?></a></li>

        <?php } else { ?>

        <h4><?php the_field( 'division_tagline' ); ?></h4>


        <?php } ?>


        <a href="<?php the_permalink(); ?>" class="profileLink button" alt="View the profile for <?php the_title(); ?>" title="View the profile for <?php the_title(); ?>">View Full Profile</a>

        <?php if ( get_field( 'enable_faculty_website' ) == 1 ) {
                echo '<a href="http://www.med.ucf.edu/'.get_field( 'website_nid' ).'" class="profileLink button" alt="View the website for '.get_the_title().'" title="View the website for '.get_the_title().'" target="_blank">View Faculty Website</a>';
            } ?>

    </ul>

</aside>