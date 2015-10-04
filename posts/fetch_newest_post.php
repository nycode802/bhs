<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/15/2015
 * Time: 10:16 PM
 */
include_once "../DatabaseManager.php";
$user = "westbvlb_admin";
$pass = "@Mommy828";
$host = "localhost";
$db = "westbvlb_system";
$am = DatabaseManager::getInstance();
mysql_connect($host,$user,$pass);
mysql_select_db($db);
$sql = "SELECT * FROM posts ORDER BY id DESC LIMIT 0,1";
$response = mysql_query($sql);
while($row = mysql_fetch_array($response))
{
    $userID = $row['userid'];
    $text = html_entity_decode(substr($row['text'],  0, 120));
    $title = html_entity_decode($row['title']);
    $time = $row['time'];
    $postID = $row['id'];
    $type = $row['type'];
}
if($type==2)
{
    $text = "<i>YouTube Video</i>";
}
if($type==3)
{
    $text = "<img src='$text' width='75%' height='auto' />";
}

$username = $am->getUsername($userID);
$post = "
<a href='posts/post.php?id=$postID'>
<h3 class='title'>$title</h3>
 <h5 class='title'>$username</h5>
 <p class='title' style='font-size: 100%;'>
 $text
 </p>
 <br>
 <p class='title'><i>$time</i></p></a>";
echo $post;
