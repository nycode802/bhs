<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 10/1/2015
 * Time: 9:35 PM
 */
$session = $_POST['session'];
$userid = $_POST['userid'];
$points = $_POST['points'];
include_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
if($am->isValidAdminSession($session))
{
    $am->addPoints($am->getUsername($userid), $points);
    header('Location: stats.php?id=' . $userid);
}
else{
    header("Location: ../account_scripts/user_logout.php");
}
