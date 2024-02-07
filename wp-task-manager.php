<?php
/**
* Plugin Name: WP Task Manager
* Plugin URI: 
* Description: Simple Task Manager plugin
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
        80
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
    
    wp_enqueue_style('wp_tm_bootstrap', '//cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css', null, false, 'all');

    wp_enqueue_style( 'wp_tm-style', plugin_dir_url( __FILE__ ) . 'build/index.css' );
    wp_enqueue_script( 'wp_tm-script', plugin_dir_url( __FILE__ ) . 'build/index.js', array( 'wp-element' ), '1.0.0', true );
}
add_action( 'admin_enqueue_scripts', 'wp_tm_admin_enqueue_scripts' );