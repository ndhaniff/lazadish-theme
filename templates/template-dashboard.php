<?php
/**
 * Template Name: Dashboard
 */

get_header(); 

echo 
$message = "";

if($_GET['ref'] == 'restaurant-submitted'){
	$message = "Your Restaurant Has Been Submitted!";
}

?>
	<div style="min-height:80vh" class="dashboard-container container my-5">
		<div class="row">
		<div class="col-md-3 dashboard-nav-pane">
			<div class="nav dashboard-nav">
				<div class="nav-item w-100">
					<a class="nav-link active" data-toggle="tab" href="#profile"><i class="ion-person"></i>&emsp;Profile</a>
				</div>
				<div class="nav-item w-100">
					<a class="nav-link" data-toggle="tab" href="#product"><i class="ion-cube"></i>&emsp;Product</a>
				</div>
				<div class="nav-item w-100">
					<a class="nav-link" data-toggle="tab" href="#orders"><i class="ion-document-text">&emsp;</i>Orders</a>
				</div>
				<div class="nav-item w-100">
					<a class="nav-link" data-toggle="tab" href="#editrestaurant"><i class="ion-document-text">&emsp;</i>Edit Restaurant</a>
				</div>
			</div>
		</div>
		<div class="col-md-9 tab-content">
			<div class="tab-pane" id="orders">
					<h3 class="p-2 my-2 border order-processing">Processing</h3>
					<div class="container">				
					<?php get_template_part( '/lib/component/template-parts/orders-dashboard' ); ?>
					</div>
					<h3 class="p-2 my-2 border order-completed">Completed</h3>
					<div class="container">
					<?php get_template_part( '/lib/component/template-parts/orders-completed' ); ?>
					</div>
			</div>
			<div class="tab-pane active" id="profile">
				<?php if($message != "") : ?><p class="text-success"><?php echo $message; endif; ?></p>
				<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
					<?php the_content() ?>
				<?php endwhile; endif;  ?>
			</div>
			<div class="tab-pane" id="product" >
				<div class="nav nav-tabs product-tabs">
					<div class="nav-item">
						<a class="nav-link active" href="#addproduct" data-toggle="tab">Add Product</a>
					</div>
					<div class="nav-item">
						<a class="nav-link" href="#yourproduct" data-toggle="tab">Your Product</a>
					</div>
				</div>
				<div class="tab-content">
					<div class="tab-pane active pl-4" id="addproduct">
						<?php echo do_shortcode('[add-product]'); ?>
					</div>
					<div class="tab-pane" id="yourproduct">
						<?php get_template_part('/lib/component/template-parts/yourproduct-dashboard') ?>
					</div>
				</div>
			</div>
			<div class="tab-pane" id="editrestaurant">
				<?php get_template_part('/lib/component/template-parts/editrestaurant-dashboard') ?>
			</div>
		</div>
		</div>
	</div>
	<script type="text/javascript">
		jQuery(document).ready(function($){
			$('.completeorder').hide();
			var badge = $('.orderstatus');
			var btnStatus = $('.processorder');
			var btnStatusComplete = $('.completeorder');
			$('.nav-link').on('click',function(e){
			  	localStorage.setItem('slide', $(e.currentTarget).attr('href'));
			  });
			var slide = localStorage.getItem('slide');
				  if (slide) {
				    $("a[href='" + slide + "']").click();
				  }

			for(var i = 0; i < btnStatusComplete.length; i++ ){
				if(btnStatusComplete[i].dataset.status == 'Chef is Preparing'){
					$(btnStatusComplete[i]).show();
				}
			}

			for(var i = 0; i < btnStatus.length; i++ ){
				if(btnStatus[i].dataset.status == 'Chef is Preparing'){
					$(btnStatus[i]).hide();
				}
			}

			for (var i = 0; i < badge.length; i++){
				if(badge[i].innerHTML == 'Chef is Preparing'){
					$(badge[i]).addClass('bg-yellow');
				}
			}
    			

			$(document).on('click','.completeorder', function(e){
				e.preventDefault();
				$(this).prop('disabled',true);
				var that = $(this);
				var ajaxUrl = that.data('url');
				var orderId = that.data('orderid');

				$.ajax({
					url : ajaxUrl,
					type: 'post',
					data: {
						orderID: orderId,
						action: 'ld_order_ajax',
					},
					success: function(response){
						console.log(response);
						$('#'+orderId).html(response);
						$('#'+orderId).addClass('bg-green');
						that.hide();
						$('#list'+orderId).hide();
						setTimeout(function(){// wait for 5 secs(2)
				           location.reload(); // then reload the page.(3)
				      	}, 100);
					},
					error: function(response){
						console.log(response);
					}
				});
			});

		$(document).on('click','#processorder', function(e){
				e.preventDefault();

				$(this).prop('disabled',true);
				var that = $(this);
				var ajaxUrl = that.data('url');
				var orderId = that.data('orderid');

				$.ajax({
					url : ajaxUrl,
					type: 'post',
					data: {
						orderID: orderId,
						action: 'ld_processorder_ajax',
					},
					success: function(response){
						console.log(response);
						$('#'+orderId).html(response);
						$('#'+orderId).addClass('bg-yellow');
						that.hide();
						$('#complete'+orderId).show();
					},
					error: function(response){
						console.log(response);
					}
				});
			});
				
		});
	</script>

<?php get_footer(); ?>