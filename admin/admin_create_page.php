<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 10/3/2015
 * Time: 5:25 PM
 */
$page_name = $_POST['page_name'];
$page_content = $_POST['page_content'];
$userID = $_POST['userid'];
include_once "../DatabaseManager.php";
$am=DatabaseManager::getInstance();
$page_name = ($page_name);
$page_content = ($page_content);
$am->createPage($userID, $page_content, $page_name);
header('Location: ../pages.php');