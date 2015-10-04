<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/20/2015
 * Time: 6:24 PM
 */
$username = $_POST['westbvlb_username'];
require_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$am->resetPicture($username);
header('Location: ../accounts/admin.php');