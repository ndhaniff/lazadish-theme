<?php

/**
 * Template Name: Fullwidth Template
 */

get_header();

?>

	<div>
		<div style="min-height: 80vh" class="container m-auto">
			<div class="row">
			     <div class="breadcrumb my-3 col-md-12">
			          <?php woocommerce_breadcrumb(); ?>
			        </div>
			    </div>  
		<div class="row">
			<?php while(have_posts()) : the_post() ; ?>
				<div class=" col-md-12 pb-5">
					<?php the_content(); ?>
				</div>
			<?php endwhile; ?>
		</div>
	</div>
	</div>

<?php get_footer(); ?>