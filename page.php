<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<?php get_header(); ?>
<div id="page" class="<?php mts_single_page_class(); ?>">
	<?php dynamic_sidebar('Header Ad'); ?>
	<article class="<?php mts_article_class(); ?>">
		<div id="content_box" >
			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
				<div id="post-<?php the_ID(); ?>" <?php post_class('g post'); ?>>
					<div class="single_post single_page">
						<header>
							<h1 class="title single-title entry-title"><?php the_title(); ?></h1>
						</header><!--.headline_area-->
						<?php $header_animation = mts_get_post_header_effect();
						$sidebar = mts_custom_sidebar();
						if ( 'parallax' === $header_animation ) {
							if (mts_get_thumbnail_url()) : ?>
						        <div id="parallax" <?php echo 'style="background-image: url('.mts_get_thumbnail_url().');"'; ?>></div>
						    <?php endif;
						} else if ( 'zoomout' === $header_animation ) {
							if (mts_get_thumbnail_url()) : ?>
						        <div id="zoom-out-effect"><div id="zoom-out-bg" <?php echo 'style="background-image: url('.mts_get_thumbnail_url().');"'; ?>></div></div>
						    <?php endif;
						} elseif ( $sidebar != 'mts_nosidebar' && !empty($mts_options['mts_single_featured_image']) && has_post_thumbnail()) { ?>
							<div class="single-featured-thumbnail">
              					<?php the_post_thumbnail('featuredfull',array('title' => '')); ?>
            				</div>
        				<?php } ?>
						<div class="post-single-content box mark-links entry-content">
							<div class="thecontent">
								<?php the_content(); ?>
							</div>
							<?php wp_link_pages(array('before' => '<div class="pagination">', 'after' => '</div>', 'link_before'  => '<span class="current"><span class="currenttext">', 'link_after' => '</span></span>', 'next_or_number' => 'next_and_number', 'nextpagelink' => '<i class="fa fa-angle-right"></i>', 'previouspagelink' => '<i class="fa fa-angle-left"></i>', 'pagelink' => '%','echo' => 1 )); ?>
						</div><!--.post-single-content-->
					</div><!--.single_post-->
				</div>
				<?php comments_template( '', true ); ?>
			<?php endwhile; ?>
		</div>
	</article>
	<?php get_sidebar(); ?>
<?php get_footer(); ?>
