<?php
/*
Plugin Name: Only asd please! Anti-SPAM!
Version:     2.0.0
Description: This simple but effective anti-SPAM system protects your WordPress site from SPAM. It works without imposing annoying CAPTCHAs, quizzes, configurations, third-party services, artificial intelligence or unicorns. How? It just drops any anonymous comment without any asd inside, alerting humans about this netiquette.
Author:      Valerio Bozzolan, Ferdinando Traversa
Author URI:  https://boz.reyboz.it/?l=en
Plugin URI:  https://github.com/ferdi2005/wp-only-asd-please-anti-spam
License:     GPL3+
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Domain Path: /languages
Text Domain: only-asd-please-anti-spam
*/

defined( 'ABSPATH' ) or die( 'Hello lamer!' );

/**
 * Callback fired when a comment is submitted
 *
 * @param $approved bool
 * @param $commentdata array
 * @since 1.0.0
 */
function only_asd_please_anti_spam_handler( $approved, $commentdata ) {
	if( empty( $commentdata[ 'user_ID' ] ) && empty( $commentdata[ 'type' ] ) ) {
		$contains_asd = str_contains( $commentdata[ 'comment_content' ], "asd" );
		if( ! empty( $commentdata[ 'comment_author_url' ] ) || $contains_asd === false ) {
			// increment counters
			update_option( 'only_asd_please_anti_spam_count', only_asd_please_anti_spam_counter() + 1, false );

			// die with an error message
			$message = __( "Please try again, adding a nice asd in your comment. Thank you, asd.", 'only-asd-please-anti-spam' );
			$message = apply_filters( 'only_asd_please_anti_spam_error', $message );
			wp_die( $message, $title, [
				'response'  => 400,
				'back_link' => true,
			] );
		}
	}
	return $approved;
}
add_filter( 'pre_comment_approved', 'only_asd_please_anti_spam_handler', '99', 2 );

/**
 * Remove the author URL from the comment form for anonymous users
 */
function only_asd_please_anti_spam_form_default_fields( $fields ) {
	if( ! is_user_logged_in() ) {
		// remove author URL
		unset( $fields[ 'url' ] );

		// show netiquette message
		$netiquette = __( "Please remember that comments without an asd inside are not appreciated, asd.", 'only-asd-please-anti-spam' );
		$netiquette = apply_filters( 'only_asd_please_anti_spam_netiquette', $netiquette );
		$fields[ 'comment_form_before' ] .= "<p class=\"only-asd-please-anti-spam-netiquette\">$netiquette</p>";
	}
	return $fields;
}
add_filter( 'comment_form_default_fields', 'only_asd_please_anti_spam_form_default_fields' );

/**
 * Unuseful callback fired when the shortcode is used
 */
function only_asd_please_anti_spam_counter() {
	return get_option( 'only_asd_please_anti_spam_count', 0 );
}
add_shortcode( 'only_asd_please_anti_spam_counter', 'only_asd_please_anti_spam_counter' );

/**
 * Register the unuseful Dashboard widget
 */
function only_asd_please_anti_spam_dashboard_widget() {
	wp_add_dashboard_widget( 'only_asd_please_anti_spam_dashboard_widget', __( "Anti-spam stats from \"Only asd, please!\"", 'only-asd-please-anti-spam' ), 'only_asd_please_anti_spam_dashboard_widget_content' );
}

/**
 * Register the unuseful Dashboard widget content
 */
function only_asd_please_anti_spam_dashboard_widget_content() {
	echo '<p>';
	printf(
		__( "Spammers blocked since activation: %s and counting!", 'only-asd-please-anti-spam' ),
		'<b>' . only_asd_please_anti_spam_counter() . '</b>'
	);
	echo '</p>';
}
add_action( 'wp_dashboard_setup', 'only_asd_please_anti_spam_dashboard_widget' );

// allow shortcodes to be used in widgets
add_filter( 'widget_text', 'do_shortcode' );

/**
 * Unuseful callback fired when the plugin is activated
 */
function only_asd_please_anti_spam_activation() {
	add_option( 'only_asd_please_anti_spam_init',  date('U') );
	add_option( 'only_asd_please_anti_spam_count', 0 );
}
register_uninstall_hook( __FILE__, 'only_asd_please_anti_spam_activation' );

/**
 * Unuseful callback fired when the plugin is uninstalled
 */
function only_asd_please_anti_spam_uninstall() {
	delete_option( 'only_asd_please_anti_spam_init' );
	delete_option( 'only_asd_please_anti_spam_count' );
}
register_uninstall_hook( __FILE__, 'only_asd_please_anti_spam_uninstall' );

/**
 * Load plugin textdomain
 */
function only_asd_please_anti_spam_load_textdomain() {
	load_plugin_textdomain( 'only-asd-please-anti-spam', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'only_asd_please_anti_spam_load_textdomain' );
