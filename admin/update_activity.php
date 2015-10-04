<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 10/2/2015
 * Time: 9:11 PM
 */
$userID = $_POST['id'];
$status = $_POST['status'];
include_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
if($status == "on")
{
    $status = 1;
}
else{
    $status = 0;
}
$am->setUserStatus($userID, $status);
header('Location: ../accounts/admin.php');