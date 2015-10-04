<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/21/2015
 * Time: 9:56 PM
 */
$postID = $_POST['postid'];
$commentID = $_POST['commentid'];
require_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$am->deleteComment($commentID, $postID);
header('Location: ../posts/post.php?id='. $postID);