<?php
header('Content-Type: text/html; charset=utf-8');

// Temporarily display all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Backend
require_once('../backend/server.php');
// Localization
require_once('../backend/localization/'. LANGUAGE . '.php');

$page = (isset($_GET["page"])) ? $_GET["page"] : '' ;
$id = (isset($_GET["id"])) ? $_GET["id"] : '' ;

// Router
switch ($page) {
    case '':
        page__block_header();
        page__block_menu();
        page__index();
        page__block_footer();
    break;
    // case 'days':
    //     echo 'Days';
    // break;
    // case 'days_month':
    //     echo 'Month';
    // break;
    // case 'days_year':
    //     echo 'Year';
    // break;
    case 'days_day':
        if(getInfo($id)) {
            page__block_header();
            page__block_menu();
            page__days_day($id);
            page__block_footer();
        } else {
            echo '404 Not Found';
        }
    break;
    case 'weight':
        echo 'Current weight';
    break;
    case 'products':
        echo 'Products list';
    break;
    default:
        echo '404 Not Found';
    break;
}
