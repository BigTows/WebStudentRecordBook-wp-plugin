<?php


namespace WebStudentRecordBook\Controller;


use StudentUtility\Repository\StudentMetaRepositoryInterface;
use WP_REST_Controller;
use WP_REST_Response;
use WP_REST_Server;

/**
 * Controller for getting student information about student by student ID
 *
 * @package WebStudentRecordBook\Controller
 */
final class UserDataByStudController extends WP_REST_Controller
{
    /**
     * Repository of student meta
     *
     * @var StudentMetaRepositoryInterface
     */
    private $studentMetaRepository;

    /**
     * UserDataByStudController constructor.
     *
     * @param StudentMetaRepositoryInterface $studentMetaRepository
     */
    public function __construct(StudentMetaRepositoryInterface $studentMetaRepository)
    {
        $this->studentMetaRepository = $studentMetaRepository;
        add_action('rest_api_init', [$this, 'rest']);
    }

    /**
     * Init rest endpoint
     */
    public function rest(): void
    {
        register_rest_route(
            'WebStudentRecordBook',
            '/getStudentDataByStudentId',
            [
                'methods'  => WP_REST_Server::READABLE,
                'callback' => [$this, 'get_items'],
            ]
        );
    }

    /**
     * {@inheritDoc}
     */
    public function get_items($data)
    {
        $studentId = $_GET['studentId'];
        if ( ! is_numeric($studentId)) {
            $responseData['Code'] = 1;

            return $this->createResponse($responseData);
        }
        $studentMeta = $this->studentMetaRepository->getStudentByStudentId((int)$studentId);
        if ($studentMeta === null) {
            $responseData['Code']    = 1;
            $responseData['Message'] = "Student with StudentID: {$studentId}, not found.";

            return $this->createResponse($responseData);
        }
        $student = get_user_by('id', $studentMeta->getUserId());
        if ($student === false || current_user_can('administrator') === false) {
            $responseData['Code'] = 1;
        } else {
            $responseData['uid']        = $studentMeta->getUserId();
            $responseData['firstName']  = $student->first_name;
            $responseData['secondName'] = $student->last_name;
            $responseData['studentId']  = (int)$studentId;
            $responseData['recordBook'] = $studentMeta->getStudentRecordBook() === null ? [] : json_decode(
                $studentMeta->getStudentRecordBook()->serialize(),
                true,
                512,
                JSON_THROW_ON_ERROR
            );
            $responseData['Code']       = 0;
        }

        return $this->createResponse($responseData);
    }

    /**
     * Create response by data
     *
     * @param $data
     *
     * @return WP_REST_Response
     */
    private function createResponse($data): WP_REST_Response
    {
        $response = new WP_REST_Response($data, 200);
        $response->set_headers(['Cache-Control' => 'must-revalidate, no-cache, no-store, private']);

        return $response;
    }

}