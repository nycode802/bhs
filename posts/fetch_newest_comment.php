<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/15/2015
 * Time: 10:23 PM
 */
include_once "../DatabaseManager.php";
$user = "westbvlb_admin";
$pass = "@Mommy828";
$host = "localhost";
$db = "westbvlb_system";
$am = DatabaseManager::getInstance();
mysql_connect($host,$user,$pass);
mysql_select_db($db);
$sql = "SELECT * FROM comments ORDER BY id DESC LIMIT 0,1";
$response = mysql_query($sql);
while($row = mysql_fetch_array($response))
{
    $userID = $row['userid'];
    $text = html_entity_decode(substr($row['text'],  0, 120));
    $time = $row['time'];
    $postID = $row['postid'];
}
$username = $am->getUsername($userID);
$post = "<a href='posts/post.php?id=$postID'><h3 class='title'>Comment by $username</h3><p class='title'>\"$text...\"</p><p class='title'><i>$time</i></p></a>";
echo $post;
