<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    PassPro
 * @subpackage PassPro/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    PassPro
 * @subpackage PassPro/admin
 * @author     Your Name <email@example.com>
 */
class PassPro_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

    /**
     * Option group name
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $option_group    Option group name
     */
    private $option_group;

    /**
     * Option name
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $option_name    Option name
     */
    private $option_name;

    /**
     * Screen hooks for the admin pages.
     *
     * @since    1.0.0
     * @access   private
     * @var      array
     */
    private $screen_hooks = []; // Store multiple hooks

    /**
     * Database handler for multiple passwords
     *
     * @since    1.0.0
     * @access   private
     * @var      PassPro_DB
     */
    private $db;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        $this->option_group = $this->plugin_name . '_settings_group';
        $this->option_name = $this->plugin_name . '_options';
        $this->db = new PassPro_DB();

	}

	/**
	 * Add the options page and enqueue scripts.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
        // Add top-level menu page
		$this->screen_hooks['main'] = add_menu_page(
			esc_html__( 'Password Protection Settings', 'passpro' ), // Page title
			esc_html__( 'PassPro', 'passpro' ), // Menu title
			'manage_options', // Capability
			$this->plugin_name, // Menu slug
			array( $this, 'display_plugin_setup_page' ), // Callback function
            'dashicons-lock', // Icon URL (using a Dashicon)
            75 // Position
		);

        // Add submenu for settings (optional, if you want the first item explicit)
        // If you want the main menu item itself to link directly to settings,
        // you can potentially remove this or adjust slugs/callbacks.
        // For simplicity, we link the main menu directly to the settings display function above.

        // Add submenu for managing multiple passwords under the new top-level menu
        $this->screen_hooks['passwords'] = add_submenu_page(
            $this->plugin_name, // Parent slug (now the top-level menu slug)
            esc_html__( 'Manage Passwords', 'passpro' ), // Page title
            esc_html__( 'Manage Passwords', 'passpro' ), // Menu title
            'manage_options', // Capability
            $this->plugin_name . '_passwords', // Menu slug
            array( $this, 'display_passwords_page' ) // Callback function
        );

        // Enqueue scripts - this hook runs for all admin pages, filtering happens inside the function
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
	}

    /**
	 * Enqueue scripts and styles for the admin settings page.
	 *
	 * @since    1.0.0
     * @param    string $hook_suffix The current admin page hook.
	 */
    public function enqueue_admin_scripts( $hook_suffix ) {
        // Only load on plugin settings page
        if ( $hook_suffix !== 'toplevel_page_' . $this->plugin_name && $hook_suffix !== $this->plugin_name . '_passwords' ) {
            return;
        }

        // Enqueue styles
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/passpro-admin-modern.css', array(), $this->version, 'all' );

        // Enqueue scripts
        wp_enqueue_media(); // For the media uploader
        wp_enqueue_script( 'wp-color-picker' );
        
        // Enqueue the admin settings JavaScript
        wp_enqueue_script( 
            $this->plugin_name, 
            plugin_dir_url( __FILE__ ) . 'js/passpro-admin-display.js', 
            array( 'jquery', 'wp-color-picker' ), 
            $this->version, 
            true 
        );
    }

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_setup_page() {
		include_once( 'partials/passpro-admin-display.php' );
	}

    /**
     * Register the settings used by the plugin.
     *
     * @since    1.0.0
     */
    public function register_settings() {
        register_setting(
            $this->option_group, // Option group
            $this->option_name, // Option name
            array( $this, 'sanitize_settings' ) // Sanitize callback
        );

        // --- General Settings Section ---
        add_settings_section(
            $this->plugin_name . '_general_section', // ID
            esc_html__( 'General Settings', 'passpro' ), // Title
            null, // Callback
            $this->option_group // Page
        );
        add_settings_field('passpro_enabled', esc_html__( 'Enable Protection', 'passpro' ), array( $this, 'render_enabled_field' ), $this->option_group, $this->plugin_name . '_general_section');
        add_settings_field('passpro_password', esc_html__( 'Password', 'passpro' ), array( $this, 'render_password_field' ), $this->option_group, $this->plugin_name . '_general_section');
        add_settings_field('passpro_allowed_ips', esc_html__( 'Allowed IP Addresses', 'passpro' ), array( $this, 'render_allowed_ips_field' ), $this->option_group, $this->plugin_name . '_general_section');
        add_settings_field('passpro_show_logout_button', esc_html__( 'Show Logout Button', 'passpro' ), array( $this, 'render_show_logout_button_field' ), $this->option_group, $this->plugin_name . '_general_section');


        // --- Appearance Settings Section ---
        add_settings_section(
            $this->plugin_name . '_appearance_section',
            esc_html__( 'Login Page Appearance', 'passpro' ),
            null,
            $this->option_group
        );

        // Logo
        add_settings_field('passpro_logo_url', esc_html__( 'Logo', 'passpro' ), array( $this, 'render_logo_field' ), $this->option_group, $this->plugin_name . '_appearance_section');

        // Logo Size
        add_settings_field(
            'passpro_logo_max_width',
            esc_html__( 'Logo Maximum Width', 'passpro' ),
            array( $this, 'render_number_field' ),
            $this->option_group,
            $this->plugin_name . '_appearance_section',
            [
                'id' => 'passpro_logo_max_width',
                'default' => '',
                'min' => 0,
                'max' => 1000,
                'placeholder' => '200',
                'description' => esc_html__( 'Maximum width for your logo in pixels (leave empty for native size).', 'passpro' )
            ]
        );
        add_settings_field(
            'passpro_logo_max_height',
            esc_html__( 'Logo Maximum Height', 'passpro' ),
            array( $this, 'render_number_field' ),
            $this->option_group,
            $this->plugin_name . '_appearance_section',
            [
                'id' => 'passpro_logo_max_height',
                'default' => '',
                'min' => 0,
                'max' => 1000,
                'placeholder' => '100',
                'description' => esc_html__( 'Maximum height for your logo in pixels (leave empty for native size).', 'passpro' )
            ]
        );
        
        // Logo Alignment
        add_settings_field(
            'passpro_logo_alignment',
            esc_html__( 'Logo Alignment', 'passpro' ),
            array( $this, 'render_select_field' ),
            $this->option_group,
            $this->plugin_name . '_appearance_section',
            [
                'id' => 'passpro_logo_alignment',
                'default' => 'center',
                'options' => [
                    'left' => esc_html__('Left', 'passpro'),
                    'center' => esc_html__('Center', 'passpro'),
                    'right' => esc_html__('Right', 'passpro')
                ],
                'description' => esc_html__('Choose how the logo should be aligned on the login page.', 'passpro')
            ]
        );

        // Page Title, Headline, Message, Background Color (from before)
        add_settings_field('passpro_page_title', esc_html__( 'Page Title', 'passpro' ), array( $this, 'render_page_title_field' ), $this->option_group, $this->plugin_name . '_appearance_section');
        add_settings_field('passpro_headline', esc_html__( 'Headline Text', 'passpro' ), array( $this, 'render_headline_field' ), $this->option_group, $this->plugin_name . '_appearance_section');
        add_settings_field('passpro_message', esc_html__( 'Message Text', 'passpro' ), array( $this, 'render_message_field' ), $this->option_group, $this->plugin_name . '_appearance_section');
        add_settings_field('passpro_background_color', esc_html__( 'Background Color', 'passpro' ), array( $this, 'render_background_color_field' ), $this->option_group, $this->plugin_name . '_appearance_section');

        // --- Login Box Styles ---
        add_settings_section(
            $this->plugin_name . '_box_style_section',
            esc_html__( 'Login Box Styles', 'passpro' ),
            null,
            $this->option_group
        );
        add_settings_field('passpro_box_bg_color', esc_html__( 'Background Color', 'passpro' ), array( $this, 'render_color_field' ), $this->option_group, $this->plugin_name . '_box_style_section', ['id' => 'passpro_box_bg_color', 'default' => '#ffffff']);
        add_settings_field('passpro_box_border_color', esc_html__( 'Border Color', 'passpro' ), array( $this, 'render_color_field' ), $this->option_group, $this->plugin_name . '_box_style_section', ['id' => 'passpro_box_border_color', 'default' => '#dddddd']);
        add_settings_field('passpro_box_border_width', esc_html__( 'Border Width (px)', 'passpro' ), array( $this, 'render_number_field' ), $this->option_group, $this->plugin_name . '_box_style_section', ['id' => 'passpro_box_border_width', 'default' => '1']);
        add_settings_field('passpro_box_border_radius', esc_html__( 'Border Radius (px)', 'passpro' ), array( $this, 'render_number_field' ), $this->option_group, $this->plugin_name . '_box_style_section', ['id' => 'passpro_box_border_radius', 'default' => '0']);

        // --- Text Styles ---
        add_settings_section(
            $this->plugin_name . '_text_style_section',
            esc_html__( 'Text Styles', 'passpro' ),
            null,
            $this->option_group
        );
        // Headline Text
        add_settings_field('passpro_headline_font_size', esc_html__( 'Headline: Font Size (px)', 'passpro' ), array( $this, 'render_number_field' ), $this->option_group, $this->plugin_name . '_text_style_section', ['id' => 'passpro_headline_font_size', 'default' => '20']);
        add_settings_field('passpro_headline_font_color', esc_html__( 'Headline: Color', 'passpro' ), array( $this, 'render_color_field' ), $this->option_group, $this->plugin_name . '_text_style_section', ['id' => 'passpro_headline_font_color', 'default' => '#444444']);
        add_settings_field('passpro_headline_font_family', esc_html__( 'Headline: Font Family', 'passpro' ), array( $this, 'render_text_field' ), $this->option_group, $this->plugin_name . '_text_style_section', ['id' => 'passpro_headline_font_family', 'placeholder' => 'e.g., Arial, sans-serif']);
        // Message Text
        add_settings_field('passpro_message_font_size', esc_html__( 'Message: Font Size (px)', 'passpro' ), array( $this, 'render_number_field' ), $this->option_group, $this->plugin_name . '_text_style_section', ['id' => 'passpro_message_font_size', 'default' => '14']);
        add_settings_field('passpro_message_font_color', esc_html__( 'Message: Color', 'passpro' ), array( $this, 'render_color_field' ), $this->option_group, $this->plugin_name . '_text_style_section', ['id' => 'passpro_message_font_color', 'default' => '#444444']);
        add_settings_field('passpro_message_font_family', esc_html__( 'Message: Font Family', 'passpro' ), array( $this, 'render_text_field' ), $this->option_group, $this->plugin_name . '_text_style_section', ['id' => 'passpro_message_font_family']);
        add_settings_field(
            'passpro_message_alignment',
            esc_html__( 'Message: Text Alignment', 'passpro' ),
            array( $this, 'render_select_field' ),
            $this->option_group,
            $this->plugin_name . '_text_style_section',
            [
                'id' => 'passpro_message_alignment',
                'default' => 'left',
                'options' => [
                    'left' => esc_html__('Left', 'passpro'),
                    'center' => esc_html__('Center', 'passpro'),
                    'right' => esc_html__('Right', 'passpro')
                ],
                'description' => esc_html__('Align the message text.', 'passpro')
            ]
        );
        // Label Text
        add_settings_field('passpro_label_font_size', esc_html__( 'Label: Font Size (px)', 'passpro' ), array( $this, 'render_number_field' ), $this->option_group, $this->plugin_name . '_text_style_section', ['id' => 'passpro_label_font_size', 'default' => '14']);
        add_settings_field('passpro_label_font_color', esc_html__( 'Label: Color', 'passpro' ), array( $this, 'render_color_field' ), $this->option_group, $this->plugin_name . '_text_style_section', ['id' => 'passpro_label_font_color', 'default' => '#72777c']);
        add_settings_field('passpro_label_font_family', esc_html__( 'Label: Font Family', 'passpro' ), array( $this, 'render_text_field' ), $this->option_group, $this->plugin_name . '_text_style_section', ['id' => 'passpro_label_font_family']);

        // --- Button Styles ---
        add_settings_section(
            $this->plugin_name . '_button_style_section',
            esc_html__( 'Login Button Styles', 'passpro' ),
            null,
            $this->option_group
        );
        
        // Button Colors Group
        add_settings_field('passpro_button_colors_heading', esc_html__( 'Button Colors', 'passpro' ), array( $this, 'render_heading_field' ), $this->option_group, $this->plugin_name . '_button_style_section', ['heading' => 'Button Colors']);
        add_settings_field('passpro_button_bg_color', esc_html__( 'Background Color', 'passpro' ), array( $this, 'render_color_field' ), $this->option_group, $this->plugin_name . '_button_style_section', ['id' => 'passpro_button_bg_color', 'default' => '#0073aa']);
        add_settings_field('passpro_button_text_color', esc_html__( 'Text Color', 'passpro' ), array( $this, 'render_color_field' ), $this->option_group, $this->plugin_name . '_button_style_section', ['id' => 'passpro_button_text_color', 'default' => '#ffffff']);
        
        // Add new hover colors
        add_settings_field('passpro_button_hover_bg_color', esc_html__( 'Hover Background Color', 'passpro' ), array( $this, 'render_color_field' ), $this->option_group, $this->plugin_name . '_button_style_section', ['id' => 'passpro_button_hover_bg_color', 'default' => '#0062a3']);
        add_settings_field('passpro_button_hover_text_color', esc_html__( 'Hover Text Color', 'passpro' ), array( $this, 'render_color_field' ), $this->option_group, $this->plugin_name . '_button_style_section', ['id' => 'passpro_button_hover_text_color', 'default' => '#ffffff']);
        
        // Button Border Group
        add_settings_field('passpro_button_border_heading', esc_html__( 'Button Border', 'passpro' ), array( $this, 'render_heading_field' ), $this->option_group, $this->plugin_name . '_button_style_section', ['heading' => 'Button Border']);
        add_settings_field('passpro_button_border_color', esc_html__( 'Border Color', 'passpro' ), array( $this, 'render_color_field' ), $this->option_group, $this->plugin_name . '_button_style_section', ['id' => 'passpro_button_border_color', 'default' => '#0073aa']);
        add_settings_field('passpro_button_border_width', esc_html__( 'Border Width (px)', 'passpro' ), array( $this, 'render_number_field' ), $this->option_group, $this->plugin_name . '_button_style_section', ['id' => 'passpro_button_border_width', 'default' => '1']);
        add_settings_field('passpro_button_border_radius', esc_html__( 'Border Radius (px)', 'passpro' ), array( $this, 'render_number_field' ), $this->option_group, $this->plugin_name . '_button_style_section', ['id' => 'passpro_button_border_radius', 'default' => '3']);
        
        // Button Text Group
        add_settings_field('passpro_button_text_heading', esc_html__( 'Button Text', 'passpro' ), array( $this, 'render_heading_field' ), $this->option_group, $this->plugin_name . '_button_style_section', ['heading' => 'Button Text']);
        
        // Button Text Label - Add this first for better visibility
        add_settings_field(
            'passpro_button_text_label',
            esc_html__( 'Button Text Label', 'passpro' ),
            array( $this, 'render_text_field' ),
            $this->option_group,
            $this->plugin_name . '_button_style_section',
            [
                'id' => 'passpro_button_text_label',
                'default' => 'Enter',
                'placeholder' => 'Enter'
            ]
        );
        
        add_settings_field('passpro_button_font_size', esc_html__( 'Font Size (px)', 'passpro' ), array( $this, 'render_number_field' ), $this->option_group, $this->plugin_name . '_button_style_section', ['id' => 'passpro_button_font_size', 'default' => '14']);
        
        // Add new text styling options
        add_settings_field('passpro_button_font_weight', esc_html__( 'Font Weight', 'passpro' ), array( $this, 'render_select_field' ), $this->option_group, $this->plugin_name . '_button_style_section', [
            'id' => 'passpro_button_font_weight',
            'default' => 'normal',
            'options' => [
                'normal' => 'Normal',
                'bold' => 'Bold',
                '300' => 'Light (300)',
                '500' => 'Medium (500)',
                '600' => 'Semi-Bold (600)',
                '800' => 'Extra-Bold (800)'
            ]
        ]);
        
        add_settings_field('passpro_button_text_transform', esc_html__( 'Text Transform', 'passpro' ), array( $this, 'render_select_field' ), $this->option_group, $this->plugin_name . '_button_style_section', [
            'id' => 'passpro_button_text_transform',
            'default' => 'none',
            'options' => [
                'none' => 'None',
                'uppercase' => 'UPPERCASE',
                'lowercase' => 'lowercase',
                'capitalize' => 'Capitalize'
            ]
        ]);
        
        // Button Size & Position
        add_settings_field('passpro_button_size_heading', esc_html__( 'Button Size & Position', 'passpro' ), array( $this, 'render_heading_field' ), $this->option_group, $this->plugin_name . '_button_style_section', ['heading' => 'Button Size & Position']);
        
        // Button Alignment
        add_settings_field(
            'passpro_button_alignment',
            esc_html__( 'Button Alignment', 'passpro' ),
            array( $this, 'render_select_field' ),
            $this->option_group,
            $this->plugin_name . '_button_style_section',
            [
                'id' => 'passpro_button_alignment',
                'default' => '',
                'options' => [
                    '' => 'Default',
                    'left' => 'Left',
                    'center' => 'Center',
                    'right' => 'Right'
                ],
                'description' => __( 'Choose how the button should be aligned in the login form.', 'passpro' )
            ]
        );
        
        // Button Width
        add_settings_field(
            'passpro_button_width',
            esc_html__( 'Button Width', 'passpro' ),
            array( $this, 'render_text_field' ),
            $this->option_group,
            $this->plugin_name . '_button_style_section',
            [
                'id' => 'passpro_button_width',
                'default' => '',
                'placeholder' => '100% or 200px',
                'description' => __( 'Set width (e.g., 100% for full width, 200px for fixed width).', 'passpro' )
            ]
        );
        
        // Button Height
        add_settings_field(
            'passpro_button_height',
            esc_html__( 'Button Height', 'passpro' ),
            array( $this, 'render_text_field' ),
            $this->option_group,
            $this->plugin_name . '_button_style_section',
            [
                'id' => 'passpro_button_height',
                'default' => '',
                'placeholder' => '40px',
                'description' => __( 'Set height (e.g., 40px). Leave empty for default.', 'passpro' )
            ]
        );
        
        // Button Padding (separate fields for each direction)
        add_settings_field(
            'passpro_button_padding_heading',
            esc_html__( 'Button Padding', 'passpro' ),
            array( $this, 'render_padding_fields' ),
            $this->option_group,
            $this->plugin_name . '_button_style_section',
            [
                'description' => __( 'Set inside spacing for each side of the button.', 'passpro' )
            ]
        );
        
        // Advanced Button Effects
        add_settings_field('passpro_button_effects_heading', esc_html__( 'Button Effects', 'passpro' ), array( $this, 'render_heading_field' ), $this->option_group, $this->plugin_name . '_button_style_section', ['heading' => 'Button Effects']);
        
        // Box Shadow
        add_settings_field(
            'passpro_button_box_shadow',
            esc_html__( 'Box Shadow', 'passpro' ),
            array( $this, 'render_select_field' ),
            $this->option_group,
            $this->plugin_name . '_button_style_section',
            [
                'id' => 'passpro_button_box_shadow',
                'default' => 'none',
                'options' => [
                    'none' => 'None',
                    'light' => 'Light Shadow',
                    'medium' => 'Medium Shadow',
                    'strong' => 'Strong Shadow'
                ],
                'description' => __( 'Add a shadow effect to the button.', 'passpro' )
            ]
        );
        
        // Transition Effect
        add_settings_field(
            'passpro_button_transition',
            esc_html__( 'Hover Transition', 'passpro' ),
            array( $this, 'render_select_field' ),
            $this->option_group,
            $this->plugin_name . '_button_style_section',
            [
                'id' => 'passpro_button_transition',
                'default' => 'normal',
                'options' => [
                    'none' => 'None (Instant)',
                    'fast' => 'Fast (0.1s)',
                    'normal' => 'Normal (0.2s)',
                    'slow' => 'Slow (0.4s)'
                ],
                'description' => __( 'Speed of color/effect changes when hovering.', 'passpro' )
            ]
        );
        
        // Button Text Label
        add_settings_field(
            'passpro_button_text_label',
            esc_html__( 'Button Text Label', 'passpro' ),
            array( $this, 'render_text_field' ),
            $this->option_group,
            $this->plugin_name . '_button_style_section',
            [
                'id' => 'passpro_button_text_label',
                'default' => 'Enter',
                'placeholder' => 'Enter'
            ]
        );

        // --- Box Options ---
        // Box Background Color
        add_settings_field(
            'passpro_box_bg_color',
            __( 'Box Background Color', 'passpro' ),
            array( $this, 'color_field_callback' ),
            $this->plugin_name . '-styles',
            'passpro_styles_section',
            array(
                'label_for' => 'passpro_box_bg_color',
                'description' => __( 'Background color for the login box.', 'passpro' ),
                'default' => '#ffffff'
            )
        );
        
        // Box Border Color
        add_settings_field(
            'passpro_box_border_color',
            __( 'Box Border Color', 'passpro' ),
            array( $this, 'color_field_callback' ),
            $this->plugin_name . '-styles',
            'passpro_styles_section',
            array(
                'label_for' => 'passpro_box_border_color',
                'description' => __( 'Border color for the login box.', 'passpro' ),
                'default' => '#c3c4c7'
            )
        );
        
        // Box Border Width
        add_settings_field(
            'passpro_box_border_width',
            __( 'Box Border Width', 'passpro' ),
            array( $this, 'number_field_callback' ),
            $this->plugin_name . '-styles',
            'passpro_styles_section',
            array(
                'label_for' => 'passpro_box_border_width',
                'description' => __( 'Border width in pixels.', 'passpro' ),
                'min' => 0,
                'max' => 10,
                'step' => 1,
                'default' => 1
            )
        );
        
        // Box Border Radius
        add_settings_field(
            'passpro_box_border_radius',
            __( 'Box Border Radius', 'passpro' ),
            array( $this, 'number_field_callback' ),
            $this->plugin_name . '-styles',
            'passpro_styles_section',
            array(
                'label_for' => 'passpro_box_border_radius',
                'description' => __( 'Border radius in pixels (for rounded corners).', 'passpro' ),
                'min' => 0,
                'max' => 20,
                'step' => 1,
                'default' => 0
            )
        );
        
        // --- Password Input Fields ---
        add_settings_field(
            'passpro_input_styling_heading',
            __( 'Password Input Fields', 'passpro' ),
            array( $this, 'section_heading_callback' ),
            $this->plugin_name . '-styles',
            'passpro_styles_section',
            array(
                'description' => __( 'Customize the appearance of the password input field.', 'passpro' )
            )
        );
        
        // Input Background Color
        add_settings_field(
            'passpro_input_bg_color',
            __( 'Input Background Color', 'passpro' ),
            array( $this, 'color_field_callback' ),
            $this->plugin_name . '-styles',
            'passpro_styles_section',
            array(
                'label_for' => 'passpro_input_bg_color',
                'description' => __( 'Background color for the password input field.', 'passpro' ),
                'default' => '#ffffff'
            )
        );
        
        // Input Text Color
        add_settings_field(
            'passpro_input_text_color',
            __( 'Input Text Color', 'passpro' ),
            array( $this, 'color_field_callback' ),
            $this->plugin_name . '-styles',
            'passpro_styles_section',
            array(
                'label_for' => 'passpro_input_text_color',
                'description' => __( 'Text color for the password input field.', 'passpro' ),
                'default' => '#2c3338'
            )
        );
        
        // Input Border Color
        add_settings_field(
            'passpro_input_border_color',
            __( 'Input Border Color', 'passpro' ),
            array( $this, 'color_field_callback' ),
            $this->plugin_name . '-styles',
            'passpro_styles_section',
            array(
                'label_for' => 'passpro_input_border_color',
                'description' => __( 'Border color for the password input field.', 'passpro' ),
                'default' => '#8c8f94'
            )
        );
        
        // Input Focus Border Color
        add_settings_field(
            'passpro_input_focus_border_color',
            __( 'Input Focus Border Color', 'passpro' ),
            array( $this, 'color_field_callback' ),
            $this->plugin_name . '-styles',
            'passpro_styles_section',
            array(
                'label_for' => 'passpro_input_focus_border_color',
                'description' => __( 'Border color for the password input field when focused.', 'passpro' ),
                'default' => '#2271b1'
            )
        );
        
        // Input Border Width
        add_settings_field(
            'passpro_input_border_width',
            __( 'Input Border Width', 'passpro' ),
            array( $this, 'number_field_callback' ),
            $this->plugin_name . '-styles',
            'passpro_styles_section',
            array(
                'label_for' => 'passpro_input_border_width',
                'description' => __( 'Border width in pixels for the password input field.', 'passpro' ),
                'min' => 0,
                'max' => 5,
                'step' => 1,
                'default' => 1
            )
        );
        
        // Input Border Radius
        add_settings_field(
            'passpro_input_border_radius',
            __( 'Input Border Radius', 'passpro' ),
            array( $this, 'number_field_callback' ),
            $this->plugin_name . '-styles',
            'passpro_styles_section',
            array(
                'label_for' => 'passpro_input_border_radius',
                'description' => __( 'Border radius in pixels for the password input field.', 'passpro' ),
                'min' => 0,
                'max' => 20,
                'step' => 1,
                'default' => 3
            )
        );
        
        // Input Font Size
        add_settings_field(
            'passpro_input_font_size',
            __( 'Input Font Size', 'passpro' ),
            array( $this, 'number_field_callback' ),
            $this->plugin_name . '-styles',
            'passpro_styles_section',
            array(
                'label_for' => 'passpro_input_font_size',
                'description' => __( 'Font size in pixels for the password input field.', 'passpro' ),
                'min' => 10,
                'max' => 32,
                'step' => 1,
                'default' => 24
            )
        );
        
        // Input Padding
        add_settings_field(
            'passpro_input_padding',
            __( 'Input Padding', 'passpro' ),
            array( $this, 'number_field_callback' ),
            $this->plugin_name . '-styles',
            'passpro_styles_section',
            array(
                'label_for' => 'passpro_input_padding',
                'description' => __( 'Padding in pixels for the password input field.', 'passpro' ),
                'min' => 0,
                'max' => 20,
                'step' => 1,
                'default' => 3
            )
        );
        
        // --- Button Colors ---

        // Button text label
        add_settings_field(
            'passpro_button_text_label',
            __( 'Button Text Label', 'passpro' ),
            array( $this, 'text_field_callback' ),
            $this->plugin_name . '-styles',
            'passpro_styles_section',
            array(
                'label_for' => 'passpro_button_text_label',
                'description' => __( 'Text to display on the login button.', 'passpro' ),
                'default' => __( 'Enter', 'passpro' )
            )
        );
        
        // --- Button Text ---
        add_settings_field(
            'passpro_button_text_heading',
            __( 'Button Text', 'passpro' ),
            array( $this, 'section_heading_callback' ),
            $this->plugin_name . '-styles',
            'passpro_styles_section',
            array(
                'description' => __( 'Customize the text appearance on the button.', 'passpro' )
            )
        );
        
        // Button Text Color
        add_settings_field(
            'passpro_button_text_color',
            __( 'Button Text Color', 'passpro' ),
            array( $this, 'color_field_callback' ),
            $this->plugin_name . '-styles',
            'passpro_styles_section',
            array(
                'label_for' => 'passpro_button_text_color',
                'description' => __( 'Text color for the button.', 'passpro' ),
                'default' => '#ffffff'
            )
        );

    }

    /**
     * Sanitize the plugin settings.
     *
     * @since    1.0.0
     * @param    array    $input    The settings input.
     * @return   array    Sanitized settings.
     */
    public function sanitize_settings( $input ) {
        $sanitized_input = array();
        $options = get_option( $this->option_name ); // Get old options if needed

        // General Settings
        $sanitized_input['passpro_enabled'] = ( isset( $input['passpro_enabled'] ) && '1' === $input['passpro_enabled'] ) ? 1 : 0;
        
        // Password handling with hash storage
        if ( isset( $input['passpro_password'] ) && ! empty( $input['passpro_password'] ) ) {
            // Store the hashed version of the password
            $sanitized_input['passpro_password_hash'] = wp_hash_password(sanitize_text_field( $input['passpro_password'] ));
            
            // For backward compatibility, also store an obfuscated version 
            // (8 chars of the real password followed by asterisks)
            $plain_password = sanitize_text_field( $input['passpro_password'] );
            $obfuscated = substr($plain_password, 0, 3) . str_repeat('*', strlen($plain_password) - 3);
            $sanitized_input['passpro_password'] = $obfuscated;
        } else {
            // Keep existing values if password field is empty
            $sanitized_input['passpro_password_hash'] = isset( $options['passpro_password_hash'] ) ? $options['passpro_password_hash'] : '';
            $sanitized_input['passpro_password'] = isset( $options['passpro_password'] ) ? $options['passpro_password'] : '';
        }
        
        $sanitized_input['passpro_allowed_ips'] = isset( $input['passpro_allowed_ips'] ) ? $this->sanitize_ip_list( $input['passpro_allowed_ips'] ) : '';
        $sanitized_input['passpro_show_logout_button'] = ( isset( $input['passpro_show_logout_button'] ) && '1' === $input['passpro_show_logout_button'] ) ? 1 : 0;

        // Appearance Settings
        $sanitized_input['passpro_logo_url'] = isset( $input['passpro_logo_url'] ) ? esc_url_raw( $input['passpro_logo_url'] ) : '';
        $sanitized_input['passpro_page_title'] = isset( $input['passpro_page_title'] ) ? sanitize_text_field( $input['passpro_page_title'] ) : '';
        $sanitized_input['passpro_headline'] = isset( $input['passpro_headline'] ) ? sanitize_text_field( $input['passpro_headline'] ) : '';
        $sanitized_input['passpro_message'] = isset( $input['passpro_message'] ) ? wp_kses_post( $input['passpro_message'] ) : '';
        $sanitized_input['passpro_background_color'] = $this->sanitize_color( isset($input['passpro_background_color']) ? $input['passpro_background_color'] : null );

        // Logo Size
        $sanitized_input['passpro_logo_max_width'] = $this->sanitize_absint( isset($input['passpro_logo_max_width']) ? $input['passpro_logo_max_width'] : null );
        $sanitized_input['passpro_logo_max_height'] = $this->sanitize_absint( isset($input['passpro_logo_max_height']) ? $input['passpro_logo_max_height'] : null );
        
        // Logo Alignment
        $sanitized_input['passpro_logo_alignment'] = $this->sanitize_select_option(
            isset($input['passpro_logo_alignment']) ? $input['passpro_logo_alignment'] : null,
            array('left', 'center', 'right')
        );

        // Box Styles
        $sanitized_input['passpro_box_bg_color'] = $this->sanitize_color( isset($input['passpro_box_bg_color']) ? $input['passpro_box_bg_color'] : null );
        $sanitized_input['passpro_box_border_color'] = $this->sanitize_color( isset($input['passpro_box_border_color']) ? $input['passpro_box_border_color'] : null );
        $sanitized_input['passpro_box_border_width'] = $this->sanitize_absint( isset($input['passpro_box_border_width']) ? $input['passpro_box_border_width'] : null );
        $sanitized_input['passpro_box_border_radius'] = $this->sanitize_absint( isset($input['passpro_box_border_radius']) ? $input['passpro_box_border_radius'] : null );

        // Input Field Styles
        $sanitized_input['passpro_input_bg_color'] = $this->sanitize_color( isset($input['passpro_input_bg_color']) ? $input['passpro_input_bg_color'] : null );
        $sanitized_input['passpro_input_text_color'] = $this->sanitize_color( isset($input['passpro_input_text_color']) ? $input['passpro_input_text_color'] : null );
        $sanitized_input['passpro_input_border_color'] = $this->sanitize_color( isset($input['passpro_input_border_color']) ? $input['passpro_input_border_color'] : null );
        $sanitized_input['passpro_input_focus_border_color'] = $this->sanitize_color( isset($input['passpro_input_focus_border_color']) ? $input['passpro_input_focus_border_color'] : null );
        $sanitized_input['passpro_input_border_width'] = $this->sanitize_absint( isset($input['passpro_input_border_width']) ? $input['passpro_input_border_width'] : null );
        $sanitized_input['passpro_input_border_radius'] = $this->sanitize_absint( isset($input['passpro_input_border_radius']) ? $input['passpro_input_border_radius'] : null );
        $sanitized_input['passpro_input_font_size'] = $this->sanitize_absint( isset($input['passpro_input_font_size']) ? $input['passpro_input_font_size'] : null );
        $sanitized_input['passpro_input_padding'] = $this->sanitize_absint( isset($input['passpro_input_padding']) ? $input['passpro_input_padding'] : null );
        
        // Text Styles
        $sanitized_input['passpro_headline_font_size'] = $this->sanitize_absint( isset($input['passpro_headline_font_size']) ? $input['passpro_headline_font_size'] : null );
        $sanitized_input['passpro_headline_font_color'] = $this->sanitize_color( isset($input['passpro_headline_font_color']) ? $input['passpro_headline_font_color'] : null );
        $sanitized_input['passpro_headline_font_family'] = $this->sanitize_font_family( isset($input['passpro_headline_font_family']) ? $input['passpro_headline_font_family'] : null );

        $sanitized_input['passpro_message_font_size'] = $this->sanitize_absint( isset($input['passpro_message_font_size']) ? $input['passpro_message_font_size'] : null );
        $sanitized_input['passpro_message_font_color'] = $this->sanitize_color( isset($input['passpro_message_font_color']) ? $input['passpro_message_font_color'] : null );
        $sanitized_input['passpro_message_font_family'] = $this->sanitize_font_family( isset($input['passpro_message_font_family']) ? $input['passpro_message_font_family'] : null );
        $sanitized_input['passpro_message_alignment'] = $this->sanitize_select_option(
            isset($input['passpro_message_alignment']) ? $input['passpro_message_alignment'] : null,
            array('left', 'center', 'right')
        );

        $sanitized_input['passpro_label_font_size'] = $this->sanitize_absint( isset($input['passpro_label_font_size']) ? $input['passpro_label_font_size'] : null );
        $sanitized_input['passpro_label_font_color'] = $this->sanitize_color( isset($input['passpro_label_font_color']) ? $input['passpro_label_font_color'] : null );
        $sanitized_input['passpro_label_font_family'] = $this->sanitize_font_family( isset($input['passpro_label_font_family']) ? $input['passpro_label_font_family'] : null );

        // Button Styles
        $sanitized_input['passpro_button_bg_color'] = $this->sanitize_color( isset($input['passpro_button_bg_color']) ? $input['passpro_button_bg_color'] : null );
        $sanitized_input['passpro_button_text_color'] = $this->sanitize_color( isset($input['passpro_button_text_color']) ? $input['passpro_button_text_color'] : null );
        $sanitized_input['passpro_button_border_color'] = $this->sanitize_color( isset($input['passpro_button_border_color']) ? $input['passpro_button_border_color'] : null );
        $sanitized_input['passpro_button_border_width'] = $this->sanitize_absint( isset($input['passpro_button_border_width']) ? $input['passpro_button_border_width'] : null );
        $sanitized_input['passpro_button_border_radius'] = $this->sanitize_absint( isset($input['passpro_button_border_radius']) ? $input['passpro_button_border_radius'] : null );
        $sanitized_input['passpro_button_font_size'] = $this->sanitize_absint( isset($input['passpro_button_font_size']) ? $input['passpro_button_font_size'] : null );

        // New button hover colors
        $sanitized_input['passpro_button_hover_bg_color'] = $this->sanitize_color( isset($input['passpro_button_hover_bg_color']) ? $input['passpro_button_hover_bg_color'] : null );
        $sanitized_input['passpro_button_hover_text_color'] = $this->sanitize_color( isset($input['passpro_button_hover_text_color']) ? $input['passpro_button_hover_text_color'] : null );
        
        // New text styling options
        $sanitized_input['passpro_button_font_weight'] = $this->sanitize_select_option( 
            isset($input['passpro_button_font_weight']) ? $input['passpro_button_font_weight'] : null,
            array('normal', 'bold', '300', '500', '600', '800')
        );
        
        $sanitized_input['passpro_button_text_transform'] = $this->sanitize_select_option(
            isset($input['passpro_button_text_transform']) ? $input['passpro_button_text_transform'] : null,
            array('none', 'uppercase', 'lowercase', 'capitalize')
        );

        // New button dimensions
        $sanitized_input['passpro_button_width'] = $this->sanitize_css_dimension( isset($input['passpro_button_width']) ? $input['passpro_button_width'] : null );
        $sanitized_input['passpro_button_height'] = $this->sanitize_css_dimension( isset($input['passpro_button_height']) ? $input['passpro_button_height'] : null );
        
        // Individual padding values (instead of one combined value)
        $sanitized_input['passpro_button_padding_top'] = $this->sanitize_absint( isset($input['passpro_button_padding_top']) ? $input['passpro_button_padding_top'] : null );
        $sanitized_input['passpro_button_padding_right'] = $this->sanitize_absint( isset($input['passpro_button_padding_right']) ? $input['passpro_button_padding_right'] : null );
        $sanitized_input['passpro_button_padding_bottom'] = $this->sanitize_absint( isset($input['passpro_button_padding_bottom']) ? $input['passpro_button_padding_bottom'] : null );
        $sanitized_input['passpro_button_padding_left'] = $this->sanitize_absint( isset($input['passpro_button_padding_left']) ? $input['passpro_button_padding_left'] : null );
        
        // Button alignment
        $sanitized_input['passpro_button_alignment'] = isset($input['passpro_button_alignment']) ? $this->sanitize_alignment( $input['passpro_button_alignment'] ) : '';
        
        // Button effects
        $sanitized_input['passpro_button_box_shadow'] = $this->sanitize_select_option(
            isset($input['passpro_button_box_shadow']) ? $input['passpro_button_box_shadow'] : null,
            array('none', 'light', 'medium', 'strong')
        );
        
        $sanitized_input['passpro_button_transition'] = $this->sanitize_select_option(
            isset($input['passpro_button_transition']) ? $input['passpro_button_transition'] : null,
            array('none', 'fast', 'normal', 'slow')
        );
        
        // Button text label
        $sanitized_input['passpro_button_text_label'] = isset($input['passpro_button_text_label']) ? sanitize_text_field($input['passpro_button_text_label']) : '';

        return $sanitized_input;
    }

    // --- Helper Sanitization Functions ---
    private function sanitize_ip_list( $ip_list_string ) {
        $ips = explode( "\n", $ip_list_string );
        $sanitized_ips = array();
        foreach ( $ips as $ip ) {
            $trimmed_ip = trim( $ip );
            if ( filter_var( $trimmed_ip, FILTER_VALIDATE_IP ) ) {
                $sanitized_ips[] = $trimmed_ip;
            }
        }
        return implode( "\n", $sanitized_ips );
    }

    private function sanitize_color( $color ) {
        if ( empty( $color ) ) {
            return '';
        }
        if ( preg_match( '/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/', $color ) ) {
            return $color;
        }
        return '';
    }

     private function sanitize_absint( $value ) {
        if ( isset($value) && $value !== '' ) {
             $intval = absint($value); // Returns 0 if conversion fails or negative
             return $intval; // Allow 0
        }
        return ''; // Return empty string if not set or empty
    }

    /**
     * Sanitize a font family value.
     *
     * @since    1.0.0
     * @param    string    $value    The value to sanitize.
     * @return   string              The sanitized value.
     */
    private function sanitize_font_family( $value ) {
        if ( empty( $value ) ) {
            return '';
        }
        return sanitize_text_field( preg_replace( '/[^a-zA-Z0-9,\s\'"-]/ ', '', $value ) );
    }

    /**
     * Sanitize a CSS dimension value (px, %, em, rem, etc).
     *
     * @since    1.0.0
     * @param    string    $value    The value to sanitize.
     * @return   string              The sanitized value.
     */
    private function sanitize_css_dimension( $value ) {
        if ( empty( $value ) ) {
            return '';
        }
        
        // Allow valid CSS dimension values (numbers followed by px, %, em, rem, vh, vw)
        if ( preg_match('/^(\d*\.?\d+)(px|%|em|rem|vh|vw|pt)?(\s+\d*\.?\d+(px|%|em|rem|vh|vw|pt)?)*$/', $value) ) {
            return sanitize_text_field( $value );
        }
        
        return '';
    }

    /**
     * Sanitize an alignment value.
     *
     * @since    1.0.0
     * @param    string    $value    The value to sanitize.
     * @return   string              The sanitized value.
     */
    private function sanitize_alignment( $value ) {
        $allowed_values = array( '', 'left', 'center', 'right' );
        if ( in_array( $value, $allowed_values, true ) ) {
            return $value;
        }
        return '';
    }

    /**
     * Sanitize a select option value.
     *
     * @since    1.0.0
     * @param    string    $value    The value to sanitize.
     * @param    array     $options  The allowed options.
     * @return   string              The sanitized value.
     */
    private function sanitize_select_option( $value, $options ) {
        if ( in_array( $value, $options, true ) ) {
            return $value;
        }
        return '';
    }

    // --- Field Rendering Callbacks ---

    /**
     * Render the 'Enabled' checkbox field.
     *
     * @since    1.0.0
     */
    public function render_enabled_field() {
        $options = get_option( $this->option_name );
        $enabled = isset( $options['passpro_enabled'] ) ? $options['passpro_enabled'] : 0;

        // Note: Removed the jQuery/CSS hack to hide the table header 'th'
        ?>
        <div class="passpro-setting-card passpro-enable-disable-card">
            <div class="passpro-setting-card-content">
                <div class="passpro-setting-card-header">
                     <label class="passpro-toggle-switch" for="passpro_enabled">
                         <input type="checkbox" id="passpro_enabled" name="<?php echo esc_attr( $this->option_name ); ?>[passpro_enabled]" value="1" <?php checked( $enabled, 1 ); ?> />
                         <span class="passpro-toggle-slider"></span>
                     </label>
                     <span class="passpro-setting-card-title"><?php esc_html_e( 'Enable Protection', 'passpro' ); ?></span>
                </div>
                <p class="passpro-setting-card-description">
                    <?php esc_html_e( 'Toggle to enable site-wide password protection. When enabled, visitors will need to enter a password before accessing your site.', 'passpro' ); ?>
                </p>
            </div>
            <div class="passpro-setting-card-icon">
                 <span class="dashicons <?php echo $enabled ? 'dashicons-lock' : 'dashicons-unlock'; ?>"></span>
            </div>
        </div>
        <?php
    }

    /**
     * Render the 'Password' input field.
     *
     * @since    1.0.0
     */
    public function render_password_field() {
        // Don't retrieve the actual password to display in the field for security.
        $options = get_option( $this->option_name );
        $has_password = !empty( $options['passpro_password'] );
        ?>
        <div class="passpro-setting-card passpro-password-card">
             <div class="passpro-setting-card-content">
                <div class="passpro-setting-card-header">
                     <span class="passpro-setting-card-title"><?php esc_html_e( 'Password', 'passpro' ); ?></span>
                </div>
                <div class="passpro-password-input-wrapper">
                    <input type="password" id="passpro_password" name="<?php echo esc_attr( $this->option_name ); ?>[passpro_password]" value="" class="regular-text passpro-password-input" placeholder="<?php esc_attr_e( 'Enter new password or leave blank', 'passpro' ); ?>" />
                    <button type="button" class="button button-secondary passpro-password-toggle"><span class="dashicons dashicons-visibility"></span></button>
                </div>
                 <p class="passpro-setting-card-description">
                    <?php if ( $has_password ): ?>
                         <span class="passpro-current-password-status"><?php esc_html_e( 'Password is currently set.', 'passpro' ); ?></span><br>
                    <?php endif; ?>
                    <?php esc_html_e( 'Enter the password required to access the site. Leave blank to keep the current password.', 'passpro' ); ?>
                 </p>
            </div>
             <div class="passpro-setting-card-icon">
                <span class="dashicons dashicons-key"></span>
            </div>
        </div>
        <?php
    }

    /**
     * Render the 'Allowed IPs' textarea field.
     *
     * @since    1.0.0
     */
    public function render_allowed_ips_field() {
        $options = get_option( $this->option_name );
        $allowed_ips = isset( $options['passpro_allowed_ips'] ) ? $options['passpro_allowed_ips'] : '';
        $has_ips = !empty( $allowed_ips );
        ?>
        <div class="passpro-setting-card passpro-ip-card">
            <div class="passpro-setting-card-content">
                <div class="passpro-setting-card-header">
                     <span class="passpro-setting-card-title"><?php esc_html_e( 'Allowed IP Addresses', 'passpro' ); ?></span>
                </div>
                <textarea id="passpro_allowed_ips" name="<?php echo esc_attr( $this->option_name ); ?>[passpro_allowed_ips]" rows="5" cols="50" class="large-text code passpro-ip-textarea" placeholder="<?php esc_attr_e( 'e.g., 192.168.1.1', 'passpro' ); ?>"><?php echo esc_textarea( $allowed_ips ); ?></textarea>
                 <p class="passpro-setting-card-description">
                     <?php if ( $has_ips ): ?>
                         <span class="passpro-current-ip-status"><?php esc_html_e( 'IP whitelist is active.', 'passpro' ); ?></span><br>
                     <?php endif; ?>
                     <?php esc_html_e( 'Enter one IP address per line. Users from these IPs will bypass password protection.', 'passpro' ); ?>
                 </p>
            </div>
             <div class="passpro-setting-card-icon">
                <span class="dashicons dashicons-networking"></span>
            </div>
        </div>
        <?php
    }

    /**
     * Render the 'Page Title' input field.
     *
     * @since    1.0.0
     */
    public function render_page_title_field() {
        $options = get_option( $this->option_name );
        $value = isset( $options['passpro_page_title'] ) ? $options['passpro_page_title'] : '';
        $default = esc_html__( 'Password Protected', 'passpro' );
        ?>
        <input type="text" id="passpro_page_title" name="<?php echo esc_attr( $this->option_name ); ?>[passpro_page_title]" value="<?php echo esc_attr( $value ); ?>" class="regular-text" placeholder="<?php echo esc_attr($default); ?>" />
        <p class="description"><?php esc_html_e( 'The title shown in the browser tab/window. Defaults to \'Password Protected\'.', 'passpro' ); ?></p>
        <?php
    }

    /**
     * Render the 'Headline' input field.
     *
     * @since    1.0.0
     */
    public function render_headline_field() {
        $options = get_option( $this->option_name );
        $value = isset( $options['passpro_headline'] ) ? $options['passpro_headline'] : '';
        $default = get_bloginfo( 'name' );
        ?>
        <input type="text" id="passpro_headline" name="<?php echo esc_attr( $this->option_name ); ?>[passpro_headline]" value="<?php echo esc_attr( $value ); ?>" class="regular-text" placeholder="<?php echo esc_attr($default); ?>" />
        <p class="description"><?php esc_html_e( 'The main heading displayed on the login page. Defaults to the site title.', 'passpro' ); ?></p>
        <?php
    }

    /**
     * Render the 'Message' textarea field.
     *
     * @since    1.0.0
     */
    public function render_message_field() {
        $options = get_option( $this->option_name );
        $value = isset( $options['passpro_message'] ) ? $options['passpro_message'] : '';
        ?>
        <textarea id="passpro_message" name="<?php echo esc_attr( $this->option_name ); ?>[passpro_message]" rows="5" cols="50" class="large-text"><?php echo esc_textarea( $value ); // Use esc_textarea for textarea value ?></textarea>
        <p class="description"><?php esc_html_e( 'Optional message displayed below the password form. Basic HTML is allowed.', 'passpro' ); ?></p>
        <?php
    }

    /**
     * Render the 'Logo' upload field.
     *
     * @since    1.0.0
     */
    public function render_logo_field() {
        $options = get_option( $this->option_name );
        $value = isset( $options['passpro_logo_url'] ) ? $options['passpro_logo_url'] : '';
        ?>
        <div class="passpro-media-uploader">
            <input type="text" id="passpro_logo_url" name="<?php echo esc_attr( $this->option_name ); ?>[passpro_logo_url]" value="<?php echo esc_attr( $value ); ?>" class="regular-text passpro-media-url" />
            <button type="button" class="button passpro-upload-button"><?php esc_html_e( 'Upload Logo', 'passpro' ); ?></button>
            <button type="button" class="button passpro-remove-button" style="<?php echo empty($value) ? 'display:none;' : ''; ?>"><?php esc_html_e( 'Remove Logo', 'passpro' ); ?></button>
            <div class="passpro-logo-preview" style="margin-top: 10px;">
                <?php if ( ! empty( $value ) ) : ?>
                    <img src="<?php echo esc_url( $value ); ?>" style="max-width: 200px; max-height: 100px;" />
                <?php endif; ?>
            </div>
        </div>
        <p class="description"><?php esc_html_e( 'Upload or select a logo to display above the headline.', 'passpro' ); ?></p>
        <?php
    }


    /**
     * Render the 'Background Color' color picker field (Updated).
     *
     * @since    1.0.0
     */
    public function render_background_color_field() {
        $options = get_option( $this->option_name );
        $value = isset( $options['passpro_background_color'] ) ? $options['passpro_background_color'] : '#f1f1f1';
        // Use the class 'passpro-color-picker' for JS targeting
        ?>
        <input type="text" id="passpro_background_color" name="<?php echo esc_attr( $this->option_name ); ?>[passpro_background_color]" value="<?php echo esc_attr( $value ); ?>" class="passpro-color-picker" data-default-color="#f1f1f1" />
        <p class="description"><?php esc_html_e( 'Select the background color for the login page.', 'passpro' ); ?></p>
        <?php
    }

    /**
     * Generic Render Callback for Color Fields.
     *
     * @since    1.0.0
     * @param    array $args Arguments passed from add_settings_field
     */
    public function render_color_field( $args ) {
        $options = get_option( $this->option_name );
        $id = esc_attr( $args['id'] );
        $default = isset($args['default']) ? esc_attr($args['default']) : '#000000';
        $value = isset( $options[$id] ) && $options[$id] !== '' ? $options[$id] : $default;
        ?>
        <input type="text" id="<?php echo $id; ?>" name="<?php echo esc_attr( $this->option_name ); ?>[<?php echo $id; ?>]" value="<?php echo esc_attr( $value ); ?>" class="passpro-color-picker" data-default-color="<?php echo $default; ?>" />
        <?php
         if ( isset($args['description']) ) {
            echo '<p class="description">' . esc_html( $args['description'] ) . '</p>';
        }
    }

    /**
     * Generic Render Callback for Number Fields.
     *
     * @since    1.0.0
     * @param    array $args Arguments passed from add_settings_field
     */
    public function render_number_field( $args ) {
        $options = get_option( $this->option_name );
        $id = esc_attr( $args['id'] );
        $default = isset($args['default']) ? esc_attr($args['default']) : '0';
        $value = isset( $options[$id] ) && $options[$id] !== '' ? $options[$id] : $default;
        $min = isset($args['min']) ? esc_attr($args['min']) : '0';
        $step = isset($args['step']) ? esc_attr($args['step']) : '1';
        $placeholder = isset($args['placeholder']) ? esc_attr($args['placeholder']) : '';
        ?>
        <input type="number" id="<?php echo $id; ?>" name="<?php echo esc_attr( $this->option_name ); ?>[<?php echo $id; ?>]" value="<?php echo esc_attr( $value ); ?>" class="small-text" min="<?php echo $min; ?>" step="<?php echo $step; ?>" placeholder="<?php echo $placeholder; ?>" />
         <?php
         if ( isset($args['description']) ) {
            echo '<p class="description">' . esc_html( $args['description'] ) . '</p>';
        }
    }

    /**
     * Generic Render Callback for Text Fields.
     *
     * @since    1.0.0
     * @param    array $args Arguments passed from add_settings_field
     */
    public function render_text_field( $args ) {
        $options = get_option( $this->option_name );
        $id = esc_attr( $args['id'] );
        $value = isset( $options[$id] ) ? $options[$id] : '';
        $placeholder = isset($args['placeholder']) ? esc_attr($args['placeholder']) : '';
        ?>
        <input type="text" id="<?php echo $id; ?>" name="<?php echo esc_attr( $this->option_name ); ?>[<?php echo $id; ?>]" value="<?php echo esc_attr( $value ); ?>" class="regular-text" placeholder="<?php echo $placeholder; ?>" />
         <?php
         if ( isset($args['description']) ) {
            echo '<p class="description">' . esc_html( $args['description'] ) . '</p>';
        }
    }

    /**
     * Render the 'Heading' field.
     *
     * @since    1.0.0
     * @param    array $args Arguments passed from add_settings_field
     */
    public function render_heading_field( $args ) {
        ?>
        <h4 style="margin: 1.5em 0 0.5em; padding-bottom: 0.2em; border-bottom: 1px solid #ccc; font-size: 14px; color: #23282d;"><?php echo esc_html( $args['heading'] ); ?></h4>
        <?php
    }

    /**
     * Render the 'Select' field.
     *
     * @since    1.0.0
     * @param    array $args Arguments passed from add_settings_field
     */
    public function render_select_field( $args ) {
        $options = get_option( $this->option_name );
        $id = esc_attr( $args['id'] );
        $default = isset($args['default']) ? esc_attr($args['default']) : '';
        $value = isset( $options[$id] ) && $options[$id] !== '' ? $options[$id] : $default;
        $options = $args['options'];
        ?>
        <select id="<?php echo $id; ?>" name="<?php echo esc_attr( $this->option_name ); ?>[<?php echo $id; ?>]">
            <?php foreach ( $options as $option_value => $option_label ) : ?>
                <option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $value, $option_value ); ?>>
                    <?php echo esc_html( $option_label ); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php
        if ( isset($args['description']) ) {
            echo '<p class="description">' . esc_html( $args['description'] ) . '</p>';
        }
    }

    /**
     * Render the 'Padding' fields.
     *
     * @since    1.0.0
     * @param    array $args Arguments passed from add_settings_field
     */
    public function render_padding_fields( $args ) {
        $options = get_option( $this->option_name );
        
        // Get individual padding values
        $padding_top = isset( $options['passpro_button_padding_top'] ) ? $options['passpro_button_padding_top'] : '';
        $padding_right = isset( $options['passpro_button_padding_right'] ) ? $options['passpro_button_padding_right'] : '';
        $padding_bottom = isset( $options['passpro_button_padding_bottom'] ) ? $options['passpro_button_padding_bottom'] : '';
        $padding_left = isset( $options['passpro_button_padding_left'] ) ? $options['passpro_button_padding_left'] : '';
        
        ?>
        <div class="padding-fields-container" style="display: flex; gap: 5px; align-items: flex-start; margin-bottom: 5px;">
            <div style="text-align: center;">
                <label for="passpro_button_padding_top"><?php esc_html_e( 'Top', 'passpro' ); ?></label><br>
                <input type="number" id="passpro_button_padding_top" name="<?php echo esc_attr( $this->option_name ); ?>[passpro_button_padding_top]" 
                    value="<?php echo esc_attr( $padding_top ); ?>" class="small-text" min="0" step="1" />
            </div>
            <div style="text-align: center;">
                <label for="passpro_button_padding_right"><?php esc_html_e( 'Right', 'passpro' ); ?></label><br>
                <input type="number" id="passpro_button_padding_right" name="<?php echo esc_attr( $this->option_name ); ?>[passpro_button_padding_right]" 
                    value="<?php echo esc_attr( $padding_right ); ?>" class="small-text" min="0" step="1" />
            </div>
            <div style="text-align: center;">
                <label for="passpro_button_padding_bottom"><?php esc_html_e( 'Bottom', 'passpro' ); ?></label><br>
                <input type="number" id="passpro_button_padding_bottom" name="<?php echo esc_attr( $this->option_name ); ?>[passpro_button_padding_bottom]" 
                    value="<?php echo esc_attr( $padding_bottom ); ?>" class="small-text" min="0" step="1" />
            </div>
            <div style="text-align: center;">
                <label for="passpro_button_padding_left"><?php esc_html_e( 'Left', 'passpro' ); ?></label><br>
                <input type="number" id="passpro_button_padding_left" name="<?php echo esc_attr( $this->option_name ); ?>[passpro_button_padding_left]" 
                    value="<?php echo esc_attr( $padding_left ); ?>" class="small-text" min="0" step="1" />
            </div>
        </div>
        <?php if ( isset($args['description']) ) : ?>
            <p class="description"><?php echo esc_html( $args['description'] ); ?></p>
        <?php endif; ?>
        <?php
    }

    /**
     * Add settings link to plugin page.
     *
     * @since    1.0.0
     * @param    array    $links    Existing links.
     * @return   array    Modified links.
     */
    public function add_action_links( $links ) {
        $settings_link = sprintf(
            '<a href="%s">%s</a>',
            esc_url( admin_url( 'admin.php?page=' . $this->plugin_name ) ), // Use admin.php for top-level pages
            esc_html__( 'Settings', 'passpro' )
        );
        array_unshift( $links, $settings_link );
        return $links;
    }

    /**
     * Handle password management actions (add, edit, delete)
     *
     * @since    1.0.0
     */
    public function handle_password_actions() {
        // Check if we're on the passwords page using the correct slug
        if (!isset($_GET['page']) || $_GET['page'] !== $this->plugin_name . '_passwords') {
            return;
        }

        // Check for password actions
        if (isset($_POST['action']) && isset($_POST['_wpnonce'])) {
            $action = sanitize_text_field($_POST['action']);
            $nonce = sanitize_text_field($_POST['_wpnonce']);

            // Verify nonce
            if (!wp_verify_nonce($nonce, 'passpro_password_action')) {
                wp_die(esc_html__('Security check failed.', 'passpro'));
            }

            // Process the action
            switch ($action) {
                case 'add_password':
                    $this->process_add_password();
                    break;
                case 'edit_password':
                    $this->process_edit_password();
                    break;
                case 'delete_password':
                    $this->process_delete_password();
                    break;
                case 'toggle_password_status':
                    $this->process_toggle_password_status();
                    break;
            }
        }

        // Check for GET actions (like delete or toggle)
        if (isset($_GET['action']) && isset($_GET['password_id']) && isset($_GET['_wpnonce'])) {
            $action = sanitize_text_field($_GET['action']);
            $password_id = intval($_GET['password_id']);
            $nonce = sanitize_text_field($_GET['_wpnonce']);

            // Verify nonce
            if (!wp_verify_nonce($nonce, 'passpro_password_action_' . $password_id)) {
                wp_die(esc_html__('Security check failed.', 'passpro'));
            }

            // Define the base redirect URL
            $redirect_url = admin_url('admin.php?page=' . $this->plugin_name . '_passwords'); // Use admin.php

            // Process the action
            switch ($action) {
                case 'delete':
                    $this->db->delete_password($password_id);
                    wp_redirect(add_query_arg('message', 'deleted', $redirect_url));
                    exit;
                case 'toggle_status':
                    $password = $this->db->get_password($password_id);
                    if ($password) {
                        $new_status = ($password->status === 'active') ? 'inactive' : 'active';
                        $this->db->update_password($password_id, array('status' => $new_status));
                    }
                    wp_redirect(add_query_arg('message', 'status_updated', $redirect_url));
                    exit;
            }
        }
    }

    /**
     * Process adding a new password
     *
     * @since    1.0.0
     */
    private function process_add_password() {
        if (!isset($_POST['password'])) {
            return;
        }

        $password = sanitize_text_field($_POST['password']);
        // Define the base redirect URL
        $redirect_url = admin_url('admin.php?page=' . $this->plugin_name . '_passwords'); // Use admin.php

        if (empty($password)) {
            wp_redirect(add_query_arg('error', 'empty_password', $redirect_url));
            exit;
        }

        // Format the expiry date properly if it exists
        $expiry_date = null;
        if (!empty($_POST['expiry_date'])) {
            // Try to parse the submitted date
            $expiry_timestamp = strtotime(sanitize_text_field($_POST['expiry_date']));
            if ($expiry_timestamp !== false) {
                $expiry_date = date('Y-m-d H:i:s', $expiry_timestamp);
            }
        }

        $data = array(
            'password' => $password,
            'name' => isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '',
            'uses_remaining' => isset($_POST['uses_remaining']) && $_POST['uses_remaining'] !== '' ? intval($_POST['uses_remaining']) : null,
            'expiry_date' => $expiry_date,
            'bypass_url' => isset($_POST['bypass_url']) ? esc_url_raw($_POST['bypass_url']) : '',
            'status' => 'active'
        );

        $result = $this->db->add_password($data);

        if ($result) {
            wp_redirect(add_query_arg('message', 'added', $redirect_url));
        } else {
            wp_redirect(add_query_arg('error', 'add_failed', $redirect_url));
        }
        exit;
    }

    /**
     * Process editing an existing password
     *
     * @since    1.0.0
     */
    private function process_edit_password() {
        if (!isset($_POST['password_id']) || !isset($_POST['password'])) {
            return;
        }

        $password_id = intval($_POST['password_id']);
        $password = sanitize_text_field($_POST['password']);
        // Define the base redirect URL
        $redirect_url = admin_url('admin.php?page=' . $this->plugin_name . '_passwords'); // Use admin.php

        if (empty($password)) {
            wp_redirect(add_query_arg('error', 'empty_password', $redirect_url));
            exit;
        }

        // Format the expiry date properly if it exists
        $expiry_date = null;
        if (!empty($_POST['expiry_date'])) {
            // Try to parse the submitted date
            $expiry_timestamp = strtotime(sanitize_text_field($_POST['expiry_date']));
            if ($expiry_timestamp !== false) {
                $expiry_date = date('Y-m-d H:i:s', $expiry_timestamp);
            }
        }

        $data = array(
            'password' => $password,
            'name' => isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '',
            'uses_remaining' => isset($_POST['uses_remaining']) && $_POST['uses_remaining'] !== '' ? intval($_POST['uses_remaining']) : null,
            'expiry_date' => $expiry_date,
            'bypass_url' => isset($_POST['bypass_url']) ? esc_url_raw($_POST['bypass_url']) : '',
        );

        $result = $this->db->update_password($password_id, $data);

        if ($result !== false) {
            wp_redirect(add_query_arg('message', 'updated', $redirect_url));
        } else {
            wp_redirect(add_query_arg('error', 'update_failed', $redirect_url));
        }
        exit;
    }

    /**
     * Process deleting a password
     *
     * @since    1.0.0
     */
    private function process_delete_password() {
        if (!isset($_POST['password_id'])) {
            return;
        }

        $password_id = intval($_POST['password_id']);
        // Define the base redirect URL
        $redirect_url = admin_url('admin.php?page=' . $this->plugin_name . '_passwords'); // Use admin.php
        $result = $this->db->delete_password($password_id);

        if ($result) {
            wp_redirect(add_query_arg('message', 'deleted', $redirect_url));
        } else {
            wp_redirect(add_query_arg('error', 'delete_failed', $redirect_url));
        }
        exit;
    }

    /**
     * Process toggling a password's status
     *
     * @since    1.0.0
     */
    private function process_toggle_password_status() {
        if (!isset($_POST['password_id'])) {
            return;
        }

        $password_id = intval($_POST['password_id']);
        // Define the base redirect URL
        $redirect_url = admin_url('admin.php?page=' . $this->plugin_name . '_passwords'); // Use admin.php
        $password = $this->db->get_password($password_id);

        if (!$password) {
            wp_redirect(add_query_arg('error', 'not_found', $redirect_url));
            exit;
        }

        $new_status = ($password->status === 'active') ? 'inactive' : 'active';
        $result = $this->db->update_password($password_id, array('status' => $new_status));

        if ($result !== false) {
            wp_redirect(add_query_arg('message', 'status_updated', $redirect_url));
        } else {
            wp_redirect(add_query_arg('error', 'update_failed', $redirect_url));
        }
        exit;
    }

    /**
     * Display the passwords management page
     *
     * @since    1.0.0
     */
    public function display_passwords_page() {
        // Get all passwords
        $passwords = $this->db->get_passwords();
        
        // Check if we're editing a password
        $editing = false;
        $password_to_edit = null;
        
        if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['password_id'])) {
            $password_id = intval($_GET['password_id']);
            $password_to_edit = $this->db->get_password($password_id);
            if ($password_to_edit) {
                $editing = true;
            }
        }
        
        // Include the view template
        include_once( 'partials/passpro-admin-passwords.php' );
    }

    /**
     * Render the 'Show Logout Button' toggle field.
     *
     * @since    1.0.6
     */
    public function render_show_logout_button_field() {
        $options = get_option( $this->option_name );
        $checked = isset( $options['passpro_show_logout_button'] ) ? (bool) $options['passpro_show_logout_button'] : true; // Default to true
        ?>
        <div class="passpro-setting-card passpro-toggle-card">
             <div class="passpro-setting-card-content">
                <div class="passpro-setting-card-header">
                    <span class="passpro-setting-card-title"><?php esc_html_e( 'Show Logout Button', 'passpro' ); ?></span>
                </div>
                <label class="passpro-switch">
                    <input type="checkbox" id="passpro_show_logout_button" name="<?php echo esc_attr( $this->option_name ); ?>[passpro_show_logout_button]" value="1" <?php checked( 1, $checked ); ?>>
                    <span class="passpro-slider round"></span>
                </label>
                <p class="passpro-setting-card-description">
                    <?php esc_html_e( 'Display a small logout button in the corner for authenticated visitors.', 'passpro' ); ?>
                </p>
            </div>
            <div class="passpro-setting-card-icon">
                <span class="dashicons dashicons-exit"></span>
            </div>
        </div>
        <?php
    }

} 