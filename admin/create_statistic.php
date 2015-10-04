<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 10/1/2015
 * Time: 10:13 PM
 */
include_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$users = $am->getUsers();
$users = explode(",",$users);
$name = $_POST['name'];
$max = $_POST['max'] + 1;
$min = $_POST['min'];

foreach($users as $user)
{
    $userID = $am->getUserID($user);
    $am->setStat($userID, $name, $min);
    $am->setStat($userID, $name . "_max", $max);
    $am->setStat(-1,$name, $min);
    $am->setStat(-1,$name . "_max", $max);
}
header('Location: stats.php');