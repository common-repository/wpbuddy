<div class="wpbuddy-navigation-wrapper">
	<div class="wpbuddy-navigation">
		<div class="wpbuddy-logo">
			<a href="<?php echo admin_url( 'admin.php?page=wpbuddy' ) ?>">WPBuddy</a>
		</div>
		<div class="wpbuddy-nav-items">
			<?php foreach ( wpbuddy_admin_navigation_items() as $nav_item ) : ?>
				<?php $is_nav_item_active = ( $nav_item['tab'] === $tab ) ? 'active' : ''; ?>
				<a href="<?php echo $nav_item['url'] ?>" class="wpbuddy-nav-item <?php echo $is_nav_item_active ?>">
					<span class="wpbuddy-icon"><?php echo $nav_item['icon'] ?></span> 
					<?php echo $nav_item['label'] ?>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</div>