<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/15/2015
 * Time: 1:48 AM
 */
date_default_timezone_set("America/New_York");
require_once "../DatabaseManager.php";
$postID = $_POST['postID'];
session_start();
$username = $_SESSION['westbvlb_username'];
$text = htmlspecialchars($_POST['text'], ENT_QUOTES);
$user = "westbvlb_admin";
$pass = "@Mommy828";
$host = "localhost";
$db = "westbvlb_system";
$am = DatabaseManager::getInstance();
mysql_connect($host,$user,$pass);
mysql_select_db($db);
$userID = $am->getUserID($username);
$time = date('g:iA n/j/y');
$sql = "INSERT INTO comments (id, userid, text, time, postid) VALUES (null, '$userID', '$text', '$time', '$postID')";
$data = mysql_query($sql);
$sql = "SELECT comments FROM posts WHERE id='$postID'";
$data = mysql_query($sql);

while($row = mysql_fetch_array($data))
{
    $comments = (unserialize($row['comments']));
}
if(empty($comments))
{
    $comments = array();

}

$commentID = $am->getCommentID();
array_push($comments, $commentID);
$comments = serialize($comments);
if(!empty($text))
{
    $sql = "UPDATE posts SET comments='$comments' WHERE id='$postID'";
    $data = mysql_query($sql);
}
header('Location: post.php?id=' . $postID);
