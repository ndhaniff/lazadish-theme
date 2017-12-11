<?php
/**
 * @package Lazadish
 */

function LD_define_constant()
{
    define('LD_VERSION', '1.0');

    // Define paths.
    if (! defined('LD_THEME_PATH')) {
        define('LD_THEME_PATH', wp_normalize_path(trailingslashit(get_template_directory())));
    }

    define('LD_PATH', LD_THEME_PATH . 'lib/');
    define('LD_ASSETS_PATH', LD_PATH . 'assets/');
    define('LD_COMPONENT_PATH', LD_PATH. 'component/');

    // Define urls.
    if (! defined('LD_THEME_URL')) {
        define('LD_THEME_URL', trailingslashit(get_template_directory_uri()));
    }

    define('LD_URL', LD_THEME_URL . 'lib/');
    define('LD_ASSETS_URL', LD_URL . 'assets/');
    define('LD_CSS_URL', LD_ASSETS_URL . 'css/');
    define('LD_JS_URL', LD_ASSETS_URL . 'js/');
    define('LD_IMG_URL', LD_ASSETS_URL . 'images/');
}

add_action('ld_init', 'ld_define_constant');

//dependencies

function ld_enqueue_scripts()
{
    wp_enqueue_style('bootstrap', LD_CSS_URL . 'bootstrap.min.css', array( ), false, 'all');
    wp_enqueue_style('ionicons', LD_CSS_URL . 'ionicons.min.css', array( ), false, 'all');
    wp_enqueue_style('ldfontss', LD_CSS_URL . 'ld-icon.css', array( ), false, 'all');
    wp_enqueue_style('lazadish', LD_CSS_URL . 'lazadish.css', array( ), false, 'all');
    wp_enqueue_style( 'timepicker-css', LD_CSS_URL . 'jquery.timepicker.min.css' , array(), false, 'all' );
    wp_enqueue_style( 'jquery-ui-css', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css' , array(), false, 'all' );

    wp_enqueue_script('jquery');
    wp_enqueue_script('popper', LD_JS_URL . 'popper.min.js', array( 'jquery' ), true);
    wp_enqueue_script('bootstrapjs', LD_JS_URL . 'theme.min.js', array( 'jquery' ), true);
    wp_enqueue_script('googlemaps-admin-js', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDSiQbulj2nBDKlMN5XQDUecixe3Eq5ZyM&libraries=places', array(), 'version', true);
    wp_enqueue_script('ajax-pagination', LD_JS_URL . 'ajax-pagination.js', array( 'jquery' ), true);
    wp_enqueue_script('timepicker-js', LD_JS_URL . 'jquery.timepicker.min.js', array('jquery-ui-core'), 'version', true);

    wp_localize_script( 'ajax-pagination', 'ajaxpagination', array(
    'ajaxurl' => admin_url( 'admin-ajax.php' ),
    'loader' => LD_ASSETS_URL . '/ajax-loader.gif'
    ));
}

add_action('wp_enqueue_scripts', 'ld_enqueue_scripts');



//requiring files

function LD_required_files()
{
    require_once(LD_COMPONENT_PATH . 'wp-bootstrap-navwalker.php');
    require_once(LD_COMPONENT_PATH . 'theme-supports.php');
    require_once(LD_COMPONENT_PATH . 'nearbyrestaurantajax.php');
    require_once(LD_COMPONENT_PATH . 'woocommerce/custom-product-fields.php');
    require_once(LD_COMPONENT_PATH . 'woocommerce/product-single.php');
    require_once(LD_COMPONENT_PATH . 'woocommerce/product-shop.php');
    require_once(LD_COMPONENT_PATH . 'restaurant/restaurant-cpt.php');
    require_once(LD_COMPONENT_PATH . 'dashboard/chef-dashboard.php');
    require_once(LD_COMPONENT_PATH . 'dashboard/cmb2-restaurant.php');
    require_once(LD_COMPONENT_PATH . 'dashboard/cmb2-restaurant-frontend.php');
}

add_action('ld_init', 'ld_required_files');

//activate the hook
do_action('ld_init');