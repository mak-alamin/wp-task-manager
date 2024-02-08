<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// REST API endpoint for fetching all tasks
add_action('rest_api_init', 'wptm_get_all_tasks_endpoint');
function wptm_get_all_tasks_endpoint() {
    register_rest_route(
        'wptm/v1',
        '/get-tasks',
        array(
            'methods'  => 'GET',
            'callback' => 'wptm_get_all_tasks_callback',
            'permission_callback' => '__return_true',
            // 'permission_callback' => function () {
            //     return current_user_can('edit_posts'); // Adjust the capability as needed
            // },
        )
    );
}

// Callback function for fetching all tasks
function wptm_get_all_tasks_callback() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'wptm_tasks';

    // Retrieve all tasks from the database
    $tasks = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

    // Check if tasks were found
    if ($tasks) {
        return new WP_REST_Response($tasks, 200);
    } else {
        return new WP_Error('no_tasks', 'No tasks found', array('status' => 404));
    }
}

// REST API endpoint for creating task
add_action('rest_api_init', 'wptm_create_task_endpoint');
function wptm_create_task_endpoint()
{
    register_rest_route(
        'wptm/v1',
        '/create-task/',
        array(
            'methods'  => 'POST',
            'callback' => 'wptm_create_task_callback',
            'permission_callback' => '__return_true',
            // 'permission_callback' => function () {
            //     return current_user_can('manage_options');
            // },
        )
    );
}

// Callback function for creating a new task
function wptm_create_task_callback($request)
{
    global $wpdb;

    // Get request parameters
    $params = $request->get_params();

    // Sanitize and validate input data
    $title       = sanitize_text_field($params['title']);
    $description = sanitize_text_field($params['description']);
    $duration    = intval($params['duration']);
    $status      = sanitize_text_field($params['status']);

    // Insert data into the database
    $table_name = $wpdb->prefix . 'wptm_tasks';
    $wpdb->insert(
        $table_name,
        array(
            'title'       => $title,
            'description' => $description,
            'duration'    => $duration,
            'status'      => $status,
        ),
        array('%s', '%s', '%d', '%s')
    );

    // Check if the task was successfully created
    if ($wpdb->insert_id) {
        return new \WP_REST_Response(array(
            'success' => true,
            'message' => 'Task created successfully'
        ), 200);
    } else {
        return new \WP_Error('error', 'Failed to create task', array('status' => 500));
    }
}
