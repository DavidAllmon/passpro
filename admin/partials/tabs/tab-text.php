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
                        <div class="passpro-color-field">
                            <input type="text" id="passpro_headline_font_color" name="<?php echo esc_attr($this->option_name); ?>[passpro_headline_font_color]" value="<?php echo esc_attr($headline_font_color); ?>" class="passpro-color-picker" data-default-color="#444444" />
                        </div>
                    </div>
                    <div class="passpro-text-family">
                        <label for="passpro_headline_font_family"><?php esc_html_e('Font Family:', 'passpro'); ?></label>
                        <select id="passpro_headline_font_family" name="<?php echo esc_attr($this->option_name); ?>[passpro_headline_font_family]" class="regular-text">
                            <option value="" <?php selected($headline_font_family, ''); ?>><?php esc_html_e('Default', 'passpro'); ?></option>
                            <option value="Arial, sans-serif" <?php selected($headline_font_family, 'Arial, sans-serif'); ?>>Arial</option>
                            <option value="Helvetica, Arial, sans-serif" <?php selected($headline_font_family, 'Helvetica, Arial, sans-serif'); ?>>Helvetica</option>
                            <option value="Georgia, serif" <?php selected($headline_font_family, 'Georgia, serif'); ?>>Georgia</option>
                            <option value="'Times New Roman', Times, serif" <?php selected($headline_font_family, "'Times New Roman', Times, serif"); ?>>Times New Roman</option>
                            <option value="Verdana, Geneva, sans-serif" <?php selected($headline_font_family, 'Verdana, Geneva, sans-serif'); ?>>Verdana</option>
                            <option value="'Courier New', Courier, monospace" <?php selected($headline_font_family, "'Courier New', Courier, monospace"); ?>>Courier New</option>
                            <option value="'Open Sans', sans-serif" <?php selected($headline_font_family, "'Open Sans', sans-serif"); ?>>Open Sans</option>
                            <option value="'Roboto', sans-serif" <?php selected($headline_font_family, "'Roboto', sans-serif"); ?>>Roboto</option>
                            <option value="'Lato', sans-serif" <?php selected($headline_font_family, "'Lato', sans-serif"); ?>>Lato</option>
                        </select>
                    </div>
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
                $message_alignment = isset($options['passpro_message_alignment']) ? $options['passpro_message_alignment'] : 'left'; // Default to left
                ?>
                <div class="passpro-text-controls">
                    <div class="passpro-text-size">
                        <label for="passpro_message_font_size"><?php esc_html_e('Font Size (px):', 'passpro'); ?></label>
                        <input type="number" id="passpro_message_font_size" name="<?php echo esc_attr($this->option_name); ?>[passpro_message_font_size]" value="<?php echo esc_attr($message_font_size); ?>" min="10" max="30" step="1" />
                    </div>
                    <div class="passpro-text-color">
                        <label for="passpro_message_font_color"><?php esc_html_e('Text Color:', 'passpro'); ?></label>
                        <div class="passpro-color-field">
                            <input type="text" id="passpro_message_font_color" name="<?php echo esc_attr($this->option_name); ?>[passpro_message_font_color]" value="<?php echo esc_attr($message_font_color); ?>" class="passpro-color-picker" data-default-color="#444444" />
                        </div>
                    </div>
                    <div class="passpro-text-family">
                        <label for="passpro_message_font_family"><?php esc_html_e('Font Family:', 'passpro'); ?></label>
                        <select id="passpro_message_font_family" name="<?php echo esc_attr($this->option_name); ?>[passpro_message_font_family]" class="regular-text">
                            <option value="" <?php selected($message_font_family, ''); ?>><?php esc_html_e('Default', 'passpro'); ?></option>
                            <option value="Arial, sans-serif" <?php selected($message_font_family, 'Arial, sans-serif'); ?>>Arial</option>
                            <option value="Helvetica, Arial, sans-serif" <?php selected($message_font_family, 'Helvetica, Arial, sans-serif'); ?>>Helvetica</option>
                            <option value="Georgia, serif" <?php selected($message_font_family, 'Georgia, serif'); ?>>Georgia</option>
                            <option value="'Times New Roman', Times, serif" <?php selected($message_font_family, "'Times New Roman', Times, serif"); ?>>Times New Roman</option>
                            <option value="Verdana, Geneva, sans-serif" <?php selected($message_font_family, 'Verdana, Geneva, sans-serif'); ?>>Verdana</option>
                            <option value="'Courier New', Courier, monospace" <?php selected($message_font_family, "'Courier New', Courier, monospace"); ?>>Courier New</option>
                            <option value="'Open Sans', sans-serif" <?php selected($message_font_family, "'Open Sans', sans-serif"); ?>>Open Sans</option>
                            <option value="'Roboto', sans-serif" <?php selected($message_font_family, "'Roboto', sans-serif"); ?>>Roboto</option>
                            <option value="'Lato', sans-serif" <?php selected($message_font_family, "'Lato', sans-serif"); ?>>Lato</option>
                        </select>
                    </div>
                    <div class="passpro-text-alignment">
                        <label for="passpro_message_alignment"><?php esc_html_e('Text Alignment:', 'passpro'); ?></label>
                        <select id="passpro_message_alignment" name="<?php echo esc_attr($this->option_name); ?>[passpro_message_alignment]">
                            <option value="left" <?php selected($message_alignment, 'left'); ?>><?php esc_html_e('Left', 'passpro'); ?></option>
                            <option value="center" <?php selected($message_alignment, 'center'); ?>><?php esc_html_e('Center', 'passpro'); ?></option>
                            <option value="right" <?php selected($message_alignment, 'right'); ?>><?php esc_html_e('Right', 'passpro'); ?></option>
                        </select>
                    </div>
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
                        <div class="passpro-color-field">
                            <input type="text" id="passpro_label_font_color" name="<?php echo esc_attr($this->option_name); ?>[passpro_label_font_color]" value="<?php echo esc_attr($label_font_color); ?>" class="passpro-color-picker" data-default-color="#444444" />
                        </div>
                    </div>
                    <div class="passpro-text-family">
                        <label for="passpro_label_font_family"><?php esc_html_e('Font Family:', 'passpro'); ?></label>
                        <select id="passpro_label_font_family" name="<?php echo esc_attr($this->option_name); ?>[passpro_label_font_family]" class="regular-text">
                            <option value="" <?php selected($label_font_family, ''); ?>><?php esc_html_e('Default', 'passpro'); ?></option>
                            <option value="Arial, sans-serif" <?php selected($label_font_family, 'Arial, sans-serif'); ?>>Arial</option>
                            <option value="Helvetica, Arial, sans-serif" <?php selected($label_font_family, 'Helvetica, Arial, sans-serif'); ?>>Helvetica</option>
                            <option value="Georgia, serif" <?php selected($label_font_family, 'Georgia, serif'); ?>>Georgia</option>
                            <option value="'Times New Roman', Times, serif" <?php selected($label_font_family, "'Times New Roman', Times, serif"); ?>>Times New Roman</option>
                            <option value="Verdana, Geneva, sans-serif" <?php selected($label_font_family, 'Verdana, Geneva, sans-serif'); ?>>Verdana</option>
                            <option value="'Courier New', Courier, monospace" <?php selected($label_font_family, "'Courier New', Courier, monospace"); ?>>Courier New</option>
                            <option value="'Open Sans', sans-serif" <?php selected($label_font_family, "'Open Sans', sans-serif"); ?>>Open Sans</option>
                            <option value="'Roboto', sans-serif" <?php selected($label_font_family, "'Roboto', sans-serif"); ?>>Roboto</option>
                            <option value="'Lato', sans-serif" <?php selected($label_font_family, "'Lato', sans-serif"); ?>>Lato</option>
                        </select>
                    </div>
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