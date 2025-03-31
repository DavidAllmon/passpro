<?php
/**
 * Partial template for the Button Styles tab
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

<!-- Button Styles Tab Content -->
<div id="passpro-tab-button" class="passpro-tab-content">
    <div class="passpro-settings-header">
        <div class="passpro-settings-title">
            <span class="dashicons dashicons-button"></span>
            <h2><?php esc_html_e('Button Styles', 'passpro'); ?></h2>
        </div>
        <p class="passpro-settings-description"><?php esc_html_e('Customize the appearance of the submit button on your login page.', 'passpro'); ?></p>
    </div>

    <div class="passpro-settings-grid">
        <!-- Button Text Card -->
        <div class="passpro-setting-card passpro-button-text-card">
            <div class="passpro-setting-card-content">
                <div class="passpro-setting-card-header">
                    <span class="passpro-setting-card-title"><?php esc_html_e('Button Text', 'passpro'); ?></span>
                </div>
                <?php 
                $button_text = isset($options['passpro_button_text_label']) ? $options['passpro_button_text_label'] : '';
                ?>
                <input type="text" id="passpro_button_text_label" name="<?php echo esc_attr($this->option_name); ?>[passpro_button_text_label]" value="<?php echo esc_attr($button_text); ?>" class="regular-text" placeholder="<?php esc_attr_e('Enter', 'passpro'); ?>" />
                <p class="passpro-setting-card-description">
                    <?php esc_html_e('The text displayed on the submit button. Defaults to \'Enter\'.', 'passpro'); ?>
                </p>
            </div>
            <div class="passpro-setting-card-icon">
                <span class="dashicons dashicons-editor-textcolor"></span>
            </div>
        </div>

        <!-- Button Colors Card -->
        <div class="passpro-setting-card passpro-button-colors-card">
            <div class="passpro-setting-card-content">
                <div class="passpro-setting-card-header">
                    <span class="passpro-setting-card-title"><?php esc_html_e('Button Colors', 'passpro'); ?></span>
                </div>
                <?php 
                $bg_color = isset($options['passpro_button_bg_color']) ? $options['passpro_button_bg_color'] : '#2271b1';
                $text_color = isset($options['passpro_button_text_color']) ? $options['passpro_button_text_color'] : '#ffffff';
                $bg_hover_color = isset($options['passpro_button_hover_bg_color']) ? $options['passpro_button_hover_bg_color'] : '#135e96';
                $text_hover_color = isset($options['passpro_button_hover_text_color']) ? $options['passpro_button_hover_text_color'] : '#ffffff';
                ?>
                <div class="passpro-button-colors-controls">
                    <div class="passpro-button-colors-normal">
                        <div class="passpro-button-color">
                            <label for="passpro_button_bg_color"><?php esc_html_e('Background Color:', 'passpro'); ?></label>
                            <div class="passpro-color-field">
                                <input type="text" id="passpro_button_bg_color" name="<?php echo esc_attr($this->option_name); ?>[passpro_button_bg_color]" value="<?php echo esc_attr($bg_color); ?>" class="passpro-color-picker" data-default-color="#2271b1" />
                            </div>
                        </div>
                        <div class="passpro-button-color">
                            <label for="passpro_button_text_color"><?php esc_html_e('Text Color:', 'passpro'); ?></label>
                            <div class="passpro-color-field">
                                <input type="text" id="passpro_button_text_color" name="<?php echo esc_attr($this->option_name); ?>[passpro_button_text_color]" value="<?php echo esc_attr($text_color); ?>" class="passpro-color-picker" data-default-color="#ffffff" />
                            </div>
                        </div>
                    </div>
                    <div class="passpro-button-colors-hover">
                        <div class="passpro-button-color">
                            <label for="passpro_button_hover_bg_color"><?php esc_html_e('Hover Background:', 'passpro'); ?></label>
                            <div class="passpro-color-field">
                                <input type="text" id="passpro_button_hover_bg_color" name="<?php echo esc_attr($this->option_name); ?>[passpro_button_hover_bg_color]" value="<?php echo esc_attr($bg_hover_color); ?>" class="passpro-color-picker" data-default-color="#135e96" />
                            </div>
                        </div>
                        <div class="passpro-button-color">
                            <label for="passpro_button_hover_text_color"><?php esc_html_e('Hover Text Color:', 'passpro'); ?></label>
                            <div class="passpro-color-field">
                                <input type="text" id="passpro_button_hover_text_color" name="<?php echo esc_attr($this->option_name); ?>[passpro_button_hover_text_color]" value="<?php echo esc_attr($text_hover_color); ?>" class="passpro-color-picker" data-default-color="#ffffff" />
                            </div>
                        </div>
                    </div>
                </div>
                <p class="passpro-setting-card-description">
                    <?php esc_html_e('Set the background and text colors for normal and hover states of the button.', 'passpro'); ?>
                </p>
            </div>
            <div class="passpro-setting-card-icon">
                <span class="dashicons dashicons-art"></span>
            </div>
        </div>

        <!-- Button Border Card -->
        <div class="passpro-setting-card passpro-button-border-card">
            <div class="passpro-setting-card-content">
                <div class="passpro-setting-card-header">
                    <span class="passpro-setting-card-title"><?php esc_html_e('Button Border', 'passpro'); ?></span>
                </div>
                <?php 
                $border_color = isset($options['passpro_button_border_color']) ? $options['passpro_button_border_color'] : '#2271b1';
                $border_width = isset($options['passpro_button_border_width']) ? $options['passpro_button_border_width'] : '1';
                $border_radius = isset($options['passpro_button_border_radius']) ? $options['passpro_button_border_radius'] : '3';
                ?>
                <div class="passpro-button-border-controls">
                    <div class="passpro-button-border-color">
                        <label for="passpro_button_border_color"><?php esc_html_e('Border Color:', 'passpro'); ?></label>
                        <div class="passpro-color-field">
                            <input type="text" id="passpro_button_border_color" name="<?php echo esc_attr($this->option_name); ?>[passpro_button_border_color]" value="<?php echo esc_attr($border_color); ?>" class="passpro-color-picker" data-default-color="#2271b1" />
                        </div>
                    </div>
                    <div class="passpro-button-border-size">
                        <div class="passpro-button-border-width">
                            <label for="passpro_button_border_width"><?php esc_html_e('Border Width (px):', 'passpro'); ?></label>
                            <input type="number" id="passpro_button_border_width" name="<?php echo esc_attr($this->option_name); ?>[passpro_button_border_width]" value="<?php echo esc_attr($border_width); ?>" min="0" max="10" step="1" />
                        </div>
                        <div class="passpro-button-border-radius">
                            <label for="passpro_button_border_radius"><?php esc_html_e('Border Radius (px):', 'passpro'); ?></label>
                            <input type="number" id="passpro_button_border_radius" name="<?php echo esc_attr($this->option_name); ?>[passpro_button_border_radius]" value="<?php echo esc_attr($border_radius); ?>" min="0" max="50" step="1" />
                        </div>
                    </div>
                </div>
                <p class="passpro-setting-card-description">
                    <?php esc_html_e('Configure the border appearance for the button. Adjust the color, width, and corner radius.', 'passpro'); ?>
                </p>
            </div>
            <div class="passpro-setting-card-icon">
                <span class="dashicons dashicons-admin-customizer"></span>
            </div>
        </div>
        
        <!-- Button Size and Alignment Card -->
        <div class="passpro-setting-card passpro-button-size-align-card">
            <div class="passpro-setting-card-content">
                <div class="passpro-setting-card-header">
                    <span class="passpro-setting-card-title"><?php esc_html_e('Button Size & Alignment', 'passpro'); ?></span>
                </div>
                <?php 
                $button_width = isset($options['passpro_button_width']) ? $options['passpro_button_width'] : '';
                $button_height = isset($options['passpro_button_height']) ? $options['passpro_button_height'] : '';
                $button_alignment = isset($options['passpro_button_alignment']) ? $options['passpro_button_alignment'] : '';
                ?>
                <div class="passpro-button-size-controls">
                    <div class="passpro-button-size-row">
                        <div class="passpro-button-width">
                            <label for="passpro_button_width"><?php esc_html_e('Width:', 'passpro'); ?></label>
                            <input type="text" id="passpro_button_width" name="<?php echo esc_attr($this->option_name); ?>[passpro_button_width]" value="<?php echo esc_attr($button_width); ?>" class="medium-text" placeholder="auto" />
                            <p class="description"><?php esc_html_e('E.g., 100px, 50%, auto', 'passpro'); ?></p>
                        </div>
                        <div class="passpro-button-height">
                            <label for="passpro_button_height"><?php esc_html_e('Height:', 'passpro'); ?></label>
                            <input type="text" id="passpro_button_height" name="<?php echo esc_attr($this->option_name); ?>[passpro_button_height]" value="<?php echo esc_attr($button_height); ?>" class="medium-text" placeholder="auto" />
                            <p class="description"><?php esc_html_e('E.g., 40px, auto', 'passpro'); ?></p>
                        </div>
                    </div>
                    <div class="passpro-button-alignment">
                        <label for="passpro_button_alignment"><?php esc_html_e('Alignment:', 'passpro'); ?></label>
                        <select id="passpro_button_alignment" name="<?php echo esc_attr($this->option_name); ?>[passpro_button_alignment]">
                            <option value="" <?php selected($button_alignment, ''); ?>><?php esc_html_e('Default', 'passpro'); ?></option>
                            <option value="left" <?php selected($button_alignment, 'left'); ?>><?php esc_html_e('Left', 'passpro'); ?></option>
                            <option value="center" <?php selected($button_alignment, 'center'); ?>><?php esc_html_e('Center', 'passpro'); ?></option>
                            <option value="right" <?php selected($button_alignment, 'right'); ?>><?php esc_html_e('Right', 'passpro'); ?></option>
                        </select>
                    </div>
                </div>
                <p class="passpro-setting-card-description">
                    <?php esc_html_e('Set the dimensions and alignment of the button. Leave width and height empty for automatic sizing.', 'passpro'); ?>
                </p>
            </div>
            <div class="passpro-setting-card-icon">
                <span class="dashicons dashicons-editor-kitchensink"></span>
            </div>
        </div>

        <!-- Button Padding Card -->
        <div class="passpro-setting-card passpro-button-padding-card">
            <div class="passpro-setting-card-content">
                <div class="passpro-setting-card-header">
                    <span class="passpro-setting-card-title"><?php esc_html_e('Button Padding', 'passpro'); ?></span>
                </div>
                <?php 
                $padding_top = isset($options['passpro_button_padding_top']) ? $options['passpro_button_padding_top'] : '';
                $padding_right = isset($options['passpro_button_padding_right']) ? $options['passpro_button_padding_right'] : '';
                $padding_bottom = isset($options['passpro_button_padding_bottom']) ? $options['passpro_button_padding_bottom'] : '';
                $padding_left = isset($options['passpro_button_padding_left']) ? $options['passpro_button_padding_left'] : '';
                ?>
                <div class="passpro-button-padding-controls">
                    <div class="passpro-button-padding-grid">
                        <div class="passpro-padding-top">
                            <label for="passpro_button_padding_top"><?php esc_html_e('Top (px):', 'passpro'); ?></label>
                            <input type="number" id="passpro_button_padding_top" name="<?php echo esc_attr($this->option_name); ?>[passpro_button_padding_top]" value="<?php echo esc_attr($padding_top); ?>" min="0" max="50" placeholder="10" />
                        </div>
                        <div class="passpro-padding-right">
                            <label for="passpro_button_padding_right"><?php esc_html_e('Right (px):', 'passpro'); ?></label>
                            <input type="number" id="passpro_button_padding_right" name="<?php echo esc_attr($this->option_name); ?>[passpro_button_padding_right]" value="<?php echo esc_attr($padding_right); ?>" min="0" max="50" placeholder="20" />
                        </div>
                        <div class="passpro-padding-bottom">
                            <label for="passpro_button_padding_bottom"><?php esc_html_e('Bottom (px):', 'passpro'); ?></label>
                            <input type="number" id="passpro_button_padding_bottom" name="<?php echo esc_attr($this->option_name); ?>[passpro_button_padding_bottom]" value="<?php echo esc_attr($padding_bottom); ?>" min="0" max="50" placeholder="10" />
                        </div>
                        <div class="passpro-padding-left">
                            <label for="passpro_button_padding_left"><?php esc_html_e('Left (px):', 'passpro'); ?></label>
                            <input type="number" id="passpro_button_padding_left" name="<?php echo esc_attr($this->option_name); ?>[passpro_button_padding_left]" value="<?php echo esc_attr($padding_left); ?>" min="0" max="50" placeholder="20" />
                        </div>
                    </div>
                </div>
                <p class="passpro-setting-card-description">
                    <?php esc_html_e('Set the padding inside the button. This controls the space between the button text and its border.', 'passpro'); ?>
                </p>
            </div>
            <div class="passpro-setting-card-icon">
                <span class="dashicons dashicons-editor-spellcheck"></span>
            </div>
        </div>
    </div>
</div> 