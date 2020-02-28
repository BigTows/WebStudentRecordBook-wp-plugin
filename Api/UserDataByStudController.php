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
        $studentId = $_GET['studentId'];
        if (!is_numeric($studentId)) {
            $responseData['code'] = 1;
            return $this->createResponse($responseData);
        }
        $studentMeta = $this->studentMetaRepository->getStudentByStudentId((int)$studentId);
        if ($studentMeta === null) {
            $responseData['code'] = 1;
            return $this->createResponse($responseData);
        }
        $student = get_user_by('id', $studentMeta->getUserId());
        if ($student === false || current_user_can('administrator') === false) {
            $responseData['code'] = 1;
        } else {
            $responseData['uid'] = $studentMeta->getUserId();
            $responseData['firstName'] = $student->first_name;
            $responseData['secondName'] = $student->last_name;
            $responseData['studentId'] = (int)$studentId;
            $responseData['recordBook'] = $studentMeta->getStudentRecordBook() === null ? [] : json_decode($studentMeta->getStudentRecordBook()->serialize(), true, 512, JSON_THROW_ON_ERROR);
            $responseData['code'] = 0;
        }
        return $this->createResponse($responseData);
    }

    private function createResponse($data): WP_REST_Response
    {
        $response = new WP_REST_Response($data, 200);
        // Set headers.
        $response->set_headers(['Cache-Control' => 'must-revalidate, no-cache, no-store, private']);
        return $response;
    }

}