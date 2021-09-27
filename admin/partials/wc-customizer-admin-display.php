<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Wc_Customizer
 * @subpackage Wc_Customizer/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="wc_customizer">
    <h3 class="wc_customizer-title">Customizer Settings</h3>
    <hr>
    <div class="wc_customizer-content">
        <form style="width: fit-content" method="post" action="options.php">
	        <table class="widefat">
            <?php
            settings_fields( 'wc_customizer_settings_section' );
            do_settings_fields( 'wc_customizer_settings_page', 'wc_customizer_settings_section' );
            ?>
	        </table>
		    <?php submit_button(); ?>
	    </form>
    </div>
</div>