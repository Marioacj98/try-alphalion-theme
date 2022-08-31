<?php

//Importamos todas nuestras clases que no sean singles u templates
require_once get_template_directory() . '/class/ajax.php';
require_once get_template_directory() . '/class/twig/twig-functions.php';
require_once get_template_directory() . '/class/twig/twig-extensions.php';
require_once get_template_directory() . '/class/twig/twig-globals.php';
require_once get_template_directory() . '/class/configuration-theme.php';
require_once get_template_directory() . '/class/base-class.php';

//iniciamos nuestra clase de configuracion para agregar filtros y ajax
$configuration = new ConfigurationTheme();
//Incluimos los filtros del thema 
$configuration->addFilters();
//Agregamos los callbacks  de ajax
$configuration->addAjax();

add_action(
    'after_setup_theme',
    function() {
        add_theme_support( 'html5', [ 'script', 'style' ] );
    }
);

function add_header_security() {
    header( 'X-Content-Type-Options: nosniff' );
    header( 'X-Frame-Options: SAMEORIGIN' );
    header( 'X-XSS-Protection: 1;mode=block' );
}
add_action( 'send_headers', 'add_header_security' );

function my_theme_autocomplete_off() {
    wp_register_script( 'autocomplete-off', get_template_directory_uri() . '/assets/js/autocomplete_off.js', array('jquery'), '1.0' );
    wp_enqueue_script( 'autocomplete-off' );
  }
add_action( 'login_form', 'my_theme_autocomplete_off' );

add_filter( 'rest_endpoints', function( $endpoints ){
    if ( isset( $endpoints['/wp/v2/users'] ) ) {
        unset( $endpoints['/wp/v2/users'] );
    }
    if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
        unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
    }
    return $endpoints;
});
add_filter('json_enabled', '__return_false');
add_filter('json_jsonp_enabled', '__return_false');
remove_action('wp_head', 'rest_output_link_wp_head', 10);

// Desactiva enlaces de oEmbed Discovery
remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);

// Desactiva enlace de la REST API en las cabeceras HTTP
remove_action('template_redirect', 'rest_output_link_header', 11, 0);
remove_action('WP_head', 'wp_oembed_add_discovery_links', 10);

function remove_wp_version_rss() {
    return'';
}
add_filter('the_generator','remove_wp_version_rss');

global $sitepress;
remove_action( 'wp_head', array( $sitepress, 'meta_generator_tag' ) );

if ( ! function_exists( 'ajax_country_enqueue_scripts' ) ) {
    /**
     * Create object and send to js file for location
     */
    function ajax_country_enqueue_scripts() {
        wp_localize_script(
            'wp-ajax', 'my_ajax_object', array(
                'ajax_url'   => admin_url( 'admin-ajax.php' ),
            )
        );
    }

    add_action( 'wp_enqueue_scripts', 'ajax_country_enqueue_scripts' );
}


if( function_exists('acf_add_options_page') ) {
    
    acf_add_options_page();
    
}

add_theme_support( 'post-thumbnails' );

//Stop User Enumeration
if ( ! is_admin() && isset($_SERVER['REQUEST_URI'])){
    if(preg_match('/(wp-comments-post)/', $_SERVER['REQUEST_URI']) === 0 && !empty($_REQUEST['author']) ) {
        global $wp_query;
        $wp_query->set_404();
        status_header( 404 );
        get_template_part( 404 ); exit();
    }
}


remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');


// disabled WP REST API
add_filter('rest_authentication_errors', function ($result) {
    if (!empty($result)) {
        return $result;
    }
    if (!is_user_logged_in()) {
        return new WP_Error('rest_not_logged_in', 'Rest API disabled.', array('status' => 401));
    }
    return $result;
});

// Disable RSS Feeds in WordPress
function wpb_disable_feed()
{
    wp_die(__('El feed no está disponible. ¡Por favor, visita nuestro <a href="' . get_bloginfo('url') . '">sitio</a>!'));
}
add_action('do_feed', 'wpb_disable_feed', 1);
add_action('do_feed_rdf', 'wpb_disable_feed', 1);
add_action('do_feed_rss', 'wpb_disable_feed', 1);
add_action('do_feed_rss2', 'wpb_disable_feed', 1);
add_action('do_feed_atom', 'wpb_disable_feed', 1);
add_action('do_feed_rss2_comments', 'wpb_disable_feed', 1);
add_action('do_feed_atom_comments', 'wpb_disable_feed', 1);
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
/* Eliminar el enlace corto */
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
/* Eliminar Feeds */
remove_action('wp_head', 'feed_links, 2');
remove_action('wp_head', 'feed_links_extra', 3);
/* Eliminar enlace a posts relacionados */
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

function wpdanger_remove_ver($src, $handle)
{
    $handles = ['style', 'script'];
    if (
        strpos($src, 'ver=') &&
        !in_array($handle, $handles, true) &&
        strpos($src, 'alphalion-theme/dist/css/main.css') === false &&
        strpos($src, 'alphalion-theme/dist/js/main.js') === false
    ){
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('style_loader_src', 'wpdanger_remove_ver', 9999, 2);
add_filter('script_loader_src', 'wpdanger_remove_ver', 9999, 2);

function remove_return()
{
    return "";
}
add_filter('pre_get_avatar', 'remove_return', 10, 3);
add_filter('wpseo_next_rel_link', 'remove_return', 10, 3);
add_filter('wpseo_prev_rel_link', 'remove_return', 10, 3);

// Agregar preload
add_filter('style_loader_tag', 'css_loader_custom_tag', 10, 2);
function css_loader_custom_tag($html, $handle)
{
    $handles = array('wordfenceAJAXcss', 'dashicons', 'yoast-seo-adminbar', 'admin-bar', 'wp-block-library');
    if (in_array($handle, $handles)) {
        $html = str_replace("rel='stylesheet'", "rel='preload' as='style' onload='this.onload=null;this.rel=\"stylesheet\"'", $html);
    }
    return $html;
}
// Agregar defer
function prefix_defer_js($html, $handle)
{
    $handles = array('wp-embed', 'hoverintent-js', 'admin-bar', 'wfi18njs', 'undescore', 'wordfenceAJAXjs', 'gg-recaptcha');
    if (!is_admin() && in_array($handle, $handles)) {
        $html = str_replace('></script>', ' defer></script>', $html);
    }
    return $html;
}
add_filter('script_loader_tag', 'prefix_defer_js', 10, 3);


/**
 * Remove emoji support
 *
 * @link https://wordpress.org/support/article/using-smilies/
 */
add_action(
    'init',
    function () {
        // Front-end
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
        // Admin
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('admin_print_styles', 'print_emoji_styles');
        // Feeds
        remove_theme_support('automatic-feed-links');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        // Embeds
        remove_filter('embed_head', 'print_emoji_detection_script');
        // Emails
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        /* Eliminar rel canonical */
        // remove_action('wp_head', 'rel_canonical');
        // Disable from TinyMCE editor. Disabled in block editor by default
        add_filter(
            'tiny_mce_plugins',
            function ($plugins) {
                if (is_array($plugins)) {
                    $plugins = array_diff($plugins, array('wpemoji'));
                }
                return $plugins;
            }
        );
        /**
         * Finally, disable it from the database also, to prevent characters from converting
         *  There used to be a setting under Writings to do this
         *  Not ideal to get & update it here - but it works :/
         */
        if ((int) get_option('use_smilies') === 1) {
            update_option('use_smilies', 0);
        }
    }
);

// block WP enum scans
// https://m0n.co/enum
if (!is_admin()) {
    // default URL format
    if (preg_match('/author=([0-9]*)/i', $_SERVER['QUERY_STRING'])) {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
    }
    add_filter('redirect_canonical', 'check_enum', 10, 2);
}

function check_enum($redirect, $request)
{
    // permalink URL format
    if (preg_match('/\?author=([0-9]*)(\/*)/i', $request)) {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
    } else {
        return $redirect;
    }
}

// disable author archives
function disable_author_archives()
{
    if (is_author()) {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
    } elseif (is_404()) {
        add_action('redirect_canonical', '__return_false');
    } else {
        redirect_canonical();
    }
}
add_action('template_redirect', 'disable_author_archives');
remove_filter('template_redirect', 'redirect_canonical');

function filter_oembed_response_data_author($data)
{
    unset($data['author_name']);
    unset($data['author_url']);
    return $data;
}
add_filter('oembed_response_data', 'filter_oembed_response_data_author', 10, 4);

/*=========== Custom admin login  ===========*/
function my_login_logo()
{ ?>
    <style type="text/css">
        #login h1 a,
        .login h1 a {
            display: inline-block;
            background-size: 100%;
            width: 100%;
            height: 100px;
            margin: 0;
            background-repeat: no-repeat;
            background-position: center;
            background-image: url('<?php echo get_template_directory_uri(); ?>/dist/images/global/logo.svg');
        }

        body.login {
            background: #000;
        }

        body.login #backtoblog a,
        body.login #nav a,
        .privacy-policy-link {
            color: #fff;
        }

        body.login #backtoblog a:hover,
        body.login #nav a:hover,
        .privacy-policy-link:hover {
            color: #f17812;
        }

        body.login form {
            border-radius: 10px;
            border-color: #f17812;
        }

        body.login .message,
        .login .success,
        .login #login_error {
            margin-top: 10px;
        }

        body.login .button-primary {
            background: #f17812;
            border-color: #f17812;
        }

        body.login .button-primary:hover,
        body.login .button-primary:active,
        body.login .button-primary:focus {
            background: #f17812 !important;
            border-color: #f17812 !important;
        }

        body.login .button-primary:focus {
            box-shadow: 0 0 0 1px #fff, 0 0 0 3px #000;
        }

        body.login input:focus {
            border-color: #f17812;
            box-shadow: 0 0 0 1px #f17812;
        }

        body.login .button-secondary {
            color: #fff;
        }
    </style>
<?php }
add_action('login_enqueue_scripts', 'my_login_logo');
add_filter('login_headerurl', 'custom_loginlogo_url');
function custom_loginlogo_url()
{
    return get_bloginfo('url');
}
// desactivar H1 de los campos de texto enriquecido
function remove_h1_from_editor($settings)
{
    $settings['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Preformatted=pre;';
    return $settings;
}
add_filter('tiny_mce_before_init', 'remove_h1_from_editor');

// Deshabilitar Pingback
function deshabilitar_pingback(&$links)
{
    $home = get_option('home');
    foreach ($links as $l => $link) {
        if (0 === strpos($link, $home)) {
            unset($links[$l]);
        }
    }
}
add_action('pre_ping', 'deshabilitar_pingback');

add_filter('xmlrpc_methods', function ($methods) {
    unset($methods['pingback.ping']);
    return $methods;
});