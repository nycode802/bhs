<?php
require_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$userID = $_POST['id'];
$password = $_POST['password'];
$am->setPassword($userID, $password);
header('Location: ../accounts/admin.php');
?>