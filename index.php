<?php get_header();?>

<main>
    <?php ki_breadcrumb();?>
    <section>
    <?php
	if ( have_posts() ) :
		while ( have_posts() ) : the_post(); ?>
            <h1><?php the_title();?></h1>
		    <?php the_content(); ?>
		<?php endwhile;
    endif;
		?>
    </section>
</main>

<?php get_footer();?>