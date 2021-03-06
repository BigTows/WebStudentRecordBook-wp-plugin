<?php
/*
Plugin Name: Web Student Record Book
Plugin URI: https://github.com/BigTows/WebStudentRecordBook-wp-plugin
Description: Plugin for view/edit student record book.
Author: Alexander @BigTows Chapchuk, Maxim @SphinxKingStone Mishakov, Igor @Igor-Tychinin Tychinin
Version: 1.2
Author URI: bigtows.org
License: MIT
Requires PHP: 7.3
*/

namespace WebStudentRecordBook;

use StudentUtility\API;
use WebStudentRecordBook\Controller\GetAllStudentIdController;
use WebStudentRecordBook\Controller\SaveRecordBookController;
use WebStudentRecordBook\Controller\UserDataByStudController;
use WebStudentRecordBook\Menu\WebStudentRecordBookAdminMenu;
use WebStudentRecordBook\Widget\WebStudentRecordBookWidget;

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

const LOCALE_DOMAIN = 'WebStudentRecordBook';
/**
 * Check status of plugin
 *
 * @param string $name plugin name
 *
 * @return bool if true then plugin is active else false
 */
function isPluginActive(string $name): bool
{
    if (empty($name)) {
        return false;
    }
    foreach (get_plugins() as $key => $pluginMeta) {
        if (isset($pluginMeta['Name']) && $pluginMeta['Name'] === $name) {
            return is_plugin_active($key);
        }
    }

    return false;
}

if (isPluginActive('Student Utility')) {
    require 'Controller/UserDataByStudController.php';
    require 'Controller/SaveRecordBookController.php';
    require 'Controller/GetAllStudentIdController.php';
    require 'Widget/WebStudentRecordBookWidget.php';
    require 'Menu/WebStudentRecordBookAdminMenu.php';
    load_plugin_textdomain(LOCALE_DOMAIN, false, dirname(plugin_basename(__FILE__)) . '/language');
    new WebStudentRecordBookWidget(API::getApiInstance());
    new WebStudentRecordBookAdminMenu();
    new UserDataByStudController(API::getApiInstance()->getRepository());
    new SaveRecordBookController(API::getApiInstance()->getRepository());
    new GetAllStudentIdController(API::getApiInstance()->getRepository());
}