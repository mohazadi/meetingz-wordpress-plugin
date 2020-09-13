<div class="mtz-recording-display-block">
	<div id="mtz-recordings-display-<?php echo $room_id; ?>" class="mtz-recordings-display">
		<i class="dashicons dashicons-arrow-down-alt2"></i>
		<p class="mtz-expandable-header"><?php esc_html_e( 'Collapse recordings', 'meetingz' ); ?></p>
	</div>
	<?php echo $html_recordings; ?>
</div>
