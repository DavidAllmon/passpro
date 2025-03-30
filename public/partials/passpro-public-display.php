<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    PassPro
 * @subpackage PassPro/public/partials
 */

// Get plugin options for customization
$passpro_options = get_option( 'passpro_options' ); // Make sure this matches the registered option name

// Set page title - Use custom title if set, otherwise default
$page_title = ! empty( $passpro_options['passpro_page_title'] ) ? $passpro_options['passpro_page_title'] : esc_html__( 'Password Protected', 'passpro' );

// Set headline - Use custom headline if set, otherwise site title
$headline = ! empty( $passpro_options['passpro_headline'] ) ? $passpro_options['passpro_headline'] : get_bloginfo( 'name' );

// Get custom message - allow basic HTML as sanitized by wp_kses_post
$message = ! empty( $passpro_options['passpro_message'] ) ? wp_kses_post( $passpro_options['passpro_message'] ) : '';

// Get logo URL
$logo_url = ! empty( $passpro_options['passpro_logo_url'] ) ? $passpro_options['passpro_logo_url'] : '';

// Background color is now handled by output_custom_login_styles hooked to login_head

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width">
	<title><?php echo esc_html( $page_title ); ?></title>
	
	<?php
	// Instead of wp_head() which loads all scripts, just add the essentials
	$custom_css_handle = 'passpro-public';
	wp_enqueue_style($custom_css_handle, PASSPRO_PLUGIN_URL . 'public/css/passpro-public.css', array(), PASSPRO_VERSION);
	
	// Output just the dashicons if needed
	wp_enqueue_style('dashicons');
	
	// Print only the styles we've enqueued
	wp_print_styles(array($custom_css_handle, 'dashicons'));
	
	// Allow custom login styles to be output via the custom output_custom_login_styles function
	do_action('login_head');
	?>
	
	<!-- Prevent loading of all third-party scripts and resources -->
	<script type="text/javascript">
	// Block all script loading via fetch, XMLHttpRequest, and script tags
	(function() {
		// Save original fetch
		const originalFetch = window.fetch;
		// Override fetch
		window.fetch = function(resource, init) {
			const url = resource instanceof Request ? resource.url : resource;
			// Allow only our own resources
			if (url.indexOf('<?php echo esc_js(site_url()); ?>') === 0 && 
				url.indexOf('chat') === -1 && 
				url.indexOf('messaging') === -1) {
				return originalFetch.apply(this, arguments);
			}
			console.log('PassPro: Blocked fetch request to', url);
			return Promise.resolve(new Response('', {status: 200}));
		};
		
		// Save original XMLHttpRequest
		const originalXHR = window.XMLHttpRequest;
		// Override XMLHttpRequest
		window.XMLHttpRequest = function() {
			const xhr = new originalXHR();
			const originalOpen = xhr.open;
			
			xhr.open = function(method, url, ...args) {
				// Allow only our own resources
				if (url.indexOf('<?php echo esc_js(site_url()); ?>') === 0 && 
					url.indexOf('chat') === -1 && 
					url.indexOf('messaging') === -1) {
					return originalOpen.call(this, method, url, ...args);
				}
				console.log('PassPro: Blocked XHR request to', url);
				// Call but modify to a non-existent URL to prevent actual network request
				return originalOpen.call(this, method, 'about:blank', ...args);
			};
			
			return xhr;
		};
		
		// Block script elements from being added to the page
		const originalCreateElement = document.createElement;
		document.createElement = function(tagName, ...args) {
			const element = originalCreateElement.call(document, tagName, ...args);
			
			if (tagName.toLowerCase() === 'script') {
				// Override the src setter to block external scripts
				let originalSrc = '';
				Object.defineProperty(element, 'src', {
					get: function() { return originalSrc; },
					set: function(value) {
						if (value && value.indexOf('<?php echo esc_js(site_url()); ?>') === 0 && 
							value.indexOf('chat') === -1 && 
							value.indexOf('messaging') === -1) {
							originalSrc = value;
						} else {
							console.log('PassPro: Blocked script src', value);
							originalSrc = 'about:blank';
						}
					},
					enumerable: true,
					configurable: true
				});
			}
			
			return element;
		};
	})();
	
	// Add aggressive widget blocker that detects and removes chat widgets
	(function() {
		// Create list of selectors that commonly identify chat widgets
		const chatSelectors = [
			// Class based selectors
			'.crisp-client',
			'#intercom-container',
			'.olark-chat-wrapper',
			'.fb_dialog',
			'.fb-customerchat',
			'.drift-frame-controller',
			'.drift-conductor-item',
			'[class*="livechat"]',
			'[class*="chat-widget"]',
			'[class*="chat-bubble"]',
			'[class*="chat-icon"]',
			'[class*="chat-button"]',
			'[class*="tawkto"]',
			'[id*="chat-widget"]',
			'[id*="chat-bubble"]',
			'[id*="livechat"]',
			'[id*="tawkto"]',
			'[id*="intercom"]',
			'[id*="crisp"]',
			'[id*="drift"]',
			'.tidio-chat-wrapper',
			'#tidio-chat',
			'[id*="zopim"]',
			'.zopim',
			'.wc-bubble',
			'.wc-chat',
			'div[class*="helpdesk"]',
			'div[class*="support-chat"]',
			'div[role="dialog"][aria-label*="chat"]',
			'div[class*="floating"]',
			'.fixed-chat-button',
			// Add common IDs for various chat plugins
			'#chat-application',
			'#chat-wrapper',
			'#chat-container',
			'#hubspot-messages-iframe-container',
			'#freshworks-container',
			'#Smallchat',
			'#chat-widget-container',
			// iFrames are often used for chat widgets
			'iframe[src*="chat"]',
			'iframe[src*="messaging"]',
			'iframe[src*="support"]',
			'iframe[src*="intercom"]',
			'iframe[src*="crisp"]',
			'iframe[src*="tawk"]',
			'iframe[src*="zendesk"]',
			'iframe[src*="zopim"]',
			'iframe[src*="livechat"]'
		];

		// Function to remove chat widgets
		function removeChatWidgets() {
			chatSelectors.forEach(selector => {
				const elements = document.querySelectorAll(selector);
				elements.forEach(el => {
					console.log('PassPro: Removing chat widget:', el);
					el.style.display = 'none';
					el.style.visibility = 'hidden';
					el.style.opacity = '0';
					el.style.pointerEvents = 'none';
					// Optionally remove from DOM completely
					if (el.parentNode) {
						el.parentNode.removeChild(el);
					}
				});
			});
		}

		// Run immediately when DOM is ready
		document.addEventListener('DOMContentLoaded', function() {
			console.log('PassPro: Initial chat widget removal');
			removeChatWidgets();
			
			// Also run on window load to catch late-loading widgets
			window.addEventListener('load', function() {
				console.log('PassPro: Window loaded, removing chat widgets');
				removeChatWidgets();
				
				// Set interval to keep checking for chat widgets
				setInterval(removeChatWidgets, 1000);
			});
		});

		// Create a MutationObserver to watch for changes to the DOM
		const observer = new MutationObserver(function(mutations) {
			mutations.forEach(function(mutation) {
				if (mutation.addedNodes && mutation.addedNodes.length > 0) {
					console.log('PassPro: DOM changed, checking for chat widgets');
					removeChatWidgets();
				}
			});
		});

		// Start observing the document with the configured parameters
		document.addEventListener('DOMContentLoaded', function() {
			observer.observe(document.body, { childList: true, subtree: true });
		});

		// Check at key user interaction points
		document.addEventListener('click', function() {
			setTimeout(removeChatWidgets, 500);
		});

		// Block common chat widget initialization functions
		function blockChatScripts() {
			// List of common chat widget global objects/functions
			const chatGlobals = [
				'Intercom', 'tawk', 'tawkTo', 'Tawk_API', 'zE', 'zEmbed', 
				'$zopim', 'Zendesk', 'LiveChatWidget', 'Crisp', 'CRISP_WEBSITE_ID',
				'tiledeskSettings', 'TILEDESK_WIDGET_URL', 'HubSpotConversations',
				'drift', 'driftt', 'DRIFT_CHAT_WIDGET', 'Beacon', 'ZohoSalesIQ',
				'FreshworksWidget', 'fcWidget', 'SmallChat'
			];

			chatGlobals.forEach(global => {
				try {
					// Try to override the global object/function if it exists
					if (window[global]) {
						console.log('PassPro: Blocking chat widget script:', global);
						window[global] = undefined;
						Object.defineProperty(window, global, {
							get: function() { return undefined; },
							set: function() {},
							configurable: false
						});
					}
				} catch (e) {
					console.log('PassPro: Error blocking chat script:', e);
				}
			});
		}

		// Run the blocking function
		blockChatScripts();
		document.addEventListener('DOMContentLoaded', blockChatScripts);
		window.addEventListener('load', blockChatScripts);
	})();
	</script>
</head>
<body class="login login-passpro wp-core-ui">
	<div id="login">
        <?php if ( ! empty( $logo_url ) ) : 
            // Get logo alignment from options
            $logo_alignment = ! empty( $passpro_options['passpro_logo_alignment'] ) ? $passpro_options['passpro_logo_alignment'] : 'center';
            
            // Set inline styles for container
            $container_style = 'display: block; width: 100%; text-align: ' . esc_attr($logo_alignment) . ';'; 
            
            // Set inline styles for image
            $img_style = 'display: inline-block; max-width: 100%; height: auto; ';
            
            // Get max width and height from options
            $max_width = ! empty( $passpro_options['passpro_logo_max_width'] ) ? intval($passpro_options['passpro_logo_max_width']) . 'px' : '100%';
            $max_height = ! empty( $passpro_options['passpro_logo_max_height'] ) ? intval($passpro_options['passpro_logo_max_height']) . 'px' : 'auto';
            
            $img_style .= 'max-width: ' . $max_width . '; max-height: ' . $max_height . '; ';
            
            // Set margins based on alignment
            switch ($logo_alignment) {
                case 'left':
                    $img_style .= 'margin: 0 auto 20px 0;';
                    break;
                case 'right':
                    $img_style .= 'margin: 0 0 20px auto;';
                    break;
                case 'center':
                default:
                    $img_style .= 'margin: 0 auto 20px;';
                    break;
            }
        ?>
            <div class="passpro-logo" style="<?php echo esc_attr($container_style); ?>" data-alignment="<?php echo esc_attr($logo_alignment); ?>">
                <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $headline ); ?> Logo" style="<?php echo esc_attr($img_style); ?>" />
            </div>
        <?php endif; ?>
		<h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" tabindex="-1"><?php echo esc_html( $headline ); ?></a></h1>

        <?php
        // Message moved below the login form for better user experience

        /**
         * Fires in the login page template.
         *
         * @since 1.0.0
         */
        do_action( 'login_form_passpro' );
        
        // Display custom message if it exists (moved from above)
        if ( ! empty( $message ) ) {
            echo '<div class="message">' . $message . '</div>'; // wp_kses_post already applied
        }
        ?>
	</div>
    <?php 
    // Don't use wp_footer() as it will load unnecessary scripts
    // Instead, just output necessary login scripts if any
    do_action('login_footer');
    ?>
</body>
</html> 