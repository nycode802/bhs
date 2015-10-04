<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/15/2015
 * Time: 9:33 AM
 */
$username = $_GET['username'];
echo htmlspecialchars("<div class=\"profile_picture\" style=\"background-image: url(\"../profile/pictures/$username.jpg\")\"></div>");