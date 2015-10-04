<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/20/2015
 * Time: 5:06 PM
 */
$username = $_POST['westbvlb_username'];
include_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$change = $_POST['westbvlb_changed'];
$am->renameUser($username,$change);
header('Location: ../accounts/admin.php');