<?php
/**
 * @var array $faq
 */
?>
<div class="wpbuddy-accordion">
	<div class="wpbuddy-accordion__header">
		<h3 class="wpbuddy-accordion__title"><?php echo esc_html( $faq['title'] ); ?></h3>
		<span class="wpbuddy-accordion__icon">
			<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
				<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
			</svg>
		</span>
	</div>
	<div class="wpbuddy-accordion__body">
		<?php echo wp_kses_post( $faq['body'] ); ?>
	</div>
</div>