# WordPress Form to Database Plugin

This WordPress plugin, named "Form to Database," handles form data and database operations.

## Installation

1. **Download the plugin zip file.**
2. **Upload the zip file via the WordPress admin dashboard or extract it into your WordPress plugins directory.

## Plugin Details

- **Plugin Name:** Form to Database
- **Description:** This plugin handles form data and database operations.
- **Version:** 1.811
- **Author:** Akashdeep Singh

## Database Table Creation

The plugin creates and manages a database table named `form_data` with the following columns:

- ID (mediumint, AUTO_INCREMENT)
- First Name (varchar)
- Last Name (varchar)
- Company Name (varchar)
- Sent From (varchar)
- Email (varchar)
- Job Title (varchar)
- Country (varchar)
- Message (varchar)
- Time Stamp (varchar)

## Activation and Deactivation Hooks

The plugin utilizes activation and deactivation hooks for database table setup and removal. The deactivation hook is currently empty.

## Usage

1. **Database Table Activation:**
   - When the plugin is activated, it checks for the existence of the `form_data` table.
   - If the table does not exist, it creates the table with the required columns.
   - If the table already exists, it checks for missing columns and adds them.

2. **Form Data Handling:**
   - The plugin includes a filter hook `wcra_form_data_callback` to handle form data received from an external source (API).
   - The data is sanitized and inserted into the `form_data` table in the WordPress database.

## Note

- Ensure that the 'form_data' table exists in your WordPress database with the specified columns.
- This plugin assumes the existence of a 'form_data' table and is designed to handle form data from an external API.

Feel free to customize the plugin according to your specific database structure and requirements.

**Happy form data handling with WordPress!**
