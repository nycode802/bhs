<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/20/2015
 * Time: 4:13 PM
 */
$username = $_POST['westbvlb_username'];
$signature = $_POST['westbvlb_signature'];
include_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$am->setSignature($username,$signature);
header('Location: ../accounts/admin.php');