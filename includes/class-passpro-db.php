<?php

/**
 * Database operations for the PassPro plugin.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    PassPro
 * @subpackage PassPro/includes
 */

/**
 * Database operations for the PassPro plugin.
 *
 * Handles database operations specific to the PassPro plugin,
 * including managing multiple passwords.
 *
 * @package    PassPro
 * @subpackage PassPro/includes
 * @author     Your Name <email@example.com>
 */
class PassPro_DB {

    /**
     * The table name for multiple passwords.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $passwords_table    The name of the passwords table.
     */
    private $passwords_table;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     */
    public function __construct() {
        global $wpdb;
        $this->passwords_table = $wpdb->prefix . 'passpro_passwords';
    }

    /**
     * Create the database tables needed by the plugin.
     *
     * @since    1.0.0
     */
    public static function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $passwords_table = $wpdb->prefix . 'passpro_passwords';

        $sql = "CREATE TABLE $passwords_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            password_hash varchar(255) NOT NULL,
            name varchar(255) DEFAULT '',
            uses_remaining int(11) DEFAULT NULL,
            used_count int(11) DEFAULT 0,
            date_created datetime DEFAULT CURRENT_TIMESTAMP,
            expiry_date datetime DEFAULT NULL,
            bypass_url varchar(255) DEFAULT '',
            status varchar(50) DEFAULT 'active',
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Get all passwords from the database.
     *
     * @since    1.0.0
     * @return   array    An array of password objects.
     */
    public function get_passwords() {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM {$this->passwords_table} ORDER BY id DESC");
    }

    /**
     * Get a specific password from the database.
     *
     * @since    1.0.0
     * @param    int       $id    The password ID.
     * @return   object    The password object.
     */
    public function get_password($id) {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->passwords_table} WHERE id = %d", $id));
    }

    /**
     * Add a new password to the database.
     *
     * @since    1.0.0
     * @param    array     $data    The password data.
     * @return   int|false          The password ID or false on failure.
     */
    public function add_password($data) {
        global $wpdb;
        
        $defaults = array(
            'password' => '',
            'name' => '',
            'uses_remaining' => null,
            'used_count' => 0,
            'date_created' => current_time('mysql'),
            'expiry_date' => null,
            'bypass_url' => '',
            'status' => 'active'
        );
        
        $data = wp_parse_args($data, $defaults);
        
        // Hash the password if it exists
        if (!empty($data['password'])) {
            $plain_password = $data['password'];
            $data['password_hash'] = wp_hash_password($plain_password);
            unset($data['password']); // Remove plain text password
        }
        
        $result = $wpdb->insert(
            $this->passwords_table,
            $data,
            array(
                '%s', // password_hash
                '%s', // name
                '%d', // uses_remaining
                '%d', // used_count
                '%s', // date_created
                '%s', // expiry_date
                '%s', // bypass_url
                '%s'  // status
            )
        );
        
        return $result ? $wpdb->insert_id : false;
    }

    /**
     * Update an existing password in the database.
     *
     * @since    1.0.0
     * @param    int       $id      The password ID.
     * @param    array     $data    The password data.
     * @return   int|false          The number of rows updated or false on failure.
     */
    public function update_password($id, $data) {
        global $wpdb;
        
        // Define which fields can be updated
        $allowed_fields = array(
            'password_hash' => '%s',
            'name' => '%s',
            'uses_remaining' => '%d',
            'used_count' => '%d',
            'expiry_date' => '%s',
            'bypass_url' => '%s',
            'status' => '%s'
        );
        
        // Hash the password if it's being updated
        if (isset($data['password'])) {
            $plain_password = $data['password'];
            $data['password_hash'] = wp_hash_password($plain_password);
            unset($data['password']); // Remove plain text password
        }
        
        // Filter out any fields that aren't allowed
        $update_data = array();
        $formats = array();
        
        foreach ($allowed_fields as $field => $format) {
            if (isset($data[$field])) {
                $update_data[$field] = $data[$field];
                $formats[] = $format;
            }
        }
        
        if (empty($update_data)) {
            return false;
        }
        
        return $wpdb->update(
            $this->passwords_table,
            $update_data,
            array('id' => $id),
            $formats,
            array('%d')
        );
    }

    /**
     * Delete a password from the database.
     *
     * @since    1.0.0
     * @param    int       $id    The password ID.
     * @return   int|false        The number of rows deleted or false on failure.
     */
    public function delete_password($id) {
        global $wpdb;
        return $wpdb->delete(
            $this->passwords_table,
            array('id' => $id),
            array('%d')
        );
    }

    /**
     * Mark a password as used, decrementing its uses_remaining count if it has one.
     *
     * @since    1.0.0
     * @param    int       $id    The password ID.
     * @return   bool             True on success, false on failure.
     */
    public function mark_password_used($id) {
        global $wpdb;
        
        // Get the current password record
        $password = $this->get_password($id);
        
        if (!$password) {
            return false;
        }
        
        // Increment the used_count
        $update_data = array(
            'used_count' => $password->used_count + 1
        );
        
        // If uses_remaining is set (not null), decrement it
        if ($password->uses_remaining !== null) {
            $update_data['uses_remaining'] = max(0, $password->uses_remaining - 1);
            
            // If uses_remaining reaches 0, deactivate the password
            if ($password->uses_remaining <= 1) {
                $update_data['status'] = 'inactive';
            }
        }
        
        return (bool) $this->update_password($id, $update_data);
    }

    /**
     * Verify if a password exists and is valid.
     *
     * @since    1.0.0
     * @param    string    $password    The password to verify.
     * @return   int|false              The password ID if valid, false otherwise.
     */
    public function verify_password($password) {
        global $wpdb;
        
        // Get current time in MySQL format
        $current_time = current_time('mysql');
        
        // Check for active passwords that are not expired and have uses remaining
        $query = $wpdb->prepare(
            "SELECT id, password_hash FROM {$this->passwords_table} 
            WHERE status = 'active' 
            AND (expiry_date IS NULL OR expiry_date > %s) 
            AND (uses_remaining IS NULL OR uses_remaining > 0)",
            $current_time
        );
        
        $passwords = $wpdb->get_results($query);
        
        if ($passwords) {
            foreach ($passwords as $pwd) {
                // Check if the entered password matches the stored hash
                if (wp_check_password($password, $pwd->password_hash)) {
                    // Update the usage count and remaining uses
                    $this->mark_password_used($pwd->id);
                    return $pwd->id;
                }
            }
        }
        
        return false;
    }

    /**
     * Get active passwords that can be used for authentication.
     *
     * @since    1.0.0
     * @return   array    An array of valid password strings.
     */
    public function get_active_passwords() {
        global $wpdb;
        
        // Get current time in MySQL format
        $current_time = current_time('mysql');
        
        // Get all active passwords that haven't expired and have uses remaining
        $query = $wpdb->prepare(
            "SELECT id, password_hash FROM {$this->passwords_table} 
            WHERE status = 'active' 
            AND (expiry_date IS NULL OR expiry_date > %s) 
            AND (uses_remaining IS NULL OR uses_remaining > 0)",
            $current_time
        );
        
        return $wpdb->get_results($query);
    }

    /**
     * Migrate passwords from plain text to hashes
     *
     * @since    1.0.0
     * @return   bool    Success status
     */
    public function migrate_to_password_hashes() {
        global $wpdb;
        
        // Check if the column name needs updating
        $column_exists = $wpdb->get_results(
            "SHOW COLUMNS FROM {$this->passwords_table} LIKE 'password_hash'"
        );
        
        // If password_hash column doesn't exist, but password column does, we need to migrate
        $need_migration = empty($column_exists) && $wpdb->get_results(
            "SHOW COLUMNS FROM {$this->passwords_table} LIKE 'password'"
        );
        
        if ($need_migration) {
            // Step 1: Add the new password_hash column
            $wpdb->query(
                "ALTER TABLE {$this->passwords_table} ADD COLUMN password_hash VARCHAR(255) NOT NULL DEFAULT '' AFTER password"
            );
            
            // Step 2: Get all existing passwords
            $passwords = $wpdb->get_results("SELECT id, password FROM {$this->passwords_table}");
            
            // Step 3: Hash each password and update the record
            foreach ($passwords as $pwd) {
                if (!empty($pwd->password)) {
                    $hashed = wp_hash_password($pwd->password);
                    $wpdb->update(
                        $this->passwords_table,
                        array('password_hash' => $hashed),
                        array('id' => $pwd->id),
                        array('%s'),
                        array('%d')
                    );
                }
            }
            
            // Step 4: Rename the original password column to password_old for backup
            $wpdb->query(
                "ALTER TABLE {$this->passwords_table} CHANGE COLUMN password password_old VARCHAR(255) NOT NULL DEFAULT ''"
            );
            
            return true;
        }
        
        return false;
    }
} 