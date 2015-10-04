<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 10/3/2015
 * Time: 8:11 PM
 */
include_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$users = unserialize($_POST['users']);
$statName = $_POST['stat_name'];
$statValue = $_POST['stat_value'];
$mode = $_POST['m'];
//var_dump($users);
foreach($users as $userID)
{
    if($mode==1)
    {
        $oVal = $am->getStat($userID, $statName);
        $statValue += $oVal;
    }
    $am->setStat($userID, $statName, $statValue);
}
header('Location: multi_stats.php');
