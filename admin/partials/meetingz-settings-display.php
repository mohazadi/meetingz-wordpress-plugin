<div class="mtz-settings-card">
	<h1><?php esc_html_e( 'Room Settings', 'meetingz' ); ?></h1>
	<h2><?php esc_html_e( 'Server', 'meetingz' ); ?></h2>
	<p><?php esc_html_e( 'The server settings explanation.', 'meetingz' ); ?></p>
	<form id="mtz-general-settings-form" method="POST" action="">
		<input type="hidden" name="action" value="mtz_general_settings">
		<input type="hidden" id="mtz_edit_server_settings_meta_nonce" name="mtz_edit_server_settings_meta_nonce" value="<?php echo $meta_nonce; ?>">
		<div class="mtz-row">
			<p id="mtz_endpoint_label" class="mtz-col-left mtz-important-label"><?php esc_html_e( 'EndPoint', 'meetingz' ); ?>: </p>
			<input class="mtz-col-right" type="text" name="mtz_url" size=50 value="<?php echo $mtz_settings['mtz_url']; ?>" aria-labelledby="mtz_endpoint_label">
		</div>
		<div class="mtz-row">
			<p class="mtz-col-left"></p>
			<label aria-labelledby="mtz_endpoint_label" class="mtz-col-right"><?php esc_html_e( 'Example', 'meetingz' ); ?>: <?php echo $mtz_settings['mtz_default_url']; ?></label>
		</div>
		<div class="mtz-row">
			<p id="mtz_shared_secret_label" class="mtz-col-left mtz-important-label"><?php esc_html_e( 'Shared Secret', 'meetingz' ); ?>: </p>
			<input class="mtz-col-right" type="text" name="mtz_salt" size=50 value="<?php echo $mtz_settings['mtz_salt']; ?>" aria-labelledby="mtz_shared_secret_label">
		</div>
		<div class="mtz-row">
			<p class="mtz-col-left"></p>
			<label class="mtz-col-right" aria-labelledby="mtz_shared_secret_label"><?php esc_html_e( 'Example', 'meetingz' ); ?>: <?php echo $mtz_settings['mtz_default_salt']; ?></label>
		</div>
		<?php if ( $mtz_settings['mtz_url'] == $mtz_settings['mtz_default_url'] ) { ?>
		<label><?php esc_html_e( 'Default server settings 1. Default server settings 2.', 'meetingz' ); ?></label>
		<?php } ?>
		<?php if ( $change_success == 1 ) { ?>
			<div class="updated">
				<p><?php esc_html_e( 'Save server settings success message.', 'meetingz' ); ?></p>
			</div>
		<?php } elseif ( $change_success == 2 ) { ?>
			<div class="error">
				<p><?php esc_html_e( 'Save server settings bad url error message.', 'meetingz' ); ?></p>
			</div>
		<?php } elseif ( $change_success == 3 ) { ?>
			<div class="error">
				<p><?php esc_html_e( 'Save server settings bad server settings error message.', 'meetingz' ); ?></p>
			</div>
		<?php } ?>
		<br><br>
		<input class="button button-primary mtz-settings-submit" type="submit" value="<?php esc_html_e( 'Save Changes' ); ?>"/>
	</form>
</div>
