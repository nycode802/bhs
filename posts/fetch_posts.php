<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/14/2015
 * Time: 11:41 PM
 */
date_default_timezone_set("America/New_York");
include_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$page = $_GET['p'];
$mode = $_GET['m'];
echo $am->fetchPosts($mode,$page);
