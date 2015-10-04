<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/30/2015
 * Time: 9:16 PM
 */
$title = $_POST['title'];
$text = $_POST['text'];
$id = $_POST['id'];
include_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$am->setPostText($id, $text);
$am->setPostTitle($id,$title);
header('Location: post.php?id=' . $id);
