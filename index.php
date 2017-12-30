<?php $mts_options = get_option(MTS_THEME_NAME); ?>

<?php get_header(); ?>

<?php $layout = isset( $_COOKIE['mts_posts_layout'] ) ? $_COOKIE['mts_posts_layout'] : 'post-type-1'; ?>

<?php if ( is_home() && !is_paged() && $mts_options['mts_featured_top'] == '1' ) { ?>

    <div class="top-posts">

        <div class="container">

            <div class="top-posts-inner">

                <?php if ( empty( $mts_options['mts_featured_top_cat'] ) || !is_array( $mts_options['mts_featured_top_cat'] ) ) {

                    $mts_options['mts_featured_top_cat'] = array('0');

                }



                $slider_cat = implode( ",", $mts_options['mts_featured_top_cat'] );

                $slider_query = new WP_Query('cat='.$slider_cat.'&posts_per_page=4');

                while ( $slider_query->have_posts() ) : $slider_query->the_post(); ?>

                    <article class="latestPost excerpt">

                        <a href="<?php echo esc_url( get_the_permalink() ); ?>" title="" rel="nofollow" class="post-image post-image-left">

                            <div class="featured-thumbnail">

                                <?php the_post_thumbnail('featured',array('title' => '')); ?>

                                <header>
                                    <h2 class="title front-view-title"><?php the_title(); ?></h2>
                                </header>

                            </div>

                        </a>

                    </article>

                <?php endwhile; wp_reset_postdata(); ?>

            </div>

        </div>

    </div>

<?php } ?>

<div id="page">

    <?php dynamic_sidebar('Header Ad'); ?>

    <div class="article">

		<div id="content_box">

            <?php if ( !is_paged() ) {

                $featured_categories = array();

                if ( !empty( $mts_options['mts_featured_categories'] ) ) {

                    foreach ( $mts_options['mts_featured_categories'] as $section ) {

                        $category_id = $section['mts_featured_category'];

                        $featured_categories[] = $category_id;

                        $posts_num = $section['mts_featured_category_postsnum'];

                        if ( 'latest' == $category_id ) { ?>

                            <div class="featured-categories"><h2><?php _e('Latest','mythemeshop'); ?></h2></div>

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

                                        <?php endwhile; endif; ?>

                                        <?php if ( $j !== 0 ) { // No pagination if there is no posts ?>

                                            <?php mts_pagination(); ?>

                                        <?php } ?>

                                    </div>

                                </div>

                            </div>

                        <?php } else { // if $category_id != 'latest':

                            $j = 0;

                            $cat_query = new WP_Query('cat='.$category_id.'&posts_per_page='.$posts_num); ?>

                            <div class="featured-categories"><h2><a href="<?php echo esc_url( get_category_link( $category_id ) ); ?>" title="<?php echo esc_attr( get_cat_name( $category_id ) ); ?>"><?php echo esc_html( get_cat_name( $category_id ) ); ?></a></h2></div>

                            <div class="featured-view">

                                <ul class="links">

                                    <li <?php if( 'post-type-3' === $layout ) echo 'class="active"'; ?>><a href="post-type-3"><i class="fa fa-list"></i></a></li>

                                    <li <?php if( 'post-type-2' === $layout ) echo 'class="active"'; ?>><a href="post-type-2"><i class="fa fa-th-list"></i></a></li>

                                    <li <?php if( 'post-type-1' === $layout ) echo 'class="active"'; ?>><a href="post-type-1"><i class="fa fa-th-large"></i></a></li>

                                </ul>

                                <div class="featured-content">

                                    <div class="featured-view-posts <?php echo $layout; ?>">

                                        <?php if ( $cat_query->have_posts() ) : while ( $cat_query->have_posts() ) : $cat_query->the_post(); ?>

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

                                        <?php endwhile; endif; wp_reset_postdata(); ?>

                                    </div>

                                </div>

                            </div>

                        <?php }

                    }

                }

            } else { //Paged ?>

                <div class="featured-categories"><h2><?php _e('Latest','mythemeshop'); ?></h2></div>

                <div class="featured-view">

                    <ul class="links">

                        <li <?php if( 'post-type-3' === $layout ) echo 'class="active"'; ?>><a href="post-type-3"><i class="fa fa-list"></i></a></li>

                        <li <?php if( 'post-type-2' === $layout ) echo 'class="active"'; ?>><a href="post-type-2"><i class="fa fa-th-list"></i></a></li>

                        <li <?php if( 'post-type-1' === $layout ) echo 'class="active"'; ?>><a href="post-type-1"><i class="fa fa-th-large"></i></a></li>

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

                            <?php endwhile; endif; ?>

                            <?php if ( $j !== 0 ) { // No pagination if there is no posts ?>

                                <?php mts_pagination(); ?>

                            <?php } ?>

                        </div>

                    </div>

                </div>

            <?php } ?>

        </div>

    </div>

    <?php get_sidebar(); ?>

    <?php if ( is_home() && $mts_options['mts_featured_slider'] == '1' ) { ?>

        <div class="bottom-slider-container clearfix loading">

            <div class="container">

                <div id="slider" class="bottom-slider">

                    <?php if ( empty( $mts_options['mts_custom_slider'] ) ) { ?>

                        <?php if ( empty( $mts_options['mts_featured_slider_cat'] ) || !is_array( $mts_options['mts_featured_slider_cat'] ) ) {

                            $mts_options['mts_featured_slider_cat'] = array('0');

                        }

                        $slider_cat = implode( ",", $mts_options['mts_featured_slider_cat'] );

                        $slider_query = new WP_Query('cat='.$slider_cat.'&posts_per_page='.$mts_options['mts_featured_slider_num']);

                        while ( $slider_query->have_posts() ) : $slider_query->the_post(); ?>

                            <div class="bottom-inner">

                                <a href="<?php echo esc_url( get_the_permalink() ); ?>">

                                    <?php the_post_thumbnail('slider',array('title' => '')); ?>

                                    <div class="slide-caption">

                                        <h2 class="slide-title"><?php the_title(); ?></h2>

                                    </div>

                                </a>

                            </div>

                        <?php endwhile; wp_reset_postdata();

                    } else {

                        foreach( $mts_options['mts_custom_slider'] as $slide ) : ?>

                            <div class="bottom-inner">

                                <a href="<?php echo esc_url( $slide['mts_custom_slider_link'] ); ?>">

                                    <?php echo wp_get_attachment_image( $slide['mts_custom_slider_image'], 'slider', false, array('title' => '') ); ?>

                                    <div class="slide-caption">

                                        <h2 class="slide-title"><?php echo esc_html( $slide['mts_custom_slider_title'] ); ?></h2>

                                    </div>

                                </a>

                            </div>

                        <?php endforeach;

                    } ?>

                </div><!-- .primary-slider -->

            </div><!-- .primary-slider-container -->

        </div>

    <?php }

    get_footer(); ?>
