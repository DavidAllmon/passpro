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
    /**
     * Fires in the login page header after scripts are enqueued.
     * Includes print_styles, print_scripts, etc.
     * Our custom styles are output via the 'login_head' action.
     */
    wp_head(); 
    ?>
	<?php /* Inline styles removed - now handled by login_head action */ ?>
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
    /**
     * Fires in the login page footer.
     */
    wp_footer(); 
    ?>
</body>
</html> 