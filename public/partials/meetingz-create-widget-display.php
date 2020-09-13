<div class="mtz-top-bottom-margin">
	<p>
		<label for="<?php echo $text_id; ?>" class="mtz-width-30 mtz-inline-block"><?php esc_html_e( 'Tokens (separated by comma)', 'meetingz' ); ?>:</label>
		<input class="widefat" id=<?php echo esc_attr( $text_id ); ?> name="<?php echo esc_attr( $text_name ); ?>" type="text" value="<?php echo esc_attr( $text_value ); ?>" />
	</p>
</div>
