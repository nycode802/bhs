<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/20/2015
 * Time: 4:13 PM
 */
$username = $_POST['westbvlb_username'];
$description = $_POST['westbvlb_description'];
include_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$am->setDescription($username,$description);
header('Location: ../accounts/admin.php');