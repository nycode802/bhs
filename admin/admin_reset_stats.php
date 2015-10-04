<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 10/2/2015
 * Time: 9:18 PM
 */
include_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$id = $_POST['id'];
if($id==0)
{
    $am->resetStats();
}
else{
    $am->setupStats($id);
}
header('Location: stats.php');