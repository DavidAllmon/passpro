<?php
/**
 * Provide an admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    PassPro
 * @subpackage PassPro/admin/partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}
?>

<div class="wrap passpro-settings-wrap passpro-admin-modern-wrap">
    <h1 class="passpro-header"><?php echo esc_html( get_admin_page_title() ); ?></h1>
    
    <div class="passpro-dashboard">
        <?php 
        // Include the tab navigation
        require_once plugin_dir_path( __FILE__ ) . 'tabs/tab-navigation.php';
        ?>

        <!-- Tab Content Area -->
        <div class="passpro-content-container">
            <form method="post" action="options.php" class="passpro-settings-form">
                <?php settings_fields($this->option_group); // Output nonce, action, and option_page fields for the group. ?>

                <div id="passpro-settings-tabs">
                    <?php 
                    // Include tab content files
                    require_once plugin_dir_path( __FILE__ ) . 'tabs/tab-general.php';
                    require_once plugin_dir_path( __FILE__ ) . 'tabs/tab-appearance.php';
                    require_once plugin_dir_path( __FILE__ ) . 'tabs/tab-loginbox.php';
                    require_once plugin_dir_path( __FILE__ ) . 'tabs/tab-text.php';
                    require_once plugin_dir_path( __FILE__ ) . 'tabs/tab-button.php';
                    ?>
                </div>
                
                <?php submit_button( esc_html__( 'Save Settings', 'passpro' ), 'primary', 'submit', true, array( 'id' => 'passpro-save-settings' ) ); ?>
            </form>
        </div>
    </div>
</div> 