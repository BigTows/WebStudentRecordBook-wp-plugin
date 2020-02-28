<?php


namespace WebStudentRecordBook\Api;


use StudentUtility\Repository\Meta\StudentRecordBook;
use StudentUtility\Repository\StudentMetaRepositoryInterface;
use WP_REST_Controller;
use WP_REST_Response;
use WP_REST_Server;

class SaveRecordBookController extends WP_REST_Controller
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
        register_rest_route('my-plugin/v1', '/save', [
            'methods'  => WP_REST_Server::READABLE,
            'callback' => [$this, 'get_items'],
        ]);
    }


    public function get_items($data)
    {
        try {
            $data = StudentRecordBook::unserialize(json_encode(['academicYearList' => current($_GET['recordBook'])]));
            if ($data instanceof StudentRecordBook) {
                $student = $this->studentMetaRepository->getStudentByStudentId((int)$_GET['studentId']);
                $this->studentMetaRepository->save($student->setStudentRecordBook($data));
            }
            return $this->createResponse(['Code' => 0, 'Message' => ''], 200);
        } catch (\Throwable $e) {
            return $this->createResponse(['Code' => 2, 'Message' => $e->getMessage()], 500);
        }
    }

    private function createResponse($data, $statusCode): WP_REST_Response
    {
        $response = new WP_REST_Response($data, $statusCode);
        // Set headers.
        $response->set_headers(['Cache-Control' => 'must-revalidate, no-cache, no-store, private']);
        return $response;
    }

}