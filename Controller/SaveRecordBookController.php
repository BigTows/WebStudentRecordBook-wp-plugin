<?php


namespace WebStudentRecordBook\Controller;


use RuntimeException;
use StudentUtility\Repository\Meta\StudentRecordBook;
use StudentUtility\Repository\StudentMetaRepositoryInterface;
use Throwable;
use WP_REST_Controller;
use WP_REST_Response;
use WP_REST_Server;

/**
 * Controller for saving record book of student
 *
 * @package WebStudentRecordBook\Controller
 */
final class SaveRecordBookController extends WP_REST_Controller
{
    /**
     * Repository of student meta
     *
     * @var StudentMetaRepositoryInterface
     */
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
        register_rest_route('WebStudentRecordBook', '/save', [
            'methods'  => WP_REST_Server::READABLE,
            'callback' => [$this, 'get_items'],
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function get_items($data)
    {
        $studentId = (int)$_GET['studentId'];
        try {
            $student = $this->studentMetaRepository->getStudentByStudentId($studentId);
            if ($student === null) {
                throw new RuntimeException("Студент с номером зачетной книжки: {$studentId} не найден");
            }
            $studentRecordBook = StudentRecordBook::unserialize(json_encode(['academicYearList' => current($_GET['recordBook'])], JSON_THROW_ON_ERROR, 512));
            if ($studentRecordBook instanceof StudentRecordBook) {
                $student = $this->studentMetaRepository->getStudentByStudentId((int)$_GET['studentId']);
                $this->studentMetaRepository->save($student->setStudentRecordBook($studentRecordBook));
            }
            return $this->createResponse(['Code' => 0, 'Message' => ''], 200);
        } catch (Throwable $e) {
            return $this->createResponse(['Code' => 2, 'Message' => $e->getMessage()], 200);
        }
    }

    /**
     * Create response based on data and Http code
     *
     * @param     $data
     * @param int $statusCode http code
     *
     * @return WP_REST_Response
     */
    private function createResponse($data, int $statusCode): WP_REST_Response
    {
        $response = new WP_REST_Response($data, $statusCode);
        $response->set_headers(['Cache-Control' => 'must-revalidate, no-cache, no-store, private']);
        return $response;
    }

}