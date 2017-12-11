<?php

function ld_get_restaurant_id(){
	$getuser = wp_get_current_user_id();
	$user = $getuser->user_login;
	echo $user;

	$restq = new WP_Query(array(
		'post_type' => 'ldish_restaurant',
		'post_author' => $getuser
	));

	?><pre><?php print_r($restq); ?></pre><?php

	foreach ($restq as $rest ) {
		$rest_id = $rest->ID;
	}
	echo $rest_id;
}

function restauranteditcpt_frontend_form_register() {
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_ld_';

	$getuser = wp_get_current_user();
	$user = $getuser->user_login;

	$cmb = new_cmb2_box( array(
		'id'           => 'front-end-edit-post-form',
		'object_types' => array( 'ldish_restaurant' ),
		'hookup'       => false,
		'save_fields'  => false,
	) );

	$cmb->add_field( array(
	'name'    => 'Restaurant Logo',
	'desc'    => 'Upload an image or enter an URL.',
	'id'      => $prefix . 'restaurantlogo',
	'type'    => 'file',
	'attributes' => array(
		'required' => 'required',
	),
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
		'name'    => __( 'Restaurant Name', 'Lazadish' ),
		'id'      => 'submitted_post_title',
		'type'    => 'text',
		'default' => __( 'New Restaurant', 'Lazadish' ),
		'attributes' => array(
		'required' => 'required',
	),
	) );
	$cmb->add_field( array(
		'name'    => __( 'Description', 'Lazadish' ),
		'id'      => 'submitted_post_content',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 12,
			'media_buttons' => false,
		),
	) );

	$cmb->add_field( array(
		'name'       => __( 'Restaurant Background Image', 'Lazadish' ),
		'id'         => 'submitted_post_thumbnail',
		'type'       => 'text',
		'attributes' => array(
			'type' => 'file', // Let's use a standard file upload field
			'required' => 'required',
		),

	) );
	
	$cmb->add_field( array(
		'name'       => __( 'Specialization', 'lazadish' ),
		'id'         => $prefix . 'specialization',
		'type'       => 'text',
		'attributes' => array(
		'required' => 'required',
	),
	) );

	$cmb->add_field( array(
		'name'       => __( 'Owner', 'lazadish' ),
		'id'         => $prefix . 'owner',
		'type'       => 'text',
		'default'	 => $user,
		'attributes' => array(
			'readonly' => 'readonly'
		)
	) );

	$cmb->add_field( array(
		'name'       => __( 'Phone / Whatapp Number', 'lazadish' ),
		'id'         => $prefix . 'whatapp',
		'type'       => 'text',
		'default'	 => ld_ws_default,
		'attributes' => array(
			'pattern' => '^\s*\(?(020[7,8]{1}\)?[ ]?[1-9]{1}[0-9{2}[ ]?[0-9]{4})|(0[1-8]{1}[0-9]{3}\)?[ ]?[1-9]{1}[0-9]{2}[ ]?[0-9]{3})\s*$',
			'required' => 'required',
		),	
	) );

	$cmb->add_field( array(
		'name'       => __( 'Drop Location', 'lazadish' ),
		'desc'       => __( 'Drop location', 'lazadish' ),
		'id'         => $prefix . 'map',
		'type'       => 'map',
		'attributes' => array(
		'required' => 'required',
	),
	) );
	
}
add_action( 'cmb2_init', 'restauranteditcpt_frontend_form_register' );

ld_get_restaurant_id();

