<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/15/2015
 * Time: 12:43 PM
 */
$username = $_POST['westbvlb_username'];
$signature = htmlspecialchars($_POST['signature'], ENT_QUOTES);
include_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$am->setSignature($username,$signature);
header('Location: ../accounts/settings.php');