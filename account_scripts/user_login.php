<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/14/2015
 * Time: 10:56 PM
 */
include_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$username = $_POST['westbvlb_username'];
$password = $_POST['westbvlb_password'];
if($am->verifyLogin($username,$password))
{
    session_start();
    $_SESSION['westbvlb_username'] = $username;
    $am->checkProfile($username);
    $am->generateUserToken($username);
    $_SESSION['westbvlb_user_token'] = $am->getUserToken($username);
    //$am->generateNewKey();
    header('Location: ../feed.php');
}
else{
    header('Location: ../accounts/login.php?e=1');
}