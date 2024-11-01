<div class="wpbuddy-tab-header">
	<div class="wpbuddy-tab-title">Settings</div>
</div>
<h2 style="height: 1px;line-height: 1px;margin: 0;"></h2>
<div class="wpbuddy-tab-content">
	<div class="wpbuddy-admin-grid">
		<div class="wpbuddy-license">
			<form id="wpbuddy-settings-form" method="post" action="options.php">
				<div class="wpbuddy-admin-panel">
					<div class="wpbuddy-panel-header">
						<h2>License Key</h2>
						<div class="wpbuddy-tooltip">
							<span class="dashicons dashicons-editor-help"></span>
							<div class="wpbuddy-tooltip-content">
								<p>Enter you License Key to enable all WPBuddy features</p>
							</div>
						</div>
					</div>
					<div class="wpbuddy-panel-body">
						<p>To unlock all features, please enter your License key below. If you don't have a licence key, please see <a rel="noopener" target="_blank" href="https://wpproblemsolvers.com/wordpress-maintenance-and-care-plans/">details & pricing</a>.</p>
						<?php
						// Output security fields for the registered setting "wpbuddy".
						settings_fields( 'wpbuddy' );
						// Output setting sections and their fields.
						do_settings_sections( 'wpbuddy' );
						?>
						<div class="wpbuddy-license-status">
							<div class="wpbuddy-status-text">
							</div>
							<div class="spinner wpbuddy-admin-spinner"></div>
						</div>
					</div>
					<div class="wpbuddy-panel-footer">
						<?php submit_button( null, 'primary', '', true ); ?>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>