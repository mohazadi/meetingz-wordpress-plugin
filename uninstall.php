<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://meetingz.ir
 * @since      3.0.0
 *
 * @package    Meetingz
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( ! class_exists( 'Meetingz_Uninstall' ) ) {
	class Meetingz_Uninstall {

		/**
		 * Remove all capabilities associated with this plugin.
		 *
		 * Remove capabilities for creating/editing/viewing rooms and joining as moderator/viewer.
		 *
		 * @since    3.0.0
		 */
		public static function uninstall() {
			if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
				exit;
			}

			self::trash_rooms_and_categories();
			self::delete_capabilities();
			self::remove_options();
		}

		/**
		 * Remove all rooms and custom types.
		 *
		 * @since 3.0.0
		 */
		private static function trash_rooms_and_categories() {
			global $wpdb;
			$wpdb->query( 'DELETE FROM wp_postmeta WHERE post_id in (SELECT id from wp_posts where post_type="mtz-room");' );
			$wpdb->query( 'DELETE FROM wp_term_relationships WHERE term_taxonomy_id in (SELECT term_taxonomy_id from wp_term_taxonomy WHERE taxonomy="mtz-room-category");' );
			$wpdb->query( 'DELETE FROM wp_posts WHERE post_type="mtz-room";' );
			$wpdb->query( 'DELETE FROM wp_term_taxonomy WHERE taxonomy="mtz-room-category";' );
		}

		/**
		 * Delete all capabilitie sassociated with this plugin.
		 *
		 * @since 3.0.0
		 */
		private static function delete_capabilities() {
			$rooms               = get_post_type_object( 'mtz-room' );
			$custom_capabilities = array(
				'join_as_moderator_mtz_room',
				'join_as_viewer_mtz_room',
				'join_with_access_code_mtz_room',
				'create_recordable_mtz_room',
				'manage_mtz_room_recordings',
				'view_extended_mtz_room_recording_formats',
			);

			if ( empty( $rooms ) ) {
				$room_capabilities = array(
					'edit_mtz_room',
					'read_mtz_room',
					'delete_mtz_room',
					'edit_mtz_rooms',
					'edit_others_mtz_rooms',
					'publish_mtz_rooms',
					'read_private_mtz_rooms',
					'delete_mtz_rooms',
					'delete_private_mtz_rooms',
					'delete_published_mtz_rooms',
					'delete_others_mtz_rooms',
					'edit_private_mtz_rooms',
					'edit_published_mtz_rooms'
				);
			} elseif ( property_exists( $rooms, 'cap' ) ) {
				$room_capabilities = array_values( get_object_vars( $rooms->cap ) );
			} else {
				$room_capabilities = [];
			}

			$capabilities = array_merge( $room_capabilities, $custom_capabilities );
			$roles        = get_editable_roles();
			$role_names   = array_keys( $roles );

			foreach ( $role_names as $name ) {
				$role = get_role( $name );

				foreach ( $capabilities as $cap ) {
					if ( strpos( $cap, 'mtz_room' ) === false ) {
						continue;
					}
					if ( $role->has_cap( $cap ) ) {
						$role->remove_cap( $cap );
					}
				}
			}
		}

		/**
		 * Remove meetingz specific options.
		 *
		 * @since 3.0.0
		 */
		private static function remove_options() {
			delete_option( 'meetingz_url' );
			delete_option( 'meetingz_salt' );
			delete_option( 'meetingz_plugin_version' );
			delete_option( 'meetingz_default_roles_set' );
		}
	}
}
Meetingz_Uninstall::uninstall();
