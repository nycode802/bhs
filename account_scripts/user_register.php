<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/14/2015
 * Time: 10:56 PM
 */
date_default_timezone_set("America/New_York");
$username = ($_POST['westbvlb_username']);

$password = htmlspecialchars($_POST['westbvlb_password'], ENT_QUOTES);
$email = htmlspecialchars($_POST['westbvlb_email'], ENT_QUOTES);
$phone = htmlspecialchars($_POST['westbvlb_phone'], ENT_QUOTES);
$first = htmlspecialchars($_POST['westbvlb_first'], ENT_QUOTES);
$last = htmlspecialchars($_POST['westbvlb_last'], ENT_QUOTES);
if(ctype_alnum($username) && !empty($email) && !empty($password) && !empty($phone) && !empty($first) && !empty($last))
{
    $username = htmlspecialchars($username,ENT_QUOTES);
    include_once "../DatabaseManager.php";
    $am = DatabaseManager::getInstance();
    $am->setupAccount($username,$password,$email,$phone, $first, $last);
}
else
{
    header('Location: ../accounts/register.php?error=2');
}

