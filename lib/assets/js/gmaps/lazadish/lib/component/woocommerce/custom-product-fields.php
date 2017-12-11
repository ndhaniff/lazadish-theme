<?php

class ld_custom_WooCommerce_Field
{
    private $wpget_user;
    
    public function __construct()
    {
        $this->wpget_user = wp_get_current_user();
    }
    
    public function init()
    {
        add_action('woocommerce_product_options_general_product_data', array(
            $this,
            'product_options_grouping'
        ));
        add_action('woocommerce_process_product_meta', array(
            $this,
            'ldish_custom_field_save'
        ));
    }
    
    public function product_options_grouping()
    {
        global $post;
        woocommerce_wp_text_input(array(
            'id' => '_spicy_level',
            'label' => __('Spicy Level', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('How spicy this is, rate from 0 - 5', 'woocommerce'),
            'type' => 'number',
            'placeholder' => 'Rate from 0 to 5',
            'custom_attributes' => array(
                "min" => '0',
                "max" => '5'
            ),
            'value' => get_post_meta($post->ID, '_ld_spicy_level', true)
        ));
        
        woocommerce_wp_text_input(array(
            'id' => '_max_pack',
            'label' => __('Max Pack', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('How many dishes left?', 'woocommerce'),
            'type' => 'number',
            'placeholder' => 'eg: 100',
            'value' => get_post_meta($post->ID, '_stock', true)
        ));
        woocommerce_wp_text_input(array(
            'id' => '_chef_owner',
            'label' => __('Chef', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('Chef who make this?', 'woocommerce'),
            'type' => 'text',
            'value' => get_post_meta($post->ID, '_ld_chef_owner', true)
            
        ));
        woocommerce_wp_text_input(array(
            'id' => 'ld_sell_from',
            'label' => __('Selling Date Start', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('Selling Date From', 'woocommerce'),
            'type' => 'text',
            'value' => get_post_meta($post->ID, '_ld_sell_from', true)
            
        ));
        
        woocommerce_wp_text_input(array(
            'id' => 'ld_sell_to',
            'label' => __('Selling Date End', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('Selling Date End', 'woocommerce'),
            'type' => 'text',
            'value' => get_post_meta($post->ID, '_ld_sell_to', true)
            
        ));
        
        woocommerce_wp_text_input(array(
            'id' => '_pickup1',
            'label' => __('Pickup Date From', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('Pickup Date From', 'woocommerce'),
            'type' => 'text',
            'value' => get_post_meta($post->ID, '_ld_pickup1', true)
            
        ));
        
        woocommerce_wp_text_input(array(
            'id' => '_pickup2',
            'label' => __('Pickup Date Until', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('Pickup Date Until', 'woocommerce'),
            'type' => 'text',
            'value' => get_post_meta($post->ID, '_ld_pickup2', true)
            
        ));

        woocommerce_wp_text_input(array(
            'id' => '_last_time',
            'label' => __('Last Order Time', 'woocommerce'),
            'desc_tip' => 'true',
            'description' => __('Last Order Time Until', 'woocommerce'),
            'type' => 'text',
            'value' => get_post_meta($post->ID, '_ld_last_time', true)
            
        ));


    }
    public function ldish_custom_field_save($post_id)
    {
        ?><pre><?php print_r($_POST); ?></pre><?php
        if (isset($_POST['_spicy_level'])):
            update_post_meta($post_id, '_ld_spicy_level', sanitize_text_field($_POST['_spicy_level']));
        endif;
        if (isset($_POST['_max_pack'])):
            update_post_meta($post_id, '_stock', sanitize_text_field($_POST['_max_pack']));
        endif;
        if (isset($_POST['_chef_owner'])):
            update_post_meta($post_id, '_ld_chef_owner', sanitize_text_field($_POST['_chef_owner']));
        endif;
        if (isset($_POST['ld_sell_from'])):
            update_post_meta($post_id, '_ld_sell_from', esc_html($_POST['ld_sell_from']));
        endif;
        if (isset($_POST['ld_sell_to'])):
            update_post_meta($post_id, '_ld_sell_to', esc_html($_POST['ld_sell_to']));
        endif;
        if (isset($_POST['_pickup1'])):
            update_post_meta($post_id, '_ld_pickup1', esc_html($_POST['_pickup1']));
        endif;
        if (isset($_POST['_pickup2'])):
            update_post_meta($post_id, '_ld_pickup2', esc_html($_POST['_pickup2']));
        endif;

    }
}

function ld_activate_cf()
{
    $ld_woo_field = new ld_custom_WooCommerce_Field();
    $ld_woo_field->init();
}

add_action('init', 'ld_activate_cf');

function ld_cpt_enqueue( $hook_suffix ){
    $cpt = 'product';

    if( in_array($hook_suffix, array('post.php', 'post-new.php') ) ){
        $screen = get_current_screen();

        if( is_object( $screen ) && $cpt == $screen->post_type ){
             wp_enqueue_style( 'timepicker-css', LD_CSS_URL . 'jquery.timepicker.min.css' , array(), false, 'all' );

            wp_enqueue_script('timepicker-js', LD_JS_URL . 'jquery.timepicker.min.js', array('jquery-ui-core'), 'version', true);
            wp_enqueue_script( 'cpf-script', LD_JS_URL .'woocommerce/custom-product-fields.js', array( 'jquery' ), true );
        }
    }
}

add_action( 'admin_enqueue_scripts', 'ld_cpt_enqueue');

//product expiry
function ld_filter_expired_posts( $q ) {
     
    // doesn't affect admin screens
    if ( is_admin() )
        return;
    
    $meta_query = $q->get( 'meta_query' );
    $meta_query[] = array(
                    array(
                        "key" => "_ld_sell_from",
                        "value" => date('Y-m-d'),
                        "compare" => ">=",
                        "TYPE" => "DATE"
                    ),
                    array(
                        "key" => "_ld_sell_to",
                        "value" => date('Y-m-d'),
                        "compare" => "!=",
                        "TYPE" => "DATE"
                    ),
                'relation'  => 'AND',
                );

    $q->set( 'meta_query', $meta_query );

}
add_action( 'woocommerce_product_query', 'ld_filter_expired_posts' );