<?php get_header(); ?>

	<div class="container">	
	      <div class="row">
      <div class="breadcrumb my-3 col-md-12">
          <?php woocommerce_breadcrumb(); ?>
        </div>
    </div>  		
			<div class="row">
				<div class="col-md-9 post-content my-3">
					<?php if(have_posts()) : while(have_posts()) : the_post(); ?>	
					  <?php get_template_part( 'templates/content', get_post_format() ); ?>
					<?php endwhile; endif; ?>
				</div>	
				<div class="col-md-3 my-5 sidebar-content">
					<?php dynamic_sidebar('sidebar-post'); ?>
				</div>				
			</div>
	</div>

    
<?php get_footer(); ?>