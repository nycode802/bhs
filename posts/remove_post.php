<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/20/2015
 * Time: 3:23 PM
 */
$postID = $_POST['postID'];
require_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$am->deletePost($postID);
header('Location: ../feed.php');