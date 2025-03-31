<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    PassPro
 * @subpackage PassPro/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks for
 * the public-facing side of the site.
 *
 * @package    PassPro
 * @subpackage PassPro/public
 * @author     Your Name <email@example.com>
 */
class PassPro_Public {

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
	 * Plugin options.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $options    The plugin options.
	 */
    private $option_name;
    private $options;

    /**
     * The name of the cookie used to store the session.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $cookie_name    The cookie name.
     */
    private $cookie_name;

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
	 * @param    string    $plugin_name       The name of the plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name; // This is 'passpro'
		$this->version = $version;
        $this->option_name = $this->plugin_name . '_options'; // This becomes 'passpro_options'
        $this->options = get_option( $this->option_name );
        // Use a hash of site URL and plugin name for cookie name for uniqueness
        $this->cookie_name = 'wp_passpro_' . md5( get_site_url() . $this->plugin_name );
        $this->db = new PassPro_DB();

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
        // Only enqueue styles if protection is active and needed
        if ( $this->is_protection_needed() ) {
		    wp_enqueue_style( $this->plugin_name, PASSPRO_PLUGIN_URL . 'public/css/passpro-public.css', array(), $this->version, 'all' );
            
            // If protection is needed, also stop other scripts and styles that might add chat widgets
            add_action('wp_print_scripts', array($this, 'dequeue_chat_scripts'), 100);
            add_action('wp_print_styles', array($this, 'dequeue_chat_styles'), 100);
        }
	}

    /**
     * Dequeue scripts that might be loading chat widgets
     * 
     * @since    1.0.0
     */
    public function dequeue_chat_scripts() {
        global $wp_scripts;
        
        if (!is_object($wp_scripts) || empty($wp_scripts->registered)) {
            return;
        }
        
        // List of script handles or partial matches to dequeue
        $chat_scripts = array(
            'chat', 'crisp', 'intercom', 'zendesk', 'zopim', 'livechat', 'tawkto', 'tawk',
            'hubspot', 'tidio', 'drift', 'freshchat', 'freshdesk', 'smallchat', 'messaging',
            'messenger', 'fb-customer-chat', 'facebook-messenger'
        );
        
        // List of script URLs or partial matches to dequeue
        $chat_urls = array(
            'chat', 'crisp.chat', 'intercom.io', 'zendesk.com', 'zopim.com', 'livechatinc.com',
            'tawk.to', 'hubspot.com', 'tidio.co', 'drift.com', 'freshchat', 'freshdesk',
            'smallchat', 'messenger', 'connect.facebook.net'
        );
        
        foreach ($wp_scripts->registered as $handle => $script) {
            // Check script handle against our list
            foreach ($chat_scripts as $chat_script) {
                if (stripos($handle, $chat_script) !== false) {
                    wp_dequeue_script($handle);
                    wp_deregister_script($handle);
                    continue 2; // Skip to next registered script
                }
            }
            
            // Check script source URL against our list
            if (isset($script->src)) {
                foreach ($chat_urls as $chat_url) {
                    if (stripos($script->src, $chat_url) !== false) {
                        wp_dequeue_script($handle);
                        wp_deregister_script($handle);
                        break;
                    }
                }
            }
        }
    }
    
    /**
     * Dequeue styles that might be related to chat widgets
     * 
     * @since    1.0.0
     */
    public function dequeue_chat_styles() {
        global $wp_styles;
        
        if (!is_object($wp_styles) || empty($wp_styles->registered)) {
            return;
        }
        
        // List of style handles or partial matches to dequeue
        $chat_styles = array(
            'chat', 'crisp', 'intercom', 'zendesk', 'zopim', 'livechat', 'tawkto', 'tawk',
            'hubspot', 'tidio', 'drift', 'freshchat', 'freshdesk', 'smallchat', 'messaging',
            'messenger', 'fb-customer-chat', 'facebook-messenger'
        );
        
        foreach ($wp_styles->registered as $handle => $style) {
            // Check style handle against our list
            foreach ($chat_styles as $chat_style) {
                if (stripos($handle, $chat_style) !== false) {
                    wp_dequeue_style($handle);
                    wp_deregister_style($handle);
                    break;
                }
            }
        }
    }

    /**
     * Check if the user needs to be prompted for a password.
     *
     * @since    1.0.0
     * @return   bool    True if protection is needed, false otherwise.
     */
    private function is_protection_needed() {
        // 1. Check if protection is enabled in settings
        if ( empty( $this->options['passpro_enabled'] ) || ! $this->options['passpro_enabled'] ) {
            return false;
        }

        // 2. Check if a valid password is set
        if ( empty( $this->options['passpro_password'] ) ) {
            // If enabled but no password set, maybe show an admin notice? For now, disable protection.
            return false;
        }

        // 3. Check if user is logged in and has manage_options capability (administrator)
        if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
            return false;
        }

        // 4. Check if the visitor's IP is in the allowed list
        if ( $this->is_ip_allowed() ) {
            return false;
        }

        // 5. Check for a valid cookie
        if ( isset( $_COOKIE[ $this->cookie_name ] ) && $_COOKIE[ $this->cookie_name ] === $this->generate_cookie_hash() ) {
            return false;
        }

        // If none of the above bypass conditions are met, protection is needed.
        return true;
    }

    /**
     * Check if the current visitor's IP is in the allowed list.
     *
     * @since    1.0.0
     * @return   bool    True if IP is allowed, false otherwise.
     */
    private function is_ip_allowed() {
        $allowed_ips_string = isset( $this->options['passpro_allowed_ips'] ) ? $this->options['passpro_allowed_ips'] : '';
        if ( empty( $allowed_ips_string ) ) {
            return false;
        }

        $allowed_ips = explode( "\n", $allowed_ips_string );
        $visitor_ip = $this->get_visitor_ip();

        foreach ( $allowed_ips as $ip ) {
            $trimmed_ip = trim( $ip );
            if ( ! empty( $trimmed_ip ) && $visitor_ip === $trimmed_ip ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the visitor's IP address.
     *
     * @since    1.0.0
     * @return   string|false    Visitor's IP address or false if not found.
     */
    private function get_visitor_ip() {
        $ip_keys = array(
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        );
        foreach ( $ip_keys as $key ) {
            if ( array_key_exists( $key, $_SERVER ) === true ) {
                foreach ( explode( ',', $_SERVER[ $key ] ) as $ip ) {
                    $ip = trim( $ip );
                    if ( filter_var( $ip, FILTER_VALIDATE_IP ) !== false ) {
                        return $ip;
                    }
                }
            }
        }
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
    }

    /**
     * Generate the expected cookie hash based on the password.
     *
     * @since    1.0.0
     * @return   string    The expected cookie hash.
     */
    private function generate_cookie_hash() {
        // Use site-specific salt instead of the actual password
        $site_salt = wp_salt('auth');
        return md5($site_salt . '|' . $this->plugin_name . '|' . get_site_url());
    }

    /**
     * Check password protection status on template redirect.
     *
     * @since    1.0.0
     */
    public function check_password_protection() {

        // Check if this is a login attempt
        if ( isset( $_POST['passpro_password_submit'] ) ) {
            $this->handle_login_attempt();
            // handle_login_attempt will redirect on success, or fall through to show form again on failure.
        }

        // Check if protection is needed for the current user/request
        if ( $this->is_protection_needed() ) {
            // Prevent caching of the login page
            nocache_headers();

            // Load the login page template
            include_once( PASSPRO_PLUGIN_DIR . 'public/partials/passpro-public-display.php' );

            // Stop further execution
            exit;
        }
    }

    /**
     * Handle the password submission.
     *
     * @since    1.0.0
     */
    private function handle_login_attempt() {
        if ( ! isset( $_POST['passpro_pwd'] ) || ! isset( $_POST['_wpnonce'] ) ) {
             return; // Missing fields
        }

        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'passpro_login_nonce' ) ) {
            wp_die( esc_html__( 'Security check failed. Please try again.', 'passpro' ) );
        }

        $submitted_password = sanitize_text_field( $_POST['passpro_pwd'] );
        $main_password_hash = isset( $this->options['passpro_password_hash'] ) ? $this->options['passpro_password_hash'] : '';
        $main_password = isset( $this->options['passpro_password'] ) ? $this->options['passpro_password'] : '';

        // First check against the main password in settings
        $password_valid = false;
        
        // Check if we're using the new hashed password or legacy plain text
        if (!empty($main_password_hash)) {
            $password_valid = wp_check_password($submitted_password, $main_password_hash);
        } else if (!empty($main_password)) {
            // Legacy check for plain text password - for backward compatibility
            $password_valid = ($submitted_password === $main_password);
        }
        
        // If it didn't match the main password, try the multiple passwords
        if (!$password_valid) {
            $valid_password_id = $this->db->verify_password($submitted_password);
            $password_valid = ($valid_password_id !== false);
        }

        if ($password_valid) {
            // Set cookie - lasts for 1 day by default
            $cookie_hash = $this->generate_cookie_hash();
            $expire = apply_filters( 'passpro_cookie_expire', time() + DAY_IN_SECONDS );
            $secure = apply_filters( 'passpro_cookie_secure', is_ssl() ); // Set secure flag if site is SSL

            setcookie( $this->cookie_name, $cookie_hash, $expire, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, $secure, true ); // httponly = true

            // Redirect back to the originally requested page
            $redirect_to = isset( $_REQUEST['redirect_to'] ) ? esc_url_raw( $_REQUEST['redirect_to'] ) : home_url( '/' );
            wp_safe_redirect( $redirect_to );
            exit;
        } else {
            // Add a query arg to indicate failed login attempt (optional)
            // You could use this in the template to show an error message.
            $current_url = add_query_arg( 'login', 'failed', $this->get_current_url() );
            wp_redirect( $current_url ); // Redirect back to login form with error indicator
            exit;
        }
    }

    /**
     * Render the login form.
     *
     * @since    1.0.0
     */
    public function render_login_form() {
        $login_failed = isset( $_GET['login'] ) && $_GET['login'] === 'failed';
        $redirect_to = $this->get_current_url(); // Redirect back to current page after login
        
        // Get button text from settings with fallback
        $options = get_option( $this->option_name );
        $button_text = ! empty( $options['passpro_button_text_label'] ) ? 
                       $options['passpro_button_text_label'] : 
                       esc_attr__( 'Log In', 'passpro' );
        ?>
        <form name="loginform" id="loginform" action="" method="post">
            <?php if ( $login_failed ) : ?>
                <p class="passpro-error"><?php esc_html_e( 'Incorrect password. Please try again.', 'passpro' ); ?></p>
            <?php endif; ?>
            <p>
                <label for="passpro_pwd"><?php esc_html_e( 'Password', 'passpro' ); ?><br />
                <input type="password" name="passpro_pwd" id="passpro_pwd" class="input" value="" size="20" /></label>
            </p>
            <p class="submit">
                <?php wp_nonce_field( 'passpro_login_nonce' ); ?>
                <input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>" />
                <input type="submit" name="passpro_password_submit" id="wp-submit" class="button button-primary button-large" value="<?php echo esc_attr( $button_text ); ?>" />
            </p>
        </form>
        <?php
    }

    /**
     * Get the current page URL.
     *
     * @since    1.0.0
     * @return string The current page URL.
     */
    private function get_current_url() {
        global $wp;
        // Remove the 'login=failed' query arg if present
        $current_url = remove_query_arg( 'login', home_url( $wp->request ) );
        // Ensure we have a trailing slash if it's the home URL
        if ( trailingslashit($current_url) === trailingslashit(home_url('/')) ) {
            return home_url( '/' );
        }
        return trailingslashit( $current_url );
    }

    /**
     * Output custom CSS to the login page header.
     *
     * @since    1.0.0
     */
    public function output_custom_login_styles() {
        // Re-fetch options here in case they were updated since construct
        $options = get_option( $this->option_name );
        
        // Only output styles if we're on our password page or if login is needed
        // Start building CSS
        $css = "/* PassPro Custom Styles */\n";

        // --- VERY Aggressive Background Style ---
        $bg_color = ! empty( $options['passpro_background_color'] ) ? $options['passpro_background_color'] : '#f1f1f1';
        $css .= "html, body, body.login, body.login-passpro { background-color: " . esc_attr($bg_color) . " !important; }\n";

        // --- Hide Chat Widgets and Floating Elements ---
        $css .= "/* Hide chat widgets and floating elements on the password page */\n";
        $css .= "body.login-passpro .crisp-client, "; // Crisp chat
        $css .= "body.login-passpro #intercom-container, "; // Intercom chat
        $css .= "body.login-passpro .olark-chat-wrapper, "; // Olark chat
        $css .= "body.login-passpro .fb_dialog, "; // Facebook Messenger
        $css .= "body.login-passpro .fb-customerchat, "; // Facebook Customer Chat
        $css .= "body.login-passpro .drift-frame-controller, "; // Drift chat
        $css .= "body.login-passpro .drift-conductor-item, "; // Drift chat elements
        $css .= "body.login-passpro [class*='livechat'], "; // LiveChat
        $css .= "body.login-passpro [class*='chat-widget'], "; // Generic chat widgets
        $css .= "body.login-passpro [class*='chat-bubble'], "; // Generic chat bubbles
        $css .= "body.login-passpro [class*='chat-icon'], "; // Generic chat icons
        $css .= "body.login-passpro [class*='chat-button'], "; // Generic chat buttons
        $css .= "body.login-passpro [class*='tawkto'], "; // Tawk.to chat
        $css .= "body.login-passpro [id*='chat-widget'], "; // Generic chat widgets by ID
        $css .= "body.login-passpro [id*='chat-bubble'], "; // Generic chat bubbles by ID
        $css .= "body.login-passpro [id*='livechat'], "; // LiveChat by ID
        $css .= "body.login-passpro [id*='tawkto'], "; // Tawk.to by ID
        $css .= "body.login-passpro [id*='intercom'], "; // Intercom by ID
        $css .= "body.login-passpro [id*='crisp'], "; // Crisp by ID
        $css .= "body.login-passpro [id*='drift'], "; // Drift by ID
        $css .= "body.login-passpro .tidio-chat-wrapper, "; // Tidio chat
        $css .= "body.login-passpro #tidio-chat, "; // Tidio chat
        $css .= "body.login-passpro [id*='zopim'], "; // Zendesk chat (formerly Zopim)
        $css .= "body.login-passpro .zopim, "; // Zendesk chat
        $css .= "body.login-passpro .wc-bubble, "; // Various WooCommerce chat bubbles
        $css .= "body.login-passpro .wc-chat, "; // Various WooCommerce chat
        $css .= "body.login-passpro div[class*='helpdesk'], "; // Various help desk widgets
        $css .= "body.login-passpro div[class*='support-chat'], "; // Various support chat widgets
        $css .= "body.login-passpro div[role='dialog'][aria-label*='chat'], "; // ARIA labeled chat dialogs
        $css .= "body.login-passpro div[class*='floating'], "; // Generic floating elements
        $css .= "body.login-passpro .fixed-chat-button "; // Fixed position chat buttons
        $css .= "{ display: none !important; visibility: hidden !important; opacity: 0 !important; }\n";

        // --- Logo --- (.passpro-logo img)
        if ( ! empty( $options['passpro_logo_max_width'] ) || ! empty( $options['passpro_logo_max_height'] ) || ! empty( $options['passpro_logo_alignment'] ) ) {
            // Container styles - make sure they always apply
            $css .= "html body.login-passpro #login .passpro-logo, html body.login #login .passpro-logo, .passpro-logo { ";
            $css .= "display: block !important; width: 100% !important; ";
            if ( ! empty( $options['passpro_logo_alignment'] ) ) {
                $css .= "text-align: " . esc_attr($options['passpro_logo_alignment']) . " !important; ";
            } else {
                $css .= "text-align: center !important; "; // Default to center
            }
            $css .= "}\n";
            
            // Image styles
            $css .= "html body.login-passpro #login .passpro-logo img, html body.login #login .passpro-logo img, .passpro-logo img { ";
            $css .= "display: inline-block !important; "; // Change to inline-block for proper alignment
            
            // Handle max-width and height with proper constraints
            if ( ! empty( $options['passpro_logo_max_width'] ) ) {
                $css .= "max-width: " . intval($options['passpro_logo_max_width']) . "px !important; ";
            } else {
                $css .= "max-width: 100% !important; "; // Constrain to container by default
            }
            
            if ( ! empty( $options['passpro_logo_max_height'] ) ) {
                $css .= "max-height: " . intval($options['passpro_logo_max_height']) . "px !important; ";
            }
            
            $css .= "height: auto !important; "; // Maintain aspect ratio
            $css .= "width: auto !important; "; // Allow width to be automatically calculated
            
            // Apply margin based on alignment
            if ( ! empty( $options['passpro_logo_alignment'] ) ) {
                switch ( $options['passpro_logo_alignment'] ) {
                    case 'left':
                        $css .= "margin: 0 auto 20px 0 !important; ";
                        break;
                    case 'right':
                        $css .= "margin: 0 0 20px auto !important; ";
                        break;
                    case 'center':
                    default:
                        $css .= "margin: 0 auto 20px !important; ";
                        break;
                }
            } else {
                $css .= "margin: 0 auto 20px !important; "; // Default to center
            }
            
            $css .= "}\n";
        }
        // Handle logo alignment independently (ensure it's always applied even if no max width/height is set)
        else if ( ! empty( $options['passpro_logo_alignment'] ) ) {
            // Container styles
            $css .= "html body.login-passpro #login .passpro-logo, html body.login #login .passpro-logo, .passpro-logo { ";
            $css .= "text-align: " . esc_attr($options['passpro_logo_alignment']) . " !important; ";
            $css .= "display: block !important; width: 100% !important; "; // Ensure container takes full width
            $css .= "}\n";
            
            // Image styles
            $css .= "html body.login-passpro #login .passpro-logo img, html body.login #login .passpro-logo img, .passpro-logo img { ";
            $css .= "display: inline-block !important; "; // Use inline-block instead of block
            $css .= "max-width: 100% !important; "; // Constrain to container width
            $css .= "height: auto !important; "; // Maintain aspect ratio
            
            switch ( $options['passpro_logo_alignment'] ) {
                case 'left':
                    $css .= "margin: 0 auto 20px 0 !important; ";
                    break;
                case 'right':
                    $css .= "margin: 0 0 20px auto !important; ";
                    break;
                case 'center':
                default:
                    $css .= "margin: 0 auto 20px !important; ";
                    break;
            }
            $css .= "}\n";
        }

        // --- Headline --- (h1 a)
        if ( ! empty( $options['passpro_headline_font_size'] ) || ! empty( $options['passpro_headline_font_color'] ) || ! empty( $options['passpro_headline_font_family'] ) ) {
            $css .= "body.login-passpro #login h1 a, #login h1 a { ";
            if ( ! empty( $options['passpro_headline_font_size'] ) ) {
                $css .= "font-size: " . intval($options['passpro_headline_font_size']) . "px !important; ";
            }
            if ( ! empty( $options['passpro_headline_font_color'] ) ) {
                $css .= "color: " . esc_attr($options['passpro_headline_font_color']) . " !important; ";
            }
            if ( ! empty( $options['passpro_headline_font_family'] ) ) {
                $css .= "font-family: " . esc_attr($options['passpro_headline_font_family']) . ", sans-serif !important; ";
            }
            $css .= "}\n";
        }

        // --- Message --- (.message)
        if ( ! empty( $options['passpro_message_font_size'] ) || ! empty( $options['passpro_message_font_color'] ) || ! empty( $options['passpro_message_font_family'] ) ) {
            $css .= "body.login-passpro #login .message, #login .message { ";
            $css .= "margin-top: 20px !important; "; // Add spacing above the message when it's below the form
            if ( ! empty( $options['passpro_message_font_size'] ) ) {
                $css .= "font-size: " . intval($options['passpro_message_font_size']) . "px !important; ";
            }
            if ( ! empty( $options['passpro_message_font_color'] ) ) {
                $css .= "color: " . esc_attr($options['passpro_message_font_color']) . " !important; ";
            }
            if ( ! empty( $options['passpro_message_font_family'] ) ) {
                $css .= "font-family: " . esc_attr($options['passpro_message_font_family']) . ", sans-serif !important; ";
            }
            $css .= "}\n";
        } else {
            // Add basic styling for the message even if no specific options are set
            $css .= "body.login-passpro #login .message, #login .message { ";
            $css .= "margin-top: 20px !important; "; // Add spacing above the message 
            $css .= "}\n";
        }

        // --- Login Box --- (#loginform)
        if ( ! empty( $options['passpro_box_bg_color'] ) || ! empty( $options['passpro_box_border_color'] ) || 
            isset( $options['passpro_box_border_width'] ) || isset( $options['passpro_box_border_radius'] ) ) {
            
            $css .= "body.login-passpro #loginform, #loginform { ";
            if ( ! empty( $options['passpro_box_bg_color'] ) ) {
                $css .= "background-color: " . esc_attr($options['passpro_box_bg_color']) . " !important; ";
            }
            if ( ! empty( $options['passpro_box_border_color'] ) ) {
                $css .= "border-color: " . esc_attr($options['passpro_box_border_color']) . " !important; ";
            }
            if ( isset( $options['passpro_box_border_width'] ) && $options['passpro_box_border_width'] !== '' ) {
                $css .= "border-width: " . intval($options['passpro_box_border_width']) . "px !important; border-style: solid !important; ";
            }
            if ( isset( $options['passpro_box_border_radius'] ) && $options['passpro_box_border_radius'] !== '' ) {
                $css .= "border-radius: " . intval($options['passpro_box_border_radius']) . "px !important; ";
            }
            $css .= "}\n";
        }

        // --- Labels --- (#loginform label)
        if ( ! empty( $options['passpro_label_font_size'] ) || ! empty( $options['passpro_label_font_color'] ) || ! empty( $options['passpro_label_font_family'] ) ) {
            $css .= "body.login-passpro #loginform label, #loginform label { ";
            if ( ! empty( $options['passpro_label_font_size'] ) ) {
                $css .= "font-size: " . intval($options['passpro_label_font_size']) . "px !important; ";
            }
            if ( ! empty( $options['passpro_label_font_color'] ) ) {
                $css .= "color: " . esc_attr($options['passpro_label_font_color']) . " !important; ";
            }
            if ( ! empty( $options['passpro_label_font_family'] ) ) {
                $css .= "font-family: " . esc_attr($options['passpro_label_font_family']) . ", sans-serif !important; ";
            }
            $css .= "}\n";
        }
        
        // --- Input Field --- (#loginform input[type=password])
        if ( ! empty( $options['passpro_input_bg_color'] ) || ! empty( $options['passpro_input_text_color'] ) || 
            ! empty( $options['passpro_input_border_color'] ) || isset( $options['passpro_input_border_width'] ) || 
            isset( $options['passpro_input_border_radius'] ) || ! empty( $options['passpro_input_font_size'] ) ) {
            
            $css .= "body.login-passpro #loginform input[type=password], #loginform input[type=password] { ";
            if ( ! empty( $options['passpro_input_bg_color'] ) ) {
                $css .= "background-color: " . esc_attr($options['passpro_input_bg_color']) . " !important; ";
            }
            if ( ! empty( $options['passpro_input_text_color'] ) ) {
                $css .= "color: " . esc_attr($options['passpro_input_text_color']) . " !important; ";
            }
            if ( ! empty( $options['passpro_input_border_color'] ) ) {
                $css .= "border-color: " . esc_attr($options['passpro_input_border_color']) . " !important; ";
            }
            if ( isset( $options['passpro_input_border_width'] ) && $options['passpro_input_border_width'] !== '' ) {
                $css .= "border-width: " . intval($options['passpro_input_border_width']) . "px !important; border-style: solid !important; ";
            }
            if ( isset( $options['passpro_input_border_radius'] ) && $options['passpro_input_border_radius'] !== '' ) {
                $css .= "border-radius: " . intval($options['passpro_input_border_radius']) . "px !important; ";
            }
            if ( ! empty( $options['passpro_input_font_size'] ) ) {
                $css .= "font-size: " . intval($options['passpro_input_font_size']) . "px !important; ";
            }
            if ( ! empty( $options['passpro_input_padding'] ) ) {
                $css .= "padding: " . intval($options['passpro_input_padding']) . "px !important; ";
            }
            $css .= "width: 100% !important; "; // Always ensure inputs are full width
            $css .= "box-sizing: border-box !important; "; // Ensure padding is included in width
            $css .= "}\n";
            
            // Add focus state
            $css .= "body.login-passpro #loginform input[type=password]:focus, #loginform input[type=password]:focus { ";
            if ( ! empty( $options['passpro_input_focus_border_color'] ) ) {
                $css .= "border-color: " . esc_attr($options['passpro_input_focus_border_color']) . " !important; ";
                $css .= "box-shadow: 0 0 0 1px " . esc_attr($options['passpro_input_focus_border_color']) . " !important; ";
            } else if ( ! empty( $options['passpro_input_border_color'] ) ) {
                // Default focus state if no specific focus color is set
                $css .= "border-color: " . esc_attr($options['passpro_input_border_color']) . " !important; ";
                $css .= "box-shadow: 0 0 0 1px " . esc_attr($options['passpro_input_border_color']) . " !important; ";
            }
            $css .= "outline: none !important; ";
            $css .= "}\n";
        }

        // --- Button --- (#wp-submit)
        if ( ! empty( $options['passpro_button_bg_color'] ) || ! empty( $options['passpro_button_text_color'] ) || 
            ! empty( $options['passpro_button_border_color'] ) || isset( $options['passpro_button_border_width'] ) || 
            isset( $options['passpro_button_border_radius'] ) || ! empty( $options['passpro_button_font_size'] ) ||
            ! empty( $options['passpro_button_width'] ) || ! empty( $options['passpro_button_height'] ) || 
            ! empty( $options['passpro_button_padding_top'] ) || ! empty( $options['passpro_button_padding_right'] ) ||
            ! empty( $options['passpro_button_padding_bottom'] ) || ! empty( $options['passpro_button_padding_left'] ) ||
            ! empty( $options['passpro_button_font_weight'] ) || ! empty( $options['passpro_button_text_transform'] ) ||
            ! empty( $options['passpro_button_box_shadow'] ) || ! empty( $options['passpro_button_transition'] ) ||
            ! empty( $options['passpro_button_alignment'] ) ) {
            
            $css .= "body.login-passpro #loginform #wp-submit, #loginform #wp-submit { ";
            if ( ! empty( $options['passpro_button_bg_color'] ) ) {
                $css .= "background-color: " . esc_attr($options['passpro_button_bg_color']) . " !important; ";
                $css .= "background-image: none !important; "; // Override any gradient images
            }
            if ( ! empty( $options['passpro_button_text_color'] ) ) {
                $css .= "color: " . esc_attr($options['passpro_button_text_color']) . " !important; ";
            }
            if ( ! empty( $options['passpro_button_border_color'] ) ) {
                $css .= "border-color: " . esc_attr($options['passpro_button_border_color']) . " !important; ";
            }
            if ( isset( $options['passpro_button_border_width'] ) && $options['passpro_button_border_width'] !== '' ) {
                $css .= "border-width: " . intval($options['passpro_button_border_width']) . "px !important; border-style: solid !important; ";
            }
            if ( isset( $options['passpro_button_border_radius'] ) && $options['passpro_button_border_radius'] !== '' ) {
                $css .= "border-radius: " . intval($options['passpro_button_border_radius']) . "px !important; ";
            }
            if ( ! empty( $options['passpro_button_font_size'] ) ) {
                $css .= "font-size: " . intval($options['passpro_button_font_size']) . "px !important; ";
            }
            
            // Text styling options
            if ( ! empty( $options['passpro_button_font_weight'] ) ) {
                $css .= "font-weight: " . esc_attr($options['passpro_button_font_weight']) . " !important; ";
            }
            if ( ! empty( $options['passpro_button_text_transform'] ) ) {
                $css .= "text-transform: " . esc_attr($options['passpro_button_text_transform']) . " !important; ";
            }
            
            // Button dimension properties
            if ( ! empty( $options['passpro_button_width'] ) ) {
                $css .= "width: " . esc_attr($options['passpro_button_width']) . " !important; ";
                
                // Add specific styling for better positioning when width is set
                if ( ! empty( $options['passpro_button_alignment'] ) && $options['passpro_button_alignment'] === 'center' ) {
                    $css .= "display: block !important; ";
                    $css .= "margin-left: auto !important; ";
                    $css .= "margin-right: auto !important; ";
                }
            }
            if ( ! empty( $options['passpro_button_height'] ) ) {
                $css .= "height: " . esc_attr($options['passpro_button_height']) . " !important; ";
                // Use flexbox for proper vertical centering instead of line-height
                $css .= "display: flex !important; ";
                $css .= "align-items: center !important; ";
                $css .= "justify-content: center !important; ";
                $css .= "line-height: normal !important; "; // Override default line-height
            } else {
                // For auto height, use more standard button styling
                $css .= "line-height: 1.5 !important; ";
                $css .= "display: inline-block !important; ";
            }
            
            // Box shadow effect
            if ( ! empty( $options['passpro_button_box_shadow'] ) ) {
                switch( $options['passpro_button_box_shadow'] ) {
                    case 'light':
                        $css .= "box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important; ";
                        break;
                    case 'medium':
                        $css .= "box-shadow: 0 4px 8px rgba(0,0,0,0.15) !important; ";
                        break;
                    case 'strong':
                        $css .= "box-shadow: 0 6px 12px rgba(0,0,0,0.2) !important; ";
                        break;
                    case 'none':
                    default:
                        $css .= "box-shadow: none !important; ";
                        break;
                }
            }
            
            // Transition effect for hover
            if ( ! empty( $options['passpro_button_transition'] ) ) {
                switch( $options['passpro_button_transition'] ) {
                    case 'fast':
                        $css .= "transition: all 0.1s ease !important; ";
                        break;
                    case 'normal':
                        $css .= "transition: all 0.2s ease !important; ";
                        break;
                    case 'slow':
                        $css .= "transition: all 0.4s ease !important; ";
                        break;
                    case 'none':
                    default:
                        $css .= "transition: none !important; ";
                        break;
                }
            }
            
            // Use individual padding values instead of a single value
            $has_padding = false;
            $padding_css = "";
            
            if ( ! empty( $options['passpro_button_padding_top'] ) ) {
                $padding_css .= "padding-top: " . intval($options['passpro_button_padding_top']) . "px !important; ";
                $has_padding = true;
            }
            if ( ! empty( $options['passpro_button_padding_right'] ) ) {
                $padding_css .= "padding-right: " . intval($options['passpro_button_padding_right']) . "px !important; ";
                $has_padding = true;
            }
            if ( ! empty( $options['passpro_button_padding_bottom'] ) ) {
                $padding_css .= "padding-bottom: " . intval($options['passpro_button_padding_bottom']) . "px !important; ";
                $has_padding = true;
            }
            if ( ! empty( $options['passpro_button_padding_left'] ) ) {
                $padding_css .= "padding-left: " . intval($options['passpro_button_padding_left']) . "px !important; ";
                $has_padding = true;
            }
            
            if ( $has_padding ) {
                $css .= $padding_css;
                $css .= "box-sizing: border-box !important; "; // Ensure padding is included in width/height
            }
            $css .= "}\n";
            
            // Add hover state with specific hover colors if provided
            $css .= "body.login-passpro #loginform #wp-submit:hover, #loginform #wp-submit:hover { ";
            
            if ( ! empty( $options['passpro_button_hover_bg_color'] ) ) {
                $css .= "background-color: " . esc_attr($options['passpro_button_hover_bg_color']) . " !important; ";
            } else if ( ! empty( $options['passpro_button_bg_color'] ) ) {
                // Fall back to darkening the regular background color
                $css .= "background-color: " . esc_attr($options['passpro_button_bg_color']) . " !important; ";
                $css .= "filter: brightness(90%) !important; ";
            }
            
            if ( ! empty( $options['passpro_button_hover_text_color'] ) ) {
                $css .= "color: " . esc_attr($options['passpro_button_hover_text_color']) . " !important; ";
            }
            
            $css .= "}\n";
            
            // Also add the same styles for focus state (for keyboard accessibility)
            $css .= "body.login-passpro #loginform #wp-submit:focus, #loginform #wp-submit:focus { ";
            
            if ( ! empty( $options['passpro_button_hover_bg_color'] ) ) {
                $css .= "background-color: " . esc_attr($options['passpro_button_hover_bg_color']) . " !important; ";
            } else if ( ! empty( $options['passpro_button_bg_color'] ) ) {
                // Fall back to darkening the regular background color
                $css .= "background-color: " . esc_attr($options['passpro_button_bg_color']) . " !important; ";
                $css .= "filter: brightness(90%) !important; ";
            }
            
            if ( ! empty( $options['passpro_button_hover_text_color'] ) ) {
                $css .= "color: " . esc_attr($options['passpro_button_hover_text_color']) . " !important; ";
            }
            
            // Add focus outline for better keyboard accessibility
            $css .= "outline: 2px solid " . (! empty( $options['passpro_button_hover_bg_color'] ) ? esc_attr($options['passpro_button_hover_bg_color']) : (! empty( $options['passpro_button_bg_color'] ) ? esc_attr($options['passpro_button_bg_color']) : '#2271b1')) . " !important; ";
            $css .= "outline-offset: 2px !important; ";
            
            $css .= "}\n";

            // Add alignment for button's container (p.submit)
            if ( ! empty( $options['passpro_button_alignment'] ) ) {
                // Style the container for alignment
                $css .= "body.login-passpro #loginform p.submit, #loginform p.submit, .login-passpro .submit { ";
                $css .= "text-align: " . esc_attr($options['passpro_button_alignment']) . " !important; ";
                $css .= "display: block !important; ";
                $css .= "width: 100% !important; ";
                $css .= "}\n";
                
                // Special handling for center alignment
                if ( $options['passpro_button_alignment'] === 'center' ) {
                    // If no specific width is set, we use auto margins
                    if ( empty( $options['passpro_button_width'] ) ) {
                        $css .= "body.login-passpro #loginform #wp-submit, #loginform #wp-submit, .login-passpro #wp-submit { ";
                        $css .= "display: inline-block !important; ";
                        $css .= "float: none !important; ";
                        $css .= "margin-left: auto !important; ";
                        $css .= "margin-right: auto !important; ";
                        // Add more reliable centering
                        $css .= "position: relative !important; ";
                        $css .= "left: 50% !important; ";
                        $css .= "transform: translateX(-50%) !important; ";
                        $css .= "}\n";
                    } else {
                        // If width is set, we directly center the button
                        $css .= "body.login-passpro #loginform #wp-submit, #loginform #wp-submit, .login-passpro #wp-submit { ";
                        $css .= "display: block !important; ";
                        $css .= "float: none !important; ";
                        $css .= "margin-left: auto !important; ";
                        $css .= "margin-right: auto !important; ";
                        $css .= "}\n";
                    }
                }
                
                // Special handling for right alignment to override default float
                if ( $options['passpro_button_alignment'] === 'right' ) {
                    $css .= "body.login-passpro #loginform #wp-submit, #loginform #wp-submit, .login-passpro #wp-submit { ";
                    $css .= "float: right !important; ";
                    $css .= "}\n";
                }
                
                // Special handling for left alignment to override any other styles
                if ( $options['passpro_button_alignment'] === 'left' ) {
                    $css .= "body.login-passpro #loginform #wp-submit, #loginform #wp-submit, .login-passpro #wp-submit { ";
                    $css .= "float: left !important; ";
                    $css .= "}\n";
                }
            }
        }

        // Add global CSS for form spacing and balance - independent of user settings
        $css .= "body.login-passpro #loginform, #loginform { padding: 26px 24px 26px !important; box-sizing: border-box !important; }\n";
        
        // Improved button container styling with better spacing
        $css .= "body.login-passpro #loginform p.submit, #loginform p.submit { 
            margin-bottom: 0 !important; 
            padding-bottom: 0 !important; 
            margin-top: 24px !important; 
            clear: both !important;
            overflow: hidden !important;
            box-sizing: border-box !important;
            width: 100% !important;
        }\n";
        
        // Improved button styling for more consistent appearance
        $css .= "body.login-passpro #loginform #wp-submit, #loginform #wp-submit { 
            min-height: 36px !important;
            line-height: 2.15384615 !important;
            display: inline-block !important;
            text-decoration: none !important;
            cursor: pointer !important;
            border-style: solid !important;
            -webkit-appearance: none !important;
            box-sizing: border-box !important;
            text-align: center !important;
            vertical-align: middle !important;
            justify-content: center !important;
            align-items: center !important;
        }\n";
        
        // Apply default padding only if custom padding is not set
        if (empty($options['passpro_button_padding_top']) && 
            empty($options['passpro_button_padding_right']) && 
            empty($options['passpro_button_padding_bottom']) && 
            empty($options['passpro_button_padding_left'])) {
            $css .= "body.login-passpro #loginform #wp-submit, #loginform #wp-submit { 
                padding: 4px 16px !important;
            }\n";
        }
        
        $css .= "body.login-passpro #loginform .input, #loginform .input { margin-top: 2px !important; margin-bottom: 12px !important; }\n";
        $css .= "body.login-passpro #loginform label, #loginform label { margin-bottom: 0 !important; display: block !important; padding-bottom: 3px !important; }\n";
        $css .= "body.login-passpro #loginform p, #loginform p { margin-top: 0 !important; margin-bottom: 16px !important; }\n";
        $css .= "body.login-passpro #loginform p:last-of-type, #loginform p:last-of-type { margin-bottom: 0 !important; }\n";
        
        // Add responsive container and vertical centering
        $css .= "html body.login-passpro, html body.login { display: flex !important; flex-direction: column !important; justify-content: center !important; min-height: 100vh !important; overflow-x: hidden !important; padding: 20px 0 !important; box-sizing: border-box !important; }\n";

        // Make login container compact again but responsive
        $css .= "html body.login-passpro #login, html body.login #login { width: 320px !important; max-width: 90% !important; margin: 0 auto !important; padding: 0 !important; box-sizing: border-box !important; }\n";

        // Special container for logos - maintain overflow visibility but with constraints
        $css .= "html body.login-passpro #login .passpro-logo, html body.login #login .passpro-logo, .passpro-logo { overflow-x: visible !important; max-width: 100% !important; box-sizing: border-box !important; }\n";

        // Add special handling for wide logos with fixed height
        $css .= "@media screen and (max-width: 700px) { ";
        $css .= "html body.login-passpro #login .passpro-logo img, html body.login #login .passpro-logo img, .passpro-logo img { ";
        $css .= "max-width: 100% !important; width: auto !important; height: auto !important; ";
        $css .= "} }\n";

        // Add specific fix for wide logos with fixed heights - this addresses the 700x100 case
        if ( ! empty( $options['passpro_logo_max_height'] ) && (empty($options['passpro_logo_max_width']) || intval($options['passpro_logo_max_width']) > 500) ) {
            $css .= "html body.login-passpro #login .passpro-logo, html body.login #login .passpro-logo, .passpro-logo { ";
            $css .= "max-width: 320px !important; margin: 0 auto !important; ";
            $css .= "}\n";
            
            // Ensure image respects container boundaries
            $css .= "html body.login-passpro #login .passpro-logo img, html body.login #login .passpro-logo img, .passpro-logo img { ";
            $css .= "max-width: 100% !important; height: auto !important; ";
            $css .= "}\n";
        }

        // Specific fix for wide logos with height set to 100px (targeting 700x100 ratio)
        if (!empty($options['passpro_logo_max_height']) && $options['passpro_logo_max_height'] == 100) {
            // For all alignments
            $css .= "html body.login-passpro #login, html body.login #login { ";
            $css .= "min-width: 320px !important; width: 360px !important; max-width: 90% !important; margin: 0 auto !important; ";
            $css .= "}\n";
            
            // Ensure image remains properly sized but within container bounds
            $css .= "html body.login-passpro #login .passpro-logo img, html body.login #login .passpro-logo img, .passpro-logo img { ";
            $css .= "max-height: 100px !important; max-width: 100% !important; width: auto !important; ";
            $css .= "}\n";
            
            // Handle different alignments for this specific case
            if (!empty($options['passpro_logo_alignment'])) {
                $css .= "html body.login-passpro #login .passpro-logo, html body.login #login .passpro-logo, .passpro-logo { ";
                $css .= "max-width: 100% !important; ";
                if ($options['passpro_logo_alignment'] == 'center') {
                    $css .= "text-align: center !important; ";
                } elseif ($options['passpro_logo_alignment'] == 'left') {
                    $css .= "text-align: left !important; ";
                } elseif ($options['passpro_logo_alignment'] == 'right') {
                    $css .= "text-align: right !important; ";
                }
                $css .= "}\n";
            }
        }
        
        // Output the generated CSS with debugging info
        if ( ! empty( $css ) ) {
            echo "<!-- PassPro Debug: Options from " . esc_html($this->option_name) . " -->\n";
            
            // Add logo alignment debug info
            if ( ! empty( $options['passpro_logo_alignment'] ) ) {
                echo "<!-- PassPro Logo Alignment: " . esc_html($options['passpro_logo_alignment']) . " -->\n";
            } else {
                echo "<!-- PassPro Logo Alignment: Not set (defaulting to center) -->\n";
            }
            
            // Start the style tag
            echo "<style type=\"text/css\" id=\"passpro-custom-styles\">\n" . $css . "</style>\n";
            
            // Add Button Hover JS if hover colors are set
            if (!empty($options['passpro_button_hover_bg_color']) || !empty($options['passpro_button_hover_text_color'])) {
                $hover_bg = !empty($options['passpro_button_hover_bg_color']) ? esc_js($options['passpro_button_hover_bg_color']) : '';
                $hover_text = !empty($options['passpro_button_hover_text_color']) ? esc_js($options['passpro_button_hover_text_color']) : '';
                $normal_bg = !empty($options['passpro_button_bg_color']) ? esc_js($options['passpro_button_bg_color']) : '#2271b1';
                $normal_text = !empty($options['passpro_button_text_color']) ? esc_js($options['passpro_button_text_color']) : '#ffffff';
                
                echo "<script type=\"text/javascript\">
                document.addEventListener('DOMContentLoaded', function() {
                    var submitButton = document.getElementById('wp-submit');
                    if (submitButton) {
                        // Store original colors
                        var originalBg = '{$normal_bg}';
                        var originalText = '{$normal_text}';
                        var hoverBg = " . ($hover_bg ? "'{$hover_bg}'" : 'originalBg') . ";
                        var hoverText = " . ($hover_text ? "'{$hover_text}'" : 'originalText') . ";
                        
                        // Add hover effect
                        submitButton.addEventListener('mouseenter', function() {
                            this.style.backgroundColor = hoverBg;
                            this.style.color = hoverText;
                        });
                        
                        submitButton.addEventListener('mouseleave', function() {
                            this.style.backgroundColor = originalBg;
                            this.style.color = originalText;
                        });
                    }
                });
                </script>\n";
            }
            
            // Add JavaScript for logo alignment if needed
            if ( ! empty( $options['passpro_logo_alignment'] ) ) {
                $alignment = esc_js($options['passpro_logo_alignment']);
                $max_width = ! empty( $options['passpro_logo_max_width'] ) ? intval($options['passpro_logo_max_width']) . 'px' : '100%';
                $max_height = ! empty( $options['passpro_logo_max_height'] ) ? intval($options['passpro_logo_max_height']) . 'px' : 'auto';
                
                echo "<script type=\"text/javascript\">
                console.log('PassPro logo alignment script loading. Alignment: {$alignment}');
                document.addEventListener('DOMContentLoaded', function() {
                    // Apply logo alignment via JavaScript as a fallback to ensure it works
                    var logoContainer = document.querySelector('.passpro-logo');
                    var logoImg = logoContainer ? logoContainer.querySelector('img') : null;
                    
                    console.log('DOM loaded, searching for logo elements...');
                    console.log('Logo container found:', logoContainer !== null);
                    console.log('Logo image found:', logoImg !== null);
                    
                    if (logoContainer) {
                        // Apply container styles
                        logoContainer.style.textAlign = '{$alignment}';
                        logoContainer.style.display = 'block';
                        logoContainer.style.width = '100%';
                        logoContainer.setAttribute('data-alignment', '{$alignment}');
                        console.log('Applied logo container alignment:', '{$alignment}');
                    }
                    
                    if (logoImg) {
                        // Apply image styles
                        logoImg.style.display = 'inline-block';
                        logoImg.style.maxWidth = '{$max_width}';
                        logoImg.style.maxHeight = '{$max_height}';
                        logoImg.style.height = 'auto';
                        logoImg.style.width = 'auto';
                        
                        // Reset all margins first
                        logoImg.style.marginLeft = '';
                        logoImg.style.marginRight = '';
                        logoImg.style.marginTop = '0';
                        logoImg.style.marginBottom = '20px';
                        
                        // Apply specific margin based on alignment
                        switch('{$alignment}') {
                            case 'left':
                                logoImg.style.marginLeft = '0';
                                logoImg.style.marginRight = 'auto';
                                break;
                            case 'right':
                                logoImg.style.marginLeft = 'auto';
                                logoImg.style.marginRight = '0';
                                break;
                            case 'center':
                            default:
                                logoImg.style.marginLeft = 'auto';
                                logoImg.style.marginRight = 'auto';
                                break;
                        }
                        logoImg.setAttribute('data-alignment', '{$alignment}');
                        console.log('Applied logo image alignment:', '{$alignment}');
                    } else if (logoContainer) {
                        console.log('Logo container found but no image');
                    } else {
                        console.log('Logo container not found');
                        
                        // Attempt to find any images on the page that might be the logo
                        var images = document.querySelectorAll('img');
                        console.log('Total images found on page:', images.length);
                        
                        // If there's only one image, assume it's the logo
                        if (images.length === 1) {
                            console.log('Found a single image, attempting to apply logo alignment');
                            var img = images[0];
                            img.style.display = 'inline-block';
                            img.style.maxWidth = '{$max_width}';
                            img.style.maxHeight = '{$max_height}';
                            img.style.height = 'auto';
                            img.style.width = 'auto';
                            
                            img.style.marginLeft = '{$alignment}' === 'left' ? '0' : '{$alignment}' === 'right' ? 'auto' : 'auto';
                            img.style.marginRight = '{$alignment}' === 'right' ? '0' : '{$alignment}' === 'left' ? 'auto' : 'auto';
                            img.style.marginTop = '0';
                            img.style.marginBottom = '20px';
                            
                            // Find the parent and set its alignment
                            if (img.parentElement) {
                                img.parentElement.style.textAlign = '{$alignment}';
                                img.parentElement.style.display = 'block';
                                img.parentElement.style.width = '100%';
                                img.parentElement.setAttribute('data-alignment', '{$alignment}');
                            }
                            
                            img.setAttribute('data-alignment', '{$alignment}');
                        }
                    }
                });
                </script>\n";
            }
            
            // Add button text JavaScript if needed
            if ( ! empty( $options['passpro_button_text_label'] ) ) {
                echo "<script type=\"text/javascript\">
                document.addEventListener('DOMContentLoaded', function() {
                    var submitButton = document.getElementById('wp-submit');
                    if (submitButton) {
                        submitButton.value = \"" . esc_js($options['passpro_button_text_label']) . "\";
                        console.log('Button text updated to: " . esc_js($options['passpro_button_text_label']) . "');
                    } else {
                        console.log('Button element not found');
                    }
                });
                </script>\n";
            }
            
            // Debug information
            if ( isset( $_GET['passpro_debug'] ) ) {
                echo "<!-- PassPro Debug Options: \n";
                echo "Option name: " . esc_html($this->option_name) . "\n";
                echo "Options: " . esc_html(print_r($options, true)) . "\n";
                echo "-->\n";
            }
        }
    }

    /**
     * Add a floating logout button to the frontend when user is logged in.
     * 
     * @since    1.0.0
     */
    public function add_logout_button() {
        // Only show logout button if user is already authenticated
        if (isset($_COOKIE[$this->cookie_name]) && $_COOKIE[$this->cookie_name] === $this->generate_cookie_hash()) {
            ?>
            <div class="passpro-logout-container">
                <a href="<?php echo esc_url(admin_url('admin-post.php?action=passpro_logout')); ?>" class="passpro-logout-button">
                    <span class="dashicons dashicons-lock"></span> Logout
                </a>
            </div>
            <style>
            .passpro-logout-container {
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 9999;
            }
            .passpro-logout-button {
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #2271b1;
                color: #fff;
                border-radius: 50px;
                padding: 8px 16px;
                text-decoration: none;
                font-size: 14px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
                transition: all 0.3s ease;
            }
            .passpro-logout-button:hover {
                background-color: #135e96;
                color: #fff;
                box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            }
            .passpro-logout-button .dashicons {
                margin-right: 5px;
                font-size: 16px;
                width: 16px;
                height: 16px;
            }
            </style>
            <?php
            // Enqueue dashicons if not already enqueued
            wp_enqueue_style('dashicons');
        }
    }
    
    /**
     * Process logout action and clear the cookie.
     * 
     * @since    1.0.0
     */
    public function process_logout() {
        // Clear the cookie by setting it to expire in the past
        if (isset($_COOKIE[$this->cookie_name])) {
            setcookie($this->cookie_name, '', time() - 3600, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), true);
            // Also unset it from the current request
            unset($_COOKIE[$this->cookie_name]);
        }
        
        // Redirect to home page
        wp_redirect(home_url('/'));
        exit;
    }

    private function define_public_hooks() {

		$plugin_public = new PassPro_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'template_redirect', $plugin_public, 'check_password_protection', 1 ); // Priority 1 to run early
        $this->loader->add_action( 'login_form_passpro', $plugin_public, 'render_login_form' ); // Custom action for login form
        $this->loader->add_action( 'wp_head', $plugin_public, 'output_custom_login_styles' ); // Action to output custom CSS
        $this->loader->add_action( 'login_head', $plugin_public, 'output_custom_login_styles' ); // Also hook to login_head for login pages
        $this->loader->add_action( 'wp_footer', $plugin_public, 'add_logout_button' ); // Add action for logout button
        $this->loader->add_action( 'admin_post_passpro_logout', $plugin_public, 'process_logout' ); // Handle logout action
        $this->loader->add_action( 'admin_post_nopriv_passpro_logout', $plugin_public, 'process_logout' ); // Handle logout for non-admin users

	}

} 