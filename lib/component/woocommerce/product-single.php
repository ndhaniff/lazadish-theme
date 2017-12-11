<?php 

/**
 * Hook to Single Page
 */
   add_action( 'woocommerce_after_shop_loop_item', 'remove_add_to_cart_buttons', 1 );

    function remove_add_to_cart_buttons() {
      if( is_product_category() || is_shop()) { 
        remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
      }
    }

add_action('woocommerce_before_add_to_cart_button','ld_choose_delivery_date');

function ld_choose_delivery_date(){
	$value = isset( $_POST['delivery_date'] ) ? sanitize_text_field( $_POST['delivery_date'] ) : '';
    $owner = get_post_meta(get_the_ID(),'_ld_chef_owner',true);
    $pquery = new WP_Query(array(
        "post_type" => 'ldish_restaurant',
        "post_per_page" => 1,
        "meta_query" => array(
            array(
                'key' => '_ld_owner',
                'value' => $owner,
                'compare' => '='
            ),
        ),
    ));
    $prequery = $pquery->get_posts();
    $ownerrestaurant = $prequery[0]->post_title;
    $ownerID = $prequery[0]->ID;
    $ownerrestauranturl = get_permalink($ownerID);
    $pick = get_post_meta($ownerID,'_ld_map');
    $pickuplocation = $pick[0]['address'];
    $days = get_post_meta(get_the_ID(),'_ld_last_Date_Days');
    $whatsapp = get_post_meta($ownerID,'_ld_whatapp',true); 
    $wspath = 'https://api.whatsapp.com/send?phone='.$whatsapp;
    foreach ($days as $day) {
        if(count($day) == 5){
            $dayz = implode(" , ",$day);
        }
        if (count($day) == 7){
            $dayz = 'Daily';
        }
    }
	?> 
        <p class="text-silver mb-2"><span>By:&emsp;</span><a class="text-yellow" href="<?php echo $ownerrestauranturl ?>"><?php echo $ownerrestaurant;?></a></p>
        <a href="<?php echo $wspath ?>"><p class="text-silver mb-2"><i class="text-success ion-social-whatsapp-outline"></i>&nbsp;<?php echo $whatsapp ?></p></a>
        <p data-spicy="<?php echo get_post_meta(get_the_ID(),'_ld_spicy_level',true) ?>" class="text-silver spicy-level mb-2"><span>Spicy Level:&emsp;</span></p>
        <p class="text-silver mb-2"><span>Start Selling Date:&emsp;</span><?php echo get_post_meta(get_the_ID(),'_ld_sell_from',true) ?></p>
        <p class="text-silver mb-2"><span>Last Selling Date:&emsp;</span><?php echo get_post_meta(get_the_ID(),'_ld_sell_to',true) ?></p>
        <p class="text-silver mb-2"><span>Open Order Days:&emsp;</span><?php echo $dayz ?></p>
        <p class="text-silver mb-2"><span>Last Order Time:&emsp;</span><?php echo get_post_meta(get_the_ID(),'_ld_last_Date_Time',true) ?></p>
        <p class="text-silver mb-2"><span>Pickup Time:&emsp;</span><?php echo get_post_meta(get_the_ID(),'_ld_pickup1',true) ?>&emsp;to&emsp;<?php echo get_post_meta(get_the_ID(),'_ld_pickup2',true) ?></p>
        <p class="text-silver mb-2"><span>Pickup Location:&emsp;</span><i class="text-green ion-ios-location"></i>&nbsp;<?php echo $pickuplocation ?></p>
		<input type="hidden" name="from_date" id="from_date" value="<?php echo get_post_meta( get_the_ID(), '_ld_sell_from', true ); ?>" readonly>
		<input type="hidden" name="max_date" id="max_date" value="<?php echo get_post_meta( get_the_ID(), '_ld_sell_to', true ); ?>" readonly>
		<?php printf('<span class="text-silver">Select Pickup Date:&emsp;</span><input type="text" name="delivery_date" id="delivery_date" value="%s">',esc_attr( $value )); ?>
        <?php printf('<span class="text-silver">Whatapp:&emsp;</span><input type="hidden" name="whatsapp_number" id="whatsapp_number" value="%s">',$whatsapp ); ?>
        <p class=" text-silver mb-3 mt-2"><span>Note by Chef:&emsp;</span><?php echo get_post_meta(get_the_ID(),'_ld_note',true) ?></p>
		
	<?php

		function ld_enq_date(){
			wp_enqueue_script('product-single', LD_JS_URL . '/woocommerce/product-single.js', array('jquery-ui-datepicker'), true);?>
            <script type="text/javascript">
                jQuery(function($){
                    var spicy = $('.spicy-level').data('spicy');
                    console.log(spicy);
                    if(spicy == 5){
                        $('.spicy-level').append('<i class="text-danger ion-flame"></i>&nbsp;<i class="text-danger ion-flame"></i>&nbsp;<i class="text-danger ion-flame"></i>&nbsp;<i class="text-danger ion-flame"></i>&nbsp;<i class="text-danger ion-flame"></i>&nbsp;')
                    }
                    if(spicy == 4){
                        $('.spicy-level').append('<i class="text-danger ion-flame"></i>&nbsp;<i class="text-danger ion-flame"></i>&nbsp;<i class="text-danger ion-flame"></i>&nbsp;<i class="text-danger ion-flame"></i>&nbsp;')
                    }
                    if(spicy == 3){
                        $('.spicy-level').append('<i class="text-danger ion-flame"></i>&nbsp;<i class="text-danger ion-flame"></i>&nbsp;<i class="text-danger ion-flame"></i>&nbsp;')
                    }
                    if(spicy == 2){
                        $('.spicy-level').append('<i class="text-danger ion-flame"></i>&nbsp;<i class="text-danger ion-flame"></i>&nbsp;')
                    }
                    if(spicy == 1){
                        $('.spicy-level').append('<i class="text-danger ion-flame"></i>&nbsp;')
                    }
                    if(spicy == 0){
                        $('.spicy-level').append('<i class="text-primary">Not Spicy at All</i>&nbsp;')
                    }
                 });
            </script>
		<?php }
		add_action('wp_footer','ld_enq_date');
}

function ld_add_to_cart_validation($passed, $product_id, $qty){
 
    if( isset( $_POST['delivery_date'] ) && sanitize_text_field( $_POST['delivery_date'] ) == '' ){
        $product = wc_get_product( $product_id );
        wc_add_notice( sprintf( 'please select your pickup time before adding to cart.', $product->get_title() ), 'error' );
        return false;
    }
 
    return $passed;
 
}
add_filter( 'woocommerce_add_to_cart_validation', 'ld_add_to_cart_validation', 10, 3 );

function ld_add_cart_item_data( $cart_item, $product_id ){
 
    if( isset( $_POST['delivery_date'] ) ) {
        $cart_item['delivery_date'] = sanitize_text_field( $_POST['delivery_date'] );
        $cart_item['whatsapp_number'] = sanitize_text_field( $_POST['whatsapp_number'] );
    }
 
    return $cart_item;
 
}
add_filter( 'woocommerce_add_cart_item_data', 'ld_add_cart_item_data', 10, 2 );

function ld_get_cart_item_from_session( $cart_item, $values ) {
 
    if ( isset( $values['delivery_date'] ) ){
        $cart_item['delivery_date'] = $values['delivery_date'];
        $cart_item['whatsapp_number'] = $values['whatsapp_number'];
    }
 
    return $cart_item;
 
}
add_filter( 'woocommerce_get_cart_item_from_session', 'ld_get_cart_item_from_session', 20, 2 );
 
function ld_add_order_item_meta( $item_id, $values ) {
    if ( ! empty( $values['delivery_date'] ) ) {
        woocommerce_add_order_item_meta( $item_id, 'pickup', $values['delivery_date'] );  
        woocommerce_add_order_item_meta( $item_id, 'Whatsapp Chef', $values['whatsapp_number'] );         
    }
}
add_action( 'woocommerce_add_order_item_meta', 'ld_add_order_item_meta', 10, 2 );

function ld_get_item_data( $other_data, $cart_item ) {
    if ( isset( $cart_item['delivery_date'] ) ){
 
        $other_data[] = array(
            'name' => 'Pickup on',
            'value' => sanitize_text_field( $cart_item['delivery_date'] )
        );
 
    }
    if ( isset( $cart_item['whatsapp_number'] ) ){
 
        $other_data[] = array(
            'name' => 'Whatsapp Chef',
            'value' => sanitize_text_field( $cart_item['whatsapp_number'] )
        );
 
    }
 
    return $other_data;
 
}
add_filter( 'woocommerce_get_item_data', 'ld_get_item_data', 10, 2 );

 