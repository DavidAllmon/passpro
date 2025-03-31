<?php
/**
 * Partial template for the tab navigation
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

<!-- Tab Navigation Card -->
<div class="passpro-card passpro-tabs-card">
    <div class="passpro-card-header">
        <div class="passpro-card-title">
            <span class="dashicons dashicons-admin-generic"></span>
            <h2><?php esc_html_e('Settings Menu', 'passpro'); ?></h2>
        </div>
    </div>
    <div class="passpro-card-body">
        <ul class="passpro-vertical-tabs" id="passpro-settings-tabs-nav">
            <li>
                <a href="#passpro-tab-general" class="passpro-tab-link passpro-tab-active">
                    <span class="dashicons dashicons-admin-settings"></span>
                    <span class="tab-label"><?php esc_html_e('General', 'passpro'); ?></span>
                </a>
            </li>
            <li>
                <a href="#passpro-tab-appearance" class="passpro-tab-link">
                    <span class="dashicons dashicons-admin-appearance"></span>
                    <span class="tab-label"><?php esc_html_e('Appearance', 'passpro'); ?></span>
                </a>
            </li>
            <li>
                <a href="#passpro-tab-loginbox" class="passpro-tab-link">
                    <span class="dashicons dashicons-feedback"></span>
                    <span class="tab-label"><?php esc_html_e('Login Box', 'passpro'); ?></span>
                </a>
            </li>
            <li>
                <a href="#passpro-tab-text" class="passpro-tab-link">
                    <span class="dashicons dashicons-editor-textcolor"></span>
                    <span class="tab-label"><?php esc_html_e('Text Styles', 'passpro'); ?></span>
                </a>
            </li>
            <li>
                <a href="#passpro-tab-button" class="passpro-tab-link">
                    <span class="dashicons dashicons-button"></span>
                    <span class="tab-label"><?php esc_html_e('Button Styles', 'passpro'); ?></span>
                </a>
            </li>
        </ul>
    </div>
</div> 