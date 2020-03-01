<?php


namespace WebStudentRecordBook\Controller;


use RuntimeException;
use StudentUtility\Repository\Meta\StudentRecordBook;
use StudentUtility\Repository\StudentMetaRepositoryInterface;
use WP_REST_Controller;
use WP_REST_Response;
use WP_REST_Server;

class SaveRecordBookController extends WP_REST_Controller
{
    private $studentMetaRepository;

    /**
     * SaveRecordBookController constructor.
     *
     * @param StudentMetaRepositoryInterface $studentMetaRepository
     *
     * @uses initRestRoute
     */
    public function __construct(StudentMetaRepositoryInterface $studentMetaRepository)
    {
        $this->studentMetaRepository = $studentMetaRepository;
        add_action('rest_api_init', [$this, 'initRestRoute']);
    }

    /**
     * Initialize rest route
     */
    public function initRestRoute(): void
    {
        // Register route.
        register_rest_route('WebStudentRecordBook', '/save', [
            'methods'  => WP_REST_Server::READABLE,
            'callback' => [$this, 'get_items'],
        ]);
    }


    public function get_items($data)
    {
        $studentId = (int)$_GET['studentId'];
        try {
            $student = $this->studentMetaRepository->getStudentByStudentId($studentId);
            if ($student === null) {
                throw new RuntimeException("Student with student ID {$studentId} not found");
            }
            $studentRecordBook = StudentRecordBook::unserialize(json_encode(['academicYearList' => current($_GET['recordBook'])], JSON_THROW_ON_ERROR, 512));
            if ($studentRecordBook instanceof StudentRecordBook) {
                $student = $this->studentMetaRepository->getStudentByStudentId((int)$_GET['studentId']);
                $this->studentMetaRepository->save($student->setStudentRecordBook($studentRecordBook));
            }
            return $this->createResponse(['Code' => 0, 'Message' => ''], 200);
        } catch (\Throwable $e) {
            return $this->createResponse(['Code' => 2, 'Message' => $e->getMessage()], 200);
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