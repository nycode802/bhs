<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/27/2015
 * Time: 3:27 PM
 */
include_once "../DatabaseManager.php";
$postID = $_POST['postID'];
session_start();
$username = $_SESSION['westbvlb_username'];
$am = DatabaseManager::getInstance();
$am->unlike($postID, $am->getUserID($username));
header('Location: post.php?id=' . $postID);