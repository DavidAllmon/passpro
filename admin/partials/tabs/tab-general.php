<?php
/**
 * Partial template for the General Settings tab
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    PassPro
 * @subpackage PassPro/admin/partials/tabs
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}
?>

<!-- General Settings Tab Content -->
<div id="passpro-tab-general" class="passpro-tab-content passpro-tab-active">
    <div class="passpro-settings-header">
        <div class="passpro-settings-title">
            <span class="dashicons dashicons-admin-settings"></span>
            <h2><?php esc_html_e('General Settings', 'passpro'); ?></h2>
        </div>
        <p class="passpro-settings-description"><?php esc_html_e('Configure the core settings for your password protection.', 'passpro'); ?></p>
    </div>
    
    <div class="passpro-settings-grid">
        <?php 
        // Directly call the rendering functions for general settings
        if (method_exists($this, 'render_enabled_field')) $this->render_enabled_field();
        if (method_exists($this, 'render_password_field')) $this->render_password_field();
        if (method_exists($this, 'render_allowed_ips_field')) $this->render_allowed_ips_field();
        if (method_exists($this, 'render_show_logout_button_field')) $this->render_show_logout_button_field();
        ?>
    </div>
</div> 