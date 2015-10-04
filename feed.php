<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/14/2015
 * Time: 10:10 PM
 */
date_default_timezone_set("America/New_York");
session_start();
$username = $_SESSION['westbvlb_username'];
$msg = "<a href='accounts/login.php'>Login</a> | <a href='accounts/register.php'>Register</a>";
if(isset($username))
{
    $msg = "Greetings " . $username . "  (<a href='account_scripts/user_logout.php'>Logout</a> | <a href='accounts/settings.php'>Settings</a> | <a href='accounts/user.php?user=$username'>Profile</a>)" ;
}
$mustLogin = "YES";
$isLoggedIn = empty($username);
$p = $_GET['p'];
$m = $_GET['m'];
include_once "DatabaseManager.php";
$am = DatabaseManager::getInstance();
$nPostColor = $am->getStyleColor("newest_post_color");
$nCommentColor = $am->getStyleColor("newest_comment_color");
$tColor = $am->getStyleColor("title_color");
$titleText = $am->getTextSetting("title_text");
$siteName = $am->getTextSetting("site_name");
$siteTagline = $am->getTextSetting("site_tagline");
?>
<html>
<head>
    <link rel='icon' type='image/png' href='../images/favicon-32x32.png' sizes='32x32' />
    <link rel='icon' type='image/png' href='../images/favicon-16x16.png' sizes='16x16' />
    <title><?php echo $siteName; ?> | Home</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
    <style>
        <?php echo $am->getButtonCSS(); ?>
        <?php echo $am->getTextAreaHTML(); ?>
    </style>
    <script>

        function updateContent(jContainer, htmlSource, doit) {
            $.get(htmlSource, function(html) {
                if(doit)
                {
                    jContainer.slideUp(1);
                    jContainer.html(html);
                    jContainer.slideDown(1200);
                }
                else
                {
                    jContainer.html(html);
                }
            });
        }
        function updateHeader(jContainer, htmlSource, id)
        {
            $.ajax({
                type: "GET",
                url: htmlSource,
                data: "a=" + id, // appears as $_GET['id'] @ your backend side
                success: function(data) {
                    // data is ur summary
                    jContainer.html(data);
                }

            });
        }
        function updatePosts(jContainer, htmlSource, doit, id)
        {
            $.ajax({
                type: "GET",
                url: htmlSource,
                data: "p=" + id + "&m=" +  "<?php echo $m; ?>", // appears as $_GET['id'] @ your backend side
                success: function(data) {
                    // data is ur summary
                    if(doit)
                    {
                        jContainer.slideUp(1);
                        jContainer.html(data);
                        jContainer.slideDown(1200);
                    }
                    else
                    {
                        jContainer.html(data);
                    }
                }

            });
        }
        function updateBackground(jContainer, htmlSource)
        {

            $.ajax({
                type: "GET",
                url: htmlSource,
                data: "", // appears as $_GET['id'] @ your backend side
                success: function(data) {
                    // data is ur summary
                    jContainer.css("background-image", "url(" +  data+ ")");
                }

            });

        }
        $(document).ready(function(){
            updatePosts($("#posts"), 'posts/fetch_posts.php', true, '<?php echo $p; ?>');
            updateContent($("#newest_post"), 'posts/fetch_newest_post.php', true);
            updateContent($("#newest_comment"), 'posts/fetch_newest_comment.php', true);
            updateHeader($("#header"), 'backend/fetch_header.php', 0);
           // updateBackground($("#mainbody"), 'backend/random_background.php');
        });

        setInterval(function() {
            updatePosts($("#posts"), 'posts/fetch_posts.php', false, '<?php echo $p; ?>');
            updateContent($("#newest_post"), 'posts/fetch_newest_post.php', false);
            updateContent($("#newest_comment"), 'posts/fetch_newest_comment.php', false);
            updateHeader($("#header"), 'backend/fetch_header.php', 0);
        }, 1000);
    </script>
</head>
<body class="mainbody" id="mainbody">
<div id="header">
</div>
<div style="position: fixed; padding-top: 10px; top: 20%; left: 10px; width: 20%;">
    <div class="post_container" style="width: 100%; <?php echo $nCommentColor ;?>">
        <h1 class="title">Newest Comment</h1>
        <div id="newest_comment">
        </div>
    </div>
</div>
<div style="position: fixed; padding-top: 10px; top: 20%; right: 10px; width: 20%;">
    <div class="post_container" style="width: 100%; <?php echo $nPostColor ;?>">
        <h1 class="title">Newest Post</h1>
        <div id="newest_post">
        </div>
    </div>
</div>
<br><br><br>
<table width="100%" style="">
    <tr>
        <td width="20%" style="">
        </td>
        <td width="60%">
            <div class="post_container" style="<?php echo $am->getStyleColor("tagbar_container"); ?>">
                <div class="post_container" style="<?php echo $tColor; ?>">
                    <center>
                        <h2><?php echo $titleText; ?></h2>
                        <a href="feed.php"><img src="images/logo.png" height="auto" width="20%" class="profile_picture"/></a><br>
                        <h3><?php echo $siteTagline; ?></h3>
                        <br>
                        <h4>Sort by</h4><br>
                        <a href="feed.php?m=1">Popular</a> | <a href="feed.php">Newest</a>
                    </center
                    <br>
                </div>
            </div>

                <div id="posts"><center><br><Br><img src="images/loading.gif" width="5%"/> </center>
                <br><br>
            </div></td>
        <td width="20%">
        </td>
    </tr>
</table>
<br>
</body>
</html>
