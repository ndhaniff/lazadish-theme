<?php
function restaurantcpt_frontend_form_register() {
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_ld_';

	$getuser = wp_get_current_user();
	$user = $getuser->user_login;

	$cmb = new_cmb2_box( array(
		'id'           => 'front-end-post-form',
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
add_action( 'cmb2_init', 'restaurantcpt_frontend_form_register' );

function restaurantcpt_frontend_cmb2_get() {
	// Use ID of metabox in bookcpt_frontend_form_register
	$metabox_id = 'front-end-post-form';
	// Post/object ID is not applicable since we're using this form for submission
	$object_id  = 'fake-oject-id';
	// Get CMB2 metabox object
	return cmb2_get_metabox( $metabox_id, $object_id );
}

function restaurantcpt_do_frontend_form_submission_shortcode( $atts = array() ) {

	function ld_enqueue_meta_script(){
		wp_enqueue_script('droplocation', LD_JS_URL .'gmaps/droplocation.js', array('jquery'));
	} add_action('wp_footer','ld_enqueue_meta_script');
	// Get CMB2 metabox object
	$cmb = restaurantcpt_frontend_cmb2_get();
	// Get $cmb object_types
	$post_types = $cmb->prop( 'object_types' );
	// Current user
	$user_id = get_current_user_id();
	// Parse attributes
	$atts = shortcode_atts( array(
		'post_author' => $user_id, // Current user, or admin
		'post_status' => 'publish',
		'post_type'   => 'ldish_restaurant', // Only use first object_type in array
	), $atts, 'restaurant-submission' );
	/*
	 * Let's add these attributes as hidden fields to our cmb form
	 * so that they will be passed through to our form submission
	 */
	foreach ( $atts as $key => $value ) {
		$cmb->add_hidden_field( array(
			'field_args'  => array(
				'id'    => "atts[$key]",
				'type'  => 'hidden',
				'default' => $value,
			),
		) );
	}
	// Initiate our output variable
	$output = '';
	// Get any submission errors
	if ( ( $error = $cmb->prop( 'submission_error' ) ) && is_wp_error( $error ) ) {
		// If there was an error with the submission, add it to our ouput.
		$output .= '<h3>' . sprintf( __( 'There was an error in the submission: %s', 'wpsessions' ), '<strong>'. $error->get_error_message() .'</strong>' ) . '</h3>';
	}
	// If the post was submitted successfully, notify the user.
	if ( isset( $_GET['post_submitted'] ) && ( $post = get_post( absint( $_GET['post_submitted'] ) ) ) ) {
		// Get submitter's name
		$name = get_post_meta( $post->ID, '_ld_owner', 1 );
		$name = $name ? ' '. $name : '';
		// Add notice of submission to our output
		$output .= '<h3>' . sprintf( __( 'Thank you%s, your restaurant has been submitted and is pending review by a site administrator.', 'wpsessions' ), esc_html( $name ) ) . '</h3>';
	}
	// Get our form
	$output .= cmb2_get_metabox_form( $cmb, 'fake-oject-id', array( 'save_button' => __( 'Add Restaurant', 'wpsessions' ) ) );
	return $output;
}
add_shortcode( 'restaurant-submission', 'restaurantcpt_do_frontend_form_submission_shortcode' );

function restaurantcpt_handle_frontend_new_post_form_submission() {
	// If no form submission, bail
	if ( empty( $_POST ) || ! isset( $_POST['submit-cmb'], $_POST['object_id'] ) ) {
		return false;
	}
	// Get CMB2 metabox object
	$cmb = restaurantcpt_frontend_cmb2_get();
	$post_data = array();
	// Get our shortcode attributes and set them as our initial post_data args
	if ( isset( $_POST['atts'] ) ) {
		foreach ( (array) $_POST['atts'] as $key => $value ) {
			$post_data[ $key ] = sanitize_text_field( $value );
		}
		unset( $_POST['atts'] );
	}
	// Check security nonce
	if ( ! isset( $_POST[ $cmb->nonce() ] ) || ! wp_verify_nonce( $_POST[ $cmb->nonce() ], $cmb->nonce() ) ) {
		return $cmb->prop( 'submission_error', new WP_Error( 'security_fail', __( 'Security check failed.' ) ) );
	}
	// Check title submitted
	if ( empty( $_POST['submitted_post_title'] ) ) {
		return $cmb->prop( 'submission_error', new WP_Error( 'post_data_missing', __( 'New post requires a title.' ) ) );
	}
	// And that the title is not the default title
	if ( $cmb->get_field( 'submitted_post_title' )->default() == $_POST['submitted_post_title'] ) {
		return $cmb->prop( 'submission_error', new WP_Error( 'post_data_missing', __( 'Please enter a new title.' ) ) );
	}
	/**
	 * Fetch sanitized values
	 */
	$sanitized_values = $cmb->get_sanitized_values( $_POST );
	// Set our post data arguments
	$post_data['post_title']   = $sanitized_values['submitted_post_title'];
	unset( $sanitized_values['submitted_post_title'] );
	$post_data['post_content'] = $sanitized_values['submitted_post_content'];
	unset( $sanitized_values['submitted_post_content'] );	
	$post_data['post_type'] = 'ldish_restaurant';
	$post_data['post_status'] = 'publish';
	// Create the new post
	$new_submission_id = wp_insert_post( $post_data, true );
	
	// If we hit a snag, update the user
	if ( is_wp_error( $new_submission_id ) ) {
		return $cmb->prop( 'submission_error', $new_submission_id );
	}
	/**
	 * Other than post_type and post_status, we want
	 * our uploaded attachment post to have the same post-data
	 */
	unset( $post_data['post_type'] );
	unset( $post_data['post_status'] );
	// Try to upload the featured image
	$img_id = restaurantcpt_frontend_form_photo_upload( $new_submission_id, $post_data );
	// If our photo upload was successful, set the featured image
	if ( $img_id && ! is_wp_error( $img_id ) ) {
		set_post_thumbnail( $new_submission_id, $img_id );
	}
	// Loop through remaining (sanitized) data, and save to post-meta
	foreach ( $sanitized_values as $key => $value ) {
		update_post_meta( $new_submission_id, $key, $value );
	}
	//wp_redirect( home_url() . '/chef-dashboard' );
	wp_redirect( bloginfo('url') . '/chef-dashboard?ref=restaurant-submitted' );
	exit;
}
add_action( 'cmb2_after_init', 'restaurantcpt_handle_frontend_new_post_form_submission' );

function restaurantcpt_frontend_form_photo_upload( $post_id, $attachment_post_data = array() ) {
	// Make sure the right files were submitted
	if (
		empty( $_FILES )
		|| ! isset( $_FILES['submitted_post_thumbnail'] )
		|| isset( $_FILES['submitted_post_thumbnail']['error'] ) && 0 !== $_FILES['submitted_post_thumbnail']['error']
	) {
		return;
	}
	// Filter out empty array values
	$files = array_filter( $_FILES['submitted_post_thumbnail'] );
	// Make sure files were submitted at all
	if ( empty( $files ) ) {
		return;
	}
	// Make sure to include the WordPress media uploader API if it's not (front-end)
	if ( ! function_exists( 'media_handle_upload' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );
	}
	// Upload the file and send back the attachment post ID
	return media_handle_upload( 'submitted_post_thumbnail', $post_id, $attachment_post_data );
}