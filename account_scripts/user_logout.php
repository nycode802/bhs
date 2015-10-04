<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/14/2015
 * Time: 10:56 PM
 */
session_start();
$_SESSION['westbvlb_username'] = NULL;
$_SESSION['westbvlb_user_token'] = NULL;
header('Location: ../feed.php');