<?php
/**
 * Created by PhpStorm.
 * Date: 10/12/2018
 * Time: 15:54
 */
function cplat_robo_adviser_post_type() {
	$admins = get_role( 'administrator' );

	$admins->add_cap( 'edit_robo_adviser' );
	$admins->add_cap( 'edit_robo_advisers' );
	$admins->add_cap( 'edit_other_robo_advisers' );
	$admins->add_cap( 'publish_robo_advisers' );
	$admins->add_cap( 'read_robo_adviser' );
	$admins->add_cap( 'read_private_robo_advisers' );
	$admins->add_cap( 'delete_robo_adviser' );
	$robo_editor = add_role( Robo_Calculator::ROLE, 'Robo editor', [
		'edit_robo_advisers',
		'read_robo_advisers'
	] );
	$labels      = array(
		'name'               => _x( 'Robo advisers', 'Post Type General Name', 'cplat' ),
		'singular_name'      => _x( 'Robo adviser', 'Post Type Singular Name', 'cplat' ),
		'menu_name'          => __( 'Robo', 'cplat' ),
		'parent_item_colon'  => __( 'Robo adviser:', 'cplat' ),
		'all_items'          => __( 'All Robo advisers', 'cplat' ),
		'view_item'          => __( 'View Robo adviser data', 'cplat' ),
		'add_new_item'       => __( 'Add New Robo adviser data', 'cplat' ),
		'add_new'            => __( 'New Robo adviser', 'cplat' ),
		'edit_item'          => __( 'Edit Robo adviser', 'cplat' ),
		'update_item'        => __( 'Update Robo adviser', 'cplat' ),
		'search_items'       => __( 'Search Robo advisers', 'cplat' ),
		'not_found'          => __( 'No Robo adviser data found', 'cplat' ),
		'not_found_in_trash' => __( 'No Robo adviser data found in Trash', 'cplat' ),
	);

	$args = array(
		'label'               => __( 'Robo-adviser', 'cplat' ),
		'description'         => __( 'Robo-adviser information pages', 'cplat' ),
		'labels'              => $labels,
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => false,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-images-alt2',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'query_var'           => 'robo',
		'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'capabilities'        => array(
			'edit_post'          => 'edit_robo_adviser',
			'edit_posts'         => 'edit_robo_advisers',
			'edit_others_posts'  => 'edit_other_robo_advisers',
			'publish_posts'      => 'publish_robo_advisers',
			'read_post'          => 'read_robo_advisers',
			'read_private_posts' => 'read_private_robo_advisers',
			'delete_post'        => 'delete_robo_advisers'
		),
		'map_meta_cap'        => true,
	);
	register_post_type( 'robo-adviser', $args );

}

add_action( 'init', 'cplat_robo_adviser_post_type' );


/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function robo_save_meta_box_data( $post_id ) {

	// Check the user's permissions.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}

	// Prevent quick edit from clearing custom fields
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return $post_id;
	}

	if ( isset( $_POST['post_type'] ) && 'robo-adviser' == $_POST['post_type'] ) {
		if ( $_POST['post_status'] == 'auto-draft' ) {
			return $post_id;
		}

		if ( ! current_user_can( 'edit_robo_adviser', $post_id ) ) {
			return $post_id;
		}


		if ( isset( $_POST['post_title'] ) ) {

			global $robo_api_post;
			if ( empty( $robo_api_post ) ) {
				$robo_api_post = new ROBO_API( null, [] );
			}
			$data = $robo_api_post->get_values();

			$clean_data = $robo_api_post->sanitize_data( $data );

			$robo = $robo_api_post->robo_data_exist( $post_id );
			if ( ! empty( $robo ) ) {
				$update = $robo_api_post->update_robo_data( $clean_data, $post_id );
			} else {
				$update = $robo_api_post->save_robo_data( $clean_data );

			}

			update_post_meta( $post_id, Robo_Calculator::POST_META_KEY, $clean_data );
		}
	}
}

function cmb2_robo_edit_metabox() {
	global $robo_api;
	if ( empty( $robo_api ) ) {
		$robo_api = new ROBO_API();
	}
	/*-----------------------------------------------------------------------------------*/
	/*	Research, tools and information
	/*-----------------------------------------------------------------------------------*/
	$prefix   = "robo";
	$cmb_info = new_cmb2_box( array(
		'id'           => $prefix . '_info_metabox',
		'title'        => __( 'Robo Details', 'cmb2' ),
		'object_types' => array( 'robo-adviser' ), // Post type

	) );

	$group_field_id = $cmb_info->add_field( array(
		'id'          => 'robo_details',
		'type'        => 'group',
		'description' => __( '', 'cmb2' ),
		'repeatable'  => false, // use false if you want non-repeatable group
	) );

// Id's for group's fields only need to be unique for the group. Prefix is not needed.
	$cmb_info->add_group_field( $group_field_id, array(
		'name' => 'Website',
		'id'   => ROBO_API::FIELD_WEBSITE,
		'type' => 'text',
		// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );
	$cmb_info->add_group_field( $group_field_id, array(
		'name' => 'Referral',
		'id'   => ROBO_API::FIELD_REFERRAL,
		'type' => 'text',
		// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );
	$cmb_info->add_group_field( $group_field_id, array(
		'name'    => __( 'Recommended ', 'cmb2' ),
		'desc'    => __( '', 'cmb2' ),
		'id'      => ROBO_API::FIELD_RECOMMENDED,
		'type'    => 'radio',
		'options' => array(
			'1' => 'Yes',
			'0' => 'No'
		)
	) );

	$cmb_products_info = new_cmb2_box( array(
		'id'           => $prefix . '_product_info_metabox',
		'title'        => __( 'Products and Wrappers', 'cmb2' ),
		'object_types' => array( 'robo-adviser' ), // Post type

	) );

	$group_field_id = $cmb_products_info->add_field( array(
		'id'          => 'product_details',
		'type'        => 'group',
		'description' => __( '', 'cmb2' ),
		'repeatable'  => false, // use false if you want non-repeatable group
	) );
	$cmb_products_info->add_group_field( $group_field_id, array(
		'name' => __( 'General Investments', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . '_available_products_gia',
		'type' => 'checkbox'

	) );
	$cmb_products_info->add_group_field( $group_field_id, array(
		'name'            => 'Gia Notes',
		'description'     => '',
		'id'              => 'gia_notes',
		'type'            => 'textarea_small',
		'sanitization_cb' => 'robo_html_sanitize'
	) );
	$cmb_products_info->add_group_field( $group_field_id, array(
		'name' => __( 'ISAS', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . '_available_products_isa',
		'type' => 'checkbox'

	) );

	$cmb_products_info->add_group_field( $group_field_id, array(
		'name'            => 'ISAS Notes',
		'description'     => '',
		'id'              => 'isa_notes',
		'type'            => 'textarea_small',
		'sanitization_cb' => 'robo_html_sanitize'
	) );
	$cmb_products_info->add_group_field( $group_field_id, array(
		'name' => __( 'Pensions & SIPPs', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . '_available_products_sipp',
		'type' => 'checkbox'
	) );
	$cmb_products_info->add_group_field( $group_field_id, array(
		'name'            => 'Pensions & SIPPs Notes',
		'description'     => '',
		'id'              => 'sipp_notes',
		'type'            => 'textarea_small',
		'sanitization_cb' => 'robo_html_sanitize'
	) );
	$cmb_products_info->add_group_field( $group_field_id, array(
		'name' => __( 'JISAS', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . '_available_products_jisa',
		'type' => 'checkbox'
	) );
	$cmb_products_info->add_group_field( $group_field_id, array(
		'name'            => 'JISAS Notes',
		'description'     => '',
		'id'              => 'jisa_notes',
		'type'            => 'textarea_small',
		'sanitization_cb' => 'robo_html_sanitize'
	) );
	$cmb_products_info->add_group_field( $group_field_id, array(
		'name' => __( 'JSIPPs', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . '_available_products_jsipp',
		'type' => 'checkbox'
	) );
	$cmb_products_info->add_group_field( $group_field_id, array(
		'name'            => 'JSIPPs Notes',
		'description'     => '',
		'id'              => 'jsipp_notes',
		'type'            => 'textarea_small',
		'sanitization_cb' => 'robo_html_sanitize'
	) );
	$cmb_products_info->add_group_field( $group_field_id, array(
		'name' => __( 'Lifetime ISAS', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . '_available_products_lisa',
		'type' => 'checkbox'
	) );
	$cmb_products_info->add_group_field( $group_field_id, array(
		'name'            => 'Lifetime ISAS Notes',
		'description'     => '',
		'id'              => 'lisa_notes',
		'type'            => 'textarea_small',
		'sanitization_cb' => 'robo_html_sanitize'
	) );
	$cmb_products_info->add_group_field( $group_field_id, array(
		'name' => __( 'Others', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . '_available_products_other',
		'type' => 'checkbox'
	) );
	$cmb_products_info->add_group_field( $group_field_id, array(
		'name'            => 'Others Notes',
		'description'     => '',
		'id'              => 'other_notes',
		'type'            => 'textarea_small',
		'sanitization_cb' => 'robo_html_sanitize'
	) );

	/*RSPL Task#57 starts*/
    $monthly_investment_details_info = new_cmb2_box( array(
        'id'           => $prefix . '_info_monthly_investment_details_metabox',
        'title'        => __( 'Minimum Investments', 'cmb2' ),
        'object_types' => array( 'robo-adviser' ), // Post type

    ) );
    $group_field_id = $monthly_investment_details_info->add_field( array(
        'id'          => 'product_details_monthly',
        'type'        => 'group',
        'description' => __( '', 'cmb2' ),
        'repeatable'  => false, // use false if you want non-repeatable group
    ) );
    // Id's for group's fields only need to be unique for the group. Prefix is not needed.
    $monthly_investment_details_info->add_group_field( $group_field_id, array(
        'name' => __( 'Lump-sum', 'cmb2' ),
        'desc' => __( '', 'cmb2' ),
        'id'   => 'monthly_investment_lump_sum',
        'type' => 'checkbox'
    ) );
    $monthly_investment_details_info->add_group_field( $group_field_id, array(
        'name'            => 'Lump-sum Notes',
        'description'     => '',
        'id'              => 'monthly_investment_lump_sum_notes',
        'type'            => 'textarea_small',
        'sanitization_cb' => 'robo_html_sanitize'
    ) );
    $monthly_investment_details_info->add_group_field( $group_field_id, array(
        'name' => __( 'Top-ups', 'cmb2' ),
        'desc' => __( '', 'cmb2' ),
        'id'   => 'monthly_investment_top_up',
        'type' => 'checkbox'
    ) );
    $monthly_investment_details_info->add_group_field( $group_field_id, array(
        'name'            => 'Top-ups Notes',
        'description'     => '',
        'id'              => 'monthly_investment_top_up_notes',
        'type'            => 'textarea_small',
        'sanitization_cb' => 'robo_html_sanitize'
    ) );
    $monthly_investment_details_info->add_group_field( $group_field_id, array(
        'name' => __( 'Monthly', 'cmb2' ),
        'desc' => __( '', 'cmb2' ),
        'id'   => 'monthly_investment_monthly',
        'type' => 'checkbox'
    ) );
    $monthly_investment_details_info->add_group_field( $group_field_id, array(
        'name'            => 'Monthly Notes',
        'description'     => '',
        'id'              => 'monthly_investment_monthly_notes',
        'type'            => 'textarea_small',
        'sanitization_cb' => 'robo_html_sanitize'
    ) );
	/*RSPL Task#57 ends*/

	$cmb_research_tools_investments = new_cmb2_box( array(
		'id'           => $prefix . '_research_tools_metabox',
		'title'        => __( 'Research, Tools and Information', 'cmb2' ),
		'object_types' => array( 'robo-adviser', ), // Post type

	) );

	$group_field_id = $cmb_research_tools_investments->add_field( array(
		'id'          => 'research_details',
		'type'        => 'group',
		'description' => __( 'Items', 'cmb2' ),
		// 'repeatable'  => false, // use false if you want non-repeatable group
		'options'     => array(
			'group_title'   => __( 'Entry {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
			'add_button'    => __( 'Add Another Entry', 'cmb2' ),
			'remove_button' => __( 'Remove Entry', 'cmb2' ),
			'sortable'      => true, // beta

		),
	) );

// Id's for group's fields only need to be unique for the group. Prefix is not needed.
    //RSPL Task#66
	$cmb_research_tools_investments->add_group_field( $group_field_id, array(
		'name' => 'Entry Label',
		'id'   => $prefix . '_research_label',
		'type' => 'text',
        'sanitization_cb' => 'robo_html_sanitize',
		// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );

    //RSPL Task#66
	$cmb_research_tools_investments->add_group_field( $group_field_id, array(
		'name'        => 'Entry Data',
		'description' => '',
		'id'          => $prefix . '_research_tools_data',
		'type'        => 'textarea_small',
        'sanitization_cb' => 'robo_html_sanitize',
	) );

	/*-----------------------------------------------------------------------------------*/
	/*	Table of charges
	/*-----------------------------------------------------------------------------------*/

	$cmb_charges = new_cmb2_box( array(
		'id'           => $prefix . '_charges_metabox',
		'title'        => __( 'Robo Charges', 'cmb2' ),
		'object_types' => array( 'robo-adviser', ), // Post type

	) );

	$charges_group_field_id = $cmb_charges->add_field( array(
		'id'          => 'charges_details',
		'type'        => 'group',
		'description' => __( 'Charges Items', 'cmb2' ),
		// 'repeatable'  => false, // use false if you want non-repeatable group
		'options'     => array(
			'group_title'   => __( 'Row Entry {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
			'add_button'    => __( 'Add Another Entry', 'cmb2' ),
			'remove_button' => __( 'Remove Entry', 'cmb2' ),
			'sortable'      => true, // beta
			// 'closed'     => true, // true to have the groups closed by default
		),
	) );

// Id's for group's fields only need to be unique for the group. Prefix is not needed.
	$cmb_charges->add_group_field( $charges_group_field_id, array(
		'name' => 'Entry Label',
		'id'   => $prefix . '_charges_label',
		'type' => 'text',
		// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );

// Id's for group's fields only need to be unique for the group. Prefix is not needed.
	$cmb_charges->add_group_field( $charges_group_field_id, array(
		'name' => 'Robo Charges',
		'id'   => $prefix . '_charges',
		'type' => 'textarea_small',
		// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );
// Id's for group's fields only need to be unique for the group. Prefix is not needed.
	$cmb_charges->add_group_field( $charges_group_field_id, array(
		'name' => 'Product Charges',
		'id'   => $prefix . '_product_charges',
		'type' => 'textarea_small',

	) );
// Id's for group's fields only need to be unique for the group. Prefix is not needed.
	$cmb_charges->add_group_field( $charges_group_field_id, array(
		'name' => 'Other Charges',
		'id'   => $prefix . '_other_charges',
		'type' => 'textarea_small',

	) );


	/*-----------------------------------------------------------------------------------*/
	/*	Compare the platform says
	/*-----------------------------------------------------------------------------------*/
	$rating                = new_cmb2_box( array(
		'id'           => $prefix . '_rating_metabox',
		'title'        => __( 'COMPARE THE PLATFORM SAYS', 'cmb2' ),
		'object_types' => array( 'robo-adviser', ), // Post type
		// 'show_on_cb' => 'cplat_show_if_front_page', // function should return a bool value
		'context'      => 'normal',
		'priority'     => 'high',

	) );
	$rating_group_field_id = $rating->add_field( array(
		'id'          => 'ratings_details',
		'type'        => 'group',
		'description' => __( '', 'cmb2' ),
		'repeatable'  => false, // use false if you want non-repeatable group

	) );
	$rating->add_group_field( $rating_group_field_id, array(
		'name' => __( 'Products & Wrappers', 'cmb2' ),

		'id'      => $prefix . '_rating_products',
		'type'    => 'select',
		'options' => array(
			'1' => __( '1', 'cmb2' ),
			'2' => __( '2', 'cmb2' ),
			'3' => __( '3', 'cmb2' ),
			'4' => __( '4', 'cmb2' ),
			'5' => __( '5', 'cmb2' ),
		),
	) );
	$rating->add_group_field( $rating_group_field_id, array(
		'name' => __( 'Products & Wrappers Note', 'cmb2' ),
		'id'   => $prefix . '_rating_products_note',
		'type' => 'text'
	) );

	$rating->add_group_field( $rating_group_field_id, array(
		'name' => __( 'Investments', 'cmb2' ),

		'id'      => $prefix . '_rating_investments',
		'type'    => 'select',
		'options' => array(
			'1' => __( '1', 'cmb2' ),
			'2' => __( '2', 'cmb2' ),
			'3' => __( '3', 'cmb2' ),
			'4' => __( '4', 'cmb2' ),
			'5' => __( '5', 'cmb2' ),
		),
	) );
	$rating->add_group_field( $rating_group_field_id, array(
		'name' => __( 'Investments Note', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . '_rating_investments_note',
		'type' => 'text'
	) );

	$rating->add_group_field( $rating_group_field_id, array(
		'name'    => __( 'Research & Guidance', 'cmb2' ),
		'desc'    => __( '', 'cmb2' ),
		'id'      => $prefix . '_rating_research',
		'type'    => 'select',
		'options' => array(
			'1' => __( '1', 'cmb2' ),
			'2' => __( '2', 'cmb2' ),
			'3' => __( '3', 'cmb2' ),
			'4' => __( '4', 'cmb2' ),
			'5' => __( '5', 'cmb2' ),
		),
	) );
	$rating->add_group_field( $rating_group_field_id, array(
		'name' => __( 'Research & Guidance', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . '_rating_research_note',
		'type' => 'text'
	) );

	$rating->add_group_field( $rating_group_field_id, array(
		'name'    => __( 'Charges', 'cmb2' ),
		'desc'    => __( '', 'cmb2' ),
		'id'      => $prefix . '_rating_charges',
		'type'    => 'select',
		'options' => array(
			'1' => __( '1', 'cmb2' ),
			'2' => __( '2', 'cmb2' ),
			'3' => __( '3', 'cmb2' ),
			'4' => __( '4', 'cmb2' ),
			'5' => __( '5', 'cmb2' ),
		),
	) );
	$rating->add_group_field( $rating_group_field_id, array(
		'name' => __( 'Charges Note', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . '_rating_charges_note',
		'type' => 'text'
	) );


	$rating->add_group_field( $rating_group_field_id, array(
		'name'    => __( 'Overall Service', 'cmb2' ),
		'desc'    => __( '', 'cmb2' ),
		'id'      => $prefix . '_rating_service',
		'type'    => 'select',
		'options' => array(
			'1' => __( '1', 'cmb2' ),
			'2' => __( '2', 'cmb2' ),
			'3' => __( '3', 'cmb2' ),
			'4' => __( '4', 'cmb2' ),
			'5' => __( '5', 'cmb2' ),
		),
	) );
	$rating->add_group_field( $rating_group_field_id, array(
		'name' => __( 'Overall Service Note', 'cmb2' ),
		'desc' => __( '', 'cmb2' ),
		'id'   => $prefix . '_rating_service_note',
		'type' => 'text'
	) );
  
	// $rating->add_group_field( $rating_group_field_id, array(
	// 	'name' => __( 'CTP Comment', 'cmb2' ),
	// 	'desc' => __( 'CTP Comment', 'cmb2' ),
	// 	'id'   => '_robo_platforms_common_comment_desc',
	// 	'type' => 'wysiwyg',
	// ) );

  
  $rating->add_field( array(
    'name' => __( 'CTP Comment', 'cmb2' ),
    'desc' => __( 'CTP Comment', 'cmb2' ),
    'id'   => '_robo_platforms_common_comment_desc',
    'type' => 'wysiwyg',
  ) );

	$rating->add_group_field( $rating_group_field_id, array(
		'name'    => __( 'Average Rating shown on results', 'cmb2' ),
		'desc'    => __( '', 'cmb2' ),
		'id'      => '_robo_ratings',
		'type'    => 'select',
		'options' => array(
			'1' => __( '1', 'cmb2' ),
			'2' => __( '2', 'cmb2' ),
			'3' => __( '3', 'cmb2' ),
			'4' => __( '4', 'cmb2' ),
			'5' => __( '5', 'cmb2' ),
		)
	) );
	$rating->add_group_field( $rating_group_field_id, array(
		'name' => __( 'Platform Comment', 'cmb2' ),
		'desc' => __( 'Platform Comment', 'cmb2' ),
		'id'   => '_robo_platforms_comment_desc',
		'type' => 'textarea_small',
	) );
}

add_filter( 'cmb2_override_charges_details_meta_value', 'robo_charges_override_meta_value', 10, 4 );
function robo_charges_override_meta_value( $data, $object_id, $args, $field ) {
	/** @var ROBO_API $robo_api */
	global $robo_api;
	check_for_global( $object_id );
	$values = $robo_api->get_values();
	if ( $args['field_id'] == 'ratings_details' || $args['field_id'] == 'research_details' || $args['field_id'] == 'charges_details' || $args['field_id'] == 'product_details' || $args['field_id'] == 'product_details_monthly' ) {
		$values = $values[ $args['field_id'] ];
	} else {
		$values = [ $values ];
	}
	// Here, we're pulling from the options table, but you can query from any data source here.
	// If from a custom table, you can use the $object_id to query against.
	return $values;
}

function check_for_global( $robo_id ) {
	global $robo_api;
	if ( empty( $robo_api ) || empty( $robo_api->get_values() ) ) {
		$robo_api  = new ROBO_API();
		$robo_data = $robo_api->get_robo_data( $robo_id );
		$robo      = [];
		if ( $robo_data ) {
			$robo_version_data      = $robo_data['version'];
			$robo_api->version_data = $robo_version_data;

			if ( ! empty( $robo_version_data ) ) {
				$robo_version_data_for_status = $robo_version_data;
				$robo                         = reset( array_filter( $robo_version_data_for_status, function ( $vals ) {
					return $vals['status'] === 1;
				} ) );


				$robo = array_merge( $robo_data['data'], $robo );
			}
			if ( empty( $robo ) ) {
				$robo = $robo_data['data'];
			}
			if ( isset( $robo[ ROBO_API::FIELD_RESEARCH_DETAILS ] ) ) {
				$robo[ ROBO_API::FIELD_RESEARCH_DETAILS ] = json_decode( $robo[ ROBO_API::FIELD_RESEARCH_DETAILS ], true );
			}
			if ( isset( $robo[ ROBO_API::FIELD_CHARGES_DETAILS ] ) ) {
				$robo[ ROBO_API::FIELD_CHARGES_DETAILS ] = json_decode( $robo[ ROBO_API::FIELD_CHARGES_DETAILS ], true );
			}
			if ( isset( $robo[ ROBO_API::FIELD_RATINGS_DETAILS ] ) ) {
				$robo[ ROBO_API::FIELD_RATINGS_DETAILS ] = json_decode( $robo[ ROBO_API::FIELD_RATINGS_DETAILS ], true );
			}
			if ( isset( $robo[ ROBO_API::FIELD_PRODUCT_DETAILS ] ) ) {
				$robo[ ROBO_API::FIELD_PRODUCT_DETAILS ] = json_decode( $robo[ ROBO_API::FIELD_PRODUCT_DETAILS ], true );
			}

		}

		$robo_api->set_values( $robo );
	}

}

function robo_charges_metabox_override_meta_save( $override, $args, $field_args, $field ) {
	// Here, we're storing the data to the options table, but you can store to any data source here.
	// If to a custom table, you can use the $args['id'] as the reference id.
	global $robo_api_post;
	if ( empty( $robo_api_post ) || empty( $robo_api_post->get_values() ) ) {
		$robo_api_post = new ROBO_API( null, [] );
		$values        = [];
	} else {
		$values = $robo_api_post->get_values();
	}
	if ( $args['field_id'] == 'robo_details' ) {
		$values = array_merge( $values, $args['value'][0] );
	} else {
		$values[ $args['field_id'] ] = wp_slash( json_encode( $args['value'] ) );

	}
	$robo_api_post->set_values( $values );
}

add_action( 'cmb2_render_robo_version_list', 'cmb2_render_callback_robo_version_list', 10, 5 );
function cmb2_render_callback_robo_version_list() {
	global $robo_api, $post_id;
	check_for_global( $post_id );

	$robo         = $robo_api->get_values();
	$robo_details = $robo_api->version_data;
	//$value = get_post_meta( $post->ID, Robo_Calculator::POST_META_KEY, true );

	include_once CTP_ROBO_PLUGIN_DIR . 'templates/robo-charges.php';


}

function cmb2_robo_edit_link_metabox() {

	$cmb = new_cmb2_box( array(
		'id'           => 'cmb2_robo_edit_link_metabox',
		'title'        => 'Robo Data',
		'object_types' => array( Robo_Calculator::POST_TYPE ),
	) );


	$cmb->add_field( array(
		'name' => '',
		'id'   => '_cmb2_robo_version',
		'type' => 'robo_version_list',
		'desc' => '',
	) );


}

function cmb2_robo_refresh_metabox() {

	$cmb = new_cmb2_box( array(
		'id'           => 'cmb2_robo_refresh_metabox',
		'title'        => 'Robo Data Refresh',
		'object_types' => array( Robo_Calculator::POST_TYPE ),
	) );


	$cmb->add_field( array(
		'name' => '',
		'id'   => '_cmb2_robo_refresh',
		'type' => 'robo_refresh',
		'desc' => '',
	) );


}

add_action( 'cmb2_render_robo_refresh', 'cmb2_render_callback_robo_refresh', 10, 5 );
function cmb2_render_callback_robo_refresh() {
	global $post_id;

	$url = add_query_arg( array(
		'refresh' => 'true',
		'robo_id' => $post_id
	) );

	echo '<a href="' . $url . '" class="button button-primary button-large">Refresh Data</a>';
}

function robo_refresh_data() {

	if ( isset( $_GET['refresh'], $_GET['robo_id'] ) && $_GET['refresh'] == true ) {
		global $wpdb;
		$robo_id = trim( $_GET['robo_id'] );
		$sql     = "Delete from wp_options where option_name like '%_transient_{$robo_id}robo%'";
		$result  = $wpdb->query( $sql );
		wp_safe_redirect( "post.php?post={$robo_id}&action=edit" );
	}
}

add_filter( 'cmb2_override_charges_details_meta_value', 'robo_charges_override_meta_value', 10, 4 );
add_filter( 'cmb2_override_charges_details_meta_save', 'robo_charges_metabox_override_meta_save', 10, 4 );
add_filter( 'cmb2_override_research_details_meta_value', 'robo_charges_override_meta_value', 10, 4 );
add_filter( 'cmb2_override_research_details_meta_save', 'robo_charges_metabox_override_meta_save', 10, 4 );
add_filter( 'cmb2_override_robo_details_meta_value', 'robo_charges_override_meta_value', 10, 4 );
add_filter( 'cmb2_override_robo_details_meta_save', 'robo_charges_metabox_override_meta_save', 10, 4 );
add_filter( 'cmb2_override_product_details_meta_value', 'robo_charges_override_meta_value', 10, 4 );
add_filter( 'cmb2_override_product_details_meta_save', 'robo_charges_metabox_override_meta_save', 10, 4 );

add_filter( 'cmb2_override_ratings_details_meta_value', 'robo_charges_override_meta_value', 10, 4 );


add_filter( 'cmb2_override_ratings_details_meta_save', 'robo_charges_metabox_override_meta_save', 10, 4 );
add_filter( 'cmb2_override_robo_product_details_meta_value', 'robo_charges_override_meta_value', 10, 4 );
add_filter( 'cmb2_override_robo_product_details_meta_save', 'robo_charges_metabox_override_meta_save', 10, 4 );
add_action( 'cmb2_admin_init', 'cmb2_robo_edit_metabox' );
add_action( 'cmb2_admin_init', 'cmb2_robo_edit_link_metabox' );
add_action( 'cmb2_admin_init', 'cmb2_robo_refresh_metabox' );
add_action( 'cmb2_admin_init', 'robo_refresh_data', 10 );
add_action( 'save_post', 'robo_save_meta_box_data', 999, 2 );
