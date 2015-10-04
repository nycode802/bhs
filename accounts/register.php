<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/14/2015
 * Time: 11:02 PM
 */
date_default_timezone_set("America/New_York");
$msg = "<a href='login.php'>Login</a> | <a href='register.php'>Register</a>";
$error = $_GET['error'];
if(!empty($error))
{
    if($error==2)
    {
        $error = "<div class='error'><h1>Error: Usernames must only contain letters, and all fields must be filled in.</h1></div>";
    }
}
include_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$siteName = $am->getTextSetting("site_name");
?>
<html>
<head>
    <style>
        <?php echo $am->getButtonCSS(); ?>
        <?php echo $am->getTextAreaHTML(); ?>
    </style>
    <link rel='icon' type='image/png' href='../images/favicon-32x32.png' sizes='32x32' />
    <link rel='icon' type='image/png' href='../images/favicon-16x16.png' sizes='16x16' />
    <title><?php echo $siteName;?> | Register</title>
    <link rel="stylesheet" type="text/css" href="../styles.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
    <script>
        function updateContent(jContainer, htmlSource) {
            $.get(htmlSource, function(html) {
                jContainer.html(html);
            });
        }

        setInterval(function() {
            updateContent($("#header"), '../backend/fetch_header.php');
        }, 500);
    </script>
</head>
<body class="mainbody">
<div id="header">
</div>
<br><br><br>
<div class="post_container">
    <?php echo $error; ?>
    <h1 class="title">Register on</h1><br>
    <center>
        <img src="../images/logo.png" height="110px" width="auto" class="profile_picture"/>
    </center>
    <br><hr>
    <br><Br>
    <div class="login">
        <form method="post" action="../account_scripts/user_register.php">
            <h4>First Name</h4>
            <input type="text" name="westbvlb_first"  /><br>
            <h4>Last Name</h4>
            <input type="text" name="westbvlb_last" /><br>
            <h4>Email</h4><br>
            <input type="email" name="westbvlb_email" /><br>
            <h4>Username</h4><br>
            <input type="text" name="westbvlb_username" maxlength="15"/><br>
            <h4>Password</h4><br>
            <input type="password" name="westbvlb_password" /><br>
            <h4>Phone Number</h4>
            <input type="text" name="westbvlb_phone" /><br><br>
            <input type="submit" name="submit" value="Register" />
        </form>
        <p>Already have an account? <a href="login.php">Login Here</a></p><br><br>
    </div>
    <br><Br>


</div>
</body>
</html>
