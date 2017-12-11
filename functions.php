<?php
require_once ( dirname( __FILE__ ) . '/init.php' );

/**
 * POST FILTER
 */
add_filter('excerpt_more', 'ldish_excerpt_more_change');

function ldish_excerpt_more_change($more)
{
    return ' ...';
}

/**
 * BREADCRUMB FILTER
 */

add_filter( 'woocommerce_breadcrumb_defaults', 'ld_change_breadcrumb_delimiter' );

function ld_change_breadcrumb_delimiter( $defaults ) {
	// Change the breadcrumb delimeter from '/' to '>'
	$defaults['delimiter'] = ' &gt; ';
	return $defaults;
}

	add_action('woocommerce_before_shop_loop_item_title','ld_product_wrapper');
	function ld_product_wrapper(){
		echo '<div class="product-wrapper">';
	}

	add_action('woocommerce_after_shop_loop_item_title','ld_product_wrapper_close');
	function ld_product_wrapper_close(){
		echo '</div>';
	}

/**
 * AJAX CALLBACKS
 */

function ld_order_ajax_callback(){
	if(isset($_POST['orderID'])){
		$orderID = $_POST['orderID'];
		$orders = new WC_Order($orderID);

		$orders->update_status('completed');
		$orders->save();

		$status = ld_custom_status($orders);
		echo $status;

		die();
	}
	
}

add_action('wp_ajax_ld_order_ajax','ld_order_ajax_callback');

function ld_orderprocess_ajax_callback(){
	if(isset($_POST['orderID'])){
		$orderID = $_POST['orderID'];
		$orders = new WC_Order($orderID);

		$orders->update_status('processing');
		$orders->save();

		$status = ld_custom_status($orders);
		echo $status;

		exit;
	}
	exit;
}

add_action('wp_ajax_ld_processorder_ajax','ld_orderprocess_ajax_callback');

add_action( 'template_redirect', 'wc_custom_redirect_after_purchase' ); 
function wc_custom_redirect_after_purchase() {
	global $wp;

	if ( is_checkout() && ! empty( $wp->query_vars['order-received'] ) ) {
		wp_redirect( bloginfo('url').'/thank-you' );
		exit;
	}
}

add_action( 'wp_ajax_nopriv_ajax_pagination', 'my_ajax_pagination' );
add_action( 'wp_ajax_ajax_pagination', 'my_ajax_pagination' );

function my_ajax_pagination() {
    $paged = $_POST['page']+1;
    $maxpaged = $_POST['maxpage'];
    $max = json_encode(array('max' => intval($maxpaged)));
    echo "<script>var maxx = JSON.parse('".$max."')</script>";

    if($paged != $maxpage) :
    $qpage = new WP_Query( array(
    	'post_type' => 'ldish_restaurant',
    	'posts_per_page' => 6,
		'paged' => intval($paged)
    ));
	 while($qpage->have_posts()) : $qpage->the_post() ; $id = get_the_ID();?>
	<li style="height: 20rem" class="col-md-4">
			<a href="<?php the_permalink() ?>">
			<img style="border:dashed .2rem #F8AA3E; border-radius:50%;" width="150px" height="150px" src="<?php echo get_post_meta($id,'_ld_restaurantlogo',true); ?>">
			<h5 class="pt-3"><?php the_title(); ?></h5>
			<p class="mb-1 text-silver">Specialize in : <?php echo get_post_meta($id,'_ld_specialization',true) ?></p>
			<p class="mb-1 text-silver"><i class="text-red ion-ios-location"></i>&nbsp;<?php echo get_post_meta($id,'_address',true); ?></p>
		</i>
		</a>
     </li>
     <?php endwhile; endif;?>
  <?php
    die();
} 