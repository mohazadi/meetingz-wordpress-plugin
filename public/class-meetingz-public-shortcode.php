<?php
/**
 * The shortcode for the plugin.
 *
 * @link       https://meetingz.ir
 * @since      3.0.0
 *
 * @package    Meetingz
 * @subpackage Meetingz/public
 */

/**
 * The shortcode for the plugin.
 *
 * Registers the shortcode and handles displaying the shortcode.
 *
 * @package    Meetingz
 * @subpackage Meetingz/public
 * @author     EST  <info@meetingz.ir>
 */
class Meetingz_Public_Shortcode {

	/**
	 * Register meetingz shortcodes.
	 *
	 * @since   3.0.0
	 */
	public function register_shortcodes() {
		add_shortcode( 'meetingz', array( $this, 'display_meetingz_shortcode' ) );
		add_shortcode( 'meetingz_recordings', array( $this, 'display_meetingz_old_recordings_shortcode' ) );
	}

	/**
	 * Handle shortcode attributes.
	 *
	 * @since   3.0.0
	 *
	 * @param   Array  $atts       Parameters in the shortcode.
	 * @param   String $content    Content of the shortcode.
	 *
	 * @return  String $content    Content of the shortcode with rooms and recordings.
	 */
	public function display_meetingz_shortcode( $atts = [], $content = null ) {
		global $pagenow;
		$type           = 'room';
		$author         = (int) get_the_author_meta( 'ID' );
		$display_helper = new Meetingz_Display_Helper( plugin_dir_path( __FILE__ ) );

		if ( ! Meetingz_Tokens_Helper::can_display_room_on_page() ) {
			return $content;
		}

		if ( array_key_exists( 'type', $atts ) && 'recording' == $atts['type'] ) {
			$type = 'recording';
			unset( $atts['type'] );
		}

		$tokens_string = Meetingz_Tokens_Helper::get_token_string_from_atts( $atts );

		if ( 'room' == $type ) {
			$content .= Meetingz_Tokens_Helper::join_form_from_tokens_string( $display_helper, $tokens_string, $author );
		} elseif ( 'recording' == $type ) {
			$content .= Meetingz_Tokens_Helper::recordings_table_from_tokens_string( $display_helper, $tokens_string, $author );
		}
		return $content;
	}

	/**
	 * Shows recordings for the old recordings shortcode format.
	 *
	 * @since   3.0.0
	 * @param   Array  $atts       Parameters in the shortcode.
	 * @param   String $content    Content of the shortcode.
	 *
	 * @return  String $content    Content of the shortcode with recordings.
	 */
	public function display_meetingz_old_recordings_shortcode( $atts = [], $content = null ) {
		$atts['type'] = 'recording';
		return $this->display_meetingz_shortcode( $atts, $content );
	}
}
