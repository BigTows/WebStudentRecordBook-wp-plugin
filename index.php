<?php
/*
Plugin Name: Web Student Record Book
Plugin URI: https://github.com/BigTows/WebStudentRecordBook-wp-plugin
Description: Plugin for view/edit student record book.
Author: Alexander @BigTows Chapchuk
Version: 1.0
Author URI: bigtows.org
License: MIT
*/

namespace WebStudentRecordBook;

use StudentUtility\API;
use WebStudentRecordBook\Api\SaveRecordBookController;
use WebStudentRecordBook\Api\UserDataByStudController;
use WebStudentRecordBook\Menu\AdminMenu;
use WebStudentRecordBook\Widget\WebStudentRecordBookWidget;

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

if (is_plugin_active('StudentUtility-wp-plugin/index.php')) {
    require 'Api/UserDataByStudController.php';
    require 'Api/SaveRecordBookController.php';
    require 'Widget/WebStudentRecordBookWidget.php';
    require 'Menu/AdminMenu.php';
    new WebStudentRecordBookWidget(API::getApiInstance());
    new AdminMenu();
    new UserDataByStudController(API::getApiInstance()->getRepository());
    new SaveRecordBookController(API::getApiInstance()->getRepository());
}