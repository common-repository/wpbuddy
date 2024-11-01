<?php
/**
 * @var object $item
 */
?>

<div class="wpbuddy-admin-panel wpbuddy-news-panel">
	<div class="wpbuddy-panel-header">
		<h2><?php echo wp_kses_post( $item->title ); ?></h2>
	</div>
	<div class="wpbuddy-panel-body">
		<div class="wpbuddy-news-item">
			<div class="wpbuddy-news-item__header">
				<img src="<?php echo esc_url( wpbuddy_get_api_url( '/storage/' . $item->image ) ); ?>" alt="<?php echo esc_attr( $item->title ); ?>" class="wpbuddy-news-item__image">
				<p class="wpbuddy-news-item__date"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $item->created_at ) ) ?></p>
			</div>
			<div class="wpbuddy-news-item__body">
				<?php echo wp_kses_post( $item->body ); ?>
			</div>
		</div>
	</div>
</div>
