<?php 
/**
 * @package Lazadish
 * Nearby Restaurant Template
 */
?>

<?php 
add_action('wp_ajax_nopriv_ld_save_location_query', 'ld_save_location');
add_action('wp_ajax_ld_save_location_query', 'ld_save_location');

add_action('wp_ajax_nopriv_ld_save_morelocation_query', 'ld_save_morelocation');
add_action('wp_ajax_ld_save_morelocation_query', 'ld_save_morelocation');

function ld_save_location()
{
    $loclat = floatval(wp_strip_all_tags($_POST['lat']));
    $loclng = floatval(wp_strip_all_tags($_POST['lng']));

    queryLocation($loclat, $loclng);

    die();
}

function ld_save_morelocation()
{
    $loclat = floatval(wp_strip_all_tags($_POST['lat']));
    $loclng = floatval(wp_strip_all_tags($_POST['lng']));

    queryMoreLocation($loclat, $loclng);
    die();
}

function queryLocation($lat, $lng)
{   
    $querymap = new WP_Query( array(
        'post_type' => 'ldish_restaurant',
        'meta_query' => array(
            "key" => "_map",
        ),
        'posts_per_page' => -1
     ) );
    if($querymap->have_posts()): while($querymap->have_posts()) :
            $querymap->the_post(); 
            $c = get_post_meta(get_the_ID(),'_ld_map');
            update_post_meta(get_the_ID(),'_lat',$c[0]['droplat']);
            update_post_meta(get_the_ID(),'_lng',$c[0]['droplng']);
            update_post_meta(get_the_ID(),'_address',$c[0]['address']);
    endwhile; endif;
    wp_reset_postdata();

    $args = array(
    'post_type'           => 'ldish_restaurant',
    'posts_per_page'      => 4,
    'ignore_sticky_posts' => true,
    'orderby'             => array( 'title' => 'DESC' ),
    'geo_query' => array(
        'lat'                =>  $lat,                                // Latitude point
        'lng'                =>  $lng,                               // Longitude point
        'lat_meta_key'       =>  "_lat",                          // Meta-key for the latitude data
        'lng_meta_key'       =>  "_lng",                          // Meta-key for the longitude data
        'radius'             =>  50,                               // Find locations within a given radius (km)
        'order'              =>  'ASC',                            // Order by distance
        'distance_unit'      =>  111.045,                           // Default distance unit (km per degree). Use 69.0 for statute miles per degree.
        'context'            => '\\Birgir\\Geo\\GeoQueryHaversine', // Default implementation, you can use your own here instead.
    ),
);
    $queries = new WP_Query($args); ?>
<?php

if ($queries->have_posts()):
    while ($queries->have_posts()) : $queries->the_post() ; $id = get_the_ID();?>
        
            <li class="col-md-3">
                    <a href="<?php the_permalink() ?>">
                    <img style="border:dashed .2rem #F8AA3E; border-radius:50%;" width="150px" height="150px" src="<?php echo get_post_meta($id,'_ld_restaurantlogo',true); ?>">
               <h5 class="pt-3"><?php the_title(); ?></h5>
               <p class="mb-1 text-silver">Specialize in : <?php echo get_post_meta($id,'_ld_specialization',true) ?></p>
               <p class="mb-1 text-silver"><i class="text-red ion-ios-location"></i>&nbsp;<?php echo get_post_meta($id,'_address',true); ?></p>
               <i class="mb-1 text-silver"><?php echo round($queries->post->distance_value, 2) . ' KM from you'; ?>
</i>
</a>
            </li>



<?php endwhile; ?><div class="container pt-3"><a  style="color:#747474" href="<?php echo bloginfo('url') . '/more-restaurant-nearby' ?>">view all</a></div>
<?php else : echo '<h4 class="pt-5">Oops! No Restaurant Nearby</h4><i style="font-size:4rem;color:#C00A27" class="ion-sad-outline"></i>' ?>
<?php endif; ?>





<?php wp_reset_postdata(); // reset the query
}


function queryMoreLocation($lat, $lng)
{   
    $querymap = new WP_Query( array(
        'post_type' => 'ldish_restaurant',
        'meta_query' => array(
            "key" => "_map",
        ),
        'posts_per_page' => -1
     ) );
    if($querymap->have_posts()): while($querymap->have_posts()) :
            $querymap->the_post(); 
            $c = get_post_meta(get_the_ID(),'_ld_map');
            update_post_meta(get_the_ID(),'_lat',$c[0]['droplat']);
            update_post_meta(get_the_ID(),'_lng',$c[0]['droplng']);
            update_post_meta(get_the_ID(),'_address',$c[0]['address']);
    endwhile; endif;
    wp_reset_postdata();

    $args = array(
    'post_type'           => 'ldish_restaurant',
    'posts_per_page'      => -1,
    'ignore_sticky_posts' => true,
    'orderby'             => array( 'title' => 'DESC' ),
    'geo_query' => array(
        'lat'                =>  $lat,                                // Latitude point
        'lng'                =>  $lng,                               // Longitude point
        'lat_meta_key'       =>  "_lat",                          // Meta-key for the latitude data
        'lng_meta_key'       =>  "_lng",                          // Meta-key for the longitude data
        'radius'             =>  50,                               // Find locations within a given radius (km)
        'order'              =>  'ASC',                            // Order by distance
        'distance_unit'      =>  111.045,                           // Default distance unit (km per degree). Use 69.0 for statute miles per degree.
        'context'            => '\\Birgir\\Geo\\GeoQueryHaversine', // Default implementation, you can use your own here instead.
    ),
);
    $queries = new WP_Query($args); ?>
<?php

if ($queries->have_posts()):
    while ($queries->have_posts()) : $queries->the_post() ; $id = get_the_ID();?>
        
            <li class="col-md-4">
                    <a href="<?php the_permalink() ?>">
                    <img style="border:dashed .2rem #F8AA3E; border-radius:50%;" width="150px" height="150px" src="<?php echo get_post_meta($id,'_ld_restaurantlogo',true); ?>">
               <h5 class="pt-3"><?php the_title(); ?></h5>
               <p class="mb-1 text-silver">Specialize in : <?php echo get_post_meta($id,'_ld_specialization',true) ?></p>
               <p class="mb-1 text-silver"><i class="text-red ion-ios-location"></i>&nbsp;<?php echo get_post_meta($id,'_address',true); ?></p>
               <i class="mb-1 text-silver"><?php echo round($queries->post->distance_value, 2) . ' KM from you'; ?>
</i>
</a>
            </li>



<?php endwhile; ?>
<?php else : echo '<h4 class="pt-5">Oops! No Restaurant Nearby</h4><i style="font-size:4rem;color:#C00A27" class="ion-sad-outline"></i>' ?>
<?php endif; ?>





<?php wp_reset_postdata(); // reset the query
}

