<?php 
					$currentchef = wp_get_current_user();
                    $chefname = $currentchef->user_login;
                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	                    $chefproducts = new WP_Query(array(
	                      "post_type"=>"product",
	                      "posts_per_page" => -1,
	                      "paged" => $paged,
	                      'orderby' => 'date',
	                      'order' => 'DESC',
	                      "meta_query" => array(
	                      	array(
	                      		"key" => "_ld_chef_owner",
	                      		"value" => $chefname,
	                      		"compare" => "="
	                      	),
	                      )
	                      )); ?>

	                      	<div class="container product-ld">
	                      	<?php if($chefproducts->have_posts()) : ?>
	                      	<?php while($chefproducts->have_posts()) : $chefproducts->the_post(); ?>
	                      		<?php $id = get_the_ID();
								$current_user = get_current_user(); ?>
	                      		
	                      		<div class="row your-product">
	                      			<div class="col-md-4">
	                      				<img onerror="src='http://via.placeholder.com/200x200'" width="200px" height="200px" src="<?php echo get_the_post_thumbnail_url( $id, 'post-thumbnail' ); ?>">
	                      			</div>
	                      			<div class="col-md-8">
	                      				<h2><a class="your-prod-link" href="<?php the_permalink( ); ?>"><?php the_title(); ?></a></h2>
	                      				<p class="mb-1"><?php the_excerpt(); ?></p>
	                      				<p class="mb-1"><strong>Date Added:&emsp;</strong> <?php the_date(); ?></p>
	                      				<p class="mb-4"><strong>Available on:&emsp;</strong> <?php echo get_post_meta( $id, '_ld_sell_from', true ) ?> to <?php echo get_post_meta( $id, '_ld_sell_to', true ) ?></p>
	                      				<?php if ($chefproducts->post_author == $current_user->ID) { ?>
							   			<p><a onclick="return confirm('Are you SURE you want to delete this product?')" href="<?php echo get_delete_post_link( get_the_ID() ) ?>">Delete Product</a></p>
									<?php } ?>
	                      			</div>			
	                      		</div>
	                      		<?php endwhile; wp_reset_postdata();?>
	                      	<?php else : echo '<i><p class="py-2">You have no product</p></i>' ?>
	                      	<?php endif; ?>
	                      	</div>
	                      	