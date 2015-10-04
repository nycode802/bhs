<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/15/2015
 * Time: 12:43 PM
 */
$username = $_POST['westbvlb_username'];
$description = htmlspecialchars($_POST['description'], ENT_QUOTES);
include_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$am->setDescription($username,$description);
header('Location: ../accounts/settings.php');