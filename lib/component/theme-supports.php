<?php

/**
 * base theme supports
 */
add_action('init', 'ld_add_theme_supports');

function ld_add_theme_supports()
{
    add_theme_support('post-thumbnails');
    // add_theme_support( 'post-formats',array(
    //     'aside',
    //     'gallery',
    //     'link',
    //     'video',
    //     'audio',
    //     'chat',
    //     'image',
    //     'quote'
    // ) );
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'lazadish'),
        'top-menu' => __('Top Menu', 'lazadish'),
    ));
}

add_action('widgets_init','ld_register_sidebar');

function ld_register_sidebar()
{
    register_sidebar(array(
        'name' => __('Post Sidebar', 'ldish'),
        'id' => 'sidebar-post',
        'description' => __('The main sidebar appears on the right on each page except the front page template', 'wpb'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h4 class="widget-title my-3">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => __('Shop Sidebar', 'ldish'),
        'id' => 'sidebar-shop',
        'description' => __('The main sidebar appears on the right on each page except the front page template', 'wpb'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h4 class="widget-title my-3">',
        'after_title' => '</h4>',
    ));
}
/**
 * woocommerce supports
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'my_theme_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'my_theme_wrapper_end', 10);
add_action('after_setup_theme', 'woocommerce_support');

add_filter('woocommerce_get_availability', 'custom_get_availability', 1, 2);
add_filter('loop_shop_per_page', 'new_loop_shop_per_page', 20);

function my_theme_wrapper_start()
{
    echo '<section id="main" class="container my-5  ">';
}

function my_theme_wrapper_end()
{
    echo '</section>';
}

function woocommerce_support()
{
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}

function custom_get_availability($availability, $_product)
{
    global $product;
    $stock = $product->get_total_stock();

    if ($_product->is_in_stock()) {
        $availability['availability'] = __($stock . ' Packs Left', 'woocommerce');
    }
    if (!$_product->is_in_stock()) {
        $availability['availability'] = __('SOLD OUT', 'woocommerce');
    }

    return $availability;
}


function new_loop_shop_per_page($cols)
{
    // $cols contains the current number of products per page based on the value stored on Options -> Reading
    // Return the number of products you wanna show per page.
    $cols = 12;
    return $cols;
}

/**
 * FILTERS
 */

