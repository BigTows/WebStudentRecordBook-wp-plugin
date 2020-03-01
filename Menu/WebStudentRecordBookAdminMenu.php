<?php

namespace WebStudentRecordBook\Menu;

/**
 * Class for initialize block into admin menu
 *
 * @package WebStudentRecordBook\Menu
 */
final class WebStudentRecordBookAdminMenu
{
    /**
     * WebStudentRecordBookAdminMenu constructor.
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'init']);
    }

    /**
     * Initialize into workflow wordpress.
     */
    public function init(): void
    {
        add_menu_page('Электронная зачетка', 'Электронная зачетка', 'manage_options', 'student-record-book.php', [$this, 'render'], 'dashicons-book', 100);
    }

    /**
     * Render web student record book admin menu
     */
    public function render(): void
    {
        require 'template/WebStudentRecordBookAdminMenuTemplate.phtml';
    }
}