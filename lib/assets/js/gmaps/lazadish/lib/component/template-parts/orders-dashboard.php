<?php 
				global $woocommerce;

				$loop = new WP_Query( array(
					'post_type' => 'shop_order',
					'post_status' => 'wc-on-hold,wc-processing',
                	'posts_per_page' => -1,
					));

				if($loop->have_posts()) :
					while ( $loop->have_posts() ) : $loop->the_post();
						$order_id = $loop->post->ID;
						$order = new WC_Order($order_id);

						foreach ( $order->get_items() as $item_id => $item_values ) {
					        $product_id = $item_values->get_product_id(); 
					    }
					   	$owner = get_post_meta($product_id,'_ld_chef_owner',true);
					   	$user = wp_get_current_user()->user_login;
				 ?>
			<?php if($owner == $user) : $currentstatus = ld_custom_status($order)?>
				<div class="orderlist mt-2" id="list<?php echo $order_id; ?>">
				 	<h4 class=" d-inline-block position-relative">Order #<?php echo $order_id; ?> &mdash; <time datetime="<?php the_time('c'); ?>"><?php echo the_time('d/m/Y'); ?></time></h4><span id="<?php echo $order_id ?>" class="orderstatus badge badge-secondary float-right" data-status="<?php echo $currentstatus ?>"><?php echo $currentstatus; ?></span>
				 <table class="ld-orders orders" data-orderid="<?php echo $order_id ?>">
				 	<thead>
				 		<tr>
				 		<th class="px-2">Product</th>
				 		<th class="px-2">Quantity</th>
				 		<th class="px-2">Price</th>
				 		</tr>
				 	</thead>
				 	<tfoot>
				 		<td colspan=2 class="px-2"><strong>Total</strong></td>
				 		<td class="text-center">RM:<?php echo $order->total ?></td>
				 	</tfoot>
				 	<tbody>
				 		<?php echo wc_get_email_order_items($order); ?>

				 	</tbody>
				 </table>
				 <button data-status="<?php echo $currentstatus ?>" style="cursor:pointer" data-orderid="<?php echo $order_id ?>" data-url="<?php echo admin_url('admin-ajax.php'); ?>" id="processorder" class="processorder btn bg-yellow b-radius mt-3 text-light" >Process Order</button>
				 <button data-status="<?php echo $currentstatus ?>" style="cursor:pointer" data-orderid="<?php echo $order_id ?>" data-url="<?php echo admin_url('admin-ajax.php'); ?>" id="complete<?php echo $order_id ?>" class="completeorder btn bg-green b-radius mt-3 text-light" >Complete Order</button>
				 </div>
				<?php else : echo ''?>
				<?php endif; ?>
				 <?php endwhile; 
				 else : echo '<i><p class="px-2 mb-5">No orders at the moment</p></i>';
				 endif ?>