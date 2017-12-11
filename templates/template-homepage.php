<?php
/**
 * Template Name: Homepage
 * @package Lazadish 
 */

get_header(); ?>
	
<div class="container-fluid header-img">
	<div class="hero-title text-center py-5">
		<h2>WE LOCATE<span class='ht-center'><br> HOMEMADE COOKING </span><br><span>AROUND YOU <i class="ion-ios-location"></i></span></h2>
		<p class="text-light" id="usergeolocate">cannot locate you</p>
		<span>PLEASE ENABLE YOUR GPS FOR ACCURATE LOCATION</span>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="col section-content py-4">
			<div class="section-title text-center ">
				<ul class="cat-list row mb-0">
					<li class="col-xs-6 col-md-2">
						<a href="<?php echo  get_home_url() . '/shop/dessert' ?>"><i style="font-size:5rem" class="text-silver lazadish ldish-cupcake"></i>
						<h5 class="text-silver pt-1">Dessert</h5></a>

					</li>
					<li class="col-md-2">
						<a href="<?php echo  get_home_url() . '/shop/dry-food' ?>"><i style="font-size:5rem" class="text-silver lazadish ldish-dried-fruit"></i>
						<h5 class="text-silver pt-1">Dry Food</h5></a>
					</li>
					<li class="col-md-2">
						<a href="<?php echo  get_home_url() . '/shop/frozen' ?>"><i style="font-size:5rem" class="text-silver lazadish ldish-meat"></i>
						<h5 class="text-silver pt-1">Frozen</h5></a>
					</li>
					<li class="col-md-2">
						<a href="<?php echo  get_home_url() . '/shop/groceries' ?>"><i style="font-size:5rem" class="text-silver lazadish ldish-groceries"></i>
						<h5 class="text-silver pt-1">Groceries</h5></a>
					</li>
					<li class="col-md-2">
						<a href="<?php echo  get_home_url() . '/shop/international' ?>"><i style="font-size:5rem" class="text-silver lazadish ldish-salver"></i>
						<h5 class="text-silver pt-1">International</h5></a>
					</li>
					<li class="col-md-2">
						<a href="<?php echo  get_home_url() . '/shop/main-dish' ?>"><i style="font-size:5rem" class="text-silver lazadish ldish-bell-covering-hot-dish"></i>
						<h5 class="text-silver pt-1">Main Dish</h5></a>
					</li>
				</ul>
			</div>
		</div>
		<div class="col-md-12 section-content py-4">
			<div class="section-title text-center ">
				<h3>Nearby Restaurant</h3>
				<div id="geolocater" class='container' data-url="<?php echo admin_url('admin-ajax.php'); ?>">
					<ul class="list-restaurant row" id="scanlocation">
						<div class="container">
							<h4 class="pt-5">Oops! No Restaurant Nearby</h4><i style="font-size:4rem;color:#C00A27" class="ion-sad-outline"></i>
						</div>
					</ul>
				</div>
			</div>
		</div>
		<div style="min-height:70vh" class="col-md-12 section-content text-center py-4">
			<h3>How It Works</h3>
			<div style="padding-top:15vh; padding-bottom:15vh;" class="how-it text-center row">
				<div class="howitwork col-md-3 text-silver">
					<i style="font-size:7rem" class="text-red ion-android-locate"></i>
					<h4>Locate</h4>
					<i><p>we locate your location automatically</p></i>
				</div>
				<div class="howitwork col-md-3 text-silver">
					<i style="font-size:7rem" class="text-red ion-pizza"></i>
					<h4>Choose</h4>
					<i><p>choose your food</p></i>
				</div>
				<div class="howitwork col-md-3 text-silver">
					<i style="font-size:7rem" class="text-red ion-cash"></i>
					<h4>Pay</h4>
					<i><p>pay online</p></i>
				</div>
				<div class="howitwork col-md-3 text-silver">
					<i style="font-size:7rem" class="text-red ion-happy-outline"></i>
					<h4>Enjoy!</h4>
					<i><p>bon appetit!</p></i>
				</div>
			</div>
		</div>
	</div>
</div>


<?php 
	global $post;
	$page_slug = $post->post_name;;

	function ld_home_enqueue_script(){
		wp_enqueue_script('home-js', LD_JS_URL.'homepage/home.js', array('jquery'));
	}

	if(is_page($page_slug)){
		add_action( 'wp_footer', 'ld_home_enqueue_script');
	}
 ?>

<?php get_footer(); ?>




