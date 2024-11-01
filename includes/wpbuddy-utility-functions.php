<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * This function will load in a file from the 'admin/views' directory and allows you to pass in variables to the file.
 * @since 1.0.3
 * @param string $file The file to load
 * @param array $data The data to pass to the file
 * @return void
 */
function wpbuddy_get_view( $view_path = '', $data = array() ) {
    if ( substr( $view_path, -4 ) !== '.php' ) {
        $view_path = wpbuddy_get_path( 'includes/admin/views/' . $view_path . '.php' );
    }

    // Make sure the file exists
    if ( file_exists( $view_path ) ) {
        // Extract the data
        extract( $data, EXTR_SKIP );

        // Load the file
        include $view_path;
    }
}

/**
 * Returns the path to a file in the plugin.
 * @since 1.0.3
 * @param string $path The path to the file
 * @return string The full path to the file
 */
function wpbuddy_get_path( $path = '' ) {
    return WPBUDDY_PLUGIN_DIR_PATH . ltrim( $path, '/' );
}

/**
 * This function will return the SVG icon for the plugin.
 * @since 1.0.3
 * @param string $icon The icon to return
 * @return string The SVG icon
 */
function wpbuddy_get_svg_icon( $icon = '' ) {
    // Get the SVG icons
    $icons = wpbuddy_get_svg_icons();

    // Make sure the icon exists
    if (isset($icons[ $icon ])) {
        return $icons[ $icon ];
    }

    return '';
}

/**
 * This function will return an array of SVG icons.
 * @since 1.0.3
 * @return array The SVG icons
 */
function wpbuddy_get_svg_icons() {
    return array(
        'news' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
		<path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" /></svg>',
       'support' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
	   <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
	   <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
	 </svg>',
        'settings' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
		<path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
	  </svg>
	  '
    );
}

/**
 * This function will return the admin navigation items.
 * @since 1.0.3
 * @return array The admin navigation items
 */
function wpbuddy_admin_navigation_items() {
	return array(
		array(
			'url' => admin_url('admin.php?page=wpbuddy'),
			'icon' => wpbuddy_get_svg_icon('news'),
			'label' => 'News',
			'tab' => 'news'
		),
		array(
			'url' => admin_url('admin.php?page=wpbuddy&tab=support'),
			'icon' => wpbuddy_get_svg_icon('support'),
			'label' => 'Support Tickets',
			'tab' => 'support'
		),
		array(
			'url' => admin_url('admin.php?page=wpbuddy&tab=settings'),
			'icon' => wpbuddy_get_svg_icon('settings'),
			'label' => 'Settings',
			'tab' => 'settings'
		)
	);
}

/**
 * Get API URL per environment.
 * @since 1.0.3
 * @param string $path The path to append to the API URL
 * @return string The API URL
 */
function wpbuddy_get_api_url( $path = '' ) {
	if ( defined( 'WPBUDDY_ENV' ) && WPBUDDY_ENV === 'local' ) {
		return 'http://127.0.0.1:8000' . $path;
	}
	return 'https://my.wpproblemsolvers.com' . $path;
}

/**
 * Get latest news from API.
 * @since 1.0.3
 * @return mixed The response or false
 */
function wpbuddy_get_latest_news() {
	$body = get_transient( 'wpbuddy_latest_news_response' );
	if ( ! $body ) {
		$response = wp_remote_get( wpbuddy_get_api_url( '/api/news' ) );
		if ( is_wp_error( $response ) ) {
			return false;
		}
		$body = json_decode( wp_remote_retrieve_body( $response ) );
		set_transient( 'wpbuddy_latest_news_response', $body, 60 * 60 * 24 );
	}
	return $body;
}

/**
 * Render latest news.
 * @since 1.0.3
 * @param array $news The latest news
 * @return string The rendered news
 */
function wpbuddy_render_latest_news() {

	$response = wpbuddy_get_latest_news();

	if ( empty( $response->data ) ) {
		return 'No news available.';
	}

	$html = '';

	foreach ( $response->data->news as $item ) {
		$html .= wpbuddy_get_view( 'html-news-item', array( 'item' => $item ) );
	}
	return $html;
}

/**
 * Get the latest FAQs
 * @since 1.0.3
 * @return array The latest FAQs
 */
function wpbuddy_get_faqs() {
	return array(
		array(
			'title' => 'Do you offer 24/7 technical support?',
			'body' => '<p>We currently offer support hours from 8 AM to 5 PM Central Standard Time (CST), Monday through Friday. If you submit a support ticket during off-hours, we will immediately attend to it during our regular support hours. With an advanced maintenance plan, we may be able to negotiate support on weekends or evenings for urgent issues.</p>'
		),
		array(
			'title' => 'What are the best ways to contact you for support or development?',
			'body' => '<p>For new clients, please use the our <a href="https://wpproblemsolvers.com/contact/" target="_blank" rel="noopener">contact</a> form. For existing clients, please create a new ticket through the WPBuddy chat or use our: <a target="_blank" rel="noopener" href="https://my.wpproblemsolvers.com">Client Portal</a>.</p>'
		),
		array(
			'title' => 'Do you clean up hacked and infected sites?',
			'body' => '<p>Yes, hacked site cleanup is included with all of our <a href="https://wpproblemsolvers.com/maintenance-plans/" target="_blank" rel="noopener">maintenance</a> plans. For a standard plan, each hack event will cost $50.00 to clean up. For an advanced plan, cleaning up a hacked site is included in the plan for no additional cost.</p>'
		),
		array(
			'title' => 'What do you need from me to manage my site?',
			'body' => '<p>During our onboarding process we’ll grab everything we need from you to support your site. That includes creating a WordPress Administrator account as well as the login to your hosting platform.</p><p>Our staff keeps these credentials private and will never share these details with anyone not associated with WP Problem Solvers.</p>'
		),
		array(
			'title' => 'Where is your team located?',
			'body' => '<p>We are based in Green Bay, WI USA</p>'
		),
		array(
			'title' => 'Does WP Problem Solvers do site redesigns?',
			'body' => '<p>We currently mainly focus on WordPress maintenance and WordPress support and development. However, we may add WordPress site redesign services in the future.</p>'
		),
	);
}

/**
 * Get the latest FAQs
 * @since 1.0.3
 * @return mixed The response or false
 */
function wpbuddy_render_faqs() {
	return wpbuddy_get_view( 'html-faqs' );
}

