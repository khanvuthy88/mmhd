<?php $mts_options = get_option(MTS_THEME_NAME);
// default = 3
$first_footer_num  = empty($mts_options['mts_first_footer_num']) ? 3 : $mts_options['mts_first_footer_num'];
?>
	</div><!--#page-->
       <footer id="site-footer" role="contentinfo" itemscope itemtype="http://schema.org/WPFooter">
            <div class="container">
                <?php if ($mts_options['mts_first_footer']) : ?>
                    <div class="footer-widgets first-footer-widgets widgets-num-<?php echo $first_footer_num; ?>">
                        <?php for ( $i = 1; $i <= $first_footer_num; $i++ ) {
                            $sidebar = ( $i == 1 ) ? 'footer-first' : 'footer-first-'.$i;
                            $class = ( $i == $first_footer_num ) ? 'f-widget last f-widget-'.$i : 'f-widget f-widget-'.$i; ?>
                            <?php if($i ==2) { ?> <div class="f-widget-bigger"> <?php } ?>
                                <div class="<?php echo $class;?>">
                                    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar( $sidebar ) ) : ?><?php endif; ?>
                                </div>
                                <?php if($i == $first_footer_num) { ?>
                                    <div class="copyrights">
                                        <?php mts_copyrights_credit(); ?>
                                    </div>
                                </div>
                            <?php }
                        } ?>
                    </div><!--.first-footer-widgets-->
                <?php endif; ?>
            </div>
        </footer><!--#site-footer-->
<?php mts_footer(); ?>
<?php wp_footer(); ?>
</body>
</html>