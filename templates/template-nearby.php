<?php
/**
 * Template Name: More Restaurant Nearby
 * @package Lazadish 
 */

get_header(); ?>

<div class="container">
	<div class="row">
		<div style="min-height: 80vh" class="col-md-12 section-content py-4">
			<div class="section-title text-center ">
				<h3>Nearby Restaurant</h3>
				<span>Based On:&nbsp;</span><span id="usergeo"></span>
				<div id="geolocater2" class='container' data-url="<?php echo admin_url('admin-ajax.php'); ?>">
					<ul class="list-restaurant row" id="morelocation">

					</ul>
				</div>
			</div>
		</div>
	</div>
</div>


<?php 
	global $post;
	$page_slug = $post->post_name;;

	function ld_homemore_enqueue_script(){
		wp_enqueue_script('homemore-js', LD_JS_URL.'homepage/home.js', array('jquery'));
	}

	if(is_page($page_slug)){
		add_action( 'wp_footer', 'ld_homemore_enqueue_script');
	}
 ?>

<?php get_footer(); ?>




