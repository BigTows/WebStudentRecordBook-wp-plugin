<?php


namespace WebStudentRecordBook\Controller;


use StudentUtility\Repository\StudentMetaRepositoryInterface;
use WP_REST_Controller;
use WP_REST_Response;
use WP_REST_Server;

/**
 * Controller for getting all student ID's
 *
 * @package WebStudentRecordBook\Controller
 */
final class GetAllStudentIdController extends WP_REST_Controller
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
            '/getAllStudentId',
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
        if (current_user_can('administrator') === false) {
            $response = $this->createResponse(1, 'Permission denied');
        } else {
            $response = $this->createResponse(0, '', $this->studentMetaRepository->getAllStudentId());
        }

        return $response;
    }

    /**
     * Create response by data
     *
     * @param int    $code
     * @param string $message
     *
     * @param array  $data
     *
     * @return WP_REST_Response
     */
    private function createResponse(int $code, string $message = '', $data = []): WP_REST_Response
    {
        $response = new WP_REST_Response(
            [
                'code'    => $code,
                'message' => $message,
                'data'    => $data
            ], 200
        );
        $response->set_headers(['Cache-Control' => 'must-revalidate, no-cache, no-store, private']);

        return $response;
    }

}