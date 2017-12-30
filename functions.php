<?php
/*-----------------------------------------------------------------------------------*/
/*  Do not remove these lines, sky will fall on your head.
/*-----------------------------------------------------------------------------------*/
define('MTS_THEME_NAME', 'wordx');
define( 'MTS_THEME_VERSION', '1.2.8' );
define('MTS_THEME_TEXTDOMAIN', 'mythemeshop');
require_once (dirname(__FILE__) . '/theme-options.php');
// require_once (get_template_directory().'/functions/googlephoto.php');

if (!isset($content_width)) $content_width = 830; //article content width without padding

/*-----------------------------------------------------------------------------------*/
/*  Load Options
/*-----------------------------------------------------------------------------------*/
$mts_options = get_option(MTS_THEME_NAME);

/*-----------------------------------------------------------------------------------*/
/*  Site Title
/*-----------------------------------------------------------------------------------*/
add_theme_support( 'title-tag' );
if ( ! function_exists( '_wp_render_title_tag' ) ) {
	function theme_slug_render_title() { ?>
        <title><?php wp_title( '|', true, 'right' ); ?></title>
    <?php }
	add_action( 'wp_head', 'theme_slug_render_title' );
}

/*-----------------------------------------------------------------------------------*/
/*  Load Translation Text Domain
/*-----------------------------------------------------------------------------------*/
load_theme_textdomain('mythemeshop', get_template_directory() . '/lang');

// Custom translations

if (!empty($mts_options['translate'])) {
    $mts_translations = get_option('mts_translations_' . MTS_THEME_NAME); //$mts_options['translations'];
    function mts_custom_translate($translated_text, $text, $domain) {
        if ($domain == 'mythemeshop' || $domain == 'nhp-opts') {
            global $mts_translations;
            if (!empty($mts_translations[$text])) {
                $translated_text = $mts_translations[$text];
                }
            }

        return $translated_text;
        }

    add_filter('gettext', 'mts_custom_translate', 20, 3);
    }

if (function_exists('add_theme_support')) add_theme_support('automatic-feed-links');

/*-----------------------------------------------------------------------------------*/
/*  Disable theme updates from WordPress.org theme repository
/*-----------------------------------------------------------------------------------*/

// Check if MTS Connect plugin already done this

if (!class_exists('mts_connection')) {

    // If wrong updates are already shown, delete transient so that we can run our workaround

    add_action('init', 'mts_hide_themes_plugins');
    function mts_hide_themes_plugins() {
        if (!is_admin()) return;
        if (false === get_site_transient('mts_wp_org_check_disabled')) { // run only once
            delete_site_transient('update_themes');
            delete_site_transient('update_plugins');
            add_action('current_screen', 'mts_remove_themes_plugins_from_update');
            }
        }

    // Hide mts themes/plugins

    function mts_remove_themes_plugins_from_update($screen) {
        $run_on_screens = array(
            'themes', 'themes-network', 'plugins', 'plugins-network', 'update-core', 'network-update-core'
        );
        if (in_array($screen->base, $run_on_screens)) {

            // Themes

            if ($themes_transient = get_site_transient('update_themes')) {
                if (property_exists($themes_transient, 'response') && is_array($themes_transient->response)) {
                    foreach($themes_transient->response as $key => $value)
                        {
                        $theme = wp_get_theme($value['theme']);
                        $theme_uri = $theme->get('ThemeURI');
                        if (0 !== strpos($theme_uri, 'mythemeshop.com'))
                            {
                            unset($themes_transient->response[$key]);
                            }
                        }
                    set_site_transient('update_themes', $themes_transient);
                    }
                }

            // Plugins

            if ($plugins_transient = get_site_transient('update_plugins')) {
                if (property_exists($plugins_transient, 'response') && is_array($plugins_transient->response)) {
                    foreach($plugins_transient->response as $key => $value)
                        {
                        $plugin = get_plugin_data(WP_PLUGIN_DIR . '/' . $key, false, false);
                        $plugin_uri = $plugin['PluginURI'];
                        if (0 !== strpos($plugin_uri, 'mythemeshop.com'))
                            {
                            unset($plugins_transient->response[$key]);
                            }
                        }

                    set_site_transient('update_plugins', $plugins_transient);
                    }
                }

            set_site_transient('mts_wp_org_check_disabled', time());
            }
        }

    add_action('load-themes.php', 'mts_clear_check_transient');
    add_action('load-plugins.php', 'mts_clear_check_transient');
    add_action('upgrader_process_complete', 'mts_clear_check_transient');
    function mts_clear_check_transient() {
        delete_site_transient('mts_wp_org_check_disabled');
    }
}

// Disable auto update

add_filter('auto_update_theme', '__return_false');

/*-----------------------------------------------------------------------------------*/
/*  Disable Google Typography plugin
/*-----------------------------------------------------------------------------------*/
add_action('admin_init', 'mts_deactivate_google_typography_plugin');

function mts_deactivate_google_typography_plugin() {
    if (in_array('google-typography/google-typography.php', apply_filters('active_plugins', get_option('active_plugins')))) {
        deactivate_plugins('google-typography/google-typography.php');
    }
}

/*------------------------------------------------------------------------------------------------------------*/
/*  Define MTS_ICONS constant containing all available icons for use in nav menus and icon select option
/*------------------------------------------------------------------------------------------------------------*/
function mts_get_icons() {
    $icons = array(
        __( 'Web Application Icons', 'mythemeshop' ) => array(
            'adjust', 'american-sign-language-interpreting', 'anchor', 'archive', 'area-chart', 'arrows', 'arrows-h', 'arrows-v', 'assistive-listening-systems', 'asterisk', 'at', 'audio-description', 'balance-scale', 'ban', 'bar-chart', 'barcode', 'bars', 'battery-empty', 'battery-full', 'battery-half', 'battery-quarter', 'battery-three-quarters', 'bed', 'beer', 'bell', 'bell-o', 'bell-slash', 'bell-slash-o', 'bicycle', 'binoculars', 'birthday-cake', 'blind', 'bluetooth', 'bluetooth-b', 'bolt', 'bomb', 'book', 'bookmark', 'bookmark-o', 'braille', 'briefcase', 'bug', 'building', 'building-o', 'bullhorn', 'bullseye', 'bus', 'calculator', 'calendar', 'calendar-check-o', 'calendar-minus-o', 'calendar-o', 'calendar-plus-o', 'calendar-times-o', 'camera', 'camera-retro', 'car', 'caret-square-o-down', 'caret-square-o-left', 'caret-square-o-right', 'caret-square-o-up', 'cart-arrow-down', 'cart-plus', 'cc', 'certificate', 'check', 'check-circle', 'check-circle-o', 'check-square', 'check-square-o', 'child', 'circle', 'circle-o', 'circle-o-notch', 'circle-thin', 'clock-o', 'clone', 'cloud', 'cloud-download', 'cloud-upload', 'code', 'code-fork', 'coffee', 'cog', 'cogs', 'comment', 'comment-o', 'commenting', 'commenting-o', 'comments', 'comments-o', 'compass', 'copyright', 'creative-commons', 'credit-card', 'credit-card-alt', 'crop', 'crosshairs', 'cube', 'cubes', 'cutlery', 'database', 'deaf', 'desktop', 'diamond', 'dot-circle-o', 'download', 'ellipsis-h', 'ellipsis-v', 'envelope', 'envelope-o', 'envelope-square', 'eraser', 'exchange', 'exclamation', 'exclamation-circle', 'exclamation-triangle', 'external-link', 'external-link-square', 'eye', 'eye-slash', 'eyedropper', 'fax', 'female', 'fighter-jet', 'file-archive-o', 'file-audio-o', 'file-code-o', 'file-excel-o', 'file-image-o', 'file-pdf-o', 'file-powerpoint-o', 'file-video-o', 'file-word-o', 'film', 'filter', 'fire', 'fire-extinguisher', 'flag', 'flag-checkered', 'flag-o', 'flask', 'folder', 'folder-o', 'folder-open', 'folder-open-o', 'frown-o', 'futbol-o', 'gamepad', 'gavel', 'gift', 'glass', 'globe', 'graduation-cap', 'hand-lizard-o', 'hand-paper-o', 'hand-peace-o', 'hand-pointer-o', 'hand-rock-o', 'hand-scissors-o', 'hand-spock-o', 'hashtag', 'hdd-o', 'headphones', 'heart', 'heart-o', 'heartbeat', 'history', 'home', 'hourglass', 'hourglass-end', 'hourglass-half', 'hourglass-o', 'hourglass-start', 'i-cursor', 'inbox', 'industry', 'info', 'info-circle', 'key', 'keyboard-o', 'language', 'laptop', 'leaf', 'lemon-o', 'level-down', 'level-up', 'life-ring', 'lightbulb-o', 'line-chart', 'location-arrow', 'lock', 'low-vision', 'magic', 'magnet', 'male', 'map', 'map-marker', 'map-o', 'map-pin', 'map-signs', 'meh-o', 'microphone', 'microphone-slash', 'minus', 'minus-circle', 'minus-square', 'minus-square-o', 'mobile', 'money', 'moon-o', 'motorcycle', 'mouse-pointer', 'music', 'newspaper-o', 'object-group', 'object-ungroup', 'paint-brush', 'paper-plane', 'paper-plane-o', 'paw', 'pencil', 'pencil-square', 'pencil-square-o', 'percent', 'phone', 'phone-square', 'picture-o', 'pie-chart', 'plane', 'plug', 'plus', 'plus-circle', 'plus-square', 'plus-square-o', 'power-off', 'print', 'puzzle-piece', 'qrcode', 'question', 'question-circle', 'question-circle-o', 'quote-left', 'quote-right', 'random', 'recycle', 'refresh', 'registered', 'reply', 'reply-all', 'retweet', 'road', 'rocket', 'rss', 'rss-square', 'search', 'search-minus', 'search-plus', 'server', 'share', 'share-alt', 'share-alt-square', 'share-square', 'share-square-o', 'shield', 'ship', 'shopping-bag', 'shopping-basket', 'shopping-cart', 'sign-in', 'sign-language', 'sign-out', 'signal', 'sitemap', 'sliders', 'smile-o', 'sort', 'sort-alpha-asc', 'sort-alpha-desc', 'sort-amount-asc', 'sort-amount-desc', 'sort-asc', 'sort-desc', 'sort-numeric-asc', 'sort-numeric-desc', 'space-shuttle', 'spinner', 'spoon', 'square', 'square-o', 'star', 'star-half', 'star-half-o', 'star-o', 'sticky-note', 'sticky-note-o', 'street-view', 'suitcase', 'sun-o', 'tablet', 'tachometer', 'tag', 'tags', 'tasks', 'taxi', 'television', 'terminal', 'thumb-tack', 'thumbs-down', 'thumbs-o-down', 'thumbs-o-up', 'thumbs-up', 'ticket', 'times', 'times-circle', 'times-circle-o', 'tint', 'toggle-off', 'toggle-on', 'trademark', 'trash', 'trash-o', 'tree', 'trophy', 'truck', 'tty', 'umbrella', 'universal-access', 'university', 'unlock', 'unlock-alt', 'upload', 'user', 'user-plus', 'user-secret', 'user-times', 'users', 'video-camera', 'volume-control-phone', 'volume-down', 'volume-off', 'volume-up', 'wheelchair', 'wheelchair-alt', 'wifi', 'wrench'
        ),
        __( 'Accessibility Icons', 'mythemeshop' ) => array(
            'american-sign-language-interpreting', 'assistive-listening-systems', 'audio-description', 'blind', 'braille', 'cc', 'deaf', 'low-vision', 'question-circle-o', 'sign-language', 'tty', 'universal-access', 'volume-control-phone', 'wheelchair', 'wheelchair-alt'
        ),
        __( 'Hand Icons', 'mythemeshop' ) => array(
            'hand-lizard-o', 'hand-o-down', 'hand-o-left', 'hand-o-right', 'hand-o-up', 'hand-paper-o', 'hand-peace-o', 'hand-pointer-o', 'hand-rock-o', 'hand-scissors-o', 'hand-spock-o', 'thumbs-down', 'thumbs-o-down', 'thumbs-o-up', 'thumbs-up'
        ),
        __( 'Transportation Icons', 'mythemeshop' ) => array(
            'ambulance', 'bicycle', 'bus', 'car', 'fighter-jet', 'motorcycle', 'plane', 'rocket', 'ship', 'space-shuttle', 'subway', 'taxi', 'train', 'truck', 'wheelchair'
        ),
        __( 'Gender Icons', 'mythemeshop' ) => array(
            'genderless', 'mars', 'mars-double', 'mars-stroke', 'mars-stroke-h', 'mars-stroke-v', 'mercury', 'neuter', 'transgender', 'transgender-alt', 'venus', 'venus-double', 'venus-mars'
        ),
        __( 'File Type Icons', 'mythemeshop' ) => array(
            'file', 'file-archive-o', 'file-audio-o', 'file-code-o', 'file-excel-o', 'file-image-o', 'file-o', 'file-pdf-o', 'file-powerpoint-o', 'file-text', 'file-text-o', 'file-video-o', 'file-word-o'
        ),
        __( 'Spinner Icons', 'mythemeshop' ) => array(
            'circle-o-notch', 'cog', 'refresh', 'spinner'
        ),
        __( 'Form Control Icons', 'mythemeshop' ) => array(
            'check-square', 'check-square-o', 'circle', 'circle-o', 'dot-circle-o', 'minus-square', 'minus-square-o', 'plus-square', 'plus-square-o', 'square', 'square-o'
        ),
        __( 'Payment Icons', 'mythemeshop' ) => array(
            'cc-amex', 'cc-diners-club', 'cc-discover', 'cc-jcb', 'cc-mastercard', 'cc-paypal', 'cc-stripe', 'cc-visa', 'credit-card', 'credit-card-alt', 'google-wallet', 'paypal'
        ),
        __( 'Chart Icons', 'mythemeshop' ) => array(
            'area-chart', 'bar-chart', 'line-chart', 'pie-chart'
        ),
        __( 'Currency Icons', 'mythemeshop' ) => array(
            'btc', 'eur', 'gbp', 'gg', 'gg-circle', 'ils', 'inr', 'jpy', 'krw', 'money', 'rub', 'try', 'usd'
        ),
        __( 'Text Editor Icons', 'mythemeshop' ) => array(
            'align-center', 'align-justify', 'align-left', 'align-right', 'bold', 'chain-broken', 'clipboard', 'columns', 'eraser', 'file', 'file-o', 'file-text', 'file-text-o', 'files-o', 'floppy-o', 'font', 'header', 'indent', 'italic', 'link', 'list', 'list-alt', 'list-ol', 'list-ul', 'outdent', 'paperclip', 'paragraph', 'repeat', 'scissors', 'strikethrough', 'subscript', 'superscript', 'table', 'text-height', 'text-width', 'th', 'th-large', 'th-list', 'underline', 'undo'
        ),
        __( 'Directional Icons', 'mythemeshop' ) => array(
            'angle-double-down', 'angle-double-left', 'angle-double-right', 'angle-double-up', 'angle-down', 'angle-left', 'angle-right', 'angle-up', 'arrow-circle-down', 'arrow-circle-left', 'arrow-circle-o-down', 'arrow-circle-o-left', 'arrow-circle-o-right', 'arrow-circle-o-up', 'arrow-circle-right', 'arrow-circle-up', 'arrow-down', 'arrow-left', 'arrow-right', 'arrow-up', 'arrows', 'arrows-alt', 'arrows-h', 'arrows-v', 'caret-down', 'caret-left', 'caret-right', 'caret-square-o-down', 'caret-square-o-left', 'caret-square-o-right', 'caret-square-o-up', 'caret-up', 'chevron-circle-down', 'chevron-circle-left', 'chevron-circle-right', 'chevron-circle-up', 'chevron-down', 'chevron-left', 'chevron-right', 'chevron-up', 'exchange', 'hand-o-down', 'hand-o-left', 'hand-o-right', 'hand-o-up', 'long-arrow-down', 'long-arrow-left', 'long-arrow-right', 'long-arrow-up'
        ),
        __( 'Video Player Icons', 'mythemeshop' ) => array(
            'arrows-alt', 'backward', 'compress', 'eject', 'expand', 'fast-backward', 'fast-forward', 'forward', 'pause', 'pause-circle', 'pause-circle-o', 'play', 'play-circle', 'play-circle-o', 'random', 'step-backward', 'step-forward', 'stop', 'stop-circle', 'stop-circle-o', 'youtube-play'
        ),
        __( 'Brand Icons', 'mythemeshop' ) => array(
            '500px', 'adn', 'amazon', 'android', 'angellist', 'apple', 'behance', 'behance-square', 'bitbucket', 'bitbucket-square', 'black-tie', 'bluetooth', 'bluetooth-b', 'btc', 'buysellads', 'cc-amex', 'cc-diners-club', 'cc-discover', 'cc-jcb', 'cc-mastercard', 'cc-paypal', 'cc-stripe', 'cc-visa', 'chrome', 'codepen', 'codiepie', 'connectdevelop', 'contao', 'css3', 'dashcube', 'delicious', 'deviantart', 'digg', 'dribbble', 'dropbox', 'drupal', 'edge', 'empire', 'envira', 'expeditedssl', 'facebook', 'facebook-official', 'facebook-square', 'firefox', 'first-order', 'flickr', 'font-awesome', 'fonticons', 'fort-awesome', 'forumbee', 'foursquare', 'get-pocket', 'gg', 'gg-circle', 'git', 'git-square', 'github', 'github-alt', 'github-square', 'gitlab', 'glide', 'glide-g', 'google', 'google-plus', 'google-plus-official', 'google-plus-square', 'google-wallet', 'gratipay', 'hacker-news', 'houzz', 'html5', 'instagram', 'internet-explorer', 'ioxhost', 'joomla', 'jsfiddle', 'lastfm', 'lastfm-square', 'leanpub', 'linkedin', 'linkedin-square', 'linux', 'maxcdn', 'meanpath', 'medium', 'mixcloud', 'modx', 'odnoklassniki', 'odnoklassniki-square', 'opencart', 'openid', 'opera', 'optin-monster', 'pagelines', 'paypal', 'pied-piper', 'pied-piper-alt', 'pied-piper-pp', 'pinterest', 'pinterest-p', 'pinterest-square', 'product-hunt', 'qq', 'rebel', 'reddit', 'reddit-alien', 'reddit-square', 'renren', 'safari', 'scribd', 'sellsy', 'share-alt', 'share-alt-square', 'shirtsinbulk', 'simplybuilt', 'skyatlas', 'skype', 'slack', 'slideshare', 'snapchat', 'snapchat-ghost', 'snapchat-square', 'soundcloud', 'spotify', 'stack-exchange', 'stack-overflow', 'steam', 'steam-square', 'stumbleupon', 'stumbleupon-circle', 'tencent-weibo', 'themeisle', 'trello', 'tripadvisor', 'tumblr', 'tumblr-square', 'twitch', 'twitter', 'twitter-square', 'usb', 'viacoin', 'viadeo', 'viadeo-square', 'vimeo', 'vimeo-square', 'vine', 'vk', 'weibo', 'weixin', 'whatsapp', 'wikipedia-w', 'windows', 'wordpress', 'wpbeginner', 'wpforms', 'xing', 'xing-square', 'y-combinator', 'yahoo', 'yelp', 'yoast', 'youtube', 'youtube-play', 'youtube-square'
        ),
        __( 'Medical Icons', 'mythemeshop' ) => array(
            'ambulance', 'h-square', 'heart', 'heart-o', 'heartbeat', 'hospital-o', 'medkit', 'plus-square', 'stethoscope', 'user-md', 'wheelchair'
        )
    );
    return $icons;
}

/*-----------------------------------------------------------------------------------*/
/*  Post Thumbnail Support
/*-----------------------------------------------------------------------------------*/

if (function_exists('add_theme_support')) {
    add_theme_support('post-thumbnails');
    add_image_size('featured', 292, 400, true); //featured area
    add_image_size('featured1', 400, 250, true); //latest post thumb
    add_image_size('featured2', 240, 150, true); //latest post thumb medium
    add_image_size('featured3', 160, 100, true); //latest post thumb small
    add_image_size('featuredfull', 830, 450, true); //single post thumb
    add_image_size('related', 160, 100, true); //related
    add_image_size('widgetthumb', 100, 62, true); //widget
    add_image_size('widgetfull', 300, 200, true); //widget full
    add_image_size('slider', 234, 146, true); //footer carousel

    add_action( 'init', 'newspaper_wp_review_thumb_size', 11 );
    function newspaper_wp_review_thumb_size() {
        add_image_size( 'wp_review_large', 300, 200, true ); 
        add_image_size( 'wp_review_small', 100, 62, true );
    }
}

function mts_get_thumbnail_url( $size = 'full' ) {
    global $post;
    if (has_post_thumbnail( $post->ID ) ) {
        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $size );
        return $image[0];
    }
    
    // use first attached image
    $images = get_children( 'post_type=attachment&post_mime_type=image&post_parent=' . $post->ID );
    if (!empty($images)) {
        $image = reset($images);
        $image_data = wp_get_attachment_image_src( $image->ID, $size );
        return $image_data[0];
    }
        
    // use no preview fallback
    if ( file_exists( get_template_directory().'/images/nothumb-'.$size.'.png' ) ) {
        $placeholder = get_template_directory_uri().'/images/nothumb-'.$size.'.png';
        $mts_options = get_option( MTS_THEME_NAME );
        if ( ! empty( $mts_options['mts_lazy_load'] ) && ! empty( $mts_options['mts_lazy_load_thumbs'] ) ) {
            $placeholder_src = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
            $layzr_attr = ' data-layzr="'.esc_attr( $placeholder ).'"';
        } else {
            $placeholder_src = $placeholder;
            $layzr_attr = '';
        }
        
        $placeholder_classs = 'attachment-'.$size.' wp-post-image';
        return '<img src="'.esc_url( $placeholder_src ).'" class="'.esc_attr( $placeholder_classs ).'" alt="'.esc_attr( get_the_title() ).'"'.$layzr_attr.'>';
    }
    return '';
}

/**
 * Add data-layzr attribute to featured image ( for lazy load )
 *
 * @param array $attr
 * @param WP_Post $attachment
 * @param string|array $size
 *
 * @return array
 */
function mts_image_lazy_load_attr( $attr, $attachment, $size ) {
    if ( is_admin() || is_feed() ) return $attr;
    $mts_options = get_option( MTS_THEME_NAME );
    if ( ! empty( $mts_options['mts_lazy_load'] ) && ! empty( $mts_options['mts_lazy_load_thumbs'] ) ) {
        $attr['data-layzr'] = $attr['src'];
        $attr['src'] = '';
        if ( isset( $attr['srcset'] ) ) {
            $attr['data-layzr-srcset'] = $attr['srcset'];
            $attr['srcset'] = '';
        }
    }
    return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'mts_image_lazy_load_attr', 10, 3 );
/**
 * Add data-layzr attribute to post content images ( for lazy load )
 *
 * @param string $content
 *
 * @return string
 */
function mts_content_image_lazy_load_attr( $content ) {
    $mts_options = get_option( MTS_THEME_NAME );
    if ( ! empty( $mts_options['mts_lazy_load'] )
         && ! empty( $mts_options['mts_lazy_load_content'] )
         && ! empty( $content ) ) {
        $content = preg_replace_callback(
            '/<img([^>]+?)src=[\'"]?([^\'"\s>]+)[\'"]?([^>]*)>/',
            'mts_content_image_lazy_load_attr_callback',
            $content
        );
    }
    return $content;
}
add_filter('the_content', 'mts_content_image_lazy_load_attr');
/**
 * Callback to move src to data-src and replace it with a 1x1 tranparent image.
 *
 * @param $matches
 *
 * @return string
 */
function mts_content_image_lazy_load_attr_callback( $matches ) {
    $transparent_img = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
    if ( preg_match( '/ data-lazy *= *"false" */', $matches[0] ) ) {
        return '<img' . $matches[1] . 'src="' . $matches[2] . '"' . $matches[3] . '>';
    } else {
        return '<img' . $matches[1] . 'src="' . $transparent_img . '" data-layzr="' . $matches[2] . '"' . str_replace( 'srcset=', 'data-layzr-srcset=', $matches[3]). '>';
    }
}

/*-----------------------------------------------------------------------------------*/
/*  CREATE AND SHOW COLUMN FOR FEATURED IN PORTFOLIO ITEMS LIST ADMIN PAGE
/*-----------------------------------------------------------------------------------*/

// Get Featured image

function mts_get_featured_image($post_ID) {
    $post_thumbnail_id = get_post_thumbnail_id($post_ID);
    if ($post_thumbnail_id) {
        $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'widgetthumb');
        return $post_thumbnail_img[0];
    }
}

function mts_columns_head($defaults) {
    if (get_post_type() == 'post') $defaults['featured_image'] = __('Featured Image', 'mythemeshop');
    return $defaults;
}

function mts_columns_content($column_name, $post_ID) {
    if ($column_name == 'featured_image') {
        $post_featured_image = mts_get_featured_image($post_ID);
        if ($post_featured_image) {
            echo '<img width="100" height="62" src="' . esc_attr($post_featured_image) . '" />';
        }
    }
}

add_filter('manage_posts_columns', 'mts_columns_head');
add_action('manage_posts_custom_column', 'mts_columns_content', 10, 2);

/*-----------------------------------------------------------------------------------*/
/*  Use first attached image as post thumbnail (fallback)
/*-----------------------------------------------------------------------------------*/
add_filter( 'post_thumbnail_html', 'mts_post_image_html', 10, 5 );
function mts_post_image_html( $html, $post_id, $post_image_id, $size, $attr ) {
    if ( has_post_thumbnail( $post_id ) || get_post_type( $post_id ) != 'post'  )
        return $html;
    
    // use first attached image
    $images = get_children( 'post_type=attachment&post_mime_type=image&post_parent=' . $post_id );
    if (!empty($images)) {
        $image = reset($images);
        return wp_get_attachment_image( $image->ID, $size, false, $attr );
    }
        
    // use no preview fallback
    if ( file_exists( get_template_directory().'/images/nothumb-'.$size.'.png' ) ) {
        $placeholder_src = get_template_directory_uri().'/images/nothumb-'.$size.'.png';
        $placeholder_classs = 'attachment-'.$size.' wp-post-image';
        return '<img src="'.esc_attr( $placeholder_src ).'" class="'.esc_attr( $placeholder_classs ).'" alt="'.esc_attr( get_the_title() ).'">';
    } else {
        return '';
    }
    
}

/*-----------------------------------------------------------------------------------*/
/*  Custom Menu Support
/*-----------------------------------------------------------------------------------*/
add_theme_support('menus');

if (function_exists('register_nav_menus')) {
    register_nav_menus(array(
        'primary-menu' => __('Primary Menu', 'mythemeshop') ,
    ));
}

/*-----------------------------------------------------------------------------------*/
/*  Enable Widgetized sidebar and Footer
/*-----------------------------------------------------------------------------------*/

if (function_exists('register_sidebar')) {
    function mts_register_sidebars() {
        $mts_options = get_option(MTS_THEME_NAME);

        // Default sidebar

        register_sidebar(array(
            'name' => __('Sidebar','mythemeshop'),
            'description' => __('Default sidebar.', 'mythemeshop') ,
            'id' => 'sidebar',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title"><span>',
            'after_title' => '</span></h3>',
        ));
        register_sidebar(array(
            'name' => __('Related Posts Ad','mythemeshop'),
            'description' => __('Related Posts Ad Area.', 'mythemeshop') ,
            'id' => 'related-ad',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));

        // Header Ad sidebar

        register_sidebar(array(
            'name' => __('Header Ad','mythemeshop'),
            'description' => __('728x90 Ad Area', 'mythemeshop') ,
            'id' => 'widget-header',
            'before_widget' => '<div id="%1$s" class="widget-header">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));

        // Top level footer widget areas

        if (!empty($mts_options['mts_first_footer'])) {
            if (empty($mts_options['mts_first_footer_num'])) $mts_options['mts_first_footer_num'] = 4;
            register_sidebars($mts_options['mts_first_footer_num'], array(
                'name' => __('First Footer %d', 'mythemeshop') ,
                'description' => __('Appears at the top of the footer.', 'mythemeshop') ,
                'id' => 'footer-first',
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title"><span>',
                'after_title' => '</span></h3>',
            ));
        }

        // Custom sidebars

        if (!empty($mts_options['mts_custom_sidebars']) && is_array($mts_options['mts_custom_sidebars'])) {
            foreach($mts_options['mts_custom_sidebars'] as $sidebar) {
                if (!empty($sidebar['mts_custom_sidebar_id']) && !empty($sidebar['mts_custom_sidebar_id']) && $sidebar['mts_custom_sidebar_id'] != 'sidebar-') {
                    register_sidebar(array(
                        'name' => '' . $sidebar['mts_custom_sidebar_name'] . '',
                        'id' => '' . sanitize_title(strtolower($sidebar['mts_custom_sidebar_id'])) . '',
                        'before_widget' => '<div id="%1$s" class="widget %2$s">',
                        'after_widget' => '</div>',
                        'before_title' => '<h3>',
                        'after_title' => '</h3>'
                    ));
                    }
                }
            }
        }

    add_action('widgets_init', 'mts_register_sidebars');
    }

function mts_custom_sidebar() {
    $mts_options = get_option(MTS_THEME_NAME);

    // Default sidebar

    $sidebar = 'sidebar';
    if (is_home() && !empty($mts_options['mts_sidebar_for_home'])) $sidebar = $mts_options['mts_sidebar_for_home'];
    if (is_single() && !empty($mts_options['mts_sidebar_for_post'])) $sidebar = $mts_options['mts_sidebar_for_post'];
    if (is_page() && !empty($mts_options['mts_sidebar_for_page'])) $sidebar = $mts_options['mts_sidebar_for_page'];

    // Archives

    if (is_archive() && !empty($mts_options['mts_sidebar_for_archive'])) $sidebar = $mts_options['mts_sidebar_for_archive'];
    if (is_category() && !empty($mts_options['mts_sidebar_for_category'])) $sidebar = $mts_options['mts_sidebar_for_category'];
    if (is_tag() && !empty($mts_options['mts_sidebar_for_tag'])) $sidebar = $mts_options['mts_sidebar_for_tag'];
    if (is_date() && !empty($mts_options['mts_sidebar_for_date'])) $sidebar = $mts_options['mts_sidebar_for_date'];
    if (is_author() && !empty($mts_options['mts_sidebar_for_author'])) $sidebar = $mts_options['mts_sidebar_for_author'];

    // Other

    if (is_search() && !empty($mts_options['mts_sidebar_for_search'])) $sidebar = $mts_options['mts_sidebar_for_search'];
    if (is_404() && !empty($mts_options['mts_sidebar_for_notfound'])) $sidebar = $mts_options['mts_sidebar_for_notfound'];

    // Page/post specific custom sidebar

    if ( is_page() || is_single() ) {
        wp_reset_postdata();
        global $post, $wp_registered_sidebars;
        $custom = get_post_meta( $post->ID, '_mts_custom_sidebar', true );
        if ( !empty( $custom ) && array_key_exists( $custom, $wp_registered_sidebars ) || 'mts_nosidebar' == $custom ) $sidebar = $custom;
    }

    // Posts page
    if ( is_home() && ! is_front_page() && 'page' == get_option( 'show_on_front' ) ) {
        wp_reset_postdata();
        global $wp_registered_sidebars;
        $custom = get_post_meta( get_option( 'page_for_posts' ), '_mts_custom_sidebar', true );
        if ( !empty( $custom ) && array_key_exists( $custom, $wp_registered_sidebars ) || 'mts_nosidebar' == $custom ) {
            $sidebar = $custom;
        }
    }

    return $sidebar;
    }

/*-----------------------------------------------------------------------------------*/
/*  Load Widgets, Actions and Libraries
/*-----------------------------------------------------------------------------------*/

// Add the 125x125 Ad Block Custom Widget
locate_template( "functions/widget-ad125.php", true, true );

// Add the 300x250 Ad Block Custom Widget
locate_template( "functions/widget-ad300.php", true, true );

// Add the Footer logo Block Custom Widget
locate_template( "functions/widget-footer-logo.php", true, true );

// Add the 728x90 Ad Block Custom Widget
locate_template( "functions/widget-ad728.php", true, true );

// Add the Latest Tweets Custom Widget
locate_template( "functions/widget-tweets.php", true, true );

// Add Recent Posts Widget
locate_template( "functions/widget-recentposts.php", true, true );

// Add Related Posts Widget
locate_template( "functions/widget-relatedposts.php", true, true );

// Add Author Posts Widget
locate_template( "functions/widget-authorposts.php", true, true );

// Add Popular Posts Widget
locate_template( "functions/widget-popular.php", true, true );

// Add Facebook Like box Widget
locate_template( "functions/widget-fblikebox.php", true, true );

// Add Social Profile Widget
locate_template( "functions/widget-social.php", true, true );

// Add Category Posts Widget
locate_template( "functions/widget-catposts.php", true, true );

// Add Category Posts Widget
locate_template( "functions/widget-postslider.php", true, true );

// Add Welcome message
include_once ("functions/welcome-message.php");

// Template Functions
include_once ("functions/theme-actions.php");

// Post/page editor meta boxes
include_once ("functions/metaboxes.php");

// TGM Plugin Activation
include_once ("functions/plugin-activation.php");

// AJAX Contact Form - mts_contact_form()
include_once ('functions/contact-form.php');

// Custom menu walker
include_once ('functions/nav-menu.php');

/*-----------------------------------------------------------------------------------*/
/*  RTL language support - also in mts_load_footer_scripts()
/*-----------------------------------------------------------------------------------*/

if (!empty($mts_options['mts_rtl'])) {
    function mts_rtl() {
        if ( is_admin() ) {
            return;
        }
        global $wp_locale, $wp_styles;
        $wp_locale->text_direction = 'rtl';
        if (!is_a($wp_styles, 'WP_Styles')) {
            $wp_styles = new WP_Styles();
            $wp_styles->text_direction = 'rtl';
        }
    }

    add_action('init', 'mts_rtl');
}

/*-----------------------------------------------------------------------------------*/
/*  Retina Images
/*-----------------------------------------------------------------------------------*/

if (!empty($mts_options['mts_retina'])) {
    function mts_generate_retina($file, $width, $height, $crop = false) {
        if ($width || $height) {
            $resized_file = wp_get_image_editor($file);
            if (!is_wp_error($resized_file)) {
                $filename = $resized_file->generate_filename($width . 'x' . $height . '@2x');
                $resized_file->resize($width * 2, $height * 2, $crop);

                // discard if it's not exactly 2x in size

                $info = $resized_file->get_size();
                if ($info['width'] < $width * 2 || $info['height'] < $height * 2) {
                    return false;
                    }

                $resized_file->save($filename);
                return array(
                    'file' => wp_basename($filename) ,
                    'width' => $info['width'],
                    'height' => $info['height'],
                );
                }
            }

        return false;
        }

    function mts_add_retina($metadata, $attachment_id) {
        foreach($metadata as $key => $value) {
            if (is_array($value)) {
                foreach($value as $image => $attr) {
                    if (is_array($attr)) mts_generate_retina(get_attached_file($attachment_id) , $attr['width'], $attr['height'], true);
                    }
                }
            }

        return $metadata;
        }

    add_filter('wp_generate_attachment_metadata', 'mts_add_retina', 10, 2);
    }

// Remove Retina image anyway when deleting media

function mts_remove_retina($attachment_id) {
    $meta = wp_get_attachment_metadata($attachment_id);
    $upload_dir = wp_upload_dir();
    if (!is_array($meta) || empty($meta['file'])) return;
    $path = pathinfo($meta['file']);
    foreach($meta as $key => $value) {
        if ('sizes' === $key) {
            foreach($value as $sizes => $size) {
                $original_filename = $upload_dir['basedir'] . '/' . $path['dirname'] . '/' . $size['file'];
                $retina_filename = substr_replace($original_filename, '@2x.', strrpos($original_filename, '.') , strlen('.'));
                if (file_exists($retina_filename)) unlink($retina_filename);
                }
            }
        }
    }

add_filter('delete_attachment', 'mts_remove_retina');
/*-----------------------------------------------------------------------------------*/
/*  Filters customize wp_title
/*-----------------------------------------------------------------------------------*/

function mts_wp_title($title, $sep) {
    global $paged, $page;
    if (is_feed()) return $title;

    // Add the site name.

    $title.= get_bloginfo('name');

    // Add the site description for the home/front page.

    $site_description = get_bloginfo('description', 'display');
    if ($site_description && (is_home() || is_front_page())) $title = "$title $sep $site_description";

    // Add a page number if necessary.

    if ($paged >= 2 || $page >= 2) $title = "$title $sep " . sprintf(__('Page %s', 'mythemeshop') , max($paged, $page));
    return $title;
    }

add_filter('wp_title', 'mts_wp_title', 10, 2);
/*-----------------------------------------------------------------------------------*/
/*  Javascript
/*-----------------------------------------------------------------------------------*/

function mts_nojs_js_class() {
    echo '<script type="text/javascript">document.documentElement.className = document.documentElement.className.replace( /\bno-js\b/,\'js\' );</script>';
    }

add_action('wp_head', 'mts_nojs_js_class', 1);

function mts_add_scripts() {
    $mts_options = get_option(MTS_THEME_NAME);
    echo '<script>var mars_ajax_url = "'.admin_url('admin-ajax.php').'";</script>';
    wp_enqueue_script('jquery');
    if(is_singular()){
        // wp_enqueue_script('jwplayer-script',get_template_directory_uri().'/js/jwplayer_demo.js',array(),'',false);
        wp_enqueue_script('jwplayer_demo','https://content.jwplatform.com/libraries/DbXZPMBQ.js',array(),'',false);
        wp_enqueue_script('video-js','//vjs.zencdn.net/6.4.0/video.js',array(),'',true);

    };
    wp_localize_script( 'love', 'postlove', array(
        'ajax_url' => admin_url( 'admin-ajax.php' )
    ));
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    wp_enqueue_script('jquery-cookie', get_template_directory_uri() . '/js/jquery.cookie.js', array() , null, true);
    
    wp_register_script('customscript', get_template_directory_uri() . '/js/customscript.js', true);
    if (!empty($mts_options['mts_show_primary_nav']) && !empty($mts_options['mts_show_secondary_nav'])) {
        $nav_menu = 'both';
        } else {
        if (!empty($mts_options['mts_show_primary_nav'])) {
            $nav_menu = 'primary';
        } else {
            $nav_menu = 'none';
        }
    }

    wp_localize_script('customscript', 'mts_customscript', array(
        'responsive' => (empty($mts_options['mts_responsive']) ? false : true) ,
        'nav_menu' => $nav_menu
    ));
    wp_enqueue_script('customscript');

    // Slider

    wp_register_script('owl-carousel', get_template_directory_uri() . '/js/owl.carousel.min.js', array() , null, true);
    wp_localize_script('owl-carousel', 'slideropts', array(
        'rtl_support' => $mts_options['mts_rtl']
    ));
    if (!empty($mts_options['mts_featured_slider'])) {
        wp_enqueue_script('owl-carousel');
    }

    // Animated single post/page header

    if (is_singular()) {
        $header_animation = mts_get_post_header_effect();
        if ('parallax' == $header_animation) {
            wp_register_script('jquery-parallax', get_template_directory_uri() . '/js/parallax.js');
            wp_enqueue_script('jquery-parallax');
        } elseif ('zoomout' == $header_animation) {
            wp_register_script('jquery-zoomout', get_template_directory_uri() . '/js/zoomout.js');
            wp_enqueue_script('jquery-zoomout');
        }
    }
}
add_action('wp_enqueue_scripts', 'mts_add_scripts');

function mts_load_footer_scripts() {
    $mts_options = get_option(MTS_THEME_NAME);

    // Lightbox

    if (!empty($mts_options['mts_lightbox'])) {
        wp_register_script('magnificPopup', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js', true);
        wp_enqueue_script('magnificPopup');
    }

    // RTL

    if (!empty($mts_options['mts_rtl'])) {
        wp_register_style('mts_rtl', get_template_directory_uri() . '/css/rtl.css', 'style', true);
        wp_enqueue_style('mts_rtl');
    }

    // Responsive

    if (!empty($mts_options['mts_responsive'])) {
        wp_enqueue_style('responsive', get_template_directory_uri() . '/css/responsive.css', 'style');
    }

    // Retina
    if ( ! empty( $mts_options['mts_retina'] ) ) {
        wp_enqueue_script( 'retina_js', get_template_directory_uri() . '/js/retina.js', '', '', true );
    }

    // Lazy Load
    if ( ! empty( $mts_options['mts_lazy_load'] ) ) {
        if ( ! empty( $mts_options['mts_lazy_load_thumbs'] ) || ( ! empty( $mts_options['mts_lazy_load_content'] ) && is_singular() ) ) {
            wp_enqueue_script( 'layzr', get_template_directory_uri() . '/js/layzr.min.js', '', '', true );
        }
    }

    // Ajax Load More and Search Results

    wp_register_script('mts_ajax', get_template_directory_uri() . '/js/ajax.js', true);
    if (!empty($mts_options['mts_pagenavigation_type']) && $mts_options['mts_pagenavigation_type'] >= 2 && !is_singular()) {
        wp_enqueue_script('mts_ajax');
        wp_register_script('historyjs', get_template_directory_uri() . '/js/history.js', true);
        wp_enqueue_script('historyjs');

        // Add parameters for the JS

        global $wp_query;
        $max = $wp_query->max_num_pages;
        $paged = (get_query_var('paged') > 1) ? get_query_var('paged') : 1;
        $autoload = ($mts_options['mts_pagenavigation_type'] == 3);
        wp_localize_script('mts_ajax', 'mts_ajax_loadposts', array(
            'startPage' => $paged,
            'maxPages' => $max,
            'nextLink' => next_posts($max, false) ,
            'autoLoad' => $autoload,
            'i18n_loadmore' => __('Load More Posts', 'mythemeshop') ,
            'i18n_loading' => __('Loading...', 'mythemeshop') ,
            'i18n_nomore' => __('No more posts.', 'mythemeshop')
        ));
        }

    if (!empty($mts_options['mts_ajax_search'])) {
        wp_enqueue_script('mts_ajax');
        wp_localize_script('mts_ajax', 'mts_ajax_search', array(
            'url' => admin_url('admin-ajax.php') ,
            'ajax_search' => '1'
        ));
    }
}

add_action('wp_footer', 'mts_load_footer_scripts');

if (!empty($mts_options['mts_ajax_search'])) {
    add_action('wp_ajax_mts_search', 'ajax_mts_search');
    add_action('wp_ajax_nopriv_mts_search', 'ajax_mts_search');
}

/*-----------------------------------------------------------------------------------*/
/* Enqueue CSS
/*-----------------------------------------------------------------------------------*/

function mts_enqueue_css() {
    $mts_options = get_option(MTS_THEME_NAME);
    wp_enqueue_style('stylesheet', get_stylesheet_directory_uri() . '/style.css', 'style');

    // Slider
    // also enqueued in slider widget

    wp_register_style('owl-carousel', get_template_directory_uri() . '/css/owl.carousel.css', array() , null);
    if (!empty($mts_options['mts_featured_slider'])) {
        wp_enqueue_style('owl-carousel');
    }

    $handle = 'stylesheet';

    // Lightbox

    if (!empty($mts_options['mts_lightbox'])) {
        wp_register_style('magnificPopup', get_template_directory_uri() . '/css/magnific-popup.css', 'style');
        wp_enqueue_style('magnificPopup');
    }

    // Font Awesome

    wp_register_style('fontawesome', get_template_directory_uri() . '/css/font-awesome.min.css', 'style');
    wp_enqueue_style('fontawesome');

    $mts_bg = '';
    if ($mts_options['mts_bg_pattern_upload'] != '') {
        $mts_bg = $mts_options['mts_bg_pattern_upload'];
    } else {
        if (!empty($mts_options['mts_bg_pattern'])) {
            $mts_bg = get_template_directory_uri() . '/images/' . $mts_options['mts_bg_pattern'] . '.png';
        }
    }

    $mts_sclayout = '';
    $mts_shareit_left = '';
    $mts_shareit_right = '';
    $mts_author = '';
    $mts_header_section = '';
    $mts_floating_nav = '';
    if (is_page() || is_single()) {
        $mts_sidebar_location = get_post_meta(get_the_ID() , '_mts_sidebar_location', true);
    } else {
        $mts_sidebar_location = '';
    }

    if ($mts_sidebar_location != 'right' && ($mts_options['mts_layout'] == 'sclayout' || $mts_sidebar_location == 'left')) {
        $mts_sclayout = '.article { float: right;} .sidebar.c-4-12 { float: left; padding-right: 0; }';
        if (isset($mts_options['mts_social_button_position']) && $mts_options['mts_social_button_position'] == 'floating') {
            $mts_shareit_right = '.shareit { margin: 0 834px 0; border-left: 0; }';
        }
    }

    if (empty($mts_options['mts_header_section2'])) {
        $mts_header_section = '.logo-wrap, .widget-header { display: none; } .navigation { border-top: 0; } #primary-navigation { margin-left: 0; } #primary-navigation > nav > ul > li:first-child a { padding-left:0; }';
    }

    if (isset($mts_options['mts_social_button_position']) && $mts_options['mts_social_button_position'] == 'floating') {
        $mts_shareit_left = '.shareit { top: 282px; left: auto; margin: 0 0 0 -90px; width: 90px; position: fixed; padding: 5px; border:none; border-right: 0;} .share-item {margin: 2px;}';
    }

    if (!empty($mts_options['mts_author_comment'])) {
        $mts_author = '.bypostauthor .comment-text-wrap { background: #E3E3E3; } .bypostauthor:after { content: "' . __('Author', 'mythemeshop') . '"; position: absolute; right: 0; top: 0; padding: 1px 10px; background: #818181; color: #FFF; } .bypostauthor.comment .comment-text-wrap:before { border-color: rgba(0, 0, 0, 0) #E3E3E3; }';
    }

    if (!empty($mts_options['mts_sticky_nav'])) {
        $mts_floating_nav = '#site-header { position: fixed; top: 0; z-index: 1000; } .main-container { padding-top: 80px; }';
    }

    $example_bg = mts_get_background_styles('mts_background');
    $custom_css = "

        body { background-color:{$mts_options['mts_bg_color']}; background-image: url( {$mts_bg} ); }
        .pace .pace-progress, #mobile-menu-wrapper ul li a:hover { background: {$mts_options['mts_color_scheme']}; }
        .postauthor h5, .single_post a, .textwidget a, .pnavigation2 a, #sidebar a:hover, .related-posts a:hover, #comments h4 span, .reply a, .featured-view-posts .latestPost .title a:hover, .primary-navigation .wpmm-megamenu-showing.wpmm-light-scheme, .title a:hover, .post-info a:hover, .comm, #tabber .inside li a:hover, .readMore a:hover, .related-posts .title a:hover, .fn a, a, a:hover, #comments .post-info .theauthor a:hover { color:{$mts_options['mts_color_scheme']}; }  
        a#pull, .widget h3 span:after, .related-posts h4:before, #comments h4:before, #commentsAdd h4:before, .postauthor h4:before, .pagination ul li a, #commentform input#submit, .contact-form input[type='submit'], .pagination a, #tabber ul.tabs li a.selected, #searchsubmit, .latestPost .latestPost-review-wrapper, #wpmm-megamenu .review-total-only, .owl-theme .owl-nav div, .latestPost .review-type-circle.review-total-only, .latestPost .review-type-circle.wp-review-show-total, .widget .review-total-only.small-thumb { background-color:{$mts_options['mts_color_scheme']}; color: #fff!important; }
        #site-header { background: {$mts_options['mts_color_scheme']}; }
        #primary-navigation ul ul li a, #primary-navigation ul ul ul li a { color:{$mts_options['mts_color_scheme']} !important; }
        .navigation ul ul { border-bottom: 4px solid {$mts_options['mts_color_scheme']} !important; }
        .post-type-1 .thecategory, .related-posts .thecategory, .post-type .latestPost .thecategory, .pagination .current, .pagination a:hover, #move-to-top, .tagcloud a:hover { background-color:{$mts_options['mts_color_scheme2']}; }
        .widget .wpt_widget_content .tab_title.selected a, .widget .wp_review_tab_widget_content .tab_title.selected a, .widget .wp_review_tab_widget_content .has-4-tabs .tab_title.selected a, .widget .wpt_widget_content .has-4-tabs .tab_title.selected a { border-bottom: 1px solid {$mts_options['mts_color_scheme']}; }
        #site-footer { background: {$mts_options['mts_footer_bg_color']};}
        {$mts_sclayout}
        {$mts_shareit_left}
        {$mts_shareit_right}
        {$mts_author}
        {$mts_header_section}
        {$mts_floating_nav}
        {$mts_options['mts_custom_css']} 
    ";
    wp_add_inline_style($handle, $custom_css);
}

add_action('wp_enqueue_scripts', 'mts_enqueue_css', 99);

/*-----------------------------------------------------------------------------------*/
/*  Wrap videos in .responsive-video div
/*-----------------------------------------------------------------------------------*/
function mts_responsive_video( $html, $url, $attr ) {

    // Only video embeds
    $video_providers = array(
        'youtube',
        'vimeo',
        'dailymotion',
        'wordpress.tv',
        'vine.co',
        'animoto',
        'blip.tv',
        'collegehumor.com',
        'funnyordie.com',
        'hulu.com',
        'revision3.com',
        'ted.com',
    );

    // Allow user to wrap other embeds
    $providers = apply_filters('mts_responsive_video', $video_providers );

    foreach ( $providers as $provider ) {
        if ( strstr($url, $provider) ) {
            $html = '<div class="flex-video flex-video-' . sanitize_html_class( $provider ) . '">' . $html . '</div>';
            break;// Break if video found
        }
    }

    return $html;
}
add_filter( 'embed_oembed_html', 'mts_responsive_video', 99, 3 );

/*-----------------------------------------------------------------------------------*/
/*  Filters that allow shortcodes in Text Widgets
/*-----------------------------------------------------------------------------------*/
add_filter('widget_text', 'shortcode_unautop');
add_filter('widget_text', 'do_shortcode');
add_filter('the_content_rss', 'do_shortcode');

/*-----------------------------------------------------------------------------------*/
/*  Custom Comments template
/*-----------------------------------------------------------------------------------*/

function mts_comments($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    $mts_options = get_option(MTS_THEME_NAME); ?>

   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
        <div class="comment_wrap" id="comment-<?php comment_ID(); ?>" itemscope itemtype="http://schema.org/UserComments">
            <div class="comment-author vcard">
                <?php echo get_avatar($comment->comment_author_email, 60); ?>
            </div>

            <div class="comment-text-wrap"> 
                <div class="post-info">
                    <?php printf('<span class="theauthor"><span>%s</span></span>', get_comment_author_link()) ?>
                    <?php if (!empty($mts_options['mts_comment_date'])) { ?>
                        <span class="thetime updated"><span><?php comment_date(get_option('date_format')); ?></span></span>
                    <?php } ?>
                    <span class="reply">
                        <?php comment_reply_link(array_merge($args, array(
                            'depth' => $depth,
                            'max_depth' => $args['max_depth']
                        ))) ?>
                    </span>
                </div>
                <span class="comment-meta">
                    <?php edit_comment_link(__('( Edit )', 'mythemeshop') , '  ', '') ?>
                </span>
                
                <?php if ($comment->comment_approved == '0'): ?>
                    <em><?php _e('Your comment is awaiting moderation.', 'mythemeshop') ?></em>
                    <br />
                <?php endif; ?>
                <div class="commentmetadata">
                    <div class="commenttext" itemprop="commentText">
                        <?php comment_text() ?>
                    </div>
                </div>
            </div>
        </div>
    <?php }

/*-----------------------------------------------------------------------------------*/
/*  Excerpt
/*-----------------------------------------------------------------------------------*/

// Increase max length

function mts_excerpt_length($length) {
    return 100;
}
add_filter('excerpt_length', 'mts_excerpt_length', 20);

// Remove [...] and shortcodes

function mts_custom_excerpt($output) {
    return preg_replace('/\[[^\]]*]/', '', $output);
}
add_filter('get_the_excerpt', 'mts_custom_excerpt');

// Truncate string to x letters/words

function mts_truncate($str, $length = 40, $units = 'letters', $ellipsis = '&nbsp;&hellip;') {
    if ($units == 'letters') {
        if (mb_strlen($str) > $length) {
            return mb_substr($str, 0, $length) . $ellipsis;
        } else {
            return $str;
        }
    } else {
        return wp_trim_words( $str, $length, $ellipsis );
    }
}

if (!function_exists('mts_excerpt')) {
    function mts_excerpt($limit = 40) {
        return esc_html(mts_truncate(get_the_excerpt() , $limit, 'words'));
    }
}

/*-----------------------------------------------------------------------------------*/
/*  Remove more link from the_content and use custom read more
/*-----------------------------------------------------------------------------------*/
add_filter('the_content_more_link', 'mts_remove_more_link', 10, 2);

function mts_remove_more_link($more_link, $more_link_text) {
    return '';
}

// shorthand function to check for more tag in post

function mts_post_has_moretag() {
    global $post;
    return strpos($post->post_content, '<!--more-->');
}

if (!function_exists('mts_readmore')) {
    function mts_readmore() { ?>
        <div class="readMore">
            <a href="<?php echo esc_url(get_the_permalink()); ?>" title="<?php echo esc_attr(get_the_title()); ?>" rel="nofollow">
                <?php _e('Read More', 'mythemeshop'); ?>
            </a>
        </div>
    <?php }
}

/*-----------------------------------------------------------------------------------*/
/* nofollow to next/previous links
/*-----------------------------------------------------------------------------------*/

function mts_pagination_add_nofollow($content) {
    return 'rel="nofollow"';
    }

add_filter('next_posts_link_attributes', 'mts_pagination_add_nofollow');
add_filter('previous_posts_link_attributes', 'mts_pagination_add_nofollow');

/*-----------------------------------------------------------------------------------*/
/* Nofollow to category links
/*-----------------------------------------------------------------------------------*/
add_filter('the_category', 'mts_add_nofollow_cat');

function mts_add_nofollow_cat($text) {
    $text = str_replace('rel="category tag"', 'rel="nofollow"', $text);
    return $text;
}

/*-----------------------------------------------------------------------------------*/
/* nofollow post author link
/*-----------------------------------------------------------------------------------*/
add_filter('the_author_posts_link', 'mts_nofollow_the_author_posts_link');

function mts_nofollow_the_author_posts_link($link) {
    return str_replace('<a href=', '<a rel="nofollow" href=', $link);
}

/*-----------------------------------------------------------------------------------*/
/* nofollow to reply links
/*-----------------------------------------------------------------------------------*/
function mts_add_nofollow_to_reply_link($link) {
    return str_replace('" )\'>', '" )\' rel=\'nofollow\'>', $link);
}

add_filter('comment_reply_link', 'mts_add_nofollow_to_reply_link');

/*-----------------------------------------------------------------------------------*/
/* removes the WordPress version from your header for security
/*-----------------------------------------------------------------------------------*/
function mts_remove_wpversion() {
    return '<!--Theme by MyThemeShop.com-->';
}

add_filter('the_generator', 'mts_remove_wpversion');

/*-----------------------------------------------------------------------------------*/
/* Removes Trackbacks from the comment count
/*-----------------------------------------------------------------------------------*/
add_filter('get_comments_number', 'mts_comment_count', 0);

function mts_comment_count($count) {
    if (!is_admin()) {
        global $id;
        $comments = get_comments('status=approve&post_id=' . $id);
        $comments_by_type = separate_comments($comments);
        return count($comments_by_type['comment']);
    } else {
        return $count;
    }
}

/*-----------------------------------------------------------------------------------*/
/* adds a class to the post if there is a thumbnail
/*-----------------------------------------------------------------------------------*/
function has_thumb_class($classes) {
    global $post;
    if (has_post_thumbnail($post->ID)) {
        $classes[] = 'has_thumb';
    }
    return $classes;
}

add_filter('post_class', 'has_thumb_class');

/*-----------------------------------------------------------------------------------*/
/* AJAX Search results
/*-----------------------------------------------------------------------------------*/

function ajax_mts_search() {
    $query = $_REQUEST['q']; // It goes through esc_sql() in WP_Query
    $search_query = new WP_Query(array(
        's' => $query,
        'posts_per_page' => 3,
        'post_status' => 'publish'
    ));
    $search_count = new WP_Query(array(
        's' => $query,
        'posts_per_page' => - 1,
        'post_status' => 'publish'
    ));
    $search_count = $search_count->post_count;
    if (!empty($query) && $search_query->have_posts()):

        // echo '<h5>Results for: '. $query.'</h5>';

        echo '<ul class="ajax-search-results">';
        while ($search_query->have_posts()):
            $search_query->the_post(); ?>
            <li>
                <a href="<?php echo esc_url(get_the_permalink()); ?>">
                    <?php the_post_thumbnail('widgetthumb', array('title' => '' )); ?>
                    <?php the_title(); ?>
                </a>
                <div class="meta">
                    <span class="thetime"><?php the_time('F j, Y'); ?></span>
                </div> <!-- / .meta -->
            </li>   
        <?php endwhile;
        echo '</ul>';
        echo '<div class="ajax-search-meta"><span class="results-count">' . $search_count . ' ' . __('Results', 'mythemeshop') . '</span><a href="' . esc_url(get_search_link($query)) . '" class="results-link">' . __('Show all results.', 'mythemeshop') . '</a></div>';
    else:
        echo '<div class="no-results">' . __('No results found.', 'mythemeshop') . '</div>';
    endif;
    wp_reset_postdata();
    exit; // required for AJAX in WP
}

/**
 *  Filters that allow shortcodes in Text Widgets
*/
add_filter( 'widget_text', 'shortcode_unautop' );
add_filter( 'widget_text', 'do_shortcode' );
add_filter( 'the_content_rss', 'do_shortcode' );

/*-----------------------------------------------------------------------------------*/
/* Redirect feed to feedburner
/*-----------------------------------------------------------------------------------*/
if ($mts_options['mts_feedburner'] != '') {
    function mts_rss_feed_redirect() {
        $mts_options = get_option(MTS_THEME_NAME);
        global $feed;
        $new_feed = $mts_options['mts_feedburner'];
        if (!is_feed()) {
            return;
        }

        if (preg_match('/feedburner/i', $_SERVER['HTTP_USER_AGENT'])) {
            return;
        }

        if ($feed != 'comments-rss2') {
            if (function_exists('status_header')) status_header(302);
            header("Location:" . $new_feed);
            header("HTTP/1.1 302 Temporary Redirect");
            exit();
        }
    }

    add_action('template_redirect', 'mts_rss_feed_redirect');
}

/*-----------------------------------------------------------------------------------*/
/* Single Post Pagination - Numbers + Previous/Next
/*-----------------------------------------------------------------------------------*/
function mts_wp_link_pages_args($args) {
    global $page, $numpages, $more, $pagenow;
    if ($args['next_or_number'] != 'next_and_number') return $args;
    $args['next_or_number'] = 'number';
    if (!$more) return $args;
    if ($page - 1) $args['before'].= _wp_link_page($page - 1) . $args['link_before'] . $args['previouspagelink'] . $args['link_after'] . '</a>';
    if ($page < $numpages) $args['after'] = _wp_link_page($page + 1) . $args['link_before'] . $args['nextpagelink'] . $args['link_after'] . '</a>' . $args['after'];
        return $args;
    }

add_filter('wp_link_pages_args', 'mts_wp_link_pages_args');

/**
 * Remove hentry class from pages
 *
 * @param $classes
 *
 * @return array
 */
function mts_remove_hentry( $classes ) {
    if ( is_page() ) {
        $classes = array_diff( $classes, array( 'hentry' ) );
    }
    return $classes;
}
add_filter( 'post_class','mts_remove_hentry' );

/*-----------------------------------------------------------------------------------*/
/* add <!-- next-page --> button to tinymce
/*-----------------------------------------------------------------------------------*/
add_filter('mce_buttons', 'wysiwyg_editor');

function wysiwyg_editor($mce_buttons) {
    $pos = array_search('wp_more', $mce_buttons, true);
    if ($pos !== false) {
        $tmp_buttons = array_slice($mce_buttons, 0, $pos + 1);
        $tmp_buttons[] = 'wp_page';
        $mce_buttons = array_merge($tmp_buttons, array_slice($mce_buttons, $pos + 1));
    }

    return $mce_buttons;
}

/*-----------------------------------------------------------------------------------*/
/*  Get Post header animation
/*-----------------------------------------------------------------------------------*/
function mts_get_post_header_effect() {
    global $post;
    $postheader_effect = get_post_meta($post->ID, '_mts_postheader', true);
    return $postheader_effect;
}

/*-----------------------------------------------------------------------------------*/
/*  Custom Gravatar Support
/*-----------------------------------------------------------------------------------*/
function mts_custom_gravatar($avatar_defaults) {
    $mts_avatar = get_template_directory_uri() . '/images/gravatar.png';
    $avatar_defaults[$mts_avatar] = 'Custom Gravatar ( /images/gravatar.png )';
    return $avatar_defaults;
}

add_filter('avatar_defaults', 'mts_custom_gravatar');

/*-----------------------------------------------------------------------------------*/
/*  WP Review Support
/*-----------------------------------------------------------------------------------*/

// Set default colors for new reviews

function new_default_review_colors($colors) {
    $colors = array(
        'color' => '#FFCA00',
        'fontcolor' => '#fff',
        'bgcolor1' => '#1E5078',
        'bgcolor2' => '#1E5078',
        'bordercolor' => '#1E5078'
    );
    return $colors;
}

add_filter('wp_review_default_colors', 'new_default_review_colors');

// Set default location for new reviews

function new_default_review_location($position) {
    $position = 'top';
    return $position;
}

add_filter('wp_review_default_location', 'new_default_review_location');

/*-----------------------------------------------------------------------------------*/
/*  Thumbnail Upscale
/*  Enables upscaling of thumbnails for small media attachments,
/*  to make sure it fits into it's supposed location.
/*  Cannot be used in conjunction with Retina Support.
/*-----------------------------------------------------------------------------------*/

if (empty($mts_options['mts_retina'])) {
    function mts_image_crop_dimensions($default, $orig_w, $orig_h, $new_w, $new_h, $crop) {
        if (!$crop) return null; // let the wordpress default function handle this
        $aspect_ratio = $orig_w / $orig_h;
        $size_ratio = max($new_w / $orig_w, $new_h / $orig_h);
        $crop_w = round($new_w / $size_ratio);
        $crop_h = round($new_h / $size_ratio);
        $s_x = floor(($orig_w - $crop_w) / 2);
        $s_y = floor(($orig_h - $crop_h) / 2);
        return array(
            0,
            0,
            ( int )$s_x,
            ( int )$s_y,
            ( int )$new_w,
            ( int )$new_h,
            ( int )$crop_w,
            ( int )$crop_h
        );
        }

    add_filter('image_resize_dimensions', 'mts_image_crop_dimensions', 10, 6);
}

/*-----------------------------------------------------------------------------------*/
/* Post view count
/* AJAX is used to support caching plugins - it is possible to disable with filter
/* It is also possible to exclude admins with another filter
/*-----------------------------------------------------------------------------------*/
// add_filter('the_content', 'mts_view_count_js'); // outputs JS for AJAX call on single
add_action('wp_ajax_mts_view_count', 'ajax_mts_view_count');
add_action('wp_ajax_nopriv_mts_view_count', 'ajax_mts_view_count');

function mts_view_count_js($content) {
    global $post;
    $id = $post->ID;
    $use_ajax = apply_filters('mts_view_count_cache_support', true);
    $exclude_admins = apply_filters('mts_view_count_exclude_admins', false); // pass in true or a user capability
    if ($exclude_admins === true) $exclude_admins = 'edit_posts';
    if ($exclude_admins && current_user_can($exclude_admins)) return $content; // do not count post views here
    if (is_single()) {
        if ($use_ajax) {

            // enqueue jquery

            wp_enqueue_script('jquery');
            $url = admin_url('admin-ajax.php');
            $content.= "
                <script type=\"text/javascript\">
                jQuery(document).ready(function($) {
                    $.post('" . esc_js($url) . "', {action: 'mts_view_count', id: '" . esc_js($id) . "'});
                });
                </script>";
            }

        if (!$use_ajax) {
            mts_update_view_count($id);
        }
    }
    return $content;
}

function ajax_mts_view_count() {
    // do count
    $post_id = $_POST['id'];
    mts_update_view_count($post_id);
    exit;
}

function mts_update_view_count($post_id) {
    $count = get_post_meta($post_id, '_mts_view_count', true);
    update_post_meta($post_id, '_mts_view_count', ++$count);
    do_action('mts_view_count_after_update', $post_id);

    return $count;
}

/*-----------------------------------------------------------------------------------*/
/*  Color manipulations
/*-----------------------------------------------------------------------------------*/

function mts_hex_to_hsl($color) {

    // Sanity check

    $color = mts_check_hex_color($color);

    // Convert HEX to DEC

    $R = hexdec($color[0] . $color[1]);
    $G = hexdec($color[2] . $color[3]);
    $B = hexdec($color[4] . $color[5]);
    $HSL = array();
    $var_R = ($R / 255);
    $var_G = ($G / 255);
    $var_B = ($B / 255);
    $var_Min = min($var_R, $var_G, $var_B);
    $var_Max = max($var_R, $var_G, $var_B);
    $del_Max = $var_Max - $var_Min;
    $L = ($var_Max + $var_Min) / 2;
    if ($del_Max == 0) {
        $H = 0;
        $S = 0;
    } else {
        if ($L < 0.5) $S = $del_Max / ($var_Max + $var_Min);
          else $S = $del_Max / (2 - $var_Max - $var_Min);
        $del_R = ((($var_Max - $var_R) / 6) + ($del_Max / 2)) / $del_Max;
        $del_G = ((($var_Max - $var_G) / 6) + ($del_Max / 2)) / $del_Max;
        $del_B = ((($var_Max - $var_B) / 6) + ($del_Max / 2)) / $del_Max;
        if ($var_R == $var_Max) $H = $del_B - $del_G;
          else
        if ($var_G == $var_Max) $H = (1 / 3) + $del_R - $del_B;
          else
        if ($var_B == $var_Max) $H = (2 / 3) + $del_G - $del_R;
        if ($H < 0) $H++;
        if ($H > 1) $H--;
    }

    $HSL['H'] = ($H * 360);
    $HSL['S'] = $S;
    $HSL['L'] = $L;
    return $HSL;
    }

function mts_hsl_to_hex($hsl = array()) {
    list($H, $S, $L) = array(
        $hsl['H'] / 360,
        $hsl['S'],
        $hsl['L']
    );
    if ($S == 0) {
        $r = $L * 255;
        $g = $L * 255;
        $b = $L * 255;
        } else {
        if ($L < 0.5) {
            $var_2 = $L * (1 + $S);
        } else {
            $var_2 = ($L + $S) - ($S * $L);
        }

        $var_1 = 2 * $L - $var_2;
        $r = round(255 * mts_huetorgb($var_1, $var_2, $H + (1 / 3)));
        $g = round(255 * mts_huetorgb($var_1, $var_2, $H));
        $b = round(255 * mts_huetorgb($var_1, $var_2, $H - (1 / 3)));
    }

    // Convert to hex

    $r = dechex($r);
    $g = dechex($g);
    $b = dechex($b);

    // Make sure we get 2 digits for decimals

    $r = (strlen("" . $r) === 1) ? "0" . $r : $r;
    $g = (strlen("" . $g) === 1) ? "0" . $g : $g;
    $b = (strlen("" . $b) === 1) ? "0" . $b : $b;
    return $r . $g . $b;
}

function mts_huetorgb($v1, $v2, $vH) {
    if ($vH < 0) {
        $vH+= 1;
    }

    if ($vH > 1) {
        $vH-= 1;
    }

    if ((6 * $vH) < 1) {
        return ($v1 + ($v2 - $v1) * 6 * $vH);
    }

    if ((2 * $vH) < 1) {
        return $v2;
    }

    if ((3 * $vH) < 2) {
        return ($v1 + ($v2 - $v1) * ((2 / 3) - $vH) * 6);
    }

    return $v1;
}

function mts_check_hex_color($hex) {

    // Strip # sign is present

    $color = str_replace("#", "", $hex);

    // Make sure it's 6 digits

    if (strlen($color) == 3) {
        $color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
    }

    return $color;
}

// Check if color is considered light or not

function mts_is_light_color($color) {
    $color = mts_check_hex_color($color);

    // Calculate straight from rbg

    $r = hexdec($color[0] . $color[1]);
    $g = hexdec($color[2] . $color[3]);
    $b = hexdec($color[4] . $color[5]);
    return (($r * 299 + $g * 587 + $b * 114) / 1000 > 130);
}

// Darken color by given amount in %

function mts_darken_color($color, $amount = 10) {
    $hsl = mts_hex_to_hsl($color);

    // Darken

    $hsl['L'] = ($hsl['L'] * 100) - $amount;
    $hsl['L'] = ($hsl['L'] < 0) ? 0 : $hsl['L'] / 100;

    // Return as HEX

    return mts_hsl_to_hex($hsl);
}

// Lighten color by given amount in %

function mts_lighten_color($color, $amount = 10) {
    $hsl = mts_hex_to_hsl($color);

    // Lighten

    $hsl['L'] = ($hsl['L'] * 100) + $amount;
    $hsl['L'] = ($hsl['L'] > 100) ? 1 : $hsl['L'] / 100;

    // Return as HEX

    return mts_hsl_to_hex($hsl);
}

/*-----------------------------------------------------------------------------------*/
/*  Generate css from background option
/*-----------------------------------------------------------------------------------*/

function mts_get_background_styles($option_id) {
    $mts_options = get_option(MTS_THEME_NAME);
    if (!isset($mts_options[$option_id])) return;
    $background_option = $mts_options[$option_id];
    $output = '';
    $background_image_type = isset($background_option['use']) ? $background_option['use'] : '';
    if (isset($background_option['color']) && !empty($background_option['color']) && 'gradient' !== $background_image_type) {
        $output.= 'background-color:' . $background_option['color'] . ';';
    }

    if (!empty($background_image_type)) {
        if ('upload' == $background_image_type) {
            if (isset($background_option['image_upload']) && !empty($background_option['image_upload'])) {
                $output.= 'background-image:url(' . $background_option['image_upload'] . ');';
            }

            if (isset($background_option['repeat']) && !empty($background_option['repeat'])) {
                $output.= 'background-repeat:' . $background_option['repeat'] . ';';
            }

            if (isset($background_option['attachment']) && !empty($background_option['attachment'])) {
                $output.= 'background-attachment:' . $background_option['attachment'] . ';';
            }

            if (isset($background_option['position']) && !empty($background_option['position'])) {
                $output.= 'background-position:' . $background_option['position'] . ';';
            }

            if (isset($background_option['size']) && !empty($background_option['size'])) {
                $output.= 'background-size:' . $background_option['size'] . ';';
            }
        } elseif ('gradient' == $background_image_type) {
            $from = $background_option['gradient']['from'];
            $to = $background_option['gradient']['to'];
            $direction = $background_option['gradient']['direction'];
            if (!empty($from) && !empty($to)) {
                $output.= 'background: ' . $background_option['color'] . ';';
                if ('horizontal' == $direction) {
                    $output.= 'background: -moz-linear-gradient(left, ' . $from . ' 0%, ' . $to . ' 100%);';
                    $output.= 'background: -webkit-gradient(linear, left top, right top, color-stop(0%,' . $from . '), color-stop(100%,' . $to . '));';
                    $output.= 'background: -webkit-linear-gradient(left, ' . $from . ' 0%,' . $to . ' 100%);';
                    $output.= 'background: -o-linear-gradient(left, ' . $from . ' 0%,' . $to . ' 100%);';
                    $output.= 'background: -ms-linear-gradient(left, ' . $from . ' 0%,' . $to . ' 100%);';
                    $output.= 'background: linear-gradient(to right, ' . $from . ' 0%,' . $to . ' 100%);';
                    $output.= "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='" . $from . "', endColorstr='" . $to . "',GradientType=1 );";
                } else {
                    $output.= 'background: -moz-linear-gradient(top, ' . $from . ' 0%, ' . $to . ' 100%);';
                    $output.= 'background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,' . $from . '), color-stop(100%,' . $to . '));';
                    $output.= 'background: -webkit-linear-gradient(top, ' . $from . ' 0%,' . $to . ' 100%);';
                    $output.= 'background: -o-linear-gradient(top, ' . $from . ' 0%,' . $to . ' 100%);';
                    $output.= 'background: -ms-linear-gradient(top, ' . $from . ' 0%,' . $to . ' 100%);';
                    $output.= 'background: linear-gradient(to bottom, ' . $from . ' 0%,' . $to . ' 100%);';
                    $output.= "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='" . $from . "', endColorstr='" . $to . "',GradientType=0 );";
                }
            }
        } elseif ('pattern' == $background_image_type) {
            $output.= 'background-image:url(' . get_template_directory_uri() . '/images/' . $background_option['image_pattern'] . '.png' . ');';
        }
    }

    return $output;
}

/*-----------------------------------------------------------------------------------*/
/*  WP Mega Menu Configuration
/*-----------------------------------------------------------------------------------*/

function megamenu_parent_element($selector) {
    return '#header';
}

add_filter('wpmm_container_selector', 'megamenu_parent_element');

/* Change image size */

function megamenu_thumbnails($thumbnail_html, $post_id) {
    $thumbnail_html = '<div class="wpmm-thumbnail">';
    $thumbnail_html.= '<a title="' . get_the_title($post_id) . '" href="' . get_permalink($post_id) . '">';
    if (has_post_thumbnail($post_id)):
        $thumbnail_html.= get_the_post_thumbnail($post_id, 'widgetfull', array(
            'title' => ''
        ));
    else:
        $thumbnail_html.= '<img src="' . get_template_directory_uri() . '/images/nothumb-widgetfull.png" alt="' . __('No Preview', 'wpmm') . '"  class="wp-post-image" />';
    endif;
    $thumbnail_html.= '</a>';

    // WP Review

    $thumbnail_html.= (function_exists('wp_review_show_total') ? wp_review_show_total(false) : '');
    $thumbnail_html.= '</div>';
    return $thumbnail_html;
}

add_filter('wpmm_thumbnail_html', 'megamenu_thumbnails', 10, 2);

// Map categories and images in group field after demo content import
add_filter( 'mts_correct_single_import_option', 'mts_correct_homepage_sections_import', 10, 3 );
function mts_correct_homepage_sections_import( $item, $key, $data ) {

    if ( 'mts_custom_slider' !== $key && 'mts_featured_categories' !== $key ) return $item;

    $new_item = $item;

    if ( 'mts_custom_slider' === $key ) {

        foreach( $item as $i => $image ) {

            $img = $image['mts_custom_slider_image'];
            if ( is_numeric( $img ) ) {

                if ( array_key_exists( $img, $data['posts'] ) ) {

                    $new_item[ $i ]['mts_custom_slider_image'] = $data['posts'][ $img ];
                }

            } else {

                if ( array_key_exists( $img, $data['image_urls'] ) ) {

                    $new_item[ $i ]['mts_custom_slider_image'] = $data['image_urls'][ $img ];
                }
            }
        }

    } else { // mts_featured_categories

        foreach( $item as $i => $category ) {

            $cat_id = $category['mts_featured_category'];

            if ( is_numeric( $cat_id ) && array_key_exists( $cat_id, $data['terms']['category'] ) ) {

                $new_item[ $i ]['mts_featured_category'] = $data['terms']['category'][ $cat_id ];
            }
        }
    }

    return $new_item;
}

function mts_admin_bar_link() {
    /** @var WP_Admin_bar $wp_admin_bar */
    global $wp_admin_bar;
    if( current_user_can( 'edit_theme_options' ) ) {
        $wp_admin_bar->add_menu( array(
            'id' => 'mts-theme-options',
            'title' => 'Theme Options',
            'href' => admin_url( 'themes.php?page=theme_options' )
        ) );
    }
}
add_action( 'admin_bar_menu', 'mts_admin_bar_link', 65 );

/**
 * Retrieves the attachment ID from the file URL
 *
 * @param $image_url
 *
 * @return string
 */
function mts_get_image_id_from_url( $image_url ) {
    global $wpdb;
    $attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ) );
    return $attachment[0]; 
}

// retrieves the attachment ID from the file URL
function mts_get_image_id( $image_url ) {
    global $wpdb;
    $attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ) );
    return $attachment[0]; 
}
function mts_escape_text_tags( $text ) {
    return (string) str_replace( array( "\r", "\n" ), '', strip_tags( $text ) );
}

function mts_single_post_schema() {
    if ( is_singular( 'post' ) ) {
        global $post, $mts_options;
        if ( has_post_thumbnail( $post->ID ) && ! empty( $mts_options['mts_logo'] ) ) {
            $images = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
            $logo = wp_get_attachment_image_src( mts_get_image_id_from_url( $mts_options['mts_logo'] ), 'full' );
            $excerpt = mts_escape_text_tags( $post->post_excerpt );
            $content = $excerpt === "" ? mb_substr( mts_escape_text_tags( $post->post_content ), 0, 110 ) : $excerpt;
            $args = array(
                "@context" => "http://schema.org",
                "@type"    => "BlogPosting",
                "mainEntityOfPage" => array(
                    "@type" => "WebPage",
                    "@id"   => get_permalink( $post->ID )
                ),
                "headline" => ( function_exists( '_wp_render_title_tag' ) ? wp_get_document_title() : wp_title( '', false, 'right' ) ),
                "image"    => array(
                    "@type"  => "ImageObject",
                    "url"    => $images[0],
                    "width"  => $images[1],
                    "height" => $images[2]
                ),
                "datePublished" => get_the_time( DATE_ISO8601, $post->ID ),
                "dateModified"  => get_post_modified_time(  DATE_ISO8601, __return_false(), $post->ID ),
                "author" => array(
                    "@type" => "Person",
                    "name"  => mts_escape_text_tags( get_the_author_meta( 'display_name', $post->post_author ) )
                ),
                "publisher" => array(
                    "@type" => "Organization",
                    "name"  => get_bloginfo( 'name' ),
                    "logo"  => array(
                        "@type"  => "ImageObject",
                        "url"    => $mts_options['mts_logo'],
                        "width"  => $logo[1],
                        "height" => $logo[2]
                    )
                ),
                "description" => ( class_exists('WPSEO_Meta') ? WPSEO_Meta::get_value( 'metadesc' ) : $content )
            );
            echo '<script type="application/ld+json">' , PHP_EOL;
            echo wp_json_encode( $args, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) , PHP_EOL;
            echo '</script>' , PHP_EOL;
        }
    }
}
add_action( 'wp_head', 'mts_single_post_schema' );

if ( ! empty( $mts_options['mts_async_js'] ) ) {
    function mts_js_async_attr($tag){
        
        if (is_admin())
            return $tag;
        $async_files = apply_filters( 'mts_js_async_files', array( 
            get_template_directory_uri() . '/js/ajax.js',
            get_template_directory_uri() . '/js/contact.js',
            get_template_directory_uri() . '/js/customscript.js',
            get_template_directory_uri() . '/js/jquery.magnific-popup.min.js',
            get_template_directory_uri() . '/js/layzr.min.js',
            get_template_directory_uri() . '/js/owl.carousel.min.js',
            get_template_directory_uri() . '/js/parallax.js',
            get_template_directory_uri() . '/js/retina.js',
            get_template_directory_uri() . '/js/sticky.js',
            get_template_directory_uri() . '/js/zoomout.js',
         ) );
        
        $add_async = false;
        foreach ($async_files as $file) {
            if (strpos($tag, $file) !== false) {
                $add_async = true;
                break;
            }
        }
        if ($add_async)
            $tag = str_replace( ' src', ' async="async" src', $tag );
        return $tag;
    }
    add_filter( 'script_loader_tag', 'mts_js_async_attr', 10 );
}
if ( ! empty( $mts_options['mts_remove_ver_params'] ) ) {
    function mts_remove_script_version( $src ){
        
        if ( is_admin() )
            return $src;
        $parts = explode( '?ver', $src );
        return $parts[0];
    }
    add_filter( 'script_loader_src', 'mts_remove_script_version', 15, 1 );
    add_filter( 'style_loader_src', 'mts_remove_script_version', 15, 1 );
}

/*
 * Check if Latest Posts are being displayed on homepage and set posts_per_page accordingly
 */
function mts_home_posts_per_page($query) {
    global $mts_options;
    if ( ! $query->is_home() || ! $query->is_main_query() )
        return;
    $set_posts_per_page = 0;
    if ( ! empty( $mts_options['mts_featured_categories'] ) ) {
        foreach ( $mts_options['mts_featured_categories'] as $section ) {
            if ( $section['mts_featured_category'] == 'latest' ) {
                $set_posts_per_page = $section['mts_featured_category_postsnum'];
                break;
            }
        }
    }
    if ( ! empty( $set_posts_per_page ) ) {
        $query->set( 'posts_per_page', $set_posts_per_page );
    }
}
add_action( 'pre_get_posts', 'mts_home_posts_per_page' );

/**
 * Basic WooCommerce support
 */
add_action( 'after_setup_theme', 'mts_woocommerce_support' );
function mts_woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

function jwplayer_key(){
    if (is_singular()) {
        echo '<script type="text/javascript">jwplayer.key="vmAEdu5OJSCiJfE3aWibJZ6338lN/A7tybduu0fdEfxYgi7AkWpjckRUFeI=";</script>';
    }
}
add_action('wp_head','jwplayer_key');


function curl($url) {
    $ch = @curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    $head[] = "Connection: keep-alive";
    $head[] = "Keep-Alive: 300";
    $head[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
    $head[] = "Accept-Language: en-us,en;q=0.5";
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
    $page = curl_exec($ch);
    curl_close($ch);
    return $page;
}
function posterImg($url, $size = "1280,720") { //poster size width,height
$internalErrors = libxml_use_internal_errors(true);
$ch = curl_init();
$timeout = 30;
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$html = curl_exec($ch);
curl_close($ch);
$sizes = explode(",",$size);
$dom = new DOMDocument();
@$dom->loadHTML($html);
libxml_use_internal_errors($internalErrors);
$maximgx = 1;
$imgx = "";
foreach($dom->getElementsByTagName('img') as $element) {
($maximgx <= 1) ? $maximgx++ && $imgx = $element->getAttribute('src') : ''; 
}
$xim = str_replace("=w214-h120-k-no","=w".$sizes[0]."-h".$sizes[1]."-no",$imgx);
return $xim;    
}

function getPhotoGoogle($link){
    $get = curl($link);
    $data = explode('url\u003d', $get);
    $url = explode('%3Dm', $data[1]);
    $decode = urldecode($url[0]);
    $count = count($data);
    $linkDownload = array();
    $files ='';
    if($count > 4) {
        $v1080p = $decode.'=m37';
        $v720p = $decode.'=m22';
        $v360p = $decode.'=m18';
        $linkDownload['1080p'] = $v1080p;
        $linkDownload['720p'] = $v720p;
        $linkDownload['360p'] = $v360p;
    }
    if($count > 3) {
        $v720p = $decode.'=m22';
        $v360p = $decode.'=m18';
        $linkDownload['720p'] = $v720p;
        $linkDownload['360p'] = $v360p;
    }
    if($count > 2) {
        $v360p = $decode.'=m18';
        $linkDownload['360p'] = $v360p;
    }

    foreach ($linkDownload as $key => $l){
        $files .= '{"type": "video/mp4", "label": "'.$key.'", "file": "'.$l.'"},';
    }

    if(@!$files) {
        $files = '{"type": "video/mp4", "label": "HD", "file": "'.$decode .'=m18'.'"}';
    }else{
        return '['.rtrim($files, ',').']';
    }
}

function fetch_value($str, $find_start = '', $find_end = ''){
    if ($find_start == '') {
            return '';
    }
    $start = strpos($str, $find_start);
    if ($start === false) {
            return '';
    }
    $length = strlen($find_start);
    $substr = substr($str, $start + $length);
    if ($find_end == '') {
            return $substr;
    }
    $end = strpos($substr, $find_end);
    if ($end === false) {
            return $substr;
    }

    return substr($substr, 0, $end);
}


add_action( 'wp_ajax_nopriv_movie_json_api', 'movie_json_api' );
add_action( 'wp_ajax_movie_json_api', 'movie_json_api' );

function movie_json_api() {
    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {        
        $url = $_POST['post_id'];
        $getGP = getPhotoGoogle($url); 
        echo $getGP;
    }
    die();
}


add_action('wp_footer','themoviedb_api_request');
function themoviedb_api_request(){
    if(get_field('_themoviedbapi')!=NULL){
        $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'featured1');
        ?>
        <script type="text/javascript">
           jQuery(document).ready(function($){
            var settings = {
              "async": true,
              "crossDomain": true,
              "url": "https://api.themoviedb.org/3/movie/<?php the_field('_themoviedbapi'); ?>?api_key=637578522d274531ac4f3b34269a694b&append_to_response=credits",
              "method": "GET",
              "headers": {},
              "data": "{}"
            }
            $.ajax(settings).done(function (response) {
                console.log(response);
                var movie_actors=$('<ul class="movie_actors"><strong>Actors :</strong> </ul>');
                var movie_director=$('<ul class="movie_director"><strong>Director : </strong></ul>')
                var movie_writer=$('<ul class="movie_writer"></ul>');
                var resultHtml=$('<div class="movie_summary" itemscope itemtype="http://schema.org/Movie" vocab="http://schema.org/" typeof="Movie"><img src="<?php echo $featured_img_url; ?>"><h1 itemprop="name" property="name" class="movie_title">'+response.original_title+'</h1><p class="movie_overview" itemprop="description" property="description">'+response.overview+'</p>');
                    for (var i = 0; i<= 5; i++) {
                        movie_actors.append('<li itemprop="author" itemscope itemtype="http://schema.org/Person" property="actor" typeof="Person"><span itemprop="name" property="name">'+response['credits']['cast'][i].name+', </span></li>');
                    }
                    resultHtml.append(movie_actors);
                    for (var i = response['credits']['crew'].length - 1; i >= 0; i--) {
                        if(response['credits']['crew'][i].job="Director"){
                            resultHtml.append('<p class="movie_director"><strong>Director : </strong>'+response['credits']['crew'][i].name+'</p>')
                            break;
                        }
                        
                    }
                    
                    resultHtml.append(movie_writer);

                    
                $('.video_palyer').append(resultHtml);

            });                
           });
        </script>
        <?php
    }
}

// add_action('wp_footer','google_photo_scripts');
function google_photo_scripts(){
    if(is_singular()){ 
        if (get_field('__video_provider')=='viki') {
            ?>

            <?php 
        }else{
            $getGP = getPhotoGoogle(get_field('_script_168')); ?>
            <script type="text/javascript">
                jQuery(document).ready(function($){
                    jwplayer("myElement").setup({
                        playlist: [{
                            "sources":<?php echo $getGP?>,
                            tracks:
                                [
                                    {
                                        file: "Subtitles/WallWomanvid.srt",
                                        label: "English",
                                        kind: "captions",
                                        "default": true
                                    },
                                    {
                                        file: "Subtitles/WallWomanvid.srt",
                                        label: "French",
                                        kind: "captions"
                                    }
                                ]
                        }],
                        allowfullscreen: true,
                        width: '100%',
                        aspectratio: '16:9',
                        abouttext: "Video Available at Blender.org",
                        aboutlink: "http://www.blender.org",
                        sharing: {
                            link: "http://example.com/page/MEDIAID/"
                            },
                    });
                });            
            </script>
        <?php } ?>
        
            
    <?php }
}


function google_photo_video(){
    if(is_singular()){ ?>
        <script type="text/javascript">

            jQuery(document).ready(function($){
                var post_id = $('#video_url_link').data('id');
                $.ajax({
                    url : mars_ajax_url.ajax_url,
                    type : 'post',
                    data : {
                        action : 'movie_json_api',
                        post_id : post_id
                    },
                    success : function( response ) {
                        $('#video_url_link').append( response );
                    }
                });
            });           
        </script>
    <?php  }

}
// add_action('wp_footer','google_photo_video',100);

