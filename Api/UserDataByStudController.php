<?php


namespace WebStudentRecordBook\Api;


use StudentUtility\Repository\StudentMetaRepositoryInterface;
use WP_REST_Controller;
use WP_REST_Response;
use WP_REST_Server;

class UserDataByStudController extends WP_REST_Controller
{
    private $studentMetaRepository;

    public function __construct(StudentMetaRepositoryInterface $studentMetaRepository)
    {
        $this->studentMetaRepository = $studentMetaRepository;
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
//        $datas = [
//            'uid' => get_current_user_id(),
//            'a'   => $_GET['stud']
//        ];

        $datas = [];

        $stud = $_GET['stud'];
//        $this->studentMetaRepository->getStudentByStudentId($stud);
        $user_id = 1;
        $user_id = null;

        if ( !current_user_can('administrator') && $user_id == null) {
            $datas["status"] = 1;
        } else {
            $datas["uid"] = get_current_user_id();
            $datas["firstName"] = wp_get_current_user()->first_name;
            $datas["secondName"] = wp_get_current_user()->last_name;
            $datas["status"] = 0;
        }

        $response = new WP_REST_Response($datas, 200);
        // Set headers.
        $response->set_headers(['Cache-Control' => 'must-revalidate, no-cache, no-store, private']);
        return $response;
    }

}