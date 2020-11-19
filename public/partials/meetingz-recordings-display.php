<div id="mtz-recordings-list-<?php echo $room_id; ?>">
	<?php if ( empty( $recordings ) ) { ?>
		<p id="mtz-no-recordings-msg"><?php esc_html_e( 'This room does not currently have any recordings.', 'meetingz' ); ?></p>
	<?php } else { ?>
		<p id="mtz-no-recordings-msg" style="display:none;"><?php esc_html_e( 'This room does not currently have any recordings.', 'meetingz' ); ?></p>
		<div id="mtz-recordings-table" class="mtz-table-container" role="table">
			<div class="mtz-flex-table mtz-flex-table-<?php echo $columns; ?> mtz-header" role="rowgroup">
				<div class="flex-row flex-row-<?php echo $columns; ?> first" role="columnheader"><?php esc_html_e( 'Meeting', 'meetingz' ); ?></div>
				<a href="<?php echo $sort_fields['name']->url; ?>" class="flex-row flex-row-<?php echo $columns; ?> <?php echo $sort_fields['name']->header_classes; ?>" role="columnheader">
					<?php esc_html_e( 'Recording', 'meetingz' ); ?>
					<i class="<?php echo $sort_fields['name']->classes; ?>"></i>
				</a>
				<a href="<?php echo $sort_fields['description']->url; ?>" class="flex-row flex-row-<?php echo $columns; ?> <?php echo $sort_fields['description']->header_classes; ?>" role="columnheader">
					<?php esc_html_e( 'Description' ); ?>
					<i class="<?php echo $sort_fields['description']->classes; ?>"></i>
				</a>
				<a href="<?php echo $sort_fields['date']->url; ?>" class="flex-row flex-row-<?php echo $columns; ?> <?php echo $sort_fields['date']->header_classes; ?>" role="columnheader">
					<?php esc_html_e( 'Date' ); ?>
					<i class="<?php echo $sort_fields['date']->classes; ?>"></i>
				</a>
				<div class="flex-row flex-row-<?php echo $columns; ?>" role="columnheader"><?php esc_html_e( 'Link' ); ?></div>
				<?php if ( $manage_mtz_recordings ) { ?>
					<div class="flex-row flex-row-<?php echo $columns; ?>" role="columnheader">
						<?php esc_html_e( 'Manage', 'meetingz' ); ?>
					</div>
				<?php } ?>
			</div>
			<?php foreach ( $recordings as $recording ) { ?>
				<div id="mtz-recording-<?php echo $recording->recordID; ?>" class="mtz-flex-table mtz-flex-table-<?php echo $columns; ?> mtz-recording-row" role="rowgroup">
					<div class="flex-row flex-row-<?php echo $columns; ?> first" role="cell"><?php echo urldecode( $recording->name ); ?></div>
					<div id="mtz-recording-name-<?php echo $recording->recordID; ?>" class="flex-row flex-row-<?php echo $columns; ?>" role="cell">
						<?php echo urldecode( $recording->metadata->{'recording-name'} ); ?>
						<?php if ( $manage_mtz_recordings ) { ?>
							<i id="edit-recording-name-<?php echo $recording->recordID; ?>"
								title="<?php esc_html_e( 'Edit' ); ?>"
								aria-label="<?php esc_html_e( 'Edit' ); ?>"
								data-record-id="<?php echo $recording->recordID; ?>"
								data-record-value="<?php echo urldecode( $recording->metadata->{'recording-name'} ); ?>"
								data-record-type="name"
								data-meta-nonce="<?php echo $meta_nonce; ?>"
								class="dashicons dashicons-edit mtz-icon mtz_edit_recording_data"></i>
						<?php } ?>
					</div>
					<div id="mtz-recording-description-<?php echo $recording->recordID; ?>" class="flex-row flex-row-<?php echo $columns; ?>" role="cell">
						<?php echo urldecode( $recording->metadata->{'recording-description'} ); ?>
						<?php if ( $manage_mtz_recordings ) { ?>
							<i id="edit-recording-description-<?php echo $recording->recordID; ?>"
								title="<?php esc_html_e( 'Edit' ); ?>"
								aria-label="<?php esc_html_e( 'Edit' ); ?>"
								data-record-id="<?php echo $recording->recordID; ?>"
								data-record-value="<?php echo urldecode( $recording->metadata->{'recording-description'} ); ?>"
								data-record-type="description"
								data-meta-nonce="<?php echo $meta_nonce; ?>"
								class="dashicons dashicons-edit mtz-icon mtz_edit_recording_data"></i>
						<?php } ?>
					</div>
					<div class="flex-row flex-row-<?php echo $columns; ?>" role="cell">
						<?php echo $recording->startTime; ?>
					</div>
					<div class="flex-row flex-row-<?php echo $columns; ?>" role="cell">
						<div id="mtz-recording-links-block-<?php echo $recording->recordID; ?>" class="mtz-recording-link-block" style="<?php echo ( $recording->published == 'false' ? 'display:none;' : '' ); ?>">
							<?php foreach ( $recording->playback->format as $format ) { ?>
								<?php if ( $format->type == $default_mtz_recording_format || $view_extended_recording_formats ) { ?>
									<div class="mtz-recording-link">
										<a target="_blank" href="<?php echo $format->url; ?>">ضبط ابری</a>
									</div>
                                    <div class="mtz-recording-link">
										<a target="_blank" href="<?php echo $format->url0; ?>">ضبط عادی</a>
									</div>
								<?php } ?>
							<?php } ?>
						</div>
					</div>
					<?php if ( $manage_mtz_recordings ) { ?>
						<div class="flex-row flex-row-<?php echo $columns; ?>" role="cell">
							<?php if ( isset( $recording->protected_icon_classes ) && isset( $recording->protected_icon_title ) ) { ?>
								<i data-record-id="<?php echo $recording->recordID; ?>"
										data-meta-nonce="<?php echo $meta_nonce; ?>"
										class="<?php echo $recording->protected_icon_classes; ?>"
										title="<?php echo $recording->protected_icon_title; ?>"
										aria-label="<?php echo $recording->protected_icon_title; ?>"></i>
								&nbsp;
							<?php } ?>
							<i data-record-id="<?php echo $recording->recordID; ?>"
									data-meta-nonce="<?php echo $meta_nonce; ?>"
									class="<?php echo $recording->published_icon_classes; ?>"
									title="<?php echo $recording->published_icon_title; ?>"
									aria-label="<?php echo $recording->published_icon_title; ?>"></i>
							&nbsp;
							<i data-record-id="<?php echo $recording->recordID; ?>"
								data-meta-nonce="<?php echo $meta_nonce; ?>"
								class="<?php echo $recording->trash_icon_classes ?>"
								title="<?php _ex( 'Trash', 'post status' ); ?>"
								aria-label="<?php _ex( 'Trash', 'post status' ); ?>"></i>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
</div>
