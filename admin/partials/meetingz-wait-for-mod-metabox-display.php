<label><?php esc_html_e( 'Wait for Moderator', 'meetingz' ); ?>: </label>
<input name="mtz-room-wait-for-moderator" type="checkbox" value="checked"
<?php if ( 'true' === $existing_value) { ?>
	checked
<?php } ?>>
