<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/15/2015
 * Time: 9:15 AM
 */
session_start();
$username = $_SESSION['westbvlb_username'];
$error = $_GET['error'];
/*
 * Error 1 = File too large
 * Error 2 = File not supported
 * Error 3 Unknown
 */
if($error==1)
{
    $error = "File is too large";
}
else if($error==2)
{
    $error = "File is not supported";
}
else if($error==3)
{
    $error = "An unknown error has occurred";
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
    <title><?php echo $siteName; ?> | Settings</title>
    <link rel="stylesheet" type="text/css" href="../styles.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
    <script>
        function updateContent(jContainer, htmlSource) {
            $.get(htmlSource, function(html) {
                jContainer.html(html);
            });
        }
        function updateProfilePicture(jContainer, htmlSource, id)
        {
            $.ajax({
                type: "GET",
                url: htmlSource,
                data: "username=" + id, // appears as $_GET['id'] @ your backend side
                success: function(data) {
                    // data is ur summary
                  (jContainer.html(data));
                }

            });
        }


        setInterval(function() {
            updateContent($("#header"), '../backend/fetch_header.php');
        }, 500);
        setInterval(function(){
           updateProfilePicture($("#profilepicture"), '../profile/scripts/fetch_profile_picture.php', <?php echo $username; ?>);
        }, 500);
    </script>
</head>
<body class="mainbody">
<div id="header"></div>
<br><br><Br>
<div class="post_container">
    <div class="settings">
        <center>
            <?php
                if(isset($error) and !empty($error))
                {
                    echo '  <div class="error">
                   <h3>' . $error . '</h3>
                   </div>';
                }

            ?>
            <h2>Account Settings for</h2><br>
            <center>
                <img src="../images/logo.png" height="110px" width="auto" class="profile_picture"/>
            </center>
            <br><hr>
            <h3>Profile Picture</h3>
                 <img src="../profile/pictures/<?php echo strtolower($username); ?>.jpg" width="25%" height="auto" class="profile_picture"/><br><br>
                        <center>
                            <form method="post" action="../account_scripts/user_update_picture.php" enctype="multipart/form-data">
                                <input type="file" name="uploaded" id="uploaded"/>
                                <input type="hidden" name="westbvlb_username" value="<?php echo $username?>" />
                                <br><br>
                                <input type="submit" name="submit" value="Upload" />

                            </form>
                        </center>
                <hr>
                <h3>Signature</h3>
                <p><?php echo html_entity_decode($am->getUserSignature($am->getUserID($username)));?></p>
                <br><br><form method="post" action="../account_scripts/user_update_signature.php">
                    <textarea cols="40" rows="8" name="signature"></textarea>
                <input type="hidden" name="westbvlb_username" value="<?php echo $username?>" /><br><br>
                    <input type="submit" name="submit" value="Update" />
                </form>
                <hr>
                <br>
                <h3>Description</h3>
                <p><?php echo html_entity_decode($am->getUserDescription($am->getUserID($username))); ?></p><br>
                <form method="post" action="../account_scripts/user_update_description.php">
                    <input type="hidden" name="westbvlb_username" value="<?php echo $username?>" />
                    <textarea cols="40" rows="8" name="description"></textarea><br><br>
                    <input type="submit" value="Update" name="submit" />
                </form>
        </center>
    </div>
</div>
</body>
</html>
