<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/15/2015
 * Time: 10:50 AM
 */
date_default_timezone_set("America/New_York");
include_once "../DatabaseManager.php";
$username = $_GET['user'];
$am = DatabaseManager::getInstance();
echo $am->getUserPosts($username);