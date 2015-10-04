<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/20/2015
 * Time: 4:12 PM
 */
$username = $_POST['westbvlb_username'];
include_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$am->deleteAccount($username);
header('Location: ../accounts/admin.php');