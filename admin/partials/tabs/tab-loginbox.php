<?php
/**
 * Partial template for the Login Box Styles tab
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

// Get options
$options = get_option($this->option_name);
?>

<!-- Login Box Styles Tab Content -->
<div id="passpro-tab-loginbox" class="passpro-tab-content">
    <div class="passpro-settings-header">
        <div class="passpro-settings-title">
            <span class="dashicons dashicons-feedback"></span>
            <h2><?php esc_html_e('Login Box Styles', 'passpro'); ?></h2>
        </div>
        <p class="passpro-settings-description"><?php esc_html_e('Configure the appearance of the login form container.', 'passpro'); ?></p>
    </div>

    <div class="passpro-settings-grid">
        <!-- Background Color Card -->
        <div class="passpro-setting-card passpro-box-bg-card">
            <div class="passpro-setting-card-content">
                <div class="passpro-setting-card-header">
                    <span class="passpro-setting-card-title"><?php esc_html_e('Background Color', 'passpro'); ?></span>
                </div>
                <?php 
                $bg_color = isset($options['passpro_box_bg_color']) ? $options['passpro_box_bg_color'] : '#ffffff';
                ?>
                <div class="passpro-color-field-wrapper">
                    <input type="text" id="passpro_box_bg_color" name="<?php echo esc_attr($this->option_name); ?>[passpro_box_bg_color]" value="<?php echo esc_attr($bg_color); ?>" class="passpro-color-picker-button" data-default-color="#ffffff" />
                    <input type="color" class="passpro-color-preview" value="<?php echo esc_attr($bg_color); ?>" data-target="passpro_box_bg_color" />
                </div>
                <p class="passpro-setting-card-description">
                    <?php esc_html_e('Choose the background color for the login form container.', 'passpro'); ?>
                </p>
            </div>
            <div class="passpro-setting-card-icon">
                <span class="dashicons dashicons-art"></span>
            </div>
        </div>

        <!-- Border Card -->
        <div class="passpro-setting-card passpro-box-border-card">
            <div class="passpro-setting-card-content">
                <div class="passpro-setting-card-header">
                    <span class="passpro-setting-card-title"><?php esc_html_e('Border Properties', 'passpro'); ?></span>
                </div>
                <?php 
                $border_color = isset($options['passpro_box_border_color']) ? $options['passpro_box_border_color'] : '#dddddd';
                $border_width = isset($options['passpro_box_border_width']) ? $options['passpro_box_border_width'] : '1';
                $border_radius = isset($options['passpro_box_border_radius']) ? $options['passpro_box_border_radius'] : '0';
                ?>
                <div class="passpro-border-controls">
                    <div class="passpro-border-color">
                        <label for="passpro_box_border_color"><?php esc_html_e('Border Color:', 'passpro'); ?></label>
                        <div class="passpro-color-field-wrapper">
                            <input type="text" id="passpro_box_border_color" name="<?php echo esc_attr($this->option_name); ?>[passpro_box_border_color]" value="<?php echo esc_attr($border_color); ?>" class="passpro-color-picker-button" data-default-color="#dddddd" />
                            <input type="color" class="passpro-color-preview" value="<?php echo esc_attr($border_color); ?>" data-target="passpro_box_border_color" />
                        </div>
                    </div>
                    <div class="passpro-border-size-controls">
                        <div class="passpro-border-width">
                            <label for="passpro_box_border_width"><?php esc_html_e('Border Width (px):', 'passpro'); ?></label>
                            <input type="number" id="passpro_box_border_width" name="<?php echo esc_attr($this->option_name); ?>[passpro_box_border_width]" value="<?php echo esc_attr($border_width); ?>" min="0" max="10" step="1" />
                        </div>
                        <div class="passpro-border-radius">
                            <label for="passpro_box_border_radius"><?php esc_html_e('Border Radius (px):', 'passpro'); ?></label>
                            <input type="number" id="passpro_box_border_radius" name="<?php echo esc_attr($this->option_name); ?>[passpro_box_border_radius]" value="<?php echo esc_attr($border_radius); ?>" min="0" max="50" step="1" />
                        </div>
                    </div>
                </div>
                <p class="passpro-setting-card-description">
                    <?php esc_html_e('Configure the border appearance for the login form container. Adjust the color, width, and corner radius.', 'passpro'); ?>
                </p>
            </div>
            <div class="passpro-setting-card-icon">
                <span class="dashicons dashicons-admin-customizer"></span>
            </div>
        </div>

        <!-- Box Preview Card -->
        <div class="passpro-setting-card passpro-box-preview-card">
            <div class="passpro-setting-card-content">
                <div class="passpro-setting-card-header">
                    <span class="passpro-setting-card-title"><?php esc_html_e('Login Box Preview', 'passpro'); ?></span>
                </div>
                <div class="passpro-box-preview">
                    <div class="passpro-preview-container" id="loginbox-preview">
                        <div class="passpro-preview-label"><?php esc_html_e('Password', 'passpro'); ?></div>
                        <div class="passpro-preview-input"></div>
                        <div class="passpro-preview-button"><?php esc_html_e('Enter', 'passpro'); ?></div>
                    </div>
                </div>
                <p class="passpro-setting-card-description">
                    <?php esc_html_e('A preview of how your login box will appear with the current settings.', 'passpro'); ?>
                </p>
            </div>
            <div class="passpro-setting-card-icon">
                <span class="dashicons dashicons-visibility"></span>
            </div>
        </div>
    </div>
</div> 