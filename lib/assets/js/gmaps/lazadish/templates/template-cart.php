<?php
/**
 * Template Name: Cart
 * @package Lazadish 
 */
get_header();
?>
		<?php if(have_posts()) : while(have_posts()) : the_post(); ?>	
			<div class="container">
				<div class="row">
			      <div class="breadcrumb my-3 col-md-12">
			          <?php woocommerce_breadcrumb(); ?>
			        </div>
			    </div> 
				<?php the_content(); ?>
			</div>
		<?php endwhile; endif; ?>

<?php get_footer(); ?>