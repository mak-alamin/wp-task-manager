<?php

add_action('rest_api_init', 'wptm_create_task_endpoint');

// Register the REST API endpoint
function wptm_create_task_endpoint()
{
    register_rest_route(
        'wptm/v1',
        '/create-task',
        array(
            'methods'  => 'POST',
            'callback' => 'wptm_create_task_callback',
            'permission_callback' => function () {
                return current_user_can('manage_options');
            },
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
        return new \WP_REST_Response(array('message' => 'Task created successfully'), 200);
    } else {
        return new \WP_Error('error', 'Failed to create task', array('status' => 500));
    }
}