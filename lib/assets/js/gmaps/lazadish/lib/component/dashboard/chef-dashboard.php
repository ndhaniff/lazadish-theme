<?php  
/**
 * DASHBOARD
 */
add_shortcode( 'add-product','ld_add_product_shortcode');

add_action('cmb2_init','ld_register_product_front_metabox');

function ld_register_product_front_metabox(){
	$prefix = '_ld_';
	$getuser = wp_get_current_user();
	$user = $getuser->user_login;

	$cmb = new_cmb2_box( array(
		'id'            => 'productmetabox',
		'title'         => __( 'Restaurant Product', 'lazadish' ),
		'object_types'  => array( 'product' ), // Post type
		'hookup'       => false,
		'save_fields'  => false,
	) );

	$cmb->add_field( array(
		'name'    => __( 'Product Name', 'Lazadish' ),
		'id'      => 'submitted_post_title',
		'type'    => 'text',
		'default' => __( 'New Product', 'Lazadish' ),
		'attributes' => array(
			"required" => "required",
		),
	) );
	
	$cmb->add_field( array(
		'name'    => __( 'Product Description', 'Lazadish' ),
		'id'      => 'submitted_post_content',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 12,
			'media_buttons' => false,
		),
		'attributes' => array(
			"required" => "required",
		),
	) );

	$cmb->add_field( array(
		'name'     => __( 'Select Category', 'lazadish' ),
		'id'       => $prefix . 'category',
		'type'     => 'taxonomy_select',
		'taxonomy' => 'product_cat', // Taxonomy Slug
		'attributes' => array(
			"required" => "required",
		),
	) );

	$cmb->add_field( array(
		'name'       => __( 'Product Image', 'Lazadish' ),
		'id'         => '_thumbnail',
		'type'       => 'file',
		'attributes' => array(
			"required" => "required",
		),
		'text' => array(
			'add_upload_file_text' => 'Upload Image'
		),
		'options' => array(
		'url' => false, // Hide the text input for the url
		),
		'query_args' => array(
		'type' => 'application/pdf', // Make library only display PDFs.
		),
		'preview_size' => 'large',
	) );

	$cmb->add_field( array(
	'name' => 'Price',
	'desc' => 'Price of the product',
	'id' => '_price',
	'type' => 'text_money',
	'before_field' => 'RM: ', // Replaces default '$'
	'attributes' => array(
			"required" => "required",
		),
) );

	$cmb->add_field( array(
		'name'    => __( 'Spicy Level', 'Lazadish' ),
		'id'      => $prefix . 'spicy_level',
		'type'    => 'text',
		'desc'	  => 'Please rate from 0 to 5',
		'default' => 0,
		'attributes' => array(
			'type' => 'number',
			"min"	=> 0,
			"max"	=> 5,
		),
		'sanitization_cb' => 'sanitize_int',
		'escape_cb' => 'sanitize_int',
	) );

	$cmb->add_field( array(
		'name'    => __( 'Owner', 'Lazadish' ),
		'id'      => $prefix . 'chef_owner',
		'type'    => 'hidden',
		'desc'	  => 'product owner',
		'default' => $user,
	) );

	$cmb->add_field( array(
		'name'    => __( 'Max Pack', 'Lazadish' ),
		'id'      => '_stock',
		'type'    => 'text',
		'desc'	  => 'Please rate from 0 to 5',
		'default' => 0,
		'attributes' => array(
			'type' => 'number',
			"min"	=> 0,
			"max"	=> 999,
		),
		'sanitization_cb' => 'sanitize_int',
		'escape_cb' => 'sanitize_int',
		'attributes' => array(
			"required" => "required",
		),
	) );

	$cmb->add_field( array(
		'name'    => __( 'Sell Start', 'Lazadish' ),
		'id'      => $prefix . 'sell_from',
		'type'    => 'text',
		'desc'	  => 'Start Sell on',
		'attributes' => array(
			"required" => "required",
		),
	) );

	$cmb->add_field( array(
		'name'    => __( 'Sell End', 'Lazadish' ),
		'id'      => $prefix . 'sell_to',
		'type'    => 'text',
		'desc'	  => 'Sell Close on',
		'attributes' => array(
			"required" => "required",
		),
	) );

	$cmb->add_field( array(
		'name'    => __( 'Start Hours', 'Lazadish' ),
		'id'      => $prefix . 'pickup1',
		'type' => 'text_time',
		'time_format' => 'G:ia',
		'attributes' => array(
			"required" => "required",
		),
	) );

	$cmb->add_field( array(
		'name'    => __( 'End Hours', 'Lazadish' ),
		'id'      => $prefix . 'pickup2',
		'type' => 'text_time',
		'time_format' => 'G:ia',
		'attributes' => array(
			"required" => "required",
		),
	) );

	$cmb->add_field( array(
	'name' => 'Last Order Time',
	'id' => $prefix .'last_Date_Time',
	'type' => 'text_time',
	'time_format' => 'G:ia',
	'attributes' => array(
			"required" => "required",
		),
	) );

	$cmb->add_field( array(
	'name'    => 'Days Open',
	'desc'    => 'Opening Days',
	'id'      => $prefix .'last_Date_Days',
	'type'    => 'multicheck_inline',
	'options' => array(
		'sun' => 'sun',
		'mon' => 'mon',
		'tue' => 'tue',
		'wed' => 'wed',
		'thu' => 'thu',
		'fri' => 'fri',
		'sat' => 'sat',
	),
) );

$cmb->add_field( array(
		'name'    => __( 'Additional Note', 'Lazadish' ),
		'id'      => $prefix . 'note',
		'type'    => 'text',
		'desc'	  => 'additional notes',
		'attributes' => array(
			"required" => "required",
		),
	) );

}

add_action('cmb2_init','productcpt_backend_cmb');

function productcpt_backend_cmb(){
	$prefix = '_ld_';

	$cmb = new_cmb2_box( array(
		'id'            => 'product_backend_metabox',
		'title'         => __( 'additional info', 'lazadish' ),
		'object_types'  => array( 'product' ), // Post type
	) );

	$cmb->add_field( array(
	'name' => 'Last Date Time',
	'id' => $prefix .'last_Date_Time',
	'type' => 'text_time',
	'time_format' => 'G:ia',
	'attributes' => array(
			"required" => "required",
		),
	) );

	$cmb->add_field( array(
	'name'    => 'Days Open',
	'desc'    => 'Opening Days',
	'id'      => $prefix .'last_Date_Days',
	'type'    => 'multicheck_inline',
	'options' => array(
		'sun' => 'sun',
		'mon' => 'mon',
		'tue' => 'tue',
		'wed' => 'wed',
		'thu' => 'thu',
		'fri' => 'fri',
		'sat' => 'sat',
	),
) );

$cmb->add_field( array(
		'name'    => __( 'Additional Note', 'Lazadish' ),
		'id'      => $prefix . 'note',
		'type'    => 'text',
		'desc'	  => 'additional notes',
		'attributes' => array(
			"required" => "required",
		),
	) );
}

function productcpt_frontend_cmb2_get() {
	// Use ID of metabox in bookcpt_frontend_form_register
	$metabox_id = 'productmetabox';
	// Post/object ID is not applicable since we're using this form for submission
	$object_id  = 'fake-oject-id';
	// Get CMB2 metabox object
	return cmb2_get_metabox( $metabox_id, $object_id );
}

function ld_add_product_shortcode(){

	function ld_enqueue_meta_script(){
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('droplocation', LD_JS_URL .'woocommerce/product-dashboard.js', array('jquery'));
	} add_action('wp_footer','ld_enqueue_meta_script');

	$cmb = productcpt_frontend_cmb2_get();
	// Get $cmb object_types
	$post_types = $cmb->prop( 'object_types' );
	// Current user
	$user_id = get_current_user_id();
	// Parse attributes
	$atts = shortcode_atts( array(
		'post_author' => $user_id ? $user_id : 1, // Current user, or admin
		'post_status' => 'publish',
		'post_type'   => reset( $post_types ), // Only use first object_type in array
	), $atts, 'add-product' );

	foreach ( $atts as $key => $value ) {
		$cmb->add_hidden_field( array(
			'field_args'  => array(
				'id'    => "atts[$key]",
				'type'  => 'hidden',
				'default' => $value,
			),
		) );
	}

	$output = '';
	// Get any submission errors
	if ( ( $error = $cmb->prop( 'submission_error' ) ) && is_wp_error( $error ) ) {
		// If there was an error with the submission, add it to our ouput.
		$output .= '<h3>' . sprintf( __( '<p class="text-danger">There was an error in the submission: %s</p>', 'wpsessions' ), '<strong>'. $error->get_error_message() .'</strong>' ) . '</h3>';
	}
	// If the post was submitted successfully, notify the user.
	if ( isset( $_GET['post_submitted'] ) && ( $post = get_post( absint( $_GET['post_submitted'] ) ) ) ) {
		// Get submitter's name
		$name = get_post_meta( $post->ID, '_ld_chef_owner', 1 );
		$name = $name ? ' '. $name : '';
		// Add notice of submission to our output
		$output .= '<h3>' . sprintf( __( '<p class="text-success">Thank you%s, your product has been submitted and is pending review by a site administrator.</p>', 'wpsessions' ), esc_html( $name ) ) . '</h3><a href="'.home_url() . '/chef-dashboard'.'">Back</a><style>.cmb2-wrap.form-table{display:none !important;}<style>';
	}
	// Get our form
	$output .= cmb2_get_metabox_form( $cmb, 'fake-oject-id', array( 'save_button' => __( 'Submit Product', 'wpsessions' ) ) );
	return $output;
}

function sanitize_int( $value, $field_args, $field ) {
	// Don't keep anything that's not numeric
	if ( ! is_numeric( $value ) ) {
		$sanitized_value = '';
	} else {
		// Ok, let's clean it up.
		$sanitized_value = absint( $value );
	}
	return $sanitized_value;
}

function productcpt_handle_frontend_new_post_form_submission($atts = array()) {
	// If no form submission, bail
	if ( empty( $_POST ) || ! isset( $_POST['submit-cmb'], $_POST['object_id'] ) ) {
		return false;
	}
	// Get CMB2 metabox object
	$cmb = productcpt_frontend_cmb2_get();
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
		return $cmb->prop( 'submission_error', new WP_Error( 'post_data_missing', __( '<p class="text-danger">New product requires a name.</p>' ) ) );
	}
	// And that the title is not the default title
	if ( $cmb->get_field( 'submitted_post_title' )->default() == $_POST['submitted_post_title'] ) {
		return $cmb->prop( 'submission_error', new WP_Error( 'post_data_missing', __( '<p class="text-danger">Please enter a new product name.</p>' ) ) );
	}
	/**
	 * Fetch sanitized values
	 */
	$sanitized_values = $cmb->get_sanitized_values( $_POST );
	// Set our post data arguments
	$post_data['post_title']   = $sanitized_values['submitted_post_title'];
	unset( $sanitized_values['submitted_post_title'] );
	$post_data['post_excerpt'] = $sanitized_values['submitted_post_content'];
	unset( $sanitized_values['submitted_post_content'] );
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
	$img_id = productcpt_frontend_form_photo_upload( $new_submission_id, $post_data );
	// If our photo upload was successful, set the featured image
	if ( $img_id && ! is_wp_error( $img_id ) ) {
		set_post_thumbnail( $new_submission_id, $img_id );
	}
	// Loop through remaining (sanitized) data, and save to post-meta
	foreach ( $sanitized_values as $key => $value ) {
		update_post_meta( $new_submission_id, $key, $value );
	}

	if ( ! empty( $_POST['_ld_category'] ) ) {
		$terms = array();
		foreach ( (array) $_POST['_ld_category'] as $cat_slug ) {
			if ( $term = get_term_by( 'slug', $cat_slug, 'product_cat' ) ) {
				$terms[] = $term->term_id;
			}
		}
		if ( ! empty( $terms ) ) {
			wp_set_post_terms( $new_submission_id, $terms, 'product_cat' );
		}
	}

	wp_redirect( bloginfo('url') . '/product-submitted' );
	exit;
}
add_action( 'cmb2_after_init', 'productcpt_handle_frontend_new_post_form_submission' );

function productcpt_frontend_form_photo_upload( $post_id, $attachment_post_data = array() ) {
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

