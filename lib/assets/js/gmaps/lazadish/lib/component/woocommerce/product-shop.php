<?php
/**
 *PRODUCT SHOP HOOKS
 */

function ld_product_availability(){
	?>	
		<div class="availability bg-red">
			<strong>Available On</strong><br>
			<span><?php echo get_post_meta( get_the_ID() , '_ld_sell_from', true ); ?></span>
			<strong>to</strong>
			<span><?php echo get_post_meta( get_the_ID() , '_ld_sell_to', true ); ?></span>
		</div>
	<?php

}

add_action('woocommerce_after_shop_loop_item','ld_product_availability');

function wc_renaming_order_status( $order_statuses ) {
    foreach ( $order_statuses as $key => $status ) {
        $new_order_statuses[ $key ] = $status;
        if ( 'wc-on-hold' === $key ) {
            $order_statuses['wc-on-hold'] = 'Pending Chef Review';
        }
        if ( 'wc-processing' === $key ) {
            $order_statuses['wc-processing'] = 'Chef is Preparing';
        }
    }
    return $order_statuses;
}
add_filter( 'wc_order_statuses', 'wc_renaming_order_status' );

function ld_custom_status($order){
    if($order->status == 'on-hold')
        return 'Pending Chef Review';
    if($order->status == 'processing')
        return 'Chef is Preparing';
    else
        return $order->status;
}