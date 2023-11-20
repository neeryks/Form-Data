<?php
/*
Plugin Name: Form to Database
Description: This plugin handles form data and database operations.
Version: 1.811
Author: Akashdeep Singh
*/

// Activation Hook: Create the database table and add missing columns
register_activation_hook(__FILE__, 'my_custom_plugin_activate');

function my_custom_plugin_activate() {
    global $wpdb;

    // Define the table name
    $table_name = $wpdb->prefix . "form_data";

    // Check if the table exists; if not, create it
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        // Define the initial table schema
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            first_name varchar(255) NOT NULL,
            last_name varchar(255) NOT NULL,
            company_name varchar(255) NOT NULL,
            sent_from varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            job_title varchar(255) NOT NULL,
            country varchar(255) NOT NULL,
            message_ varchar(255) NOT NULL,
            time_stamp varchar(255) NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    } else {
        // The table already exists; check for missing columns
        $fields = array(
            'first_name' => 'varchar(255) NOT NULL',
            'last_name' => 'varchar(255) NOT NULL',
            'company_name' => 'varchar(255) NOT NULL',
            'sent_from' => 'varchar(255) NOT NULL',
            'email' => 'varchar(255) NOT NULL',
            'job_title' => 'varchar(255) NOT NULL',
            'country' => 'varchar(255) NOT NULL',
            'message_' => 'varchar(255) NOT NULL',
            'time_stamp' => 'varchar(255) NOT NULL'
            // Add more fields here as needed
        );

        foreach ($fields as $field => $type) {
            $column_exists = $wpdb->get_var("SHOW COLUMNS FROM $table_name LIKE '$field'");
            if (!$column_exists) {
                // Add the missing column to the table
                $alter_sql = "ALTER TABLE $table_name ADD COLUMN $field $type";
                $wpdb->query($alter_sql);
            }
        }
    }
}

// Deactivation Hook: Remove the database table (optional)
register_deactivation_hook(__FILE__, 'my_custom_plugin_deactivate');

function my_custom_plugin_deactivate() {
    
}

// Filter Hook: Handle form data and store in the database
add_filter("wcra_form_data_callback", "handle_form_data_callback");

function handle_form_data_callback($param) {
    global $wpdb;

    // Assuming $param contains the "first_name," "last_name," "company_name," "sent_from," and "email" values received from the API
    $first_name = sanitize_text_field($param['first_name']); // Sanitize and extract first name
    $last_name = sanitize_text_field($param['last_name']);   // Sanitize and extract last name
    $company_name = sanitize_text_field($param['company_name']); // Sanitize and extract company name
    $sent_from = sanitize_text_field($param['sent_from']); // Sanitize and extract sent from
    $email = sanitize_email($param['email']);
    $job_title = sanitize_text_field($param['job_title']); 
    $country = sanitize_text_field($param['country']); 
    $message_ = sanitize_text_field($param['message']);
    $time_stamp = date('Y-m-d H:i:s');
                     // Sanitize and extract message

    // Check if at least one field (first name, last name, company name, sent from, or email) is provided
    if (!empty($first_name) || !empty($last_name) || !empty($company_name) || !empty($sent_from) || !empty($email) || !empty($job_title) || !empty($country) || !empty($message_)) {
        // Define the table name
        $table_name = $wpdb->prefix . "form_data";

        // Prepare an array to hold the data to be inserted
        $data_to_insert = array();

        // Add the fields if they exist
        if (!empty($first_name)) {
            $data_to_insert['first_name'] = $first_name;
        }
        if (!empty($last_name)) {
            $data_to_insert['last_name'] = $last_name;
        }
        if (!empty($company_name)) {
            $data_to_insert['company_name'] = $company_name;
        }
        if (!empty($sent_from)) {
            $data_to_insert['sent_from'] = $sent_from;
        }
        if (!empty($email)) {
            $data_to_insert['email'] = $email;
        }
        if (!empty($job_title)) {
            $data_to_insert['job_title'] = $job_title;
        }
        if (!empty($country)) {
            $data_to_insert['country'] = $country;
        }
        if (!empty($message_)) {
            $data_to_insert['message_'] = $message_;
        }
        
        $data_to_insert['time_stamp'] = $time_stamp;
        

        // Insert the data into the database
        $wpdb->insert(
            $table_name,
            $data_to_insert
        );

        // Check if the insertion was successful
        if ($wpdb->insert_id) {
            // Data inserted successfully
            return array('status' => 'success', 'message' => 'Form data saved successfully.');
        } else {
            // Failed to insert data
            return array('status' => 'error', 'message' => 'Failed to save form data.');
        }
    } else {
        // Missing all required data (first name, last name, company name, sent from, or email)
        return array('status' => 'error', 'message' => 'At least one field is required.');
    }
}

// Ensure that the database table is created when the plugin is activated
my_custom_plugin_activate();
