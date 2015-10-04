<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/15/2015
 * Time: 10:50 AM
 */
$username = $_GET['user'];
include_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$username = $am->getCorrectUsername($username);
$rankName = $am->getRank($am->getPermission($username));
$siteName = $am->getTextSetting("site_name");
$containerColor = $am->getStyleColor("post_container");
$first = $am->getFirstName($username);
$last = $am->getLastName($username);
$joined = $am->getJoinDate($username);
$lastLogin = $am->getLastLogin($username);
echo "<title>$siteName | $username</title>";

?>
<html>
<head>
    <style>
        <?php echo $am->getButtonCSS(); ?>
        <?php echo $am->getTextAreaHTML(); ?>
    </style>
    <link rel='icon' type='image/png' href='../images/favicon-32x32.png' sizes='32x32' />
    <link rel='icon' type='image/png' href='../images/favicon-16x16.png' sizes='16x16' />
    <link rel="stylesheet" type="text/css" href="../styles.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
    <script>
        function updateContent(jContainer, htmlSource) {
            $.get(htmlSource, function(html) {
                jContainer.html(html);
            });
        }
        function updatePosts(jContainer, htmlSource, username)
        {
            $.ajax({
                type: "GET",
                url: htmlSource,
                data: "user=" + username, // appears as $_GET['id'] @ your backend side
                success: function(data) {
                    // data is ur summary
                    jContainer.html(data);
                }

            });
        }
        function updateDescription(jContainer, htmlSource, username)
        {
            $.ajax({
                type: "GET",
                url: htmlSource,
                data: "user=" + username, // appears as $_GET['id'] @ your backend side
                success: function(data) {
                    // data is ur summary
                    jContainer.html(data);
                }

            });
        }


        setInterval(function() {
            updateContent($("#header"), '../backend/fetch_header.php');
        }, 500);
        setInterval(function(){
            updatePosts($("#posts"), '../posts/fetch_user_posts.php', '<?php echo $username; ?>');
            updatePosts($("#comments"), '../posts/fetch_user_comments.php', '<?php echo $username; ?>');
            updateDescription($("#description"), '../account_scripts/fetch_description.php', '<?php echo $username; ?>');
        }, 1000);
    </script>
</head>
<body class="mainbody">
<div id="header" ></div>
<br><br><Br><Br>
<div class="post_container" style="<?php echo $containerColor; ?>">

        <br><Br>
    <div class="post_container">
        <table width="100%">
            <tr>
                <td width="50%" style="text-align: center;">    <img src="../profile/pictures/<?php echo strtolower($username);?>.jpg" class="profile_picture" width="55%"/><br><i><?php echo $rankName; ?></i>
                    <h3><?php echo $first . " " . $last;?></h3><h5><?php echo $username; ?></h5><h5>
                        Last logged in <?php echo $lastLogin; ?><br>
                        Joined <?php echo $joined;?>
                    </h5></td>
                <td width="50%"> <div id="description"></div></td>
            </tr>
        </table>
    </div>
    <br>
    <div style="margin-left: auto; margin-right: auto; text-align: center">
        <div id="posts">
        </div>
        <div id="comments">
        </div>
    </div>

</div>
</body>
</html>
