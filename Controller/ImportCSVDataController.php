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
final class ImportCSVDataController extends WP_REST_Controller
{
    /**
     * Repository of student meta
     *
     * @var StudentMetaRepositoryInterface
     */
    private $studentMetaRepository;

    /**
     * ImportCSVDataController constructor.
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
            '/importCsv',
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
        } elseif (empty($_GET['csv'])) {
            $response = $this->createResponse(2, 'Invalid data');
        } else {
            $response = $this->parseCsv($_GET['csv']);
        }

        return $response;
    }

    private function parseCsv($csv): WP_REST_Response
    {
        return $this->createResponse(1, json_encode(str_getcsv($csv, ';'), JSON_THROW_ON_ERROR, 512));
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