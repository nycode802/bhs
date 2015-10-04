<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 10/1/2015
 * Time: 10:20 PM
 */
$userID = $_GET['id'];
include_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$stats = $am->getStatArray($userID);

foreach($stats as $stat)
{
    if(!strpos($stat,"_max"))
    {
        echo "<br>(<span style='color: red'>$stat</span>)<br>";
        echo $am->getStatName($stat) . "> " . $am->getStat($userID,$am->getStatName($stat)). "<br>";
    }
}