<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 10/2/2015
 * Time: 8:37 PM
 */
$userID = $_POST['id'];
$stat = $_POST['stat'];
$value = $_POST['value'];
include_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$oValue = $am->getStatValue($stat) + $value;
$am->setStat($userID,$am->getStatName($stat),$oValue);
header('Location: stats.php?id=' . $userID);