<?php
 
// Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
die ('Please do not load this page directly. Thanks!');
 
if ( post_password_required() ) { ?>
<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.','mythemeshop'); ?></p>
<?php
return;
}
?>
<!-- You can start editing here. -->
<?php if ( have_comments() ) : ?>
	<div id="comments">
		<h4 class="total-comments"><?php comments_number(__('No Responses','mythemeshop'), __('One Response','mythemeshop'),  __('% Comments','mythemeshop') );?> - <span><?php _e('Add Comment','mythemeshop'); ?></span></h4>
		<ol class="commentlist">
			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { // are there comments to navigate through ?>
				<div class="navigation">
					<div class="alignleft"><?php previous_comments_link() ?></div>
					<div class="alignright"><?php next_comments_link() ?></div>
				</div>
			<?php } ?>
			<?php wp_list_comments('type=comment&callback=mts_comments'); ?>
			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { // are there comments to navigate through ?>
				<div class="navigation">
					<div class="alignleft"><?php previous_comments_link() ?></div>
					<div class="alignright"><?php next_comments_link() ?></div>
				</div>
			<?php }	 ?>
		</ol>
	</div>
<?php endif; ?>

<?php if ( comments_open() ) : ?>
	<div id="commentsAdd">
	<h4 class=""><?php _e('Reply','mythemeshop'); ?></h4> 
		<div id="respond" class="box m-t-6">
		<div id="respond" class="comment-respond">
			<?php global $aria_req; $comments_args = array(
					'title_reply'=>'',
					'comment_notes_before' => '',
					'comment_notes_after' => '',
					'label_submit' => __('Submit','mythemeshop'),
					'comment_field' => '<p class="comment-form-comment"><label for="comment" class="comment-label">'.__('Comment','mythemeshop').'</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder=""></textarea></p>',
					'fields' => apply_filters( 'comment_form_default_fields',
						array(
							'author' => '<p class="comment-form-author"><label for="author" class="comment-label">'.__('Name','mythemeshop').'</label>'
							.( $req ? '' : '' ).'<input id="author" name="author" type="text" placeholder="" value="'.esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>',
							'email' => '<p class="comment-form-email"><label for="email" class="comment-label">'.__('Email','mythemeshop').'</label>'
							.($req ? '' : '' ) . '<input id="email" name="email" type="text" placeholder="" value="' . esc_attr(  $commenter['comment_author_email'] ).'" size="30"'.$aria_req.' /></p>',
							'url' => '<p class="comment-form-url"><label for="url" class="comment-label">'.__('Website','mythemeshop').'</label><input id="url" name="url" type="text" placeholder="" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>'
						) 
					)
				); 
			comment_form($comments_args); ?>
		</div>
	</div>
<?php endif; // if you delete this the sky will fall on your head ?>
