<?php


namespace WebStudentRecordBook\Api;


use WP_REST_Controller;
use WP_REST_Response;
use WP_REST_Server;

class RestController extends WP_REST_Controller
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'rest']);
    }

    public function rest()
    {
        // Register route.
        register_rest_route('my-plugin/v1', '/uid', [
            'methods'  => WP_REST_Server::READABLE,
            'callback' => [$this, 'get_items'],
        ]);
    }


    public function get_items($data)
    {
        // Get current user ID.
        $datas = [
            'uid' => get_current_user_id(),
            'a'=>wp_get_current_user()->display_name
        ];

        $response = new WP_REST_Response($datas, 200);
        // Set headers.
        $response->set_headers(['Cache-Control' => 'must-revalidate, no-cache, no-store, private']);

        return $response;
    }


}

new RestController();