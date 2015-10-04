<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 10/2/2015
 * Time: 8:22 PM
 */
$name = $_POST['name'];
include "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$users = $am->getUsers();
$users = explode(",",$users);
foreach($users as $user)
{
    $userID = $am->getUserID($user);
    $am->deleteStat($userID, $am->getStatName($name));
    $am->deleteStat($userID, $am->getStatName($name) . "_max");
}
header('Location: stats.php');