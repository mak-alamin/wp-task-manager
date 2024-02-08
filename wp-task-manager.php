<?php
/**
* Plugin Name: WP Task Manager
* Plugin URI: 
* Description: Simple Task Manager WP plugin
* Version: 1.0.0
* Requires at least: 5.2
* Requires PHP: 7.2
* Author: Mak Alamin
* Author URI:
* License: GPL v2 or later
* License URI: https: //www.gnu.org/licenses/gpl-2.0.html
* Update URI: 
* Text Domain: wp-task-manager
* Domain Path: /languages
*/ 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Require Rest Api File
if(file_exists( __DIR__ . '/includes/rest-api.php')){
    require_once __DIR__ . '/includes/rest-api.php';
}

/**
 * Add a top-level admin menu named "Tasks"
 *
 * @return void
 */
function wp_tm_create_tasks_menu() {
    add_menu_page(
        'Tasks',         
        'Tasks',         
        'manage_options', 
        'wp_task_manager', 
        'wp_tm_tasks_menu_page', 
        'dashicons-list-view', 
        50
    );
}
add_action('admin_menu', 'wp_tm_create_tasks_menu');

// Callback function to display the menu page
function wp_tm_tasks_menu_page() {
    echo '<div id="wp_task_manager" class="wrap"></div>';
}


/**
 * Enqueue scripts and styles.
 *
 * @return void
 */
function wp_tm_admin_enqueue_scripts($hook) {
    if('toplevel_page_wp_task_manager' != $hook){
        return;
    }
    
    wp_enqueue_style('mak_wptm_bootstrap', '//cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css', null, false, 'all');

    
    wp_enqueue_style( 'mak_wptm-style', plugin_dir_url( __FILE__ ) . 'build/index.css' );

    wp_enqueue_script('mak_wptm_bootstrap', '//cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js', array('jquery'), false, true);

    wp_enqueue_script( 'mak_wptm-script', plugin_dir_url( __FILE__ ) . 'build/index.js', array( 'wp-element' ), '1.0.0', true );

    wp_localize_script('mak_wptm-script', 'makWPtmData', array(
        'restRoot' => get_rest_url(),
        'nonce' => wp_create_nonce('wp_rest')
    ) );
}
add_action( 'admin_enqueue_scripts', 'wp_tm_admin_enqueue_scripts' );


/**
 * Create the tasks table in database
 *
 * @return void
 */
function create_wptm_tasks_table() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'wptm_tasks';

        // SQL query to create the table
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id INT NOT NULL AUTO_INCREMENT,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            duration INT,
            status VARCHAR(20),
            PRIMARY KEY (id)
        )";

        // Include the upgrade.php file for dbDelta function
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        // Execute the SQL query
        dbDelta($sql);
}
register_activation_hook( __FILE__, 'create_wptm_tasks_table' );
