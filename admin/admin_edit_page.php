<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 10/3/2015
 * Time: 5:25 PM
 */
$page_name = $_POST['page_name'];
$page_content = $_POST['page_content'];
$pagID = $_POST['id'];
include_once "../DatabaseManager.php";
$am=DatabaseManager::getInstance();
$am->editPage($pagID, $page_content, $page_name);
//echo $pagID . "<br>" . $page_name . "<br>" . $page_content;
header('Location: ../page.php?id=' . $pagID);