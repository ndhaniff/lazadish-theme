<?php 
				global $woocommerce;

				$loop = new WP_Query( array(
					'post_type' => 'shop_order',
					'post_status' => 'wc-completed',
                	'posts_per_page' => -1,
					));
				while ( $loop->have_posts() ) : $loop->the_post();
					$order_id = $loop->post->ID;
					$order = new WC_Order($order_id);

					foreach ( $order->get_items() as $item_id => $item_values ) {
					        $product_id = $item_values->get_product_id(); 
					    }
					$owner = get_post_meta($product_id,'_ld_chef_owner',true);
					$user = wp_get_current_user()->user_login;
				 ?>
				 <?php if($owner == $user) : ?>
				 <div class="orderlist mt-2" id="completedorder">
				 	<h4 class=" d-inline-block position-relative">Order #<?php echo $order_id; ?> &mdash; <time datetime="<?php the_time('c'); ?>"><?php echo the_time('d/m/Y'); ?></time></h4><span id="badge" class="badge badge-success float-right"><?php echo $order->status; ?></span>
				 <table class="orders">
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
				 </div>
				<?php else : echo "" ?>
				<?php endif; ?>
				 <?php endwhile; ?>
