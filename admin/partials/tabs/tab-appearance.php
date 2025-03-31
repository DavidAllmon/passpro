<?php
/**
 * Partial template for the Appearance tab
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
        <!-- Logo Setting Card -->
        <div class="passpro-setting-card passpro-logo-card">
            <div class="passpro-setting-card-content">
                <div class="passpro-setting-card-header">
                    <span class="passpro-setting-card-title"><?php esc_html_e('Logo', 'passpro'); ?></span>
                </div>
                <div class="passpro-media-uploader">
                    <?php 
                    $logo_url = isset($options['passpro_logo_url']) ? $options['passpro_logo_url'] : '';
                    ?>
                    <input type="text" id="passpro_logo_url" name="<?php echo esc_attr($this->option_name); ?>[passpro_logo_url]" value="<?php echo esc_attr($logo_url); ?>" class="regular-text passpro-media-url" />
                    <button type="button" class="button passpro-upload-button"><?php esc_html_e('Upload Logo', 'passpro'); ?></button>
                    <button type="button" class="button passpro-remove-button" style="<?php echo empty($logo_url) ? 'display:none;' : ''; ?>"><?php esc_html_e('Remove Logo', 'passpro'); ?></button>
                    <div class="passpro-logo-preview" style="margin-top: 10px;">
                        <?php if (!empty($logo_url)) : ?>
                            <img src="<?php echo esc_url($logo_url); ?>" style="max-width: 200px; max-height: 100px;" />
                        <?php endif; ?>
                    </div>
                </div>
                <p class="passpro-setting-card-description">
                    <?php esc_html_e('Upload or select a logo to display above the headline.', 'passpro'); ?>
                </p>
            </div>
            <div class="passpro-setting-card-icon">
                <span class="dashicons dashicons-format-image"></span>
            </div>
        </div>

        <!-- Logo Size Card -->
        <div class="passpro-setting-card passpro-logo-size-card">
            <div class="passpro-setting-card-content">
                <div class="passpro-setting-card-header">
                    <span class="passpro-setting-card-title"><?php esc_html_e('Logo Size & Alignment', 'passpro'); ?></span>
                </div>
                <?php 
                $logo_width = isset($options['passpro_logo_max_width']) ? $options['passpro_logo_max_width'] : '';
                $logo_height = isset($options['passpro_logo_max_height']) ? $options['passpro_logo_max_height'] : '';
                $logo_alignment = isset($options['passpro_logo_alignment']) ? $options['passpro_logo_alignment'] : 'center';
                ?>
                <div class="passpro-logo-size-controls">
                    <div class="passpro-logo-dimension">
                        <label for="passpro_logo_max_width"><?php esc_html_e('Maximum Width (px):', 'passpro'); ?></label>
                        <input type="number" id="passpro_logo_max_width" name="<?php echo esc_attr($this->option_name); ?>[passpro_logo_max_width]" value="<?php echo esc_attr($logo_width); ?>" placeholder="200" min="0" max="1000" />
                    </div>
                    <div class="passpro-logo-dimension">
                        <label for="passpro_logo_max_height"><?php esc_html_e('Maximum Height (px):', 'passpro'); ?></label>
                        <input type="number" id="passpro_logo_max_height" name="<?php echo esc_attr($this->option_name); ?>[passpro_logo_max_height]" value="<?php echo esc_attr($logo_height); ?>" placeholder="100" min="0" max="1000" />
                    </div>
                    <div class="passpro-logo-alignment">
                        <label for="passpro_logo_alignment"><?php esc_html_e('Alignment:', 'passpro'); ?></label>
                        <select id="passpro_logo_alignment" name="<?php echo esc_attr($this->option_name); ?>[passpro_logo_alignment]">
                            <option value="left" <?php selected($logo_alignment, 'left'); ?>><?php esc_html_e('Left', 'passpro'); ?></option>
                            <option value="center" <?php selected($logo_alignment, 'center'); ?>><?php esc_html_e('Center', 'passpro'); ?></option>
                            <option value="right" <?php selected($logo_alignment, 'right'); ?>><?php esc_html_e('Right', 'passpro'); ?></option>
                        </select>
                    </div>
                </div>
                <p class="passpro-setting-card-description">
                    <?php esc_html_e('Set the maximum dimensions for your logo and choose how it should be aligned.', 'passpro'); ?>
                </p>
            </div>
            <div class="passpro-setting-card-icon">
                <span class="dashicons dashicons-image-crop"></span>
            </div>
        </div>

        <!-- Page Title Card -->
        <div class="passpro-setting-card passpro-page-title-card">
            <div class="passpro-setting-card-content">
                <div class="passpro-setting-card-header">
                    <span class="passpro-setting-card-title"><?php esc_html_e('Page Title', 'passpro'); ?></span>
                </div>
                <?php 
                $page_title = isset($options['passpro_page_title']) ? $options['passpro_page_title'] : '';
                ?>
                <input type="text" id="passpro_page_title" name="<?php echo esc_attr($this->option_name); ?>[passpro_page_title]" value="<?php echo esc_attr($page_title); ?>" class="regular-text" placeholder="<?php echo esc_attr__('Password Protected', 'passpro'); ?>" />
                <p class="passpro-setting-card-description">
                    <?php esc_html_e('The title shown in the browser tab/window. Defaults to \'Password Protected\'.', 'passpro'); ?>
                </p>
            </div>
            <div class="passpro-setting-card-icon">
                <span class="dashicons dashicons-welcome-write-blog"></span>
            </div>
        </div>

        <!-- Headline Card -->
        <div class="passpro-setting-card passpro-headline-card">
            <div class="passpro-setting-card-content">
                <div class="passpro-setting-card-header">
                    <span class="passpro-setting-card-title"><?php esc_html_e('Headline Text', 'passpro'); ?></span>
                </div>
                <?php 
                $headline = isset($options['passpro_headline']) ? $options['passpro_headline'] : '';
                $default_headline = get_bloginfo('name');
                ?>
                <input type="text" id="passpro_headline" name="<?php echo esc_attr($this->option_name); ?>[passpro_headline]" value="<?php echo esc_attr($headline); ?>" class="regular-text" placeholder="<?php echo esc_attr($default_headline); ?>" />
                <p class="passpro-setting-card-description">
                    <?php esc_html_e('The main heading displayed on the login page. Defaults to the site title.', 'passpro'); ?>
                </p>
            </div>
            <div class="passpro-setting-card-icon">
                <span class="dashicons dashicons-heading"></span>
            </div>
        </div>

        <!-- Message Card -->
        <div class="passpro-setting-card passpro-message-card">
            <div class="passpro-setting-card-content">
                <div class="passpro-setting-card-header">
                    <span class="passpro-setting-card-title"><?php esc_html_e('Message Text', 'passpro'); ?></span>
                </div>
                <?php 
                $message = isset($options['passpro_message']) ? $options['passpro_message'] : '';
                ?>
                <textarea id="passpro_message" name="<?php echo esc_attr($this->option_name); ?>[passpro_message]" rows="5" cols="50" class="large-text"><?php echo esc_textarea($message); ?></textarea>
                <p class="passpro-setting-card-description">
                    <?php esc_html_e('Optional message displayed below the password form. Basic HTML is allowed.', 'passpro'); ?>
                </p>
            </div>
            <div class="passpro-setting-card-icon">
                <span class="dashicons dashicons-text-page"></span>
            </div>
        </div>

        <!-- Background Color Card -->
        <div class="passpro-setting-card passpro-background-color-card">
            <div class="passpro-setting-card-content">
                <div class="passpro-setting-card-header">
                    <span class="passpro-setting-card-title"><?php esc_html_e('Background Color', 'passpro'); ?></span>
                </div>
                <?php 
                $bg_color = isset($options['passpro_background_color']) ? $options['passpro_background_color'] : '#f1f1f1';
                ?>
                <div class="passpro-color-field">
                    <input type="text" id="passpro_background_color" name="<?php echo esc_attr($this->option_name); ?>[passpro_background_color]" value="<?php echo esc_attr($bg_color); ?>" class="passpro-color-picker" data-default-color="#f1f1f1" />
                </div>
                <p class="passpro-setting-card-description">
                    <?php esc_html_e('Select the background color for the login page.', 'passpro'); ?>
                </p>
            </div>
            <div class="passpro-setting-card-icon">
                <span class="dashicons dashicons-art"></span>
            </div>
        </div>
    </div>
</div> 