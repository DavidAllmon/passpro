<?php
/**
 * Partial template for the Text Styles tab
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
        <!-- Headline Text Card -->
        <div class="passpro-setting-card passpro-headline-style-card">
            <div class="passpro-setting-card-content">
                <div class="passpro-setting-card-header">
                    <span class="passpro-setting-card-title"><?php esc_html_e('Headline Styles', 'passpro'); ?></span>
                </div>
                <?php 
                $headline_font_size = isset($options['passpro_headline_font_size']) ? $options['passpro_headline_font_size'] : '20';
                $headline_font_color = isset($options['passpro_headline_font_color']) ? $options['passpro_headline_font_color'] : '#444444';
                $headline_font_family = isset($options['passpro_headline_font_family']) ? $options['passpro_headline_font_family'] : '';
                ?>
                <div class="passpro-text-controls">
                    <div class="passpro-text-size">
                        <label for="passpro_headline_font_size"><?php esc_html_e('Font Size (px):', 'passpro'); ?></label>
                        <input type="number" id="passpro_headline_font_size" name="<?php echo esc_attr($this->option_name); ?>[passpro_headline_font_size]" value="<?php echo esc_attr($headline_font_size); ?>" min="10" max="60" step="1" />
                    </div>
                    <div class="passpro-text-color">
                        <label for="passpro_headline_font_color"><?php esc_html_e('Text Color:', 'passpro'); ?></label>
                        <div class="passpro-color-field-wrapper">
                            <input type="text" id="passpro_headline_font_color" name="<?php echo esc_attr($this->option_name); ?>[passpro_headline_font_color]" value="<?php echo esc_attr($headline_font_color); ?>" class="passpro-color-picker-button" data-default-color="#444444" />
                            <input type="color" class="passpro-color-preview" value="<?php echo esc_attr($headline_font_color); ?>" data-target="passpro_headline_font_color" />
                        </div>
                    </div>
                    <div class="passpro-text-family">
                        <label for="passpro_headline_font_family"><?php esc_html_e('Font Family:', 'passpro'); ?></label>
                        <input type="text" id="passpro_headline_font_family" name="<?php echo esc_attr($this->option_name); ?>[passpro_headline_font_family]" value="<?php echo esc_attr($headline_font_family); ?>" class="regular-text" placeholder="<?php esc_attr_e('e.g., Arial, sans-serif', 'passpro'); ?>" />
                    </div>
                </div>
                <div class="passpro-text-preview headline-preview">
                    <span id="headline-preview-text"><?php esc_html_e('Headline Preview Text', 'passpro'); ?></span>
                </div>
                <p class="passpro-setting-card-description">
                    <?php esc_html_e('Customize the appearance of the headline text on your login page.', 'passpro'); ?>
                </p>
            </div>
            <div class="passpro-setting-card-icon">
                <span class="dashicons dashicons-heading"></span>
            </div>
        </div>

        <!-- Message Text Card -->
        <div class="passpro-setting-card passpro-message-style-card">
            <div class="passpro-setting-card-content">
                <div class="passpro-setting-card-header">
                    <span class="passpro-setting-card-title"><?php esc_html_e('Message Styles', 'passpro'); ?></span>
                </div>
                <?php 
                $message_font_size = isset($options['passpro_message_font_size']) ? $options['passpro_message_font_size'] : '14';
                $message_font_color = isset($options['passpro_message_font_color']) ? $options['passpro_message_font_color'] : '#444444';
                $message_font_family = isset($options['passpro_message_font_family']) ? $options['passpro_message_font_family'] : '';
                ?>
                <div class="passpro-text-controls">
                    <div class="passpro-text-size">
                        <label for="passpro_message_font_size"><?php esc_html_e('Font Size (px):', 'passpro'); ?></label>
                        <input type="number" id="passpro_message_font_size" name="<?php echo esc_attr($this->option_name); ?>[passpro_message_font_size]" value="<?php echo esc_attr($message_font_size); ?>" min="10" max="30" step="1" />
                    </div>
                    <div class="passpro-text-color">
                        <label for="passpro_message_font_color"><?php esc_html_e('Text Color:', 'passpro'); ?></label>
                        <div class="passpro-color-field-wrapper">
                            <input type="text" id="passpro_message_font_color" name="<?php echo esc_attr($this->option_name); ?>[passpro_message_font_color]" value="<?php echo esc_attr($message_font_color); ?>" class="passpro-color-picker-button" data-default-color="#444444" />
                            <input type="color" class="passpro-color-preview" value="<?php echo esc_attr($message_font_color); ?>" data-target="passpro_message_font_color" />
                        </div>
                    </div>
                    <div class="passpro-text-family">
                        <label for="passpro_message_font_family"><?php esc_html_e('Font Family:', 'passpro'); ?></label>
                        <input type="text" id="passpro_message_font_family" name="<?php echo esc_attr($this->option_name); ?>[passpro_message_font_family]" value="<?php echo esc_attr($message_font_family); ?>" class="regular-text" placeholder="<?php esc_attr_e('e.g., Arial, sans-serif', 'passpro'); ?>" />
                    </div>
                </div>
                <div class="passpro-text-preview message-preview">
                    <span id="message-preview-text"><?php esc_html_e('Message preview text. This is how your message will look on the login page.', 'passpro'); ?></span>
                </div>
                <p class="passpro-setting-card-description">
                    <?php esc_html_e('Customize the appearance of the message text shown on your login page.', 'passpro'); ?>
                </p>
            </div>
            <div class="passpro-setting-card-icon">
                <span class="dashicons dashicons-text-page"></span>
            </div>
        </div>

        <!-- Label Text Card -->
        <div class="passpro-setting-card passpro-label-style-card">
            <div class="passpro-setting-card-content">
                <div class="passpro-setting-card-header">
                    <span class="passpro-setting-card-title"><?php esc_html_e('Form Label Styles', 'passpro'); ?></span>
                </div>
                <?php 
                $label_font_size = isset($options['passpro_label_font_size']) ? $options['passpro_label_font_size'] : '14';
                $label_font_color = isset($options['passpro_label_font_color']) ? $options['passpro_label_font_color'] : '#444444';
                $label_font_family = isset($options['passpro_label_font_family']) ? $options['passpro_label_font_family'] : '';
                ?>
                <div class="passpro-text-controls">
                    <div class="passpro-text-size">
                        <label for="passpro_label_font_size"><?php esc_html_e('Font Size (px):', 'passpro'); ?></label>
                        <input type="number" id="passpro_label_font_size" name="<?php echo esc_attr($this->option_name); ?>[passpro_label_font_size]" value="<?php echo esc_attr($label_font_size); ?>" min="10" max="30" step="1" />
                    </div>
                    <div class="passpro-text-color">
                        <label for="passpro_label_font_color"><?php esc_html_e('Text Color:', 'passpro'); ?></label>
                        <div class="passpro-color-field-wrapper">
                            <input type="text" id="passpro_label_font_color" name="<?php echo esc_attr($this->option_name); ?>[passpro_label_font_color]" value="<?php echo esc_attr($label_font_color); ?>" class="passpro-color-picker-button" data-default-color="#444444" />
                            <input type="color" class="passpro-color-preview" value="<?php echo esc_attr($label_font_color); ?>" data-target="passpro_label_font_color" />
                        </div>
                    </div>
                    <div class="passpro-text-family">
                        <label for="passpro_label_font_family"><?php esc_html_e('Font Family:', 'passpro'); ?></label>
                        <input type="text" id="passpro_label_font_family" name="<?php echo esc_attr($this->option_name); ?>[passpro_label_font_family]" value="<?php echo esc_attr($label_font_family); ?>" class="regular-text" placeholder="<?php esc_attr_e('e.g., Arial, sans-serif', 'passpro'); ?>" />
                    </div>
                </div>
                <div class="passpro-text-preview label-preview">
                    <span id="label-preview-text"><?php esc_html_e('Password:', 'passpro'); ?></span>
                </div>
                <p class="passpro-setting-card-description">
                    <?php esc_html_e('Customize the appearance of the form labels on your login page.', 'passpro'); ?>
                </p>
            </div>
            <div class="passpro-setting-card-icon">
                <span class="dashicons dashicons-editor-spellcheck"></span>
            </div>
        </div>
    </div>
</div> 