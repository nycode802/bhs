<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/15/2015
 * Time: 1:13 PM
 */
include_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$userID =$_GET['userID'];
echo $am->getUserSignature($am->getUsername($userID));