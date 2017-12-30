<?php get_header(); ?>
<div id="page" class="<?php mts_single_page_class(); ?>">
	<?php dynamic_sidebar('Header Ad'); ?>
	<article class="<?php mts_article_class(); ?>">
		<div id="content_box" >
		<div class="single_post">
			<header>
				<div class="title">
					<h1><?php _e('Error 404 Not Found', 'mythemeshop'); ?></h1>
				</div>
			</header>
			<div class="post-content">
				<p><?php _e('Oops! We couldn\'t find this Page.', 'mythemeshop'); ?></p>
				<p><?php _e('Please check your URL or use the search form below.', 'mythemeshop'); ?></p>
				<?php get_search_form();?>
			</div><!--.post-content--><!--#error404 .post-->
			</div>
		</div><!--#content-->
	</article>
	<?php get_sidebar(); ?>
<?php get_footer(); ?>
