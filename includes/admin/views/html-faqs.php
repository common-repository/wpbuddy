<?php 

?>
<div class="wpbuddy-admin-panel wpbuddy-main-panel">
	<div class="wpbuddy-panel-header">
		<h2>WPBuddy - FAQs</h2>
	</div>
	<div class="wpbuddy-panel-body">
		<?php if ( ! empty( wpbuddy_get_faqs() ) ) : ?>
			<div class="wpbuddy-accordion-wrapper">
				<?php foreach ( wpbuddy_get_faqs() as $faq ) : ?>
					<?php echo wpbuddy_get_view( 'html-accordion-item', array( 'faq' => $faq ) ); ?>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<p>There are no FAQs available at this time.</p>
		<?php endif; ?>
	</div>
</div>