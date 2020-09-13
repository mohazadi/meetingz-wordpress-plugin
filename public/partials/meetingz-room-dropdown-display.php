<div>
	<p class="mtz-inline-block"><?php esc_html_e( 'Room selection' ); ?>: </p>
	<select class="mtz-room-selection">
		<?php foreach ( $rooms as $room ) { ?>
			<option value="<?php echo $room->room_id; ?>"
				<?php if ($selected_room == $room->room_id) { ?>
					selected
				<?php } ?>><?php echo $room->room_name; ?></option>
		<?php } ?>
	</select>
	<?php echo $html_form; ?>
</div>
