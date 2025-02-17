<?php
if(!function_exists('awpa_create_form_builder_table')){
	add_action('init', 'awpa_create_form_builder_table');
	function awpa_create_form_builder_table(){
		if (current_user_can('edit_posts')) {
			global $wpdb;
			$table_name = $wpdb->prefix . "wpa_form_builder";
			$charset_collate = $wpdb->get_charset_collate();
			if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
				$sql = "CREATE TABLE IF NOT EXISTS $table_name (
							id bigint(20) NOT NULL AUTO_INCREMENT,
							post_author bigint(20) UNSIGNED NOT NULL,
							post_title text NOT NULL,
							post_content longtext NOT NULL,
							form_settings longtext NOT NUll,
							payment_data longtext NOT NULL,
							social_login_setting longtext NULL,
							other_settings longtext NULL,
							post_status varchar(20) NOT NULL,
							post_date datetime NOT NULL,
							post_date_gmt datetime NOT NULL,
							post_modified datetime NOT NULL,
							post_modified_gmt datetime NOT NULL,
							editable TINYINT(1) NOT NULL DEFAULT '1',
							PRIMARY KEY id (id)
						) $charset_collate;";
				require_once ABSPATH . 'wp-admin/includes/upgrade.php';
				dbDelta($sql);
			}
		}	
	}
}
if(!function_exists('awpa_create_subscriptions_table')){
	add_action('init', 'awpa_create_subscriptions_table');
	function awpa_create_subscriptions_table(){
		if (current_user_can('edit_posts')) {
			global $wpdb;
			$table_name = $wpdb->prefix . "wpa_subscriptions";
			$charset_collate = $wpdb->get_charset_collate();
			if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
				$sql = "CREATE TABLE IF NOT EXISTS $table_name (
							id bigint(20) NOT NULL AUTO_INCREMENT,
							user_id bigint(20) NOT NULL,
							plan_name text NULL,
							plan_id longtext NULL,
							status text NOT NULL,
							gateway text NOT NULL,
							membership_type text NOT NULL,
							quantity int NOT NULL,
							starts_from datetime NOT NULL,
							trial_ends_at datetime NULL,
							ends_at datetime NULL,
							created_at datetime NULL,
							updated_at datetime NULL,
							PRIMARY KEY id (id)
							) $charset_collate;";
				require_once ABSPATH . 'wp-admin/includes/upgrade.php';
				dbDelta($sql);
			}
		}
	}
}

if(!function_exists('awpa_create_guest_authors_table')){
add_action('init', 'awpa_create_guest_authors_table');
	function awpa_create_guest_authors_table(){
		if (current_user_can('edit_posts')) {
			global $wpdb;
			$table_name = $wpdb->prefix . "wpa_guest_authors";
			$charset_collate = $wpdb->get_charset_collate();
			if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
				$sql = "CREATE TABLE IF NOT EXISTS $table_name (
					id bigint(20) NOT NULL AUTO_INCREMENT,
					user_email text NOT NULL,
					display_name text NOT NULL,
					user_nicename text NOT NULL,
					first_name text NOT NULL,
					last_name text NOT NULL,
					description text NULL,
					user_registered datetime NOT NULL,
					website text NULL,
					is_active integer NOT NULL,
					user_meta text NULL,
					is_linked tinyInt(1) NOT NULL,
					avatar_name text NULL,
					linked_user_id bigInt(20) NULL,
					PRIMARY KEY id (id)
					) $charset_collate;";
				require_once ABSPATH . 'wp-admin/includes/upgrade.php';
				$data = dbDelta($sql);
			}
			do_action('awpa_call_seeder_function');
		}
	}
}
if(!function_exists('awpa_add_columns_tables')){
	add_action('init', 'awpa_add_columns_tables');
	function awpa_add_columns_tables(){
		global $wpdb;
		$wpa_guest_authors = $wpdb->prefix . "wpa_guest_authors";


		$row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$wpa_guest_authors' AND TABLE_SCHEMA = '$wpdb->dbname' AND COLUMN_NAME = 'nice_name'");
		if ($row) {
			$wpdb->query("ALTER TABLE $wpa_guest_authors CHANGE COLUMN `nice_name` user_nicename varchar(255)");
			
		}

		$column_name = 'rating_meta';
		$column_definition = 'VARCHAR(255) NOT NULL DEFAULT ""';
// Prepare the SQL query to check if the column exists
$column_check_query = $wpdb->prepare("SHOW COLUMNS FROM $wpa_guest_authors LIKE %s", $column_name);

// Run the query and get the result
$column_exists = $wpdb->get_var($column_check_query);

	// If the column doesn't exist, add it to the table
    if (!$column_exists) {		
        $alter_table_query = "ALTER TABLE $wpa_guest_authors ADD COLUMN $column_name $column_definition";
        $wpdb->query($alter_table_query);
    }
	
		
	}
}