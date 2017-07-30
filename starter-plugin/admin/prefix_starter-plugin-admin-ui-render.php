<?php
/**
 * Admin UI setup and render
 *
 * @since 1.0
 * @function	prefix_general_settings_section_callback()	Callback function for General Settings section
 * @function	prefix_general_settings_field_callback()	Callback function for General Settings field
 * @function	prefix_admin_interface_render()				Admin interface renderer
 */

 
// Exit if accessed directly
if ( !defined('ABSPATH') ) exit;


/**
 * Callback function for General Settings section
 *
 * @since 1.0
 */
function prefix_general_settings_section_callback() {
	echo '<p>' . __('A long description for the settings section goes here.', 'abl_prefix_td') . '</p>';
}


/**
 * Callback function for General Settings field
 *
 * @since 1.0
 */
function prefix_general_settings_field_callback() {	

	// Default Values For Settings
	global $defaults;

	// Get Settings
	$settings = get_option('prefix_settings', $defaults);

	// General Settings. Name of form element should be same as the setting name in register_setting(). ?>
	
	<!-- Setting one -->
	<input type="checkbox" name="prefix_settings[setting_one]" id="prefix_settings[setting_one]" value="1" 
		<?php if ( isset( $settings['setting_one'] ) ) { checked( '1', $settings['setting_one'] ); } ?>>
		<label for="prefix_settings[setting_one]"><?php esc_html_e('Setting one', 'abl_prefix_td') ?></label>
		<br>
		
	<!-- Setting two -->
	<input type="checkbox" name="prefix_settings[setting_two]" id="prefix_settings[setting_two]" value="1" 
		<?php if ( isset( $settings['setting_two'] ) ) { checked( '1', $settings['setting_two'] ); } ?>>
		<label for="prefix_settings[setting_two]"><?php esc_html_e('Setting two', 'abl_prefix_td') ?></label>
		<br>

	<?php
}
 

/**
 * Admin interface renderer
 *
 * @since 1.0
 */ 
function prefix_admin_interface_render () {
	
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	/**
	 * If settings are inside WP-Admin > Settings, then WordPress will automatically display Settings Saved. If not used this block
	 * @refer	https://core.trac.wordpress.org/ticket/31000
	 * If the user have submitted the settings, WordPress will add the "settings-updated" $_GET parameter to the url
	 *
	if ( isset( $_GET['settings-updated'] ) ) {
		// Add settings saved message with the class of "updated"
		add_settings_error( 'prefix_settings_saved_message', 'prefix_settings_saved_message', __( 'Settings are Saved', 'abl_prefix_td' ), 'updated' );
	}
 
	// Show Settings Saved Message
	settings_errors( 'prefix_settings_saved_message' ); */?> 
	
	<div class="wrap">	
		<h1>Starter Plugin</h1>
		
		<form action="options.php" method="post">		
			<?php
			// Output nonce, action, and option_page fields for a settings page.
			settings_fields( 'prefix_settings_group' );
			
			// Prints out all settings sections added to a particular settings page. 
			do_settings_sections( 'starter-plugin' );	// Page slug
			
			// Output save settings button
			submit_button( __('Save Settings', 'abl_prefix_td') );
			?>
		</form>
	</div>
	<?php
}
 
?>