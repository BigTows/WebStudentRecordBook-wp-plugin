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
use WebStudentRecordBook\Widget\WebStudentRecordBookWidget;

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

if (is_plugin_active('StudentUtility-wp-plugin/index.php')) {
    require 'Widget/WebStudentRecordBookWidget.php';
    new WebStudentRecordBookWidget(API::getApiInstance());
}