<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/14/2015
 * Time: 11:33 PM
 */
include_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$title = htmlspecialchars($_POST['westbvlb_title'], ENT_QUOTES);
$text = htmlspecialchars($_POST['westbvlb_text'], ENT_QUOTES);

$type = htmlspecialchars($_POST['westbvlb_type']);
if($type==4)
{
    $name = $am->uploadFile($_FILES["westbvlb_file"]);
    $fileStuff = "<br><br><br><p>File download</p><br><hr width='50%'><br><Br><a href=$name>Download here</a>";
    $fileStuff = htmlspecialchars($fileStuff, ENT_QUOTES);
    $text = $text . $fileStuff;
}
session_start();
$username = $_SESSION['westbvlb_username'];

if(isset($username))
{

    $userID = $am->getUserID($username);
    $time = date('g:iA n/j/y');
    $likes = "";
    $am->createPost($type,$title,$text,$likes,$time,$userID);
    header('Location: ../feed.php');
}
else{
    header('Location: ../feed.php');
}