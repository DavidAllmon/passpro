<?php
/**
 * Provide a admin area view for the plugin
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

<div class="wrap passpro-admin-modern-wrap">
    <h1 class="passpro-header"><?php echo esc_html__( 'Password Protection', 'passpro' ); ?></h1>
    
    <?php
    // Show status messages
    if (isset($_GET['message'])) {
        $message = sanitize_text_field($_GET['message']);
        $message_text = '';
        
        switch ($message) {
            case 'added':
                $message_text = __('Password added successfully.', 'passpro');
                break;
            case 'added_email_sent':
                $message_text = __('Password added successfully and login details sent by email.', 'passpro');
                break;
            case 'added_email_failed':
                $message_text = __('Password added successfully but failed to send email with login details.', 'passpro');
                break;
            case 'updated':
                $message_text = __('Password updated successfully.', 'passpro');
                break;
            case 'updated_email_sent':
                $message_text = __('Password updated successfully and login details sent by email.', 'passpro');
                break;
            case 'updated_email_failed':
                $message_text = __('Password updated successfully but failed to send email with login details.', 'passpro');
                break;
            case 'deleted':
                $message_text = __('Password deleted successfully.', 'passpro');
                break;
            case 'status_updated':
                $message_text = __('Password status updated successfully.', 'passpro');
                break;
            case 'email_sent':
                $message_text = __('Login details sent by email successfully.', 'passpro');
                break;
            case 'email_failed':
                $message_text = __('Failed to send login details by email.', 'passpro');
                break;
        }
        
        if (!empty($message_text)) {
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($message_text) . '</p></div>';
        }
    }
    
    // Show error messages
    if (isset($_GET['error'])) {
        $error = sanitize_text_field($_GET['error']);
        $error_text = '';
        
        switch ($error) {
            case 'empty_password':
                $error_text = __('Password cannot be empty.', 'passpro');
                break;
            case 'add_failed':
                $error_text = __('Failed to add password.', 'passpro');
                break;
            case 'update_failed':
                $error_text = __('Failed to update password.', 'passpro');
                break;
            case 'delete_failed':
                $error_text = __('Failed to delete password.', 'passpro');
                break;
            case 'not_found':
                $error_text = __('Password not found.', 'passpro');
                break;
            case 'email_not_configured':
                $error_text = __('Cannot send email. No email address is configured for this password.', 'passpro');
                break;
            case 'password_not_available':
                $error_text = __('Cannot send email. The password is no longer available in plain text. Please edit the password to reset it and then send the email.', 'passpro');
                break;
        }
        
        if (!empty($error_text)) {
            echo '<div class="notice notice-error is-dismissible"><p>' . esc_html($error_text) . '</p></div>';
        }
    }
    
    // Get plugin options
    $options = get_option($this->option_name);
    $protection_enabled = isset($options['passpro_enabled']) && $options['passpro_enabled'] ? true : false;
    ?>
    
    <div class="passpro-dashboard">
        <!-- Status Card -->
        <div class="passpro-card passpro-status-card <?php echo $protection_enabled ? 'status-active' : 'status-inactive'; ?>">
            <div class="passpro-card-header">
                <div class="passpro-card-title">
                    <span class="dashicons <?php echo $protection_enabled ? 'dashicons-lock' : 'dashicons-unlock'; ?>"></span>
                    <h2><?php esc_html_e('Protection Status', 'passpro'); ?></h2>
                </div>
                <div class="passpro-status-badge <?php echo $protection_enabled ? 'status-badge-active' : 'status-badge-inactive'; ?>">
                    <?php echo $protection_enabled ? esc_html__('Enabled', 'passpro') : esc_html__('Disabled', 'passpro'); ?>
                </div>
            </div>
            <div class="passpro-card-body">
                <?php if ($protection_enabled): ?>
                    <p><?php esc_html_e('Password protection is active. Visitors will need to enter a valid password to access your site.', 'passpro'); ?></p>
                <?php else: ?>
                    <p><?php esc_html_e('Password protection is currently disabled. Your site is accessible to everyone.', 'passpro'); ?></p>
                <?php endif; ?>
                <div class="passpro-card-actions">
                    <a href="<?php echo esc_url(admin_url('admin.php?page=' . $this->plugin_name)); ?>" class="button button-primary">
                        <span class="dashicons dashicons-admin-generic"></span>
                        <?php esc_html_e('Protection Settings', 'passpro'); ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- Add/Edit Password Form Card -->
        <div class="passpro-card passpro-form-card">
            <div class="passpro-card-header">
                <div class="passpro-card-title">
                    <span class="dashicons <?php echo $editing ? 'dashicons-edit' : 'dashicons-plus-alt'; ?>"></span>
                    <h2><?php echo $editing ? esc_html__('Edit Password', 'passpro') : esc_html__('Add New Password', 'passpro'); ?></h2>
                </div>
            </div>
            <div class="passpro-card-body">
                <form method="post" action="<?php echo esc_url(admin_url('admin.php?page=' . $this->plugin_name . '_passwords')); ?>" class="passpro-form">
                    <?php wp_nonce_field('passpro_password_action'); ?>
                    <input type="hidden" name="action" value="<?php echo $editing ? 'edit_password' : 'add_password'; ?>">
                    
                    <?php if ($editing): ?>
                        <input type="hidden" name="password_id" value="<?php echo esc_attr($password_to_edit->id); ?>">
                    <?php endif; ?>
                    
                    <div class="passpro-form-grid">
                        <div class="passpro-form-group">
                            <label for="password"><?php esc_html_e('Password', 'passpro'); ?> <span class="required">*</span></label>
                            <input type="text" name="password" id="password" class="regular-text" value="<?php echo $editing ? esc_attr($password_to_edit->password) : ''; ?>" required>
                            <p class="description"><?php esc_html_e('Enter the password that visitors will use to access your site.', 'passpro'); ?></p>
                        </div>
                        
                        <div class="passpro-form-group">
                            <label for="name"><?php esc_html_e('Name/Description', 'passpro'); ?></label>
                            <input type="text" name="name" id="name" class="regular-text" value="<?php echo $editing ? esc_attr($password_to_edit->name) : ''; ?>">
                            <p class="description"><?php esc_html_e('Optional name or description for this password (for your reference only).', 'passpro'); ?></p>
                        </div>
                        
                        <div class="passpro-form-group">
                            <label for="email"><?php esc_html_e('Email Address', 'passpro'); ?></label>
                            <input type="email" name="email" id="email" class="regular-text" value="<?php echo $editing ? esc_attr($password_to_edit->email) : ''; ?>">
                            <p class="description"><?php esc_html_e('Optional email address to send login credentials to.', 'passpro'); ?></p>
                        </div>
                        
                        <div class="passpro-form-group">
                            <label for="uses_remaining"><?php esc_html_e('Uses Remaining', 'passpro'); ?></label>
                            <input type="number" name="uses_remaining" id="uses_remaining" class="small-text" min="0" value="<?php echo $editing && $password_to_edit->uses_remaining !== null ? esc_attr($password_to_edit->uses_remaining) : ''; ?>">
                            <p class="description"><?php esc_html_e('Optional limit on how many times this password can be used. Leave empty for unlimited uses.', 'passpro'); ?></p>
                        </div>
                        
                        <div class="passpro-form-group">
                            <label for="expiry_date"><?php esc_html_e('Expiry Date', 'passpro'); ?></label>
                            <input type="text" name="expiry_date" id="expiry_date" class="regular-text passpro-datepicker" value="<?php echo $editing && $password_to_edit->expiry_date ? esc_attr(date_i18n('Y-m-d H:i', strtotime($password_to_edit->expiry_date))) : ''; ?>" placeholder="<?php esc_attr_e('YYYY-MM-DD HH:MM', 'passpro'); ?>">
                            <p class="description"><?php esc_html_e('Optional expiry date for this password. Leave empty for no expiry.', 'passpro'); ?></p>
                        </div>
                        
                        <div class="passpro-form-group">
                            <label for="bypass_url"><?php esc_html_e('Bypass URL', 'passpro'); ?></label>
                            <input type="text" name="bypass_url" id="bypass_url" class="regular-text" value="<?php echo $editing ? esc_attr($password_to_edit->bypass_url) : ''; ?>">
                            <p class="description"><?php esc_html_e('Optional URL to bypass password protection. Leave empty for no bypass.', 'passpro'); ?></p>
                        </div>
                    </div>
                    
                    <div class="passpro-form-actions">
                        <button type="submit" class="button button-primary">
                            <?php if ($editing): ?>
                                <span class="dashicons dashicons-update"></span>
                                <?php esc_html_e('Update Password', 'passpro'); ?>
                            <?php else: ?>
                                <span class="dashicons dashicons-plus-alt"></span>
                                <?php esc_html_e('Add Password', 'passpro'); ?>
                            <?php endif; ?>
                        </button>
                        <?php if ($editing): ?>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=' . $this->plugin_name . '_passwords')); ?>" class="button">
                                <span class="dashicons dashicons-no-alt"></span>
                                <?php esc_html_e('Cancel', 'passpro'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Password List Card -->
    <div class="passpro-card passpro-list-card">
        <div class="passpro-card-header">
            <div class="passpro-card-title">
                <span class="dashicons dashicons-admin-network"></span>
                <h2><?php esc_html_e('Manage Passwords', 'passpro'); ?></h2>
            </div>
            <!-- View Toggle Controls -->
            <div class="passpro-view-toggle">
                <span class="view-toggle-label"><?php esc_html_e('View:', 'passpro'); ?></span>
                <div class="view-toggle-buttons">
                    <button type="button" class="view-toggle-button view-toggle-cards active" data-view="cards">
                        <span class="dashicons dashicons-grid-view"></span>
                        <?php esc_html_e('Cards', 'passpro'); ?>
                    </button>
                    <button type="button" class="view-toggle-button view-toggle-table" data-view="table">
                        <span class="dashicons dashicons-list-view"></span>
                        <?php esc_html_e('Table', 'passpro'); ?>
                    </button>
                </div>
            </div>
        </div>
        <div class="passpro-card-body">
            <?php if (!empty($options['passpro_password']) || !empty($passwords)): ?>
                <!-- Card View -->
                <div class="passpro-password-grid view-cards active">
                    <!-- Default password card -->
                    <?php if (!empty($options['passpro_password'])): 
                        $main_password = $options['passpro_password'];
                    ?>
                    <div class="passpro-password-item default-password">
                        <div class="passpro-password-item-header">
                            <div class="passpro-password-badge">
                                <?php esc_html_e('Default', 'passpro'); ?>
                            </div>
                            <div class="passpro-password-status <?php echo $protection_enabled ? 'status-active' : 'status-inactive'; ?>">
                                <?php echo $protection_enabled ? esc_html__('Active', 'passpro') : esc_html__('Inactive', 'passpro'); ?>
                            </div>
                        </div>
                        <div class="passpro-password-item-body">
                            <div class="passpro-password-value">
                                <span class="dashicons dashicons-privacy"></span>
                                <span class="password-masked"><?php echo esc_html($main_password); ?></span>
                            </div>
                            <div class="passpro-password-details">
                                <div class="passpro-password-detail">
                                    <span class="dashicons dashicons-admin-users"></span>
                                    <?php esc_html_e('Default password (from main settings)', 'passpro'); ?>
                                </div>
                                <div class="passpro-password-detail">
                                    <span class="dashicons dashicons-update"></span>
                                    <?php esc_html_e('Uses: Unlimited', 'passpro'); ?>
                                </div>
                                <div class="passpro-password-detail">
                                    <span class="dashicons dashicons-calendar-alt"></span>
                                    <?php esc_html_e('Expiry: Never', 'passpro'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="passpro-password-item-actions">
                            <a href="<?php echo esc_url(admin_url('admin.php?page=' . $this->plugin_name)); ?>" class="button button-secondary">
                                <span class="dashicons dashicons-edit"></span>
                                <?php esc_html_e('Edit in Settings', 'passpro'); ?>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Additional passwords -->
                    <?php 
                    if (!empty($passwords)):
                        foreach ($passwords as $password):
                            $is_expired = !empty($password->expiry_date) && strtotime($password->expiry_date) < time();
                            $status = $is_expired ? 'expired' : $password->status;
                            
                            switch ($status) {
                                case 'active':
                                    $status_text = __('Active', 'passpro');
                                    $status_class = 'status-active';
                                    break;
                                case 'inactive':
                                    $status_text = __('Inactive', 'passpro');
                                    $status_class = 'status-inactive';
                                    break;
                                case 'expired':
                                    $status_text = __('Expired', 'passpro');
                                    $status_class = 'status-expired';
                                    break;
                            }
                    ?>
                    <div class="passpro-password-item">
                        <div class="passpro-password-item-header">
                            <?php if (!empty($password->name)): ?>
                            <div class="passpro-password-name">
                                <?php echo esc_html($password->name); ?>
                            </div>
                            <?php endif; ?>
                            <div class="passpro-password-status <?php echo $status_class; ?>">
                                <?php echo esc_html($status_text); ?>
                            </div>
                        </div>
                        <div class="passpro-password-item-body">
                            <div class="passpro-password-value">
                                <span class="dashicons dashicons-privacy"></span>
                                <span class="password-masked"><?php esc_html_e('Hidden for security', 'passpro'); ?></span>
                            </div>
                            <div class="passpro-password-details">
                                <?php if ($password->uses_remaining !== null): ?>
                                <div class="passpro-password-detail">
                                    <span class="dashicons dashicons-update"></span>
                                    <?php 
                                    echo sprintf(esc_html__('Uses remaining: %d', 'passpro'), $password->uses_remaining);
                                    if ($password->used_count > 0) {
                                        echo ' <span class="used-count">(' . sprintf(esc_html__('Used %d times', 'passpro'), $password->used_count) . ')</span>';
                                    }
                                    ?>
                                </div>
                                <?php else: ?>
                                <div class="passpro-password-detail">
                                    <span class="dashicons dashicons-update"></span>
                                    <?php esc_html_e('Uses: Unlimited', 'passpro'); ?>
                                    <?php 
                                    if ($password->used_count > 0) {
                                        echo ' <span class="used-count">(' . sprintf(esc_html__('Used %d times', 'passpro'), $password->used_count) . ')</span>';
                                    }
                                    ?>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($password->expiry_date)): ?>
                                <div class="passpro-password-detail">
                                    <span class="dashicons dashicons-calendar-alt"></span>
                                    <?php 
                                    echo sprintf(esc_html__('Expires: %s', 'passpro'), 
                                        date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($password->expiry_date)));
                                    if ($is_expired) {
                                        echo ' <span class="expired-tag">(' . esc_html__('Expired', 'passpro') . ')</span>';
                                    }
                                    ?>
                                </div>
                                <?php else: ?>
                                <div class="passpro-password-detail">
                                    <span class="dashicons dashicons-calendar-alt"></span>
                                    <?php esc_html_e('Expiry: Never', 'passpro'); ?>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($password->bypass_url)): ?>
                                <div class="passpro-password-detail">
                                    <span class="dashicons dashicons-admin-links"></span>
                                    <?php esc_html_e('Has bypass URL', 'passpro'); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="passpro-password-item-actions">
                            <a href="<?php echo esc_url(add_query_arg(array('action' => 'edit', 'password_id' => $password->id), admin_url('admin.php?page=' . $this->plugin_name . '_passwords'))); ?>" class="button button-secondary">
                                <span class="dashicons dashicons-edit"></span>
                                <?php esc_html_e('Edit', 'passpro'); ?>
                            </a>
                            
                            <?php if (!empty($password->email)): ?>
                            <a href="<?php echo esc_url(wp_nonce_url(add_query_arg(array('action' => 'send_email', 'password_id' => $password->id), admin_url('admin.php?page=' . $this->plugin_name . '_passwords')), 'passpro_password_action_' . $password->id)); ?>" class="button button-secondary">
                                <span class="dashicons dashicons-email"></span>
                                <?php esc_html_e('Send Email', 'passpro'); ?>
                            </a>
                            <?php endif; ?>
                            
                            <?php if ($status !== 'expired'): ?>
                            <form method="post" action="<?php echo esc_url(admin_url('admin.php?page=' . $this->plugin_name . '_passwords')); ?>" class="passpro-inline-form">
                                <?php wp_nonce_field('passpro_password_action'); ?>
                                <input type="hidden" name="action" value="toggle_password_status">
                                <input type="hidden" name="password_id" value="<?php echo esc_attr($password->id); ?>">
                                <button type="submit" class="button button-secondary <?php echo $status === 'active' ? 'toggle-off' : 'toggle-on'; ?>">
                                    <span class="dashicons <?php echo $status === 'active' ? 'dashicons-unlock' : 'dashicons-lock'; ?>"></span>
                                    <?php echo $status === 'active' ? esc_html__('Deactivate', 'passpro') : esc_html__('Activate', 'passpro'); ?>
                                </button>
                            </form>
                            <?php endif; ?>
                            
                            <form method="post" action="<?php echo esc_url(admin_url('admin.php?page=' . $this->plugin_name . '_passwords')); ?>" class="passpro-inline-form" onsubmit="return confirm('<?php esc_attr_e('Are you sure you want to delete this password?', 'passpro'); ?>');">
                                <?php wp_nonce_field('passpro_password_action'); ?>
                                <input type="hidden" name="action" value="delete_password">
                                <input type="hidden" name="password_id" value="<?php echo esc_attr($password->id); ?>">
                                <button type="submit" class="button button-secondary button-delete">
                                    <span class="dashicons dashicons-trash"></span>
                                    <?php esc_html_e('Delete', 'passpro'); ?>
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Table View -->
                <div class="passpro-password-table view-table">
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th class="column-name"><?php esc_html_e('Name', 'passpro'); ?></th>
                                <th class="column-status"><?php esc_html_e('Status', 'passpro'); ?></th>
                                <th class="column-password"><?php esc_html_e('Password', 'passpro'); ?></th>
                                <th class="column-usage"><?php esc_html_e('Usage', 'passpro'); ?></th>
                                <th class="column-expiry"><?php esc_html_e('Expiry', 'passpro'); ?></th>
                                <th class="column-actions"><?php esc_html_e('Actions', 'passpro'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($options['passpro_password'])): 
                                $main_password = $options['passpro_password'];
                            ?>
                            <tr class="default-password">
                                <td class="column-name">
                                    <span class="password-name">
                                        <span class="dashicons dashicons-shield-alt"></span>
                                        <?php esc_html_e('Default', 'passpro'); ?>
                                    </span>
                                </td>
                                <td class="column-status">
                                    <span class="status-badge <?php echo $protection_enabled ? 'status-active' : 'status-inactive'; ?>">
                                        <?php echo $protection_enabled ? esc_html__('Active', 'passpro') : esc_html__('Inactive', 'passpro'); ?>
                                    </span>
                                </td>
                                <td class="column-password">
                                    <span class="dashicons dashicons-privacy"></span>
                                    <span class="password-masked"><?php echo esc_html($main_password); ?></span>
                                </td>
                                <td class="column-usage">
                                    <span class="dashicons dashicons-update"></span>
                                    <?php esc_html_e('Unlimited', 'passpro'); ?>
                                </td>
                                <td class="column-expiry">
                                    <span class="dashicons dashicons-calendar-alt"></span>
                                    <?php esc_html_e('Never', 'passpro'); ?>
                                </td>
                                <td class="column-actions">
                                    <a href="<?php echo esc_url(admin_url('admin.php?page=' . $this->plugin_name)); ?>" class="button button-small button-secondary">
                                        <span class="dashicons dashicons-edit"></span>
                                        <?php esc_html_e('Edit in Settings', 'passpro'); ?>
                                    </a>
                                </td>
                            </tr>
                            <?php endif; ?>

                            <?php 
                            if (!empty($passwords)):
                                foreach ($passwords as $password):
                                    $is_expired = !empty($password->expiry_date) && strtotime($password->expiry_date) < time();
                                    $status = $is_expired ? 'expired' : $password->status;
                                    
                                    switch ($status) {
                                        case 'active':
                                            $status_text = __('Active', 'passpro');
                                            $status_class = 'status-active';
                                            break;
                                        case 'inactive':
                                            $status_text = __('Inactive', 'passpro');
                                            $status_class = 'status-inactive';
                                            break;
                                        case 'expired':
                                            $status_text = __('Expired', 'passpro');
                                            $status_class = 'status-expired';
                                            break;
                                    }
                            ?>
                            <tr>
                                <td class="column-name">
                                    <span class="password-name">
                                        <span class="dashicons dashicons-admin-users"></span>
                                        <?php echo !empty($password->name) ? esc_html($password->name) : esc_html__('Unnamed', 'passpro'); ?>
                                    </span>
                                </td>
                                <td class="column-status">
                                    <span class="status-badge <?php echo $status_class; ?>">
                                        <?php echo esc_html($status_text); ?>
                                    </span>
                                </td>
                                <td class="column-password">
                                    <span class="dashicons dashicons-privacy"></span>
                                    <span class="password-masked"><?php esc_html_e('Hidden for security', 'passpro'); ?></span>
                                </td>
                                <td class="column-usage">
                                    <span class="dashicons dashicons-update"></span>
                                    <?php 
                                    if ($password->uses_remaining !== null) {
                                        echo sprintf(esc_html__('%d remaining', 'passpro'), $password->uses_remaining);
                                    } else {
                                        esc_html_e('Unlimited', 'passpro');
                                    }
                                    
                                    if ($password->used_count > 0) {
                                        echo ' <span class="used-count">(' . sprintf(esc_html__('Used %d times', 'passpro'), $password->used_count) . ')</span>';
                                    }
                                    ?>
                                </td>
                                <td class="column-expiry">
                                    <span class="dashicons dashicons-calendar-alt"></span>
                                    <?php 
                                    if (!empty($password->expiry_date)) {
                                        echo sprintf(esc_html__('%s', 'passpro'), 
                                            date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($password->expiry_date)));
                                        if ($is_expired) {
                                            echo ' <span class="expired-tag">(' . esc_html__('Expired', 'passpro') . ')</span>';
                                        }
                                    } else {
                                        esc_html_e('Never', 'passpro');
                                    }
                                    ?>
                                </td>
                                <td class="column-actions">
                                    <div class="row-actions visible">
                                        <a href="<?php echo esc_url(add_query_arg(array('action' => 'edit', 'password_id' => $password->id), admin_url('admin.php?page=' . $this->plugin_name . '_passwords'))); ?>" class="button button-small button-secondary">
                                            <span class="dashicons dashicons-edit"></span>
                                            <?php esc_html_e('Edit', 'passpro'); ?>
                                        </a>
                                        
                                        <?php if (!empty($password->email)): ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(add_query_arg(array('action' => 'send_email', 'password_id' => $password->id), admin_url('admin.php?page=' . $this->plugin_name . '_passwords')), 'passpro_password_action_' . $password->id)); ?>" class="button button-small button-secondary">
                                            <span class="dashicons dashicons-email"></span>
                                            <?php esc_html_e('Send Email', 'passpro'); ?>
                                        </a>
                                        <?php endif; ?>
                                        
                                        <?php if ($status !== 'expired'): ?>
                                        <form method="post" action="<?php echo esc_url(admin_url('admin.php?page=' . $this->plugin_name . '_passwords')); ?>" class="passpro-inline-form">
                                            <?php wp_nonce_field('passpro_password_action'); ?>
                                            <input type="hidden" name="action" value="toggle_password_status">
                                            <input type="hidden" name="password_id" value="<?php echo esc_attr($password->id); ?>">
                                            <button type="submit" class="button button-small button-secondary <?php echo $status === 'active' ? 'toggle-off' : 'toggle-on'; ?>">
                                                <span class="dashicons <?php echo $status === 'active' ? 'dashicons-unlock' : 'dashicons-lock'; ?>"></span>
                                                <?php echo $status === 'active' ? esc_html__('Deactivate', 'passpro') : esc_html__('Activate', 'passpro'); ?>
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                        
                                        <form method="post" action="<?php echo esc_url(admin_url('admin.php?page=' . $this->plugin_name . '_passwords')); ?>" class="passpro-inline-form" onsubmit="return confirm('<?php esc_attr_e('Are you sure you want to delete this password?', 'passpro'); ?>');">
                                            <?php wp_nonce_field('passpro_password_action'); ?>
                                            <input type="hidden" name="action" value="delete_password">
                                            <input type="hidden" name="password_id" value="<?php echo esc_attr($password->id); ?>">
                                            <button type="submit" class="button button-small button-secondary button-delete">
                                                <span class="dashicons dashicons-trash"></span>
                                                <?php esc_html_e('Delete', 'passpro'); ?>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="passpro-empty-state">
                    <span class="dashicons dashicons-shield"></span>
                    <p><?php esc_html_e('No passwords found. Add your first password above to start protecting your site.', 'passpro'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* Modern design styles for the Manage Passwords page */
.passpro-header {
    margin-bottom: 25px !important;
    font-size: 28px !important;
    font-weight: 400 !important;
    color: #23282d;
}

.passpro-dashboard {
    display: grid;
    grid-template-columns: minmax(300px, 1fr) minmax(400px, 2fr);
    gap: 20px;
    margin-bottom: 25px;
}

@media (max-width: 1200px) {
    .passpro-dashboard {
        grid-template-columns: 1fr;
    }
}

.passpro-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.2s ease;
}

.passpro-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
}

.passpro-card-header {
    padding: 18px 24px;
    border-bottom: 1px solid #f0f0f1;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.passpro-card-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.passpro-card-title h2 {
    margin: 0;
    font-size: 18px;
    font-weight: 500;
    color: #23282d;
}

.passpro-card-title .dashicons {
    font-size: 22px;
    width: 22px;
    height: 22px;
    color: #2271b1;
}

.passpro-card-body {
    padding: 24px;
}

/* Status Card Styles */
.passpro-status-card {
    position: relative;
}

.passpro-status-card.status-active {
    border-top: 4px solid #46b450;
}

.passpro-status-card.status-inactive {
    border-top: 4px solid #dc3232;
}

.passpro-status-badge {
    display: inline-flex;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    align-items: center;
}

/* View Toggle Styles */
.passpro-view-toggle {
    display: flex;
    align-items: center;
    gap: 8px;
}

.view-toggle-label {
    font-size: 14px;
    color: #646970;
}

.view-toggle-buttons {
    display: flex;
    border: 1px solid #c3c4c7;
    border-radius: 4px;
    overflow: hidden;
}

.view-toggle-button {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 6px 10px;
    background: #f6f7f7;
    border: none;
    border-right: 1px solid #c3c4c7;
    cursor: pointer;
    font-size: 13px;
    transition: all 0.2s ease;
}

.view-toggle-button:last-child {
    border-right: none;
}

.view-toggle-button .dashicons {
    font-size: 16px;
    width: 16px;
    height: 16px;
}

.view-toggle-button:hover,
.view-toggle-button:focus {
    background: #f0f0f1;
}

.view-toggle-button.active {
    background: #2271b1;
    color: #fff;
}

.view-toggle-button.active .dashicons {
    color: #fff;
}

/* View containers */
.view-cards,
.view-table {
    display: none !important;
}

.view-cards.active {
    display: grid !important;
}

.view-table.active {
    display: block !important;
}

/* Table View Styles */
.passpro-password-table table {
    border-collapse: collapse;
    width: 100%;
}

.passpro-password-table table th {
    text-align: left;
    padding: 10px;
    font-weight: 600;
    color: #1d2327;
    background: #f6f7f7;
    border-bottom: 1px solid #c3c4c7;
}

.passpro-password-table table td {
    padding: 12px 10px;
    vertical-align: middle;
}

.passpro-password-table .column-name {
    width: 20%;
}

.passpro-password-table .column-status {
    width: 10%;
}

.passpro-password-table .column-password {
    width: 15%;
}

.passpro-password-table .column-usage {
    width: 15%;
}

.passpro-password-table .column-expiry {
    width: 20%;
}

.passpro-password-table .column-actions {
    width: 20%;
}

.passpro-password-table .password-name {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
}

.passpro-password-table .status-badge {
    padding: 4px 8px;
    border-radius: 20px;
    font-size: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.passpro-password-table .status-active {
    background-color: #edfaef;
    color: #236728;
}

.passpro-password-table .status-inactive {
    background-color: #f6f7f7;
    color: #646970;
}

.passpro-password-table .status-expired {
    background-color: #fcf0f1;
    color: #d63638;
}

.passpro-password-table .row-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.passpro-password-table .button-small {
    height: 28px;
    line-height: 26px;
    padding: 0 10px;
    font-size: 12px;
}

.passpro-password-table .button-small .dashicons {
    font-size: 16px;
    width: 16px;
    height: 16px;
    margin-right: 3px;
    margin-top: 5px;
}

.passpro-password-table .used-count,
.passpro-password-table .expired-tag {
    color: #646970;
    font-size: 12px;
}

.passpro-password-table .dashicons {
    color: #646970;
    margin-right: 5px;
}

.passpro-password-table tr.default-password {
    background-color: #f0f6fc;
}

.passpro-password-table tr.default-password .password-name {
    color: #2271b1;
}

/* Responsive Styles for Table */
@media screen and (max-width: 782px) {
    .passpro-password-table .column-expiry,
    .passpro-password-table .column-usage {
        display: none;
    }
    
    .passpro-password-table .column-name {
        width: 25%;
    }
    
    .passpro-password-table .column-status {
        width: 15%;
    }
    
    .passpro-password-table .column-password {
        width: 20%;
    }
    
    .passpro-password-table .column-actions {
        width: 40%;
    }
}

@media screen and (max-width: 600px) {
    .passpro-view-toggle {
        flex-direction: column;
        align-items: flex-end;
    }
}

/* Card View Styles */
.status-badge-active {
    background-color: #ecf7ed;
    color: #2a8435;
}

.status-badge-inactive {
    background-color: #f9e2e2;
    color: #b32d2e;
}

.passpro-card-actions {
    margin-top: 20px;
}

.passpro-card-actions .button {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.passpro-card-actions .dashicons {
    font-size: 18px;
    width: 18px;
    height: 18px;
}

/* Form Styles */
.passpro-form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.passpro-form-group {
    margin-bottom: 5px;
}

.passpro-form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #23282d;
}

.passpro-form-group input[type="text"],
.passpro-form-group input[type="number"],
.passpro-form-group input[type="email"] {
    width: 100%;
    padding: 8px 12px;
    border-radius: 4px;
    border: 1px solid #8c8f94;
}

.passpro-form-group input:focus {
    border-color: #2271b1;
    box-shadow: 0 0 0 1px #2271b1;
    outline: none;
}

.passpro-form-group .description {
    margin-top: 8px;
    font-size: 13px;
    color: #646970;
}

.passpro-form-group .required {
    color: #d63638;
}

.passpro-form-actions {
    margin-top: 24px;
    display: flex;
    gap: 12px;
}

.passpro-form-actions .button {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 0 16px;
    height: 36px;
    line-height: 34px;
}

.passpro-form-actions .dashicons {
    font-size: 18px;
    width: 18px;
    height: 18px;
}

/* Password Grid List Styles */
.passpro-password-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
}

.passpro-password-item {
    background-color: #f8f9fa;
    border-radius: 6px;
    overflow: hidden;
    border: 1px solid #e2e4e7;
    transition: all 0.2s ease;
}

.passpro-password-item:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
}

.passpro-password-item.default-password {
    background-color: #f0f6fc;
    border-color: #c5d9ed;
}

.passpro-password-item-header {
    padding: 12px 16px;
    background: #fff;
    border-bottom: 1px solid #e2e4e7;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.passpro-password-name {
    font-weight: 500;
    font-size: 14px;
    color: #1d2327;
}

.passpro-password-badge {
    background-color: #2271b1;
    color: #fff;
    padding: 3px 8px;
    border-radius: 3px;
    font-size: 12px;
    font-weight: 500;
}

.passpro-password-status {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.passpro-password-status.status-active {
    background-color: #ecf7ed;
    color: #2a8435;
}

.passpro-password-status.status-inactive {
    background-color: #f6f7f7;
    color: #646970;
}

.passpro-password-status.status-expired {
    background-color: #f9e2e2;
    color: #b32d2e;
}

.passpro-password-item-body {
    padding: 16px;
}

.passpro-password-value {
    margin-bottom: 16px;
    padding: 10px;
    background: rgba(0, 0, 0, 0.03);
    border-radius: 4px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.passpro-password-value .dashicons {
    color: #2271b1;
}

.password-masked {
    font-family: monospace;
    font-size: 14px;
    color: #646970;
}

.passpro-password-details {
    display: grid;
    gap: 8px;
}

.passpro-password-detail {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #50575e;
}

.passpro-password-detail .dashicons {
    color: #2271b1;
    font-size: 16px;
    width: 16px;
    height: 16px;
}

.used-count, .expired-tag {
    color: #646970;
    font-style: italic;
}

.expired-tag {
    color: #b32d2e;
}

.passpro-password-item-actions {
    padding: 12px 16px;
    background: #fff;
    border-top: 1px solid #e2e4e7;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.passpro-password-item-actions .button {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 0 10px;
    height: 32px;
    line-height: 30px;
    font-size: 12px;
}

.passpro-password-item-actions .dashicons {
    font-size: 16px;
    width: 16px;
    height: 16px;
}

.passpro-inline-form {
    display: inline;
}

.button-delete {
    color: #b32d2e;
    border-color: #b32d2e;
}

.button-delete:hover, .button-delete:focus {
    color: #fff;
    background-color: #b32d2e;
    border-color: #b32d2e;
}

.toggle-on {
    color: #2a8435;
    border-color: #2a8435;
}

.toggle-on:hover, .toggle-on:focus {
    color: #fff;
    background-color: #2a8435;
    border-color: #2a8435;
}

.toggle-off {
    color: #646970;
    border-color: #8c8f94;
}

.toggle-off:hover, .toggle-off:focus {
    color: #fff;
    background-color: #646970;
    border-color: #646970;
}

/* Empty state */
.passpro-empty-state {
    text-align: center;
    padding: 40px 0;
    color: #646970;
}

.passpro-empty-state .dashicons {
    font-size: 48px;
    width: 48px;
    height: 48px;
    margin-bottom: 16px;
    color: #c3c4c7;
}

.passpro-empty-state p {
    font-size: 16px;
    margin: 0;
}

@media (max-width: 782px) {
    .passpro-form-grid {
        grid-template-columns: 1fr;
    }
    
    .passpro-password-grid {
        grid-template-columns: 1fr;
    }
}
</style>
<!-- Direct View Toggle Script -->
<script type="text/javascript">
jQuery(document).ready(function($) {
    console.log('View toggle script loaded');

    // Directly check if elements exist first
    if ($('.view-toggle-button').length < 1) {
        console.log('Toggle buttons not found');
        return;
    }

    // Store references to DOM elements to avoid repeated lookups
    var cardsView = $('.view-cards');
    var tableView = $('.view-table');
    var cardsButton = $('.view-toggle-cards');
    var tableButton = $('.view-toggle-table');

    if (cardsView.length < 1 || tableView.length < 1) {
        console.log('View containers not found', { cardsView: cardsView.length, tableView: tableView.length });
        return;
    }

    console.log('Found view containers and buttons');
    
    // Direct view toggle functionality - using classes, not show/hide
    $('.view-toggle-button').on('click', function(e) {
        e.preventDefault();
        var view = $(this).data('view');
        console.log('Button clicked:', view);
        
        // Update buttons first
        $('.view-toggle-button').removeClass('active');
        $(this).addClass('active');
        
        // Update views with classes
        if (view === 'cards') {
            cardsView.addClass('active');
            tableView.removeClass('active');
            console.log('Activated cards view');
        } else if (view === 'table') {
            tableView.addClass('active');
            cardsView.removeClass('active');
            console.log('Activated table view');
        }
        
        // Save preference
        try {
            localStorage.setItem('passproPrefView', view);
            console.log('Saved preference:', view);
        } catch(e) { 
            console.log('Could not save view preference:', e); 
        }
    });
    
    // Set initial view based on saved preference (simplified)
    try {
        var savedView = localStorage.getItem('passproPrefView') || 'cards';
        console.log('Initial view:', savedView);
        
        // Apply saved view directly
        if (savedView === 'cards') {
            cardsButton.addClass('active');
            tableButton.removeClass('active');
            cardsView.addClass('active');
            tableView.removeClass('active');
            console.log('Applied cards view');
        } else {
            tableButton.addClass('active');
            cardsButton.removeClass('active');
            tableView.addClass('active');
            cardsView.removeClass('active');
            console.log('Applied table view');
        }
    } catch(e) {
        console.error('Error setting initial view:', e);
        // Default to cards view
        cardsButton.addClass('active');
        tableButton.removeClass('active');
        cardsView.addClass('active');
        tableView.removeClass('active');
    }
});
</script>
<!-- End of inline script --> 