<?php


namespace WebStudentRecordBook\Widget;


use StudentUtility\API as APIStudentUtility;
use WP_Widget;

/**
 * Widget of Student record book
 *
 * @package WebStudentRecordBook\Widget
 */
final class WebStudentRecordBookWidget extends WP_Widget
{
    /**
     * @var APIStudentUtility
     */
    private $apiStudentUtility;

    /**
     * WebStudentRecordBookWidget constructor.
     *
     * @param APIStudentUtility $apiStudentUtility
     *
     * @uses registerWidget
     */
    public function __construct(APIStudentUtility $apiStudentUtility)
    {
        $widget_options = [
            'classname'   => __CLASS__,
            'description' => 'Просмотр оценок',
        ];
        parent::__construct('WebStudentRecordBookWidget', 'Электронная зачетка', $widget_options);
        add_action('widgets_init', [$this, 'registerWidget']);
        $this->apiStudentUtility = $apiStudentUtility;
    }

    /**
     * {@inheritDoc}
     */
    public function form($instance)
    {
        include 'template/settingsWidget.phtml';
    }

    /**
     * Register widget into WordPress
     */
    public function registerWidget(): void
    {
        register_widget($this);
    }


    /**
     * {@inheritDoc}
     */
    public function widget($args, $instance): void
    {
        echo $args['before_widget'] . $args['before_title'] . $args['after_title'];
        $studentMeta = $this->apiStudentUtility->getRepository()->getByUserId(get_current_user_id());
        if ($studentMeta->getNumberOfStudentCard() !== null && $studentMeta->getStudentRecordBook() !== null) {
            $data = [
                'numberOfStudentCard' => $studentMeta->getNumberOfStudentCard(),
                'recordBook'          => $studentMeta->getStudentRecordBook()
            ];
            include 'template/templateWidget.phtml';
        } else {
            include 'template/notValidStundetTemplateWidget.phtml';
        }


        echo $args['after_widget'];
    }
}