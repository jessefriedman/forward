<?php
/*
Plugin Name: Forward
Description: A WordPress plugin to help you keep your blogging goals on track
Author: Jesse Friedman
Version: 0.1
License: GPLv2 or later
*/

class WP_Forward {

	static $forward;
	const VERSION = 0.1;
	var $current_frwrd_options;
	var $frwrd_draft_cat;
	var $frwrd_frequency = array( 'Everyday' => '7', '6 days a week' => '6', '5 days a week' => '5', '4 days a week' => '4', '3 days a week' => '3', '2 days a week' => '2', 'Once a week' => '1' );


	function __construct() {
		register_activation_hook( __FILE__, array( $this, 'frwrd_create_holding_category' ) );
		add_action( 'admin_init', array( $this, 'frwrd_admin_init' ) );
		add_action( 'admin_menu', array( $this, 'frwrd_admin_menu' ) );
	}

	function frwrd_admin_init() {
		$settings_updated = $_GET
		$this->current_frwrd_options = get_option( 'frwrd_config_settings' ) ? get_option( 'frwrd_config_settings' ) : 'empty';
		register_setting( 'frwrd-config-settings', 'frwrd_config_settings' );
		add_settings_section( 'frwrd_goals', '', '__return_false', 'frwrd-config-settings' );
		add_settings_field( 'frwrd_freq_id', __( 'Goals', 'frwrd'), array( $this, 'frwrd_goal_options'), 'frwrd-config-settings', 'frwrd_goals' );
		add_settings_field( 'frwrd_email_id', __( 'Alerts', 'frwrd'), array( $this, 'frwrd_alert_options'), 'frwrd-config-settings', 'frwrd_goals' );
		add_settings_field( 'frwrd_planb_id', __( 'Plan B', 'frwrd'), array( $this, 'frwrd_planb_options'), 'frwrd-config-settings', 'frwrd_goals' );
	}

	function frwrd_goal_options() {
		?>
		<p class="description"><?php esc_html_e( 'I want to post', 'frwrd' );
			?>
			<select name="frwrd_config_settings[frwrd_freq_id]">
				<?php foreach( $this->frwrd_frequency as $key => $value ) { ?>
					<option value="<?php echo $value; ?>" <?php if( $this->current_frwrd_options['frwrd_freq_id'] == $value ) echo 'selected'; ?> ><?php echo $key; ?></option>
				<?php } ?>
			</select>
		</p>
		<?php
	}

	function frwrd_alert_options() {
		$current_frwrd_email_id = $this->current_frwrd_options['frwrd_email_id'] ? $this->current_frwrd_options['frwrd_email_id'] : $current_frwrd_email_id = get_option( 'admin_email' );
		?>
		<p class="description"><?php esc_html_e( 'Email ', 'frwrd' ); ?>
			<input type="email" name="frwrd_config_settings[frwrd_email_id]" value="<?php echo $current_frwrd_email_id ?>" />
			<?php esc_html_e( ' when I\'m falling behind on my goal ', 'frwrd' ); ?>
		</p>
	<?php
	}

	function frwrd_planb_options() {
		isset($this->current_frwrd_options['frwrd_planb_id']) ? $current_frwrd_planb_id = $this->current_frwrd_options['frwrd_planb_id'] : $current_frwrd_planb_id = 0;
		?>
		<p class="description">
			<input name="frwrd_config_settings[frwrd_planb_id]" type="checkbox" id="frwrd_planb" value="1" <?php if( 1 == $current_frwrd_planb_id ) echo 'checked="checked"'; ?> >
			<?php _e( 'I want <strong>Forward</strong> to publish a post saved in the "Drafted Forward" category to keep me on point.', 'frwrd' ); ?>
		</p>
	<?php
	}

	function frwrd_admin_menu() {
		$hook = add_options_page( __( 'Forward', 'frwrd' ), __( 'Forward', 'frwrd' ), 'manage_options', 'frwrd', array( $this, 'frwrd_build_options' ) );
	}

	function frwrd_build_options() {
		?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Forward', 'frwrd' ); ?></h2>

			<form action="options.php" method="post">
				<?php settings_fields( 'frwrd-config-settings' ); ?>
				<?php do_settings_sections( 'frwrd-config-settings' ); ?>
				<p class="submit"><input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ); ?>" /></p>
			</form>

		</div>
	<?php
	}

	function frwrd_create_holding_category() {
		$this->frwrd_draft_cat = wp_create_category( 'Forward Drafts', 0 );
		update_option( 'frwrd_draft_cat', $this->frwrd_draft_cat );
	}



	/**
	 * On activation, set a time, frequency and name of an action hook to be scheduled.
	 */
	function prefix_activation() {

	}

add_action( 'prefix_hourly_event_hook', 'prefix_do_this_hourly' );
	/**
	 * On the scheduled action hook, run the function.
	 */
	function prefix_do_this_hourly() {
		// do something every hour
	}

}

new WP_Forward;