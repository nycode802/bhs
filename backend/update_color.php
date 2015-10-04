<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/30/2015
 * Time: 3:16 PM
 */
include_once "../DatabaseManager.php";
$hex = $_POST['color'];
list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
$str = $r . "," . $g . ","  . $b;
$name = $_POST['name'];
$am = DatabaseManager::getInstance();
$am->updateColor($name, $str);
header('Location: ../accounts/admin.php');
