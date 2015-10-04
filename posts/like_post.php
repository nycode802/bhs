<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/23/2015
 * Time: 8:40 PM
 */
date_default_timezone_set("America/New_York");
$postID = $_POST['postID'];
require_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
session_start();
$am->like($postID, $am->getUserID($_SESSION['westbvlb_username']));
header('Location: post.php?id=' . $postID);