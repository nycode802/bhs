<?php

include_once "../DatabaseManager.php";
$username  =$_GET['user'];
$am = DatabaseManager::getInstance();
echo $am->getUserDescription($username);