<?php
require_once "DatabaseManager.php";
$am = DatabaseManager::getInstance();

$row = $am->getStatistics($_GET['id']);
$size = count($row);
$start = 2;
while($start <= $size)
{
    echo $row[$start] . "<br>";
    $start = $start + 1;
}
?>