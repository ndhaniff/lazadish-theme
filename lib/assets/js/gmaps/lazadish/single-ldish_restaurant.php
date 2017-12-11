<?php
/**
 * SINGLE RESTAURANT
 */ ?>
    <?php get_header(); 
    ?>
    <div class="container">
        <div class="row">
            <div class="breadcrumb my-3 col-md-12">
                <?php woocommerce_breadcrumb(); ?>
            </div>
        </div>
        <div class="rest-row row">
            <div class="rest-content col-md-12 my-3">
                <?php if(have_posts()) : while(have_posts()) : the_post(); $location = get_post_meta($post->ID,'_ld_map');?>
                <div class="rest-banner col-md-12">
                    <img onerror="src='http://via.placeholder.com/1200x480'" class="rest-big" width="100%" height="480px" src="<?php echo get_the_post_thumbnail_url($post->ID, 'post-thumbnail' ); ?>">
                    <div class="rest-inside text-center">
                        <img class="rest-logo" width="150px" height="150px" src="<?php echo get_post_meta($post->ID,'_ld_restaurantlogo',true) ?>">
                        <h3 class="text-light"><?php the_title(); ?></h3>
                        <p class="text-light mb-1"><i class="text-yellow ion-ios-location"></i>&nbsp;
                            <?php echo $location[0]['address'] ?>
                        </p>
                        <p class="text-light mb-1"><i class="text-green ion-social-whatsapp-outline"></i>&nbsp;
                            <?php echo get_post_meta($post->ID,'_ld_whatapp',true) ?>
                        </p>
                        <p class="text-light mb1">Specialize In: &nbsp;
                            <?php echo get_post_meta($post->ID,'_ld_specialization',true) ?>
                        </p>
                        <p class="text-light mb1">
                            <?php echo get_the_excerpt() ?>
                        </p>
                    </div>
                </div>
                <?php
					$id = $post->ID;
					 endwhile; endif; ?>
                    <div class="rest-product col-md-12">
                        <?php 
						$owner = get_post_meta($id,'_ld_owner',true);
						$restprod = new WP_query(array(
								'post_type' => 'product',
								'post_per_page' => -1,
								'meta_query' => array(
									array(
									'key' => '_ld_chef_owner',
									'value' => $owner,
									'compare' => '=',
									),
				                    array(
				                        "key" => "_ld_sell_from",
				                        "value" => date('Y-m-d'),
				                        "compare" => ">=",
				                        "TYPE" => "DATE"
				                    ),
				                    array(
				                        "key" => "_ld_sell_to",
				                        "value" => date('Y-m-d'),
				                        "compare" => "!=",
				                        "TYPE" => "DATE"
				                    ),
				                	'relation'  => 'AND',
								)
							)); 


							?>
                        <div class="container">
                            <div class="nav nav-tabs pt-5">
                                <div class="nav-item">
                                    <a href="#menu" class="tab-link nav-link active" data-toggle="tab">Menu</a>
                                </div>
                                <div class="nav-item">
                                    <a href="#map" class="map-tab tab-link nav-link" data-toggle="tab">Map</a>
                                </div>
                            </div>
                            <div class="tab-content p-3">
                                <div class="tab-pane active container" id="menu">
                                	<input type="hidden" name="droplat" id="droplat" value="<?php echo $location[0]['droplat']?>">
									<input type="hidden" name="droplng" id="droplng" value="<?php echo $location[0]['droplng']?>">
                                    <ul class="product-rest-ul row">
                                        <?php 
											if($restprod->have_posts()) : while($restprod->have_posts()) : 
												$restprod->the_post();
												$wc_restprod = new WC_Product(get_the_ID());
											?>
                                        <li class="product-rest-li col-md-3 text-center">
                                            <a href="<?php the_permalink(); ?>">
													<img src="<?php echo get_the_post_thumbnail_url() ?>" width="230px" height="230px">
													<div class="product-title">
														<h2><?php the_title(); ?></h2>
														<p><?php echo $wc_restprod->get_price_html(); ?></p>
													</div>
													<div class="avail">
														<strong>Available On</strong><br>
														<span><?php echo get_post_meta(get_the_ID(),'_ld_sell_from',true) ?></span>
														<strong>to</strong>
														<span><?php echo get_post_meta(get_the_ID(),'_ld_sell_to',true) ?></span>
													</div>
												</a>
                                        </li>
                                        <?php endwhile; endif; wp_reset_postdata(); ?>
                                    </ul>
                                </div>
                                <div class="tab-pane" id="map">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-10" id="restaurantmap">
                                            </div>
                                            <div class="col-md-10 m-auto py-3 text-center">
                                                <p class="distancekm"></p>
                                                <button class="btn btn-secondary" id="getdirection">Get Direction&emsp;<i class="ion-model-s"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    	
 jQuery(function($){
   	var directionsService = new google.maps.DirectionsService();
	var geocoder = new google.maps.Geocoder();

	function degreesToRadians(degrees) {
        return degrees * Math.PI / 180;
    }
    //autodetect user
    navigator.geolocation.getCurrentPosition(function(position) {
        var posit1 = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
        };

        var posit2 = {
            lat: parseFloat($('#droplat').val()),
            lng: parseFloat($('#droplng').val())
        };
    $('.map-tab').on('click', function (e) {
      var LatlngRest = new google.maps.LatLng(posit2.lat, posit2.lng);

              var mapOption = {
                  zoom: 14,
                  center: LatlngRest,
                  mapTypeId: google.maps.MapTypeId.ROADMAP
              };

              // Attach a map to the DOM Element, with the defined settings
      var singlemap = new google.maps.Map(document.getElementById("restaurantmap"), mapOption);
      //dragable position
      var restmarker = new google.maps.Marker({
          map: singlemap,
          draggable: false,
          animation: google.maps.Animation.DROP,
          position: posit2
      });

      //get directionsService
      directionsDisplay = new google.maps.DirectionsRenderer();
      directionsDisplay.setMap(singlemap);

      //calculate route
      $('#getdirection').on('click',function(e){
          restmarker.setMap(null);
          e.preventDefault();
          var start = posit1;
          var end = posit2;
          var request = {
            origin: start,
            destination: end,
            travelMode: 'DRIVING'
          };
          directionsService.route(request, function(result, status) {
            if (status == 'OK') {
              directionsDisplay.setDirections(result);
            }
          });
      });
	});

		var yourdist = distanceInKmBetweenEarthCoordinates(posit1.lat, posit1.lng, posit2.lat, posit2.lng);
        var yourdistance = parseFloat(Math.round(yourdist * 100) / 100).toFixed(2);

        $('.distancekm').html(yourdistance + ' Kilometers from your current location.');
        $('#deliinfo').html('Distance: ' + yourdistance + ' km');
 	 });

    function distanceInKmBetweenEarthCoordinates(lat1, lon1, lat2, lon2) {
        var earthRadiusKm = 6371;

        var dLat = degreesToRadians(lat2 - lat1);
        var dLon = degreesToRadians(lon2 - lon1);

        lat1 = degreesToRadians(lat1);
        lat2 = degreesToRadians(lat2);

        var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.sin(dLon / 2) * Math.sin(dLon / 2) * Math.cos(lat1) * Math.cos(lat2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return earthRadiusKm * c;
    }

   });
    
    </script>
    <?php get_footer(); ?>