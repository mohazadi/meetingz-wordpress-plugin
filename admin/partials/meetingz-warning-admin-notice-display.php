<div class="notice notice-warning is-dismissible mtz-warning-notice" data-notice="<?php esc_html_e( $mtz_warning_type ); ?>" data-nonce="<?php esc_html_e( $mtz_admin_notice_nonce ); ?>" >
	<p>
	<?php if ( isset( $mtz_action_link ) ) { ?>
		<a href="<?php echo $mtz_action_link; ?>" target="_blank"><?php esc_html_e( $mtz_admin_warning_message ); ?></a>
	<?php } else { ?>
		<?php esc_html_e( $mtz_admin_warning_message ); ?>
	<?php } ?>
	</p>
</div>
