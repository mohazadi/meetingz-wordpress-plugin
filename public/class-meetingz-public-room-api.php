<?php
/**
 * The public-facing room API of the plugin.
 *
 * @link       https://meetingz.ir
 * @since      3.0.0
 *
 * @package    Meetingz
 * @subpackage Meetingz/public
 */

/**
 * The public-facing room API of the plugin.
 *
 * Answers the API calls made from public facing pages about rooms.
 *
 * @package    Meetingz
 * @subpackage Meetingz/public
 * @author     EST  <info@meetingz.ir>
 */
class Meetingz_Public_Room_Api {
	/**
	 * The ID of this plugin.
	 *
	 * @since    3.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    3.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    3.0.0
	 * @param    string $plugin_name       The name of the plugin.
	 * @param    string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Handle user joining room.
	 *
	 * @since   3.0.0
	 */
	public function mtz_user_join_room() {
		if ( ! empty( $_POST['action'] ) && 'join_room' == $_POST['action'] && wp_verify_nonce( $_POST['mtz_join_room_meta_nonce'], 'mtz_join_room_meta_nonce' ) ) {
			$room_id             = $_POST['room_id'];
			$user                = wp_get_current_user();
			$entry_code          = '';
			$username            = $this->get_meeting_username( $user );
			$moderator_code      = strval( get_post_meta( $room_id, 'mtz-room-moderator-code', true ) );
			$viewer_code         = strval( get_post_meta( $room_id, 'mtz-room-viewer-code', true ) );
			$wait_for_mod        = get_post_meta( $room_id, 'mtz-room-wait-for-moderator', true );
			$access_using_code   = MeetingZ_Permissions_Helper::user_has_mtz_cap( 'join_with_access_code_mtz_room' );
			$access_as_moderator = MeetingZ_Permissions_Helper::user_has_mtz_cap( 'join_as_moderator_mtz_room' );
			$access_as_viewer    = MeetingZ_Permissions_Helper::user_has_mtz_cap( 'join_as_viewer_mtz_room' );
			$return_url          = esc_url( $_POST['REQUEST_URI'] );

			if ( $access_as_moderator || get_post( $room_id )->post_author == $user->ID ) {
				$entry_code = $moderator_code;
			} elseif ( $access_as_viewer ) {
				$entry_code = $viewer_code;
			} elseif ( $access_using_code && isset( $_POST['mtz_meeting_access_code'] ) ) {
				$entry_code = sanitize_text_field( $_POST['mtz_meeting_access_code'] );
				if ( $entry_code != $moderator_code && $entry_code != $viewer_code ) {
					$query = array(
						'password_error' => true,
						'room_id'        => $room_id,
						'username'       => $username,
					);
					wp_redirect( add_query_arg( $query, $return_url ) );
					return;
				}
			} else {
				wp_die( esc_html__( 'You do not have permission to enter the room. Please request permission.', 'meetingz' ) );
			}
			$this->join_meeting( $return_url, $room_id, $username, $entry_code, $viewer_code, $wait_for_mod );
		}
	}

	/**
	 * Update the join room form on the front end with the room ID and whether the access code input should be shown or not.
	 *
	 * @since   3.0.0
	 */
	public function get_join_form() {
		$response            = array();
		$response['success'] = false;

		if ( array_key_exists( 'room_id', $_POST ) ) {
			$access_using_code   = MeetingZ_Permissions_Helper::user_has_mtz_cap( 'join_with_access_code_mtz_room' );
			$access_as_moderator = ( MeetingZ_Permissions_Helper::user_has_mtz_cap( 'join_as_moderator_mtz_room' ) || ( get_current_user_id() == get_post( $_POST['room_id'] )->post_author ) );
			$access_as_viewer    = MeetingZ_Permissions_Helper::user_has_mtz_cap( 'join_as_viewer_mtz_room' );

			$response['success']                = true;
			$response['hide_access_code_input'] = $access_as_moderator || $access_as_viewer || ! $access_using_code;
		}

		wp_send_json( $response );
	}

	/**
	 * Check if the moderator has entered the room yet.
	 *
	 * @since   3.0.0
	 *
	 * @param   Array $response   Empty response without meaningful data.
	 * @param   Array $data       Request data for checking if the moderator has entered the meeting yet.
	 *
	 * @return  Array $response   Response that says if the admin has entered the meeting or not.
	 */
	public function mtz_check_meeting_state( $response, $data = [] ) {
		if ( empty( $data['check_meetingz_meeting_state'] ) || empty( $data['meetingz_room_id'] ) ) {
			return $response;
		}

		$username                                    = '';
		$room_id                                     = (int) $data['meetingz_room_id'];
		$entry_code                                  = strval( get_post_meta( $room_id, 'mtz-room-viewer-code', true ) );
		$response['meetingz_admin_has_entered'] = false;

		if ( ! MeetingZ_Permissions_Helper::user_has_mtz_cap( 'join_as_viewer_mtz_room' ) ) {
			$temp_entry_pass = sanitize_text_field( $data['meetingz_temp_room_pass'] );
			if ( ! wp_verify_nonce( $temp_entry_pass, 'meetingz_entry_code_' . $entry_code ) ) {
				$entry_code = '';
			}
		}

		if ( is_user_logged_in() ) {
			$username = wp_get_current_user()->display_name;
		} else {
			$username = sanitize_text_field( $data['meetingz_room_username'] );
		}

		$join_url = Meetingz_Api::get_join_meeting_url( $room_id, $username, $entry_code );

		if ( Meetingz_Api::is_meeting_running( $room_id ) ) {
			$response['meetingz_admin_has_entered'] = true;
			$response['meetingz_join_url']          = $join_url;
		}

		return $response;
	}

	/**
	 * Join meeting if possible.
	 *
	 * @since   3.0.0
	 *
	 * @param   String  $return_url     URL of the page the request was made from.
	 * @param   Integer $room_id        ID of the room to join.
	 * @param   String  $username       The name of the user who wants to enter the meeting.
	 * @param   String  $entry_code     The entry code the user is attempting to join with.
	 * @param   String  $viewer_code    The entry code for viewers.
	 * @param   Boolean $wait_for_mod   Boolean value for if the room requires a moderator to join before any viewers.
	 */
	private function join_meeting( $return_url, $room_id, $username, $entry_code, $viewer_code, $wait_for_mod ) {
		$join_url = Meetingz_Api::get_join_meeting_url( $room_id, $username, $entry_code, $return_url );

		if ( $entry_code == $viewer_code && 'true' == $wait_for_mod ) {
			if ( Meetingz_Api::is_meeting_running( $room_id ) ) {
				wp_redirect( $join_url );
			} else {
				$query = array(
					'meetingz_wait_for_mod' => true,
					'room_id'                    => $room_id,
				);

				$access_as_viewer = MeetingZ_Permissions_Helper::user_has_mtz_cap( 'join_as_viewer_mtz_room' );
				if ( ! is_user_logged_in() ) {
					$query['username'] = $username;
				}
				// Make user wait for moderator to join room.
				if ( ! $access_as_viewer ) {
					$query['temp_entry_pass'] = wp_create_nonce( 'meetingz_entry_code_' . $entry_code );
				}
				wp_redirect( add_query_arg( $query, $return_url ) );
			}
		} else {
			wp_redirect( $join_url );
		}
	}

	/**
	 * Get user's name for the meeting.
	 *
	 * @since   3.0.0
	 *
	 * @param   Object $user       User object.
	 * @return  String $username   Display of the user for joining the meeting.
	 */
	private function get_meeting_username( $user ) {
		$username = '';
		if ( $user && $user->display_name ) {
			$username = $user->display_name;
		} elseif ( isset( $_POST['mtz_meeting_username'] ) ) {
			$username = sanitize_text_field( $_POST['mtz_meeting_username'] );
		}
		return $username;
	}
}
