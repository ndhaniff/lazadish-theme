<?php

function ld_cptrestaurant_enqueue( $hook_suffix ){
    $cpt = 'ldish_restaurant';
 
    if( in_array($hook_suffix, array('post.php', 'post-new.php') ) ){
        $screen = get_current_screen();
 
        if( is_object( $screen ) && $cpt == $screen->post_type ){
 
        function ld_enqueue_meta_script(){
		wp_enqueue_script('droplocation', LD_JS_URL .'gmaps/droplocation.js', array('jquery'));
			} 
		add_action('admin_footer','ld_enqueue_meta_script');
        }
    }
}
 
add_action( 'admin_enqueue_scripts', 'ld_cpt_enqueue');

function restaurantcpt_register_metabox() {
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_ld_';

	$getuser = wp_get_current_user();

	$user = $getuser->user_login;
	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$cmb = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Restaurant Profile', 'lazadish' ),
		'object_types'  => array( 'ldish_restaurant', ), // Post type
		'show_on_cb'    => 'restaurantcpt_show_if_front_page', // function should return a bool value
	) );

	$cmb->add_field( array(
	'name'    => 'Restaurant Logo',
	'desc'    => 'Upload an image or enter an URL.',
	'id'      => $prefix . 'restaurantlogo',
	'type'    => 'file',
	// Optional:
	'options' => array(
		'url' => false, // Hide the text input for the url
	),
	'text'    => array(
		'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
	),
	// query_args are passed to wp.media's library query.
	'query_args' => array(
		'type' => 'application/pdf', // Make library only display PDFs.
	),
	'preview_size' => 'small', // Image size to use when previewing in the admin.
	) );

	$cmb->add_field( array(
		'name'       => __( 'Specialization', 'lazadish' ),
		'id'         => $prefix . 'specialization',
		'type'       => 'text',
	) );

	$cmb->add_field( array(
		'name'       => __( 'Owner', 'lazadish' ),
		'id'         => $prefix . 'owner',
		'type'       => 'text',
		'default'	 => $user,
	) );

	$cmb->add_field( array(
		'name'       => __( 'Phone / Whatapp Number', 'lazadish' ),
		'id'         => $prefix . 'whatapp',
		'type'       => 'text',
		'attributes' => array(
			'pattern' => '^\s*\(?(020[7,8]{1}\)?[ ]?[1-9]{1}[0-9{2}[ ]?[0-9]{4})|(0[1-8]{1}[0-9]{3}\)?[ ]?[1-9]{1}[0-9]{2}[ ]?[0-9]{3})\s*$'
		),	
	) );

	$cmb->add_field( array(
		'name'       => __( 'Drop Location', 'lazadish' ),
		'desc'       => __( 'URL of person who submitted this book (if applicable)', 'lazadish' ),
		'id'         => $prefix . 'map',
		'type'       => 'map',
	) );
}
add_action( 'cmb2_init', 'restaurantcpt_register_metabox' );