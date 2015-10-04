<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/30/2015
 * Time: 3:58 PM
 */
include_once "../DatabaseManager.php";
$value = htmlspecialchars($_POST['value'], ENT_QUOTES);
$name = $_POST['name'];
$am = DatabaseManager::getInstance();
$am->updateSetting($name, $value);
header('Location: ../accounts/admin.php');
