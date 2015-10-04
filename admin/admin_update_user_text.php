<?php
require_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$name = $_POST['setting'];
$value = $_POST['value'];
$userID = $_POST['id'];
$am->setUserText($userID, $name, $value);
header("Location: ../accounts/admin.php");
?>