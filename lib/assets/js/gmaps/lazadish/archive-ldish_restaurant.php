<?php

get_header();

$query = new WP_Query(array(
	'post_type' => 'ldish_restaurant',
	'posts_per_page' => 6,
));
$maxpaged = $query->max_num_pages;
?>

	<div>
		<div style="min-height: 80vh" class="container m-auto">
			<div class="row">
			     <div class="breadcrumb my-3 col-md-12">
			          <?php woocommerce_breadcrumb(); ?>
			        </div>
			    </div>  
		<div class="row">
				<div class=" col-md-12 pb-5">
					<div class="container">
						<ul class="pl-0 list-restaurant row text-center">
						<?php while($query->have_posts()) : $query->the_post() ; $id = get_the_ID();?>
						<li class="col-md-4">
							 <a href="<?php the_permalink() ?>">
							 <img style="border:dashed .2rem #F8AA3E; border-radius:50%;" width="150px" height="150px" src="<?php echo get_post_meta($id,'_ld_restaurantlogo',true); ?>">
							 <h5 class="pt-3"><?php the_title(); ?></h5>
							 <p class="mb-1 text-silver">Specialize in : <?php echo get_post_meta($id,'_ld_specialization',true) ?></p>
							 <p class="mb-1 text-silver"><i class="text-red ion-ios-location"></i>&nbsp;<?php echo get_post_meta($id,'_address',true); ?></p> 
							</a>
            			</li>
            			<?php endwhile; ?>
					</ul>
					</div>
				</div>
		</div>
	</div>
	</div>

<?php get_footer();

 ?>