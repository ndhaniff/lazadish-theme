<?php
/**
 * @package Lazadish
 */

function ldish_restaurant_init() {
	$labels = array(
		'name'               => _x( 'Restaurants', 'post type general name', 'your-plugin-lazadish' ),
		'singular_name'      => _x( 'Restaurant', 'post type singular name', 'your-plugin-lazadish' ),
		'menu_name'          => _x( 'Restaurants', 'admin menu', 'your-plugin-lazadish' ),
		'name_admin_bar'     => _x( 'Restaurant', 'add new on admin bar', 'your-plugin-lazadish' ),
		'add_new'            => _x( 'Add New', 'Restaurant', 'your-plugin-lazadish' ),
		'add_new_item'       => __( 'Add New Restaurant', 'your-plugin-lazadish' ),
		'new_item'           => __( 'New Restaurant', 'your-plugin-lazadish' ),
		'edit_item'          => __( 'Edit Restaurant', 'your-plugin-lazadish' ),
		'view_item'          => __( 'View Restaurant', 'your-plugin-lazadish' ),
		'all_items'          => __( 'All Restaurants', 'your-plugin-lazadish' ),
		'search_items'       => __( 'Search Restaurants', 'your-plugin-lazadish' ),
		'parent_item_colon'  => __( 'Parent Restaurants:', 'your-plugin-lazadish' ),
		'not_found'          => __( 'No restaurants found.', 'your-plugin-lazadish' ), 'not_found_in_trash' => __( 'No restaurants found in Trash.', 'your-plugin-lazadish' )
	);

	$args = array(
		'labels'             => $labels,
                'description'        => __( 'Description.', 'your-plugin-lazadish' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'restaurant' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'menu_icon' => 'dashicons-store',
		'supports'           => array( 'title', 'editor', 'thumbnail' )
	);

	register_post_type( 'ldish_restaurant', $args );
}

add_action('init','ldish_restaurant_init');

/**
 * Enqueue script to admin page
 */

function ldish_cpt_enqueue( $hook_suffix ){

    if( in_array($hook_suffix, array('post.php', 'post-new.php') ) ){
        $screen = get_current_screen();

        if( is_object( $screen ) && 'ldish_restaurant' == $screen->post_type ){

        	wp_enqueue_style( 'timepicker-css', LD_CSS_URL . 'jquery.timepicker.min.css' , array(), false, 'all' );

            wp_enqueue_script('timepicker-js', LD_JS_URL . 'jquery.timepicker.min.js', array('jquery-ui-core'), 'version', true);

           wp_enqueue_script('googlemaps-admin-js', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDSiQbulj2nBDKlMN5XQDUecixe3Eq5ZyM&libraries=places', array(), 'version', true);
    		wp_enqueue_script('cpt-admin-location-js', LD_JS_URL . 'gmaps/droplocation.js', array('jquery'), 'version', true);

        }
    }
}

add_action( 'admin_enqueue_scripts', 'ldish_cpt_enqueue');

/**
 * Change Default Text
 */

add_filter('gettext', 'change_admin_cpt_text_filter', 20, 3);

function change_admin_cpt_text_filter( $translated_text, $untranslated_text, $domain ) {

  global $typenow;

  if( is_admin() && 'ldish_restaurant' == $typenow )  {

    //make the changes to the text
    switch( $untranslated_text ) {

        case 'Author':
          $translated_text = __( 'Chef Owner' );
        break;

        case 'Enter title here':
          $translated_text = __( 'Enter Restaurant Name' );
        break;
        
        //add more items
     }
   }
   return $translated_text;
}



function ldish_restaurant_add_meta_box(){
	add_meta_box( 'ldish_restaurant_meta', 'Restaurant Info', 'ldish_restaurant_callback', 'ldish_restaurant','normal' );
}

function ldish_restaurant_callback($post){
	wp_nonce_field( 'ldish_save_restaurant_data','ldish_restaurant_metabox_nonce' );
 
	$value1 = get_post_meta( $post->ID, '_droplat_value_key', true );
	$value2 = get_post_meta( $post->ID, '_droplng_value_key', true );
	$value3 = get_post_meta( $post->ID, '_ldishaddress_value_key', true );
	$value4 = get_post_meta( $post->ID, '_specialization_value_key', true );
	$value5 = get_post_meta( $post->ID, '_fee_value_key', true );
	$value6 = get_post_meta( $post->ID, '_dcover_value', true );
	$value7 = get_post_meta( $post->ID, '_wsap_value', true );
	//opening hour
	$sun1 = get_post_meta( $post->ID, '_sun1_hour', true );
	$sun2 = get_post_meta( $post->ID, '_sun2_hour', true );
	$mon1 = get_post_meta( $post->ID, '_mon1_hour', true );
	$mon2 = get_post_meta( $post->ID, '_mon2_hour', true );
	$tue1 = get_post_meta( $post->ID, '_tue1_hour', true );
	$tue2 = get_post_meta( $post->ID, '_tue2_hour', true );
	$wed1 = get_post_meta( $post->ID, '_wed1_hour', true );
	$wed2 = get_post_meta( $post->ID, '_wed2_hour', true );
	$thu1 = get_post_meta( $post->ID, '_thu1_hour', true );
	$thu2 = get_post_meta( $post->ID, '_thu2_hour', true );
	$fri1 = get_post_meta( $post->ID, '_fri1_hour', true );
	$fri2 = get_post_meta( $post->ID, '_fri2_hour', true );
	$sat1 = get_post_meta( $post->ID, '_sat1_hour', true );
	$sat2 = get_post_meta( $post->ID, '_sat2_hour', true );
	
	
 	//specialization
 	echo '<strong><label for="ldish_specialization">Specialization: </label></strong>';
	echo '<input type="text" name="ldish_specialization" id="specialization" value="'.$value4.'"/><br>';

	echo '<strong><label for="wsap_value">Whatsap Number / Phone Number: </label></strong>';
	echo '<input type="tel" name="wsap_value" id="wsap_value" value="'.$value7.'"/><br>';

	echo '<br><strong><label for="ldish_fee">Fee on arrival: </label></strong>';
	echo '<input type="number" min="0.01" step="0.01" max="2500" name="ldish_fee" id="fee" value="'.$value5.'"/>';

	echo '<br><strong><label for="ldish_dcover">Delivery Distance Covered: </label></strong>';
	echo '<input type="number" min="0.01" step="0.01" max="2500" name="ldish_dcover" id="dcover" value="'.$value6.'"/> <span>KM</span>';
 	//drop location
 	echo '<strong><p>Drop Location</p></strong><br>';
 	echo '<div id="ldish-map" style="width: 450px; height: 300px; border: solid 2px black;"></div>';
 	echo '<button id="geolocateme">Locate Me</button><br>';
	echo '<label>Address: </labek><input value="'.$value3.'" type="text" id="ldishlocation" name="ldishaddress" size="50"><br>';
    echo '<label>Lat: </labek><input type="text" id="latbox" name="latbox" value="'.$value1.'" readonly>';
    echo '<label>Lng: </labek><input type="text" id="lngbox" name="lngbox" value="'.$value2.'" readonly>';

    //opening hours
    include get_template_directory() . '/lib/component/template-parts/opening-hour-field.php'; 
}
 
// add_action('add_meta_boxes','ldish_restaurant_add_meta_box');




function ldish_save_restaurant_data($post_id){
	//check if nonce exist
	if (!isset($_POST['ldish_restaurant_metabox_nonce'])){
		return; //stop
	}
 
	//check if nonce is not generate by wp
	if (!wp_verify_nonce( $_POST['ldish_restaurant_metabox_nonce'], 'ldish_save_restaurant_data' )){
		return;//stop
	}
 
	//check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
		return;//stop
	}
 
	//check user permission
	if (! current_user_can( 'edit_post', $post_id ) ){
		return;
	}
 
	// check if input passing
	if (! isset($_POST['latbox'])){
		return;//stop
	}
 	
 	$specialization = sanitize_text_field( $_POST['ldish_specialization'] );
	$droplat = sanitize_text_field( $_POST['latbox'] );
	$droplng = sanitize_text_field( $_POST['lngbox'] );
 	$ldishaddress = sanitize_text_field( $_POST['ldishaddress'] );
 	$fee = sanitize_text_field( $_POST['ldish_fee'] );
 	$dcover = sanitize_text_field( $_POST['ldish_dcover'] );
 	$wsap = sanitize_text_field( $_POST['wsap_value'] );
 	//opening hours
 	$sund1 = sanitize_text_field( $_POST['_sun1_hour'] );
 	$sund2 = sanitize_text_field( $_POST['_sun2_hour'] );
 	$mond1 = sanitize_text_field( $_POST['_mon1_hour'] );
 	$mond2 = sanitize_text_field( $_POST['_mon2_hour'] );
 	$tued1 = sanitize_text_field( $_POST['_tue1_hour'] );
 	$tued2 = sanitize_text_field( $_POST['_tue2_hour'] );
 	$wedn1 = sanitize_text_field( $_POST['_wed1_hour'] );
 	$wedn2 = sanitize_text_field( $_POST['_wed2_hour'] );
 	$thur1 = sanitize_text_field( $_POST['_thu1_hour'] );
 	$thur2 = sanitize_text_field( $_POST['_thu2_hour'] );
 	$frid1 = sanitize_text_field( $_POST['_fri1_hour'] );
 	$frid2 = sanitize_text_field( $_POST['_fri2_hour'] );
 	$satu1 = sanitize_text_field( $_POST['_sat1_hour'] );
 	$satu2 = sanitize_text_field( $_POST['_sat2_hour'] );


 	
	//save post
	update_post_meta( $post_id, '_specialization_value_key', $specialization );
	update_post_meta( $post_id, '_ldishaddress_value_key', $ldishaddress );
	update_post_meta( $post_id, '_droplat_value_key', $droplat );
	update_post_meta( $post_id, '_droplng_value_key', $droplng );
	update_post_meta( $post_id, '_fee_value_key', $fee );
	update_post_meta( $post_id, '_dcover_value', $dcover );
	update_post_meta( $post_id, '_wsap_value', $wsap );
	//opening hour
	update_post_meta( $post_id, '_sun1_hour', $sund1 );
	update_post_meta( $post_id, '_sun2_hour', $sund2 );
	update_post_meta( $post_id, '_mon1_hour', $mond1 );
	update_post_meta( $post_id, '_mon2_hour', $mond2 );
	update_post_meta( $post_id, '_tue1_hour', $tued1 );
	update_post_meta( $post_id, '_tue2_hour', $tued2 );
	update_post_meta( $post_id, '_wed1_hour', $wedn1 );
	update_post_meta( $post_id, '_wed2_hour', $wedn2 );
	update_post_meta( $post_id, '_thu1_hour', $thur1 );
	update_post_meta( $post_id, '_thu2_hour', $thur2 );
	update_post_meta( $post_id, '_fri1_hour', $frid1 );
	update_post_meta( $post_id, '_fri2_hour', $frid2 );
	update_post_meta( $post_id, '_sat1_hour', $satu1 );
	update_post_meta( $post_id, '_sat2_hour', $satu2 );

	
	
}
 
// add_action('save_post','ldish_save_restaurant_data');

//taxonomies and tag
add_action( 'init', 'ldish_create_dish_taxonomies', 0 );

function ldish_create_dish_taxonomies() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => _x( 'States', 'taxonomy general name', 'lazadish' ),
		'singular_name'     => _x( 'State', 'taxonomy singular name', 'lazadish' ),
		'search_items'      => __( 'Search States', 'lazadish' ),
		'all_items'         => __( 'All States', 'lazadish' ),
		'parent_item'       => __( 'Parent State', 'lazadish' ),
		'parent_item_colon' => __( 'Parent State:', 'lazadish' ),
		'edit_item'         => __( 'Edit State', 'lazadish' ),
		'update_item'       => __( 'Update State', 'lazadish' ),
		'add_new_item'      => __( 'Add New State', 'lazadish' ),
		'new_item_name'     => __( 'New State Name', 'lazadish' ),
		'menu_name'         => __( 'State', 'lazadish' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'foodstate' ),
	);

	register_taxonomy( 'ldish_state', array( 'ldish_restaurant' ), $args );

	// Add new taxonomy, NOT hierarchical (like tags)
	$labels = array(
		'name'                       => _x( 'Foods', 'taxonomy general name', 'lazadish' ),
		'singular_name'              => _x( 'Food', 'taxonomy singular name', 'lazadish' ),
		'search_items'               => __( 'Search Foods', 'lazadish' ),
		'popular_items'              => __( 'Popular Foods', 'lazadish' ),
		'all_items'                  => __( 'All Foods', 'lazadish' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Food', 'lazadish' ),
		'update_item'                => __( 'Update Food', 'lazadish' ),
		'add_new_item'               => __( 'Add New Food', 'lazadish' ),
		'new_item_name'              => __( 'New Food Name', 'lazadish' ),
		'separate_items_with_commas' => __( 'Separate Foods with commas', 'lazadish' ),
		'add_or_remove_items'        => __( 'Add or remove Foods', 'lazadish' ),
		'choose_from_most_used'      => __( 'Choose from the most used Foods', 'lazadish' ),
		'not_found'                  => __( 'No Foods found.', 'lazadish' ),
		'menu_name'                  => __( 'Foods', 'lazadish' ),
	);

	$args = array(
		'hierarchical'          => false,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'food' ),
	);

	register_taxonomy( 'food', 'ldish_restaurant', $args );
}