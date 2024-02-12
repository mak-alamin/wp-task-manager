<?php

namespace WpTaskManager;

class RestApi
{
    public function register()
    {
        add_action('rest_api_init', array($this, 'get_single_task_endpoint'));
        add_action('rest_api_init', array($this, 'get_all_tasks_endpoint'));
        add_action('rest_api_init', array($this, 'create_task_endpoint'));
        add_action('rest_api_init', array($this, 'update_single_task_endpoint'));
        add_action('rest_api_init', array($this, 'delete_task_endpoint'));
    }

    // REST API endpoint for fetching a single task
    public function get_single_task_endpoint()
    {
        register_rest_route(
            'wptm/v1',
            '/get-task/(?P<id>\d+)',
            array(
                'methods'  => 'GET',
                'callback' => array($this, 'get_single_task_callback'),
                'permission_callback' => array($this, 'verify_admin_request' ),
                'args' => array(
                    'id' => array(
                        'validate_callback' => function ($param, $request, $key) {
                            return is_numeric($param);
                        }
                    ),
                ),
            )
        );
    }

    // Callback function for fetching a single task
    public function get_single_task_callback($data)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'wptm_tasks';
        $task_id = $data['id'];

        // Retrieve the task from the database
        $task = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $task_id), ARRAY_A);

        // Check if the task exists
        if ($task) {
            return new \WP_REST_Response($task, 200);
        } else {
            return new \WP_Error('not_found', 'Task not found', array('status' => 404));
        }
    }


    // REST API endpoint for fetching all tasks
    public function get_all_tasks_endpoint()
    {
        register_rest_route(
            'wptm/v1',
            '/get-tasks',
            array(
                'methods'  => 'GET',
                'callback' => array($this, 'get_all_tasks_callback'),
                'permission_callback' => array($this, 'verify_admin_request' ),
            )
        );
    }

    // Callback function for fetching all tasks
    public function get_all_tasks_callback()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'wptm_tasks';

        // Retrieve all tasks from the database
        $tasks = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

        // Check if tasks were found
        if ($tasks) {
            return new \WP_REST_Response($tasks, 200);
        } else {
            return new \WP_Error('no_tasks', 'No tasks found', array('status' => 404));
        }
    }

    // REST API endpoint for creating a new task
    public function create_task_endpoint()
    {
        register_rest_route(
            'wptm/v1',
            '/create-task/',
            array(
                'methods'  => 'POST',
                'callback' => array($this, 'create_task_callback'),
                'permission_callback' => array($this, 'verify_admin_request' ),
            )
        );
    }

    // Callback function for creating a new task
    public function create_task_callback($request)
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

    // REST API endpoint for updating a single task
    public function update_single_task_endpoint()
    {
        register_rest_route(
            'wptm/v1',
            '/update-task/(?P<id>\d+)',
            array(
                'methods'  => 'POST',
                'callback' => array($this, 'update_single_task_callback'),
                'permission_callback' => array($this, 'verify_admin_request' ),
                'args' => array(
                    'id' => array(
                        'validate_callback' => function ($param, $request, $key) {
                            return is_numeric($param);
                        }
                    ),
                ),
            )
        );
    }

    // Callback function for updating a single task
    public  function update_single_task_callback($data)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'wptm_tasks';
        $task_id = $data['id'];

        // Check if the task exists
        $existing_task = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $task_id));

        if (!$existing_task) {
            return new \WP_Error('not_found', 'Task not found', array('status' => 404));
        }

        // Get updated data from the request
        $title       = sanitize_text_field($data['title']);
        $description = sanitize_text_field($data['description']);
        $duration    = intval($data['duration']);
        $status      = sanitize_text_field($data['status']);

        // Update the task in the database
        $wpdb->update(
            $table_name,
            array(
                'title'       => $title,
                'description' => $description,
                'duration'    => $duration,
                'status'      => $status,
            ),
            array('id' => $task_id),
            array('%s', '%s', '%d', '%s'),
            array('%d')
        );

        return new \WP_REST_Response(array(
            'success' => true,
            'message' => 'Task updated successfully'
        ), 200);
    }

    // REST API endpoint for deleting a task
    public function delete_task_endpoint()
    {
        register_rest_route(
            'wptm/v1',
            '/delete-task/(?P<id>\d+)',
            array(
                'methods'  => 'DELETE',
                'callback' => array($this, 'delete_task_callback'),
                'permission_callback' => array($this, 'verify_admin_request' ),
                'args' => array(
                    'id' => array(
                        'validate_callback' => function ($param, $request, $key) {
                            return is_numeric($param);
                        }
                    ),
                ),
            )
        );
    }

    // Callback function for deleting a task
    public function delete_task_callback($data)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'wptm_tasks';
        $task_id = $data['id'];

        // Check if the task exists
        $existing_task = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $task_id));

        if (!$existing_task) {
            return new \WP_Error('not_found', 'Task not found', array('status' => 404));
        }

        // Delete the task from the database
        $wpdb->delete($table_name, array('id' => $task_id));

        return new \WP_REST_Response(array('message' => 'Task deleted successfully'), 200);
    }

    public function verify_admin_request($request)
    {
        $headers = $request->get_headers();

        $_nonce = isset($headers['x_wp_nonce']) ? $headers['x_wp_nonce'][0] : '';

        if (!wp_verify_nonce($_nonce, 'wp_rest')) {
            return false;
        }

        return true;
    }
}
