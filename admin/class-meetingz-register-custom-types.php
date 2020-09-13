<?php
/**
 * Registration of necessary components for the plugin.
 *
 * @link       https://meetingz.ir
 * @since      3.0.0
 *
 * @package    Meetingz
 * @subpackage Meetingz/admin
 */

/**
 * Registration of necessary components for the plugin.
 *
 * Registers rooms, room categories, and metaboxes for the rooms.
 *
 * @package    Meetingz
 * @subpackage Meetingz/admin
 * @author     EST  <info@meetingz.ir>
 */
class Meetingz_Register_Custom_Types {

	/**
	 * Register room as custom post.
	 *
	 * @since   3.0.0
	 */
	public function mtz_room_as_post_type() {
		register_post_type(
			'mtz-room',
			array(
				'public'          => true,
				'show_ui'         => true,
				'labels'          => array(
					'name'                     => __( 'Rooms', 'meetingz' ),
					'add_new'                  => __( 'Add New', 'meetingz' ),
					'add_new_item'             => __( 'Add New Room', 'meetingz' ),
					'edit_item'                => __( 'Edit Room', 'meetingz' ),
					'new_item'                 => __( 'New Room', 'meetingz' ),
					'view_item'                => __( 'View Room', 'meetingz' ),
					'view_items'               => __( 'View Rooms', 'meetingz' ),
					'search_items'             => __( 'Search Rooms', 'meetingz' ),
					'not_found'                => __( 'No rooms found', 'meetingz' ),
					'not_found_in_trash'       => __( 'No rooms found in trash', 'meetingz' ),
					'all_items'                => __( 'All Rooms', 'meetingz' ),
					'archives'                 => __( 'Room Archives', 'meetingz' ),
					'attributes'               => __( 'Room Attributes', 'meetingz' ),
					'insert_into_item'         => __( 'Insert into room', 'meetingz' ),
					'uploaded_to_this_item'    => __( 'Uploaded to this room', 'meetingz' ),
					'filter_items_list'        => __( 'Filter rooms list', 'meetingz' ),
					'items_list_navigation'    => __( 'Rooms list navigation', 'meetingz' ),
					'items_list'               => __( 'Rooms list', 'meetingz' ),
					'item_published'           => __( 'Room published', 'meetingz' ),
					'item_published_privately' => __( 'Room published privately', 'meetingz' ),
					'item_reverted_to_draft'   => __( 'Room reverted to draft', 'meetingz' ),
					'item_scheduled'           => __( 'Room scheduled', 'meetingz' ),
					'item_updated'             => __( 'Room updated', 'meetingz' ),
				),
				'taxonomies'      => array( 'mtz-room-category' ),
				'capability_type' => 'mtz_room',
				'has_archive'     => true,
				'supports'        => array( 'title', 'editor' ),
				'rewrite'         => array( 'slug' => 'mtz-room' ),
				'show_in_menu'    => 'mtz_room',
				'map_meta_cap'    => true,
				// Enables block editing in the rooms editor.
				'show_in_rest'    => true,
				'supports'        => array( 'title', 'editor', 'author', 'thumbnail', 'permalink' ),
			)
		);
	}

	/**
	 * Register category as custom taxonomy.
	 *
	 * @since   3.0.0
	 */
	public function mtz_room_category_as_taxonomy_type() {
		register_taxonomy(
			'mtz-room-category',
			array( 'mtz-room' ),
			array(
				'labels'       => array(
					'name'          => __( 'Categories' ),
					'singular_name' => __( 'Category' ),
				),
				'hierarchical' => true,
				'query_var'    => true,
				'show_in_ui'   => true,
				'show_in_menu' => 'mtz_room',
				'show_in_rest' => true,
			)
		);
	}

	/**
	 * Create moderator and viewer code metaboxes on room creation and edit.
	 *
	 * @since   3.0.0
	 */
	public function register_room_code_metaboxes() {
		add_meta_box( 'mtz-moderator-code', __( 'Moderator Code', 'meetingz' ), array( $this, 'display_moderator_code_metabox' ), 'mtz-room' );
		add_meta_box( 'mtz-viewer-code', __( 'Viewer Code', 'meetingz' ), array( $this, 'display_viewer_code_metabox' ), 'mtz-room' );
	}

	/**
	 * Show recordable option in room creation to users who have the corresponding capability.
	 *
	 * @since   3.0.0
	 */
	public function register_record_room_metabox() {
		if ( current_user_can( 'create_recordable_mtz_room' ) ) {
			add_meta_box( 'mtz-room-recordable', __( 'Recordable', 'meetingz' ), array( $this, 'display_allow_record_metabox' ), 'mtz-room' );
		}
	}

	/**
	 * Show wait for moderator option in room creation.
	 *
	 * @since   3.0.0
	 */
	public function register_wait_for_moderator_metabox() {
		add_meta_box( 'mtz-room-wait-for-moderator', __( 'Wait for Moderator', 'meetingz' ), array( $this, 'display_wait_for_mod_metabox' ), 'mtz-room' );
	}

	/**
	 * Display moderator code metabox.
	 *
	 * @since   3.0.0
	 *
	 * @param   Object $object     The object that has the room ID.
	 */
	public function display_moderator_code_metabox( $object ) {
		$entry_code       = Meetingz_Admin_Helper::generate_random_code();
		$entry_code_label = __( 'Moderator Code', 'meetingz' );
		$entry_code_name  = 'mtz-moderator-code';
		$existing_value   = get_post_meta( $object->ID, 'mtz-room-moderator-code', true );
		wp_nonce_field( 'mtz-room-moderator-code-nonce', 'mtz-room-moderator-code-nonce' );
		require 'partials/meetingz-room-code-metabox-display.php';
	}

	/**
	 * Display viewer code metabox.
	 *
	 * @since   3.0.0
	 *
	 * @param   Object $object     The object that has the room ID.
	 */
	public function display_viewer_code_metabox( $object ) {
		$entry_code       = Meetingz_Admin_Helper::generate_random_code();
		$entry_code_label = __( 'Viewer Code', 'meetingz' );
		$entry_code_name  = 'mtz-viewer-code';
		$existing_value   = get_post_meta( $object->ID, 'mtz-room-viewer-code', true );
		wp_nonce_field( 'mtz-room-viewer-code-nonce', 'mtz-room-viewer-code-nonce' );
		require 'partials/meetingz-room-code-metabox-display.php';
	}

	/**
	 * Display wait for moderator metabox.
	 *
	 * @since   3.0.0
	 *
	 * @param   Object $object     The object that has the room ID.
	 */
	public function display_wait_for_mod_metabox( $object ) {
		$existing_value = get_post_meta( $object->ID, 'mtz-room-wait-for-moderator', true );
		wp_nonce_field( 'mtz-room-wait-for-moderator-nonce', 'mtz-room-wait-for-moderator-nonce' );
		require 'partials/meetingz-wait-for-mod-metabox-display.php';
	}

	/**
	 * Display recordable metabox.
	 *
	 * @since   3.0.0
	 *
	 * @param   Object $object     The object that has the room ID.
	 */
	public function display_allow_record_metabox( $object ) {
		$existing_value = get_post_meta( $object->ID, 'mtz-room-recordable', true );

		wp_nonce_field( 'mtz-room-recordable-nonce', 'mtz-room-recordable-nonce' );
		require 'partials/meetingz-recordable-metabox-display.php';
	}
}
