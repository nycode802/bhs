<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/20/2015
 * Time: 10:07 PM
 */
$username = $_POST['westbvlb_username'];
$permission = $_POST['westbvlb_permission'];
include_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$am->setPermission($username,$permission);
header('Location: ../accounts/admin.php');