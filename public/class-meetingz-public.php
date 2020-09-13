<?php
/**
 * The views of the plugin.
 *
 * @link       https://meetingz.ir
 * @since      3.0.0
 *
 * @package    Meetingz
 * @subpackage Meetingz/public
 */

/**
 * The views of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Meetingz
 * @subpackage Meetingz/public
 * @author     EST  <info@meetingz.ir>
 */
class Meetingz_Public {

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
	 * @param    String $plugin_name       The name of the plugin.
	 * @param    String $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    3.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Meetingz_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Meetingz_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/meetingz-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    3.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Meetingz_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Meetingz_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$translations = array(
			'expand_recordings'   => __( 'Expand recordings', 'meetingz' ),
			'collapse_recordings' => __( 'Collapse recordings', 'meetingz' ),
			'edit'                => __( 'Edit' ),
			'published'           => __( 'Published' ),
			'unpublished'         => __( 'Unpublished' ),
			'protected'           => __( 'Protected', 'meetingz' ),
			'unprotected'         => __( 'Unprotected', 'meetingz' ),
			'ajax_url'            => admin_url( 'admin-ajax.php' ),
		);

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/meetingz-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'php_vars', $translations );

	}

	/**
	 * Enqueues dashicon icons for use on front end.
	 *
	 * @since   3.0.0
	 */
	public function enqueue_front_end_dashicons() {
		if ( ! wp_style_is( 'dashicons', 'enqueued' ) ) {
			wp_enqueue_style( 'dashicons' );
		}
	}

	/**
	 * Enqueue heartbeat API for viewers to wait for moderator to join the meeting.
	 *
	 * @since   3.0.0
	 */
	public function enqueue_heartbeat() {
		if ( get_query_var( 'meetingz_wait_for_mod' ) ) {
			wp_enqueue_script( 'heartbeat' );
		}
	}

	/**
	 * Add query vars for conditions.
	 *
	 * @since   3.0.0
	 *
	 * @param   Array $vars List of existing vars that can be queried using WordPress core functions.
	 * @return  Array $vars List of vars that can be queried, including MeetingZ variables.
	 */
	public function add_query_vars( $vars ) {
		$vars[] = 'meetingz_wait_for_mod';
		return $vars;
	}

	/**
	 * Display join room button and recordings in the mtz-room post.
	 *
	 * @since   3.0.0
	 *
	 * @param   String $content    Post content as string.
	 * @return  String $content    Post content as string.
	 */
	public function mtz_room_content( $content ) {
		global $pagenow;

		if ( ! Meetingz_Tokens_Helper::can_display_room_on_page() ) {
			return $content;
		}

		$room_id = get_the_ID();

		if ( null === $room_id || false === $room_id || ! isset( get_post( $room_id )->post_type ) ||
			'mtz-room' != get_post( $room_id )->post_type ) {
			return $content;
		}

		$token    = 'z' . $room_id;
		$content .= '[meetingz token="' . $token . '"]';

		// Add recordings list to post content if the room is recordable.
		$room_can_record = get_post_meta( $room_id, 'mtz-room-recordable', true );

		if ( 'true' == $room_can_record ) {
			$content .= '[meetingz type="recording" token="' . $token . '"]';
		}

		return $content;
	}

	/**
	 * Register meetingz widget.
	 *
	 * @since   3.0.0
	 */
	public function register_widget() {
		register_widget( 'Meetingz_Public_Widget' );
	}
}
