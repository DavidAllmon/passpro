<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    PassPro
 * @subpackage PassPro/admin/partials
 */
?>

<div class="wrap passpro-settings-wrap passpro-admin-modern-wrap">
    <h1 class="passpro-header"><?php echo esc_html( get_admin_page_title() ); ?></h1>
    
    <div class="passpro-dashboard">
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
                        <a href="#passpro-tab-box" class="passpro-tab-link">
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

        <!-- Tab Content Area -->
        <div class="passpro-content-container">
            <form method="post" action="options.php" class="passpro-settings-form">
                <?php settings_fields($this->option_group); // Output nonce, action, and option_page fields for the group. ?>

        <div id="passpro-settings-tabs">
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
                            // We'll wrap the WordPress settings fields in our custom layout
                            ob_start();
                            do_settings_fields($this->option_group, $this->plugin_name . '_general_section');
                            $settings_fields = ob_get_clean();
                            
                            // Process the settings HTML to extract field information
                            // This is a placeholder - the actual implementation would depend on your field structure
                            echo '<div class="passpro-settings-wrapper">';
                            echo $settings_fields;
                            echo '</div>';
                            ?>
                        </div>
                    </div>

                    <!-- Appearance Tab Content -->
                    <div id="passpro-tab-appearance" class="passpro-tab-content">
                        <div class="passpro-settings-header">
                            <div class="passpro-settings-title">
                                <span class="dashicons dashicons-admin-appearance"></span>
                                <h2><?php esc_html_e('Login Page Appearance', 'passpro'); ?></h2>
                            </div>
                            <p class="passpro-settings-description"><?php esc_html_e('Customize the visual appearance of your login page.', 'passpro'); ?></p>
            </div>

                        <div class="passpro-settings-grid">
                            <div class="passpro-settings-wrapper">
                                <?php do_settings_fields($this->option_group, $this->plugin_name . '_appearance_section'); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Login Box Styles Tab Content -->
                    <div id="passpro-tab-box" class="passpro-tab-content">
                        <div class="passpro-settings-header">
                            <div class="passpro-settings-title">
                                <span class="dashicons dashicons-feedback"></span>
                                <h2><?php esc_html_e('Login Box Styles', 'passpro'); ?></h2>
                            </div>
                            <p class="passpro-settings-description"><?php esc_html_e('Configure the appearance of the login form container.', 'passpro'); ?></p>
            </div>

                        <div class="passpro-settings-grid">
                            <div class="passpro-settings-wrapper">
                                <?php do_settings_fields($this->option_group, $this->plugin_name . '_box_style_section'); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Text Styles Tab Content -->
                    <div id="passpro-tab-text" class="passpro-tab-content">
                        <div class="passpro-settings-header">
                            <div class="passpro-settings-title">
                                <span class="dashicons dashicons-editor-textcolor"></span>
                                <h2><?php esc_html_e('Text Styles', 'passpro'); ?></h2>
                            </div>
                            <p class="passpro-settings-description"><?php esc_html_e('Customize the typography and text appearance on your login page.', 'passpro'); ?></p>
            </div>

                        <div class="passpro-settings-grid">
                            <div class="passpro-settings-wrapper">
                                <?php do_settings_fields($this->option_group, $this->plugin_name . '_text_style_section'); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Button Styles Tab Content -->
                    <div id="passpro-tab-button" class="passpro-tab-content">
                        <div class="passpro-settings-header">
                            <div class="passpro-settings-title">
                                <span class="dashicons dashicons-button"></span>
                                <h2><?php esc_html_e('Login Button Styles', 'passpro'); ?></h2>
                            </div>
                            <p class="passpro-settings-description"><?php esc_html_e('Design the appearance of buttons on your login page.', 'passpro'); ?></p>
            </div>

                        <div class="passpro-settings-grid">
                            <div class="passpro-settings-wrapper">
                                <?php do_settings_fields($this->option_group, $this->plugin_name . '_button_style_section'); ?>
                            </div>
                        </div>
                    </div>
            </div>

                <div class="passpro-card passpro-actions-card">
                    <div class="passpro-card-body">
                        <div class="passpro-form-actions">
                            <button type="submit" class="button button-primary">
                                <span class="dashicons dashicons-saved"></span>
                                <?php esc_html_e('Save Settings', 'passpro'); ?>
                            </button>
                            <div class="passpro-action-description">
                                <span class="dashicons dashicons-info"></span>
                                <span><?php esc_html_e('Save your changes to update the password protection settings.', 'passpro'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Modern design styles for the PassPro Settings page */
.passpro-header {
    margin-bottom: 25px !important;
    font-size: 28px !important;
    font-weight: 400 !important;
    color: #23282d;
}

.passpro-dashboard {
    display: grid;
    grid-template-columns: 280px minmax(500px, 1fr);
    gap: 24px;
    margin-bottom: 25px;
}

@media (max-width: 1200px) {
    .passpro-dashboard {
        grid-template-columns: 1fr;
    }
}

.passpro-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.2s ease;
    margin-bottom: 24px;
}

.passpro-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
}

.passpro-card-header {
    padding: 18px 24px;
    border-bottom: 1px solid #f0f0f1;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.passpro-card-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.passpro-card-title h2 {
    margin: 0;
    font-size: 18px;
    font-weight: 500;
    color: #23282d;
}

.passpro-card-title .dashicons {
    font-size: 22px;
    width: 22px;
    height: 22px;
    color: #2271b1;
}

.passpro-card-body {
    padding: 24px;
}

/* Content container (holds all tab content and action card) */
.passpro-content-container {
    display: flex;
    flex-direction: column;
}

/* Vertical Tabs Navigation */
.passpro-vertical-tabs {
    list-style: none;
    margin: 0;
    padding: 0;
}

.passpro-vertical-tabs li {
    margin-bottom: 8px;
}

.passpro-vertical-tabs .passpro-tab-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    border-radius: 6px;
    background-color: #f8f9fa;
    color: #50575e;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s ease;
    border-left: 3px solid transparent;
}

.passpro-vertical-tabs .passpro-tab-link:hover,
.passpro-vertical-tabs .passpro-tab-link:focus {
    background-color: #f0f0f1;
    color: #2271b1;
}

.passpro-vertical-tabs .passpro-tab-link.passpro-tab-active {
    background-color: #f0f6fc;
    color: #2271b1;
    border-left: 3px solid #2271b1;
    font-weight: 600;
}

.passpro-vertical-tabs .dashicons {
    font-size: 18px;
    width: 18px;
    height: 18px;
}

/* Tab Content */
.passpro-tab-content {
    display: none;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    margin-bottom: 24px;
}

.passpro-tab-content:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
}

.passpro-tab-content.passpro-tab-active {
    display: block;
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* REDESIGNED GENERAL SETTINGS */
.passpro-settings-header {
    background: linear-gradient(135deg, #2271b1 0%, #135e96 100%);
    color: #fff;
    padding: 24px 30px;
    border-radius: 8px 8px 0 0;
}

.passpro-settings-title {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 8px;
}

.passpro-settings-title h2 {
    margin: 0;
    font-size: 20px;
    font-weight: 500;
    color: #fff;
}

.passpro-settings-title .dashicons {
    font-size: 24px;
    width: 24px;
    height: 24px;
    color: #fff;
}

.passpro-settings-description {
    margin: 0;
    font-size: 14px;
    opacity: 0.9;
}

.passpro-settings-grid {
    padding: 30px;
}

.passpro-settings-wrapper {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

/* Transform Form Tables into modern fields */
.passpro-settings-wrapper .form-table {
    border: none;
    margin: 0;
    padding: 0;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.passpro-settings-wrapper .form-table tr {
    display: flex;
    flex-wrap: wrap;
    padding: 20px;
    border-bottom: 1px solid #f0f0f1;
    transition: background-color 0.2s ease;
}

.passpro-settings-wrapper .form-table tr:hover {
    background-color: #f9fafb;
}

.passpro-settings-wrapper .form-table tr:last-child {
    border-bottom: none;
}

.passpro-settings-wrapper .form-table th,
.passpro-settings-wrapper .form-table td {
    padding: 0;
}

.passpro-settings-wrapper .form-table th {
    flex: 0 0 220px;
    font-weight: 500;
    color: #23282d;
    padding-right: 30px;
    padding-top: 8px;
}

.passpro-settings-wrapper .form-table label {
    font-size: 15px;
    font-weight: 500;
    color: #1d2327;
    margin-bottom: 6px;
    display: block;
}

.passpro-settings-wrapper .form-table td {
    flex: 1 1 300px;
}

/* Input field styling in General tab */
.passpro-settings-wrapper input[type="text"],
.passpro-settings-wrapper input[type="number"],
.passpro-settings-wrapper input[type="password"],
.passpro-settings-wrapper textarea,
.passpro-settings-wrapper select {
    border: 1px solid #d0d5dd;
    border-radius: 6px;
    padding: 10px 14px;
    min-height: 40px;
    width: 100%;
    max-width: 400px;
    font-size: 14px;
    color: #2c3338;
    box-shadow: 0 1px 2px rgba(16, 24, 40, 0.05);
    transition: all 0.2s ease;
    background-color: #fff;
}

.passpro-settings-wrapper input[type="text"]:focus,
.passpro-settings-wrapper input[type="number"]:focus,
.passpro-settings-wrapper input[type="password"]:focus,
.passpro-settings-wrapper textarea:focus,
.passpro-settings-wrapper select:focus {
    border-color: #2271b1;
    box-shadow: 0 0 0 4px rgba(34, 113, 177, 0.1);
    outline: none;
}

.passpro-settings-wrapper input[type="text"]::placeholder,
.passpro-settings-wrapper input[type="number"]::placeholder,
.passpro-settings-wrapper input[type="password"]::placeholder,
.passpro-settings-wrapper textarea::placeholder {
    color: #98a2b3;
}

.passpro-settings-wrapper select {
    min-width: 200px;
    background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M5%206l5%205%205-5%202%201-7%207-7-7%202-1z%22%20fill%3D%22%23555%22%2F%3E%3C%2Fsvg%3E");
    background-repeat: no-repeat;
    background-position: right 14px top 50%;
    background-size: 16px 16px;
    padding-right: 40px;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
}

/* Checkbox styling in General tab */
.passpro-settings-wrapper input[type="checkbox"] {
    position: relative;
    width: 18px;
    height: 18px;
    margin-right: 10px;
    border: 1px solid #d0d5dd;
    border-radius: 4px;
    background-color: #fff;
    box-shadow: 0 1px 2px rgba(16, 24, 40, 0.05);
    transition: all 0.2s ease;
    appearance: none;
    -webkit-appearance: none;
    cursor: pointer;
}

.passpro-settings-wrapper input[type="checkbox"]:checked {
    background-color: #2271b1;
    border-color: #2271b1;
}

.passpro-settings-wrapper input[type="checkbox"]:checked::before {
    content: "";
    position: absolute;
    top: 3px;
    left: 6px;
    width: 4px;
    height: 8px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

.passpro-settings-wrapper input[type="checkbox"]:focus {
    border-color: #2271b1;
    box-shadow: 0 0 0 4px rgba(34, 113, 177, 0.1);
    outline: none;
}

.passpro-settings-wrapper .checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    cursor: pointer;
}

.passpro-settings-wrapper .checkbox-label span {
    padding-top: 1px;
}

/* Field descriptions in General tab */
.passpro-settings-wrapper .description {
    margin-top: 8px;
    font-size: 13px;
    color: #667085;
    line-height: 1.5;
    max-width: 400px;
    display: block;
}

/* Group fields in General tab */
.passpro-settings-wrapper .field-group {
    background-color: #f9fafb;
    border: 1px solid #eaecf0;
    border-radius: 8px;
    padding: 16px;
    margin: 8px 0 16px;
}

/* Section headings in General tab */
.passpro-settings-wrapper .section-heading {
    font-size: 16px;
    font-weight: 600;
    color: #1d2327;
    margin: 0 0 16px 0;
    padding: 0 0 12px 0;
    border-bottom: 1px solid #eaecf0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.passpro-settings-wrapper .section-heading .dashicons {
    color: #2271b1;
    font-size: 18px;
    width: 18px;
    height: 18px;
}

/* Form actions card */
.passpro-actions-card {
    background: #f8f9fa;
    border-top: 4px solid #2271b1;
}

.passpro-form-actions {
    display: flex;
    align-items: center;
    gap: 16px;
}

.passpro-form-actions .button-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 0 20px;
    height: 40px;
    line-height: 38px;
    font-size: 14px;
    border-radius: 6px;
    box-shadow: 0 1px 2px rgba(16, 24, 40, 0.05);
    font-weight: 500;
    transition: all 0.2s ease;
}

.passpro-form-actions .button-primary:hover {
    background-color: #175c8e;
    border-color: #175c8e;
}

.passpro-form-actions .button-primary .dashicons {
    font-size: 18px;
    width: 18px;
    height: 18px;
}

.passpro-action-description {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #667085;
}

.passpro-action-description .dashicons {
    color: #2271b1;
}

/* Responsive adjustments */
@media (max-width: 782px) {
    .passpro-settings-wrapper .form-table th,
    .passpro-settings-wrapper .form-table td {
        flex: 0 0 100%;
    }
    
    .passpro-settings-wrapper .form-table th {
        margin-bottom: 12px;
    }
    
    .passpro-form-actions {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .passpro-action-description {
        margin-top: 12px;
    }
}

/* Form Field Grid for other tabs */
.passpro-field-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0;
}

/* Transform Form Tables into modern fields */
.passpro-field-grid .form-table {
    border: none;
    margin: 0;
    padding: 0;
}

.passpro-field-grid .form-table tr {
    display: flex;
    flex-wrap: wrap;
    padding: 16px 0;
    border-bottom: 1px solid #f0f0f1;
}

.passpro-field-grid .form-table tr:last-child {
    border-bottom: none;
}

.passpro-field-grid .form-table th,
.passpro-field-grid .form-table td {
    padding: 0;
}

.passpro-field-grid .form-table th {
    flex: 0 0 200px;
    font-weight: 500;
    color: #23282d;
    padding-right: 24px;
}

.passpro-field-grid .form-table td {
    flex: 1 1 300px;
}

/* Input field styling */
.passpro-field-grid input[type="text"],
.passpro-field-grid input[type="number"],
.passpro-field-grid input[type="password"],
.passpro-field-grid textarea,
.passpro-field-grid select {
    border: 1px solid #8c8f94;
    border-radius: 4px;
    padding: 8px 12px;
    min-height: 36px;
    width: 100%;
    max-width: 400px;
    font-size: 14px;
    color: #2c3338;
    box-shadow: 0 0 0 transparent;
    transition: border-color 0.2s ease;
}

.passpro-field-grid input[type="text"]:focus,
.passpro-field-grid input[type="number"]:focus,
.passpro-field-grid input[type="password"]:focus,
.passpro-field-grid textarea:focus,
.passpro-field-grid select:focus {
    border-color: #2271b1;
    box-shadow: 0 0 0 1px #2271b1;
    outline: none;
}

.passpro-field-grid select {
    min-width: 200px;
    height: 36px;
    background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M5%206l5%205%205-5%202%201-7%207-7-7%202-1z%22%20fill%3D%22%23555%22%2F%3E%3C%2Fsvg%3E");
    background-repeat: no-repeat;
    background-position: right 5px top 55%;
    background-size: 16px 16px;
    padding-right: 30px;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
}

/* Checkbox styling */
.passpro-field-grid input[type="checkbox"] {
    border: 1px solid #8c8f94;
    border-radius: 4px;
    width: 18px;
    height: 18px;
    margin-right: 8px;
    position: relative;
    top: 2px;
}

.passpro-field-grid input[type="checkbox"]:checked {
    background-color: #2271b1;
    border-color: #2271b1;
}

.passpro-field-grid input[type="checkbox"]:checked::before {
    content: "\f147";
    font-family: 'dashicons';
    font-size: 16px;
    color: #fff;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

/* Section headings */
.passpro-field-grid .section-heading {
    width: 100%;
    background: #f0f6fc;
    color: #2271b1;
    padding: 14px 16px;
    margin: 0 -24px 20px -24px;
    border-left: 4px solid #2271b1;
    font-size: 15px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.passpro-field-grid .section-heading:first-child {
    margin-top: -24px;
}

.passpro-field-grid .section-heading + td {
    display: none;
}

/* Field descriptions */
.passpro-field-grid .description {
    margin-top: 8px;
    font-size: 13px;
    color: #646970;
    line-height: 1.5;
}

/* Special styling for logo preview */
.passpro-logo-preview {
    margin-top: 16px;
    padding: 16px;
    background-color: #f8f9fa;
    border: 1px solid #e2e4e7;
    border-radius: 6px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.passpro-logo-preview img {
    max-width: 100%;
    max-height: 80px;
}

/* Color picker fields */
.passpro-field-grid .wp-picker-container {
    display: inline-block;
}

.passpro-field-grid .wp-color-result {
    height: 30px;
    border-radius: 4px;
    border: 1px solid #8c8f94;
    box-shadow: 0 1px 0 #ccc;
}

.passpro-field-grid .wp-color-result-text {
    line-height: 28px;
    border-radius: 0 2px 2px 0;
    background: #f6f7f7;
    border-left: 1px solid #dcdcde;
    padding: 0 10px;
}

.passpro-field-grid .wp-picker-container input[type="text"].wp-color-picker {
    width: 80px !important;
    vertical-align: top;
    border-color: #8c8f94;
}

/* Padding fields container */
.padding-fields-container {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 12px;
    background-color: #f8f9fa;
    border: 1px solid #e2e4e7;
    border-radius: 6px;
    padding: 16px;
    max-width: 400px;
}

.padding-fields-container > div {
    text-align: center;
    flex: 1 1 60px;
}

.padding-fields-container label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}

.padding-fields-container input[type="number"] {
    width: 100%;
    text-align: center;
}

/* Improve group field appearance */
.passpro-field-grid .field-group {
    background-color: #f8f9fa;
    border: 1px solid #e2e4e7;
    border-radius: 6px;
    padding: 16px;
    margin: 8px 0 16px;
}

/* Enhanced Security Settings */
.passpro-security-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
    overflow: hidden;
    border: 1px solid #e2e4e7;
}

.passpro-security-header {
    background: linear-gradient(135deg, #ff5722 0%, #f44336 100%);
    color: #fff;
    padding: 16px 24px;
    font-size: 16px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.passpro-security-header .dashicons {
    font-size: 20px;
    width: 20px;
    height: 20px;
}

.passpro-security-fields {
    padding: 24px;
}

.passpro-security-field {
    padding: 16px;
    border-bottom: 1px solid #f0f0f1;
}

.passpro-security-field:last-child {
    border-bottom: none;
}

.passpro-security-label {
    font-size: 15px;
    font-weight: 500;
    color: #1d2327;
    margin-bottom: 12px;
}

.passpro-security-input {
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.passpro-security-description {
    font-size: 13px;
    color: #667085;
    line-height: 1.5;
}

/* Toggle Switch */
.passpro-toggle-switch {
    position: relative;
    display: inline-block;
    width: 48px;
    height: 24px;
    cursor: pointer;
}

.passpro-toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.passpro-toggle-slider {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #e2e4e7;
    border-radius: 24px;
    transition: .3s;
}

.passpro-toggle-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    border-radius: 50%;
    transition: .3s;
}

.passpro-toggle-switch input:checked + .passpro-toggle-slider {
    background-color: #2271b1;
}

.passpro-toggle-switch input:focus + .passpro-toggle-slider {
    box-shadow: 0 0 1px #2271b1;
}

.passpro-toggle-switch input:checked + .passpro-toggle-slider:before {
    transform: translateX(24px);
}

/* Security Status */
.passpro-security-status {
    font-size: 14px;
    color: #1d2327;
}

.status-enabled {
    font-weight: 600;
    color: #00a32a;
}

.status-disabled {
    font-weight: 600;
    color: #d63638;
}

/* Password Field */
.passpro-password-container {
    display: flex;
    align-items: center;
    position: relative;
    width: 100%;
    max-width: 400px;
}

.passpro-password-container input {
    flex: 1;
    padding-right: 40px;
}

.passpro-password-toggle {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #667085;
    cursor: pointer;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.passpro-password-toggle:hover {
    color: #2271b1;
}

.passpro-password-toggle .dashicons {
    font-size: 18px;
    width: 18px;
    height: 18px;
}

/* IP Address Textarea */
.passpro-security-field textarea {
    min-height: 100px;
    font-family: monospace;
    width: 100%;
    resize: vertical;
}

/* Section Cards for All Tabs */
.passpro-section-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    margin-bottom: 20px;
    overflow: hidden;
    transition: all 0.2s ease;
}

.passpro-section-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
}

.passpro-section-header {
    background-color: #f0f6fc;
    padding: 14px 18px;
    border-bottom: 1px solid #e2e4e7;
    border-left: 4px solid #2271b1;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 15px;
    font-weight: 600;
    color: #1d2327;
}

.passpro-section-header .dashicons {
    color: #2271b1;
    font-size: 18px;
    width: 18px;
    height: 18px;
}

.passpro-section-content {
    padding: 20px;
}

/* Section content rows styling */
.passpro-section-content tr {
    display: flex;
    flex-wrap: wrap;
    padding: 14px 0;
    border-bottom: 1px solid #f0f0f1;
    transition: background-color 0.2s ease;
}

.passpro-section-content tr:hover {
    background-color: #f9fafb;
}

.passpro-section-content tr:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.passpro-section-content tr:first-child {
    padding-top: 0;
}

.passpro-section-content th,
.passpro-section-content td {
    padding: 0;
}

.passpro-section-content th {
    flex: 0 0 220px;
    font-weight: 500;
    color: #23282d;
    padding-right: 30px;
    padding-top: 8px;
}

.passpro-section-content label {
    font-size: 15px;
    font-weight: 500;
    color: #1d2327;
    margin-bottom: 6px;
    display: block;
}

.passpro-section-content td {
    flex: 1 1 300px;
}

/* Add specific styling for different types of inputs */
.passpro-settings-wrapper .wp-picker-container {
    display: inline-flex;
    align-items: center;
}

.passpro-settings-wrapper .wp-color-result {
    margin-right: 10px;
    border-radius: 4px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

/* File input styling */
.passpro-file-input-container {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.passpro-file-input-container input[type="file"] {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    border: 0;
}

.passpro-file-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 12px;
    background-color: #f0f0f1;
    color: #2c3338;
    border: 1px solid #c3c4c7;
    border-radius: 4px;
    cursor: pointer;
    font-size: 13px;
    transition: all 0.2s ease;
}

.passpro-file-button:hover {
    background-color: #e2e4e7;
}

.passpro-file-name {
    font-size: 13px;
    color: #50575e;
}

/* Visual enhancements for all tabs */
.passpro-tab-content {
    border: none;
    box-shadow: none;
}

/* Responsive adjustments for section cards */
@media (max-width: 782px) {
    .passpro-section-content th,
    .passpro-section-content td {
        flex: 0 0 100%;
    }
    
    .passpro-section-content th {
        margin-bottom: 12px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Tab switching
    $('#passpro-settings-tabs-nav a').on('click', function(e) {
        e.preventDefault();
        var targetId = $(this).attr('href');
        
        // Update active classes on tab links
        $('#passpro-settings-tabs-nav a').removeClass('passpro-tab-active');
        $(this).addClass('passpro-tab-active');
        
        // Update active classes on content divs
        $('#passpro-settings-tabs > div').removeClass('passpro-tab-active');
        $(targetId).addClass('passpro-tab-active');
    });
    
    // Add icons to section headings
    $('.section-heading').each(function() {
        if ($(this).find('.dashicons').length === 0) {
            var headingText = $(this).text();
            if (headingText.includes('Color')) {
                $(this).prepend('<span class="dashicons dashicons-admin-appearance"></span>');
            } else if (headingText.includes('Border')) {
                $(this).prepend('<span class="dashicons dashicons-admin-customizer"></span>');
            } else if (headingText.includes('Size')) {
                $(this).prepend('<span class="dashicons dashicons-editor-expand"></span>');
            } else if (headingText.includes('Button')) {
                $(this).prepend('<span class="dashicons dashicons-button"></span>');
            } else if (headingText.includes('Text')) {
                $(this).prepend('<span class="dashicons dashicons-editor-textcolor"></span>');
            } else {
                $(this).prepend('<span class="dashicons dashicons-admin-generic"></span>');
            }
        }
    });
    
    // Enhance form fields with better styling across all tabs
    enhanceAllTabsFormFields();
    
    // Enhance security settings in General tab
    enhanceSecuritySettings();
    
    // Make sure color pickers look good
    if (typeof $.fn.wpColorPicker !== 'undefined') {
        $('.passpro-field-grid .wp-color-picker, .passpro-settings-wrapper .wp-color-picker').wpColorPicker({
            change: function(event, ui) {
                // Trigger change event to ensure value is saved
                $(this).trigger('change');
            }
        });
    }
    
    // Function to enhance all tabs form fields
    function enhanceAllTabsFormFields() {
        // Apply to all tabs
        $('.passpro-tab-content .form-table tr').each(function() {
            // Check if the row has a section heading and skip it if it does
            if ($(this).find('.section-heading').length === 0) {
                // Add hover effect class
                $(this).addClass('passpro-settings-row');
                
                // Enhance checkbox fields
                var checkbox = $(this).find('input[type="checkbox"]');
                if (checkbox.length > 0) {
                    var label = $(this).find('label');
                    if (label.length > 0) {
                        checkbox.wrap('<div class="checkbox-container"></div>');
                        label.addClass('checkbox-label');
                    }
                }
            }
        });
        
        // Group related settings by their section heading in all tabs
        $('.passpro-tab-content').each(function() {
            var currentTab = $(this);
            
            // Find and enhance section headings
            currentTab.find('.section-heading').each(function() {
                var sectionHeading = $(this);
                var sectionTitle = sectionHeading.text().trim();
                
                // Create a card for this section
                var sectionCard = $('<div class="passpro-section-card"></div>');
                
                // Add heading
                sectionCard.append(
                    '<div class="passpro-section-header">' + 
                        '<span class="dashicons ' + getIconForSection(sectionTitle) + '"></span>' +
                        '<span class="section-title">' + sectionTitle + '</span>' +
                    '</div>'
                );
                
                // Get all rows until the next section heading or end of table
                var sectionRows = sectionHeading.closest('tr').nextUntil('.section-heading').addBack();
                
                // Clone the rows to the section card
                var sectionContent = $('<div class="passpro-section-content"></div>');
                sectionRows.each(function() {
                    // Skip the heading row itself
                    if (!$(this).find('.section-heading').length) {
                        sectionContent.append($(this).clone());
                    }
                });
                
                // Add content to card if we have any rows
                if (sectionContent.children().length > 0) {
                    sectionCard.append(sectionContent);
                    
                    // Insert the card before the first row of this section
                    sectionHeading.closest('tr').before(sectionCard);
                    
                    // Hide the original rows
                    sectionRows.hide();
                }
            });
        });
        
        // Style special input types across all tabs
        enhanceSpecialInputs();
    }
    
    // Function to get appropriate dashicon for a section
    function getIconForSection(title) {
        if (title.includes('Color')) {
            return 'dashicons-admin-appearance';
        } else if (title.includes('Border')) {
            return 'dashicons-admin-customizer';
        } else if (title.includes('Size') || title.includes('Dimension')) {
            return 'dashicons-editor-expand';
        } else if (title.includes('Button')) {
            return 'dashicons-button';
        } else if (title.includes('Text') || title.includes('Font')) {
            return 'dashicons-editor-textcolor';
        } else if (title.includes('Background')) {
            return 'dashicons-art';
        } else if (title.includes('Logo') || title.includes('Image')) {
            return 'dashicons-format-image';
        } else {
            return 'dashicons-admin-generic';
        }
    }
    
    // Function to enhance special inputs like color pickers, sliders, etc.
    function enhanceSpecialInputs() {
        // Make empty file inputs have a better UI
        $('.passpro-settings-wrapper input[type="file"]').each(function() {
            var fileInput = $(this);
            var fileInputId = fileInput.attr('id');
            
            // Create the enhanced file input
            var fileContainer = $('<div class="passpro-file-input-container"></div>');
            var fileLabel = $('<label class="passpro-file-button button">Choose File</label>').attr('for', fileInputId);
            var fileNameDisplay = $('<span class="passpro-file-name">No file chosen</span>');
            
            // Add the elements
            fileContainer.append(fileInput);
            fileContainer.append(fileLabel);
            fileContainer.append(fileNameDisplay);
            
            // Replace the original input with our container
            fileInput.after(fileContainer);
            fileContainer.append(fileInput);
            
            // Update the filename display when a file is selected
            fileInput.on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                if (fileName) {
                    fileNameDisplay.text(fileName);
                } else {
                    fileNameDisplay.text('No file chosen');
                }
            });
        });
    }
    
    // Function to enhance security settings
    function enhanceSecuritySettings() {
        // Find the enable protection, password, and allowed IP fields
        var enableProtectionRow = null;
        var passwordRow = null;
        var allowedIPRow = null;
        
        $('#passpro-tab-general .form-table tr').each(function() {
            var label = $(this).find('th label').text().trim();
            
            if (label.includes('Enable Protection')) {
                enableProtectionRow = $(this);
            } else if (label.includes('Password')) {
                passwordRow = $(this);
            } else if (label.includes('Allowed IP Addresses')) {
                allowedIPRow = $(this);
            }
        });
        
        // If we found all three, enhance them
        if (enableProtectionRow && passwordRow && allowedIPRow) {
            // Create a security settings card
            var securityCard = $('<div class="passpro-security-card"></div>');
            
            // Add heading
            securityCard.append('<div class="passpro-security-header"><span class="dashicons dashicons-shield"></span> Security Settings</div>');
            
            // Create container for the three settings
            var securityFields = $('<div class="passpro-security-fields"></div>');
            
            // Add the three settings to the container
            securityFields.append(createSecurityField(enableProtectionRow, 'protection'));
            securityFields.append(createSecurityField(passwordRow, 'password'));
            securityFields.append(createSecurityField(allowedIPRow, 'ip'));
            
            // Add the fields to the card
            securityCard.append(securityFields);
            
            // Insert the card before the first of these rows
            enableProtectionRow.before(securityCard);
            
            // Hide the original rows
            enableProtectionRow.hide();
            passwordRow.hide();
            allowedIPRow.hide();
        }
    }
    
    // Helper function to create security field
    function createSecurityField(row, type) {
        var field = $('<div class="passpro-security-field"></div>');
        
        // Get label and description
        var label = row.find('th label').text().trim();
        var description = row.find('.description').text().trim();
        
        // Add label
        field.append('<div class="passpro-security-label">' + label + '</div>');
        
        // Create field container
        var fieldContent = $('<div class="passpro-security-input"></div>');
        
        // Handle different types
        if (type === 'protection') {
            // Clone checkbox and wrap in styled container
            var checkbox = row.find('input[type="checkbox"]').clone();
            var toggleContainer = $('<label class="passpro-toggle-switch"></label>');
            toggleContainer.append(checkbox);
            toggleContainer.append('<span class="passpro-toggle-slider"></span>');
            fieldContent.append(toggleContainer);
            
            // Add status indicator
            fieldContent.append('<span class="passpro-security-status">Site protection is <span class="status-text">disabled</span></span>');
            
            // Update status text when checkbox changes
            checkbox.on('change', function() {
                var statusText = fieldContent.find('.status-text');
                if ($(this).is(':checked')) {
                    statusText.text('enabled').addClass('status-enabled').removeClass('status-disabled');
                } else {
                    statusText.text('disabled').addClass('status-disabled').removeClass('status-enabled');
                }
            });
            
            // Trigger change to set initial state
            checkbox.trigger('change');
        } 
        else if (type === 'password') {
            // Clone password field
            var passwordField = row.find('input[type="text"], input[type="password"]').clone();
            passwordField.attr('placeholder', 'Enter secure password');
            
            // Create password container with show/hide toggle
            var passwordContainer = $('<div class="passpro-password-container"></div>');
            passwordContainer.append(passwordField);
            passwordContainer.append('<button type="button" class="passpro-password-toggle"><span class="dashicons dashicons-visibility"></span></button>');
            fieldContent.append(passwordContainer);
            
            // Add password toggle functionality
            passwordContainer.find('.passpro-password-toggle').on('click', function(e) {
                e.preventDefault();
                var pwField = passwordContainer.find('input');
                var icon = $(this).find('.dashicons');
                
                if (pwField.attr('type') === 'password') {
                    pwField.attr('type', 'text');
                    icon.removeClass('dashicons-visibility').addClass('dashicons-hidden');
                } else {
                    pwField.attr('type', 'password');
                    icon.removeClass('dashicons-hidden').addClass('dashicons-visibility');
                }
            });
            
            // Initialize as password type
            passwordField.attr('type', 'password');
        } 
        else if (type === 'ip') {
            // Clone IP addresses textarea
            var ipField = row.find('textarea').clone();
            ipField.attr('placeholder', 'Enter one IP address per line\nExample: 192.168.1.1');
            fieldContent.append(ipField);
        }
        
        // Add field to container
        field.append(fieldContent);
        
        // Add description if it exists
        if (description) {
            field.append('<div class="passpro-security-description">' + description + '</div>');
        }
        
        return field;
    }
});
</script> 