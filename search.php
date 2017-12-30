<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<?php get_header(); ?>
<?php $layout = isset( $_COOKIE['mts_posts_layout'] ) ? $_COOKIE['mts_posts_layout'] : 'post-type-1'; ?>
<div id="page">
    <?php dynamic_sidebar('Header Ad'); ?>
	<div class="article">
		<div id="content_box">
            <div class="featured-categories">
                <h2 class="postsby">
				    <span><?php _e("Search Results for:", "mythemeshop"); ?></span> <?php the_search_query(); ?>
                </h2>
            </div>
            <div class="featured-view">
                <ul class="links">
                    <li <?php if( 'post-type-3' === $layout ) echo 'class="active"'; ?>><a href="#post-type-3"><i class="fa fa-list"></i></a></li>
                    <li <?php if( 'post-type-2' === $layout ) echo 'class="active"'; ?>><a href="#post-type-2"><i class="fa fa-th-list"></i></a></li>
                    <li <?php if( 'post-type-1' === $layout ) echo 'class="active"'; ?>><a href="#post-type-1"><i class="fa fa-th-large"></i></a></li>
                </ul>
                <div class="featured-content">
                    <div class="featured-view-posts <?php echo $layout; ?>">
                        <?php $j = 0; if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                            <article class="latestPost excerpt <?php echo (++$j % 3 == 0) ? 'lasts' : ''; ?>">
                                <a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="nofollow" class="post-image post-image-left">
                                    <div class="featured-thumbnail">
                                        <?php the_post_thumbnail('featured1',array('title' => '')); ?>
                                        <?php if (function_exists('wp_review_show_total')) wp_review_show_total(true, 'latestPost-review-wrapper'); ?>
                                    </div>
                                </a>
                                <header>
                                    <h2 class="title front-view-title"><a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"><?php the_title(); ?></a></h2>
                                    <?php mts_the_postinfo(); ?>
                                </header>
                                <div class="front-view-content">
                                    <?php echo mts_excerpt(18); ?>   
                                </div>
                            </article>
                        <?php endwhile; else: ?>
                            <div class="no-results">
                                <h2><?php _e('We apologize for any inconvenience, please hit back on your browser or use the search form below.', 'mythemeshop'); ?></h2>
                                <?php get_search_form(); ?>
                            </div><!--noResults-->
                        <?php endif; ?>
                        <?php if ( $j !== 0 ) { // No pagination if there is no posts ?>
                            <?php mts_pagination(); ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php get_sidebar(); ?>
<?php get_footer(); ?>
