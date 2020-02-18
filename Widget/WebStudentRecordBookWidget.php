<?php


namespace WebStudentRecordBook\Widget;


use DateTime;
use StudentUtility\API as APIStudentUtility;
use StudentUtility\Repository\Meta\RecordBook\AcademicYear;
use StudentUtility\Repository\Meta\RecordBook\Discipline;
use StudentUtility\Repository\Meta\RecordBook\Semester;
use StudentUtility\Repository\Meta\StudentRecordBook;
use WP_Widget;

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

    public function form($instance)
    {
        $title = @ $instance['title'] ?: 'Заголовок по умолчанию';

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }

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
        $studentMeta->setStudentRecordBook(new StudentRecordBook([
            new AcademicYear('2019/2020', [
                new Semester(1, [
                    (new Discipline('Kazach', 'Экзамен', 200))
                        ->setTeacher('Азаркин')
                        ->setRating(2)
                        ->setResultDate(new DateTime())
                    ,
                    new Discipline('Математика', 'Экзамен', 200),
                    new Discipline('Kazach', 'Экзамен', 200),
                    new Discipline('Kazach', 'Экзамен', 200),
                ]),
                new Semester(2, [
                    new Discipline('Kazach', 'Экзамен', 200),
                    new Discipline('Kazach', 'Экзамен', 200),
                    new Discipline('Kazach', 'Экзамен', 200),
                    new Discipline('Kazach', 'Экзамен', 200),
                ])
            ])
        ]));
        $this->apiStudentUtility->getRepository()->save($studentMeta);
        $data = [
            'numberOfStudentCard' => $studentMeta->getNumberOfStudentCard(),
            'recordBook'          => $studentMeta->getStudentRecordBook()
        ];
        include 'template/templateWidget.phtml';


        echo $args['after_widget'];
    }
}