<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 10/3/2015
 * Time: 6:00 PM
 */
include_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$am->deletePage($_POST['id']);
header('Location: ../pages.php');