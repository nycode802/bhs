<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/14/2015
 * Time: 11:33 PM
 */
date_default_timezone_set("America/New_York");
include_once "../DatabaseManager.php";
$postID = $_GET['id'];
$am = DatabaseManager::getInstance();
session_start();
$text = html_entity_decode($am->getPostText($postID));
$title = $am->getPostTitle($postID);
$userID = $am->getPostUserID($postID);
$username = $am->getUsername($userID);
$time = $am->getPostTime($postID);
$type = $am->getPostType($postID);
if(isset($_SESSION['westbvlb_username']))
{
    $unlikeText = $am->getUnlikeText();
    $likeText = $am->getLikeText();
    $buttonText = "";
    $script = "";
    $currentID = $am->getUserID($_SESSION['westbvlb_username']);
    if($am->isLiked($currentID, $postID))
    {
        $buttonText = $unlikeText;
        $script = "unlike_post.php";
    }
    else{
        $buttonText = $likeText;
        $script = "like_post.php";
    }
    $del = "<br><br><form method='post' action='$script'>
        <input type='hidden' name='postID' value='$postID' />
        <input type='submit' name='submit' value='" . $buttonText . "' />
        </form>
";
}
if($am->getPermission($_SESSION['westbvlb_username'])>=2)
{
    $del .= "<form method='post' action='remove_post.php'>
            <input type='hidden' value='$postID' name='postID' />
            <input type='submit' value='Delete' name='submit' />
            </form>";
    $del .="<form method='get' action='edit_post.php'>
            <input type='hidden' value='$postID' name='id' />
            <input type='submit' value='Edit' name='edit' />
             </form>
             ";
}

if($type==2)
{
    $text = "<iframe width='560' height='315' src='https://youtube.com/embed/" . $am->getVideoID($text) . "' frameborder='0' allowfullscreen></iframe>";
}
if($type==3)
{
    $text = "<img src='$text' width='auto' height='auto' />";
}
$am->addView($postID,  $am->getUserID($_SESSION['westbvlb_username']));
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
    <title><?php echo $siteName; ?> | <?php echo $title; ?></title>
    <link rel="stylesheet" type="text/css" href="../styles.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
    <script>
        function updateContent(jContainer, htmlSource) {
            $.get(htmlSource, function(html) {
                jContainer.html(html);
            });
        }
        function updateComments(jContainer, htmlSource, id)
        {
            $.ajax({
                type: "GET",
                url: htmlSource,
                data: "postID=" + id, // appears as $_GET['id'] @ your backend side
                success: function(data) {
                    // data is ur summary
                    jContainer.html(data);
                }

            });
        }
        function updateSignature(jContainer, htmlSource, id)
        {
            $.ajax({
                type: "GET",
                url: htmlSource,
                data: "userID=" + id, // appears as $_GET['id'] @ your backend side
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
            updateComments($("#comments"), 'fetch_comments.php', <?php echo $postID; ?>);
            updateSignature($("#signature"), '../account_scripts/fetch_signature.php', <?php echo $userID; ?>);
        }, 1000);
    </script>
</head>
<body class="mainbody">
<div id="header"></div><br><Br><br>
<div class="post_container">
    <table width="100%">
        <tr>
            <td width="10%" style="padding: 10px;">
                <Center>
                    <a href="../accounts/user.php?user=<?php echo $username?>">
                        <img src='../profile/pictures/<?php echo strtolower($username);?>.jpg' style='width: 100%; height: auto; margin-left: auto;' class="profile_picture"/>
                        <p class="title"><?php echo $username; ?><br><br><b><?php echo $am->getRank($am->getPermission($username)); ?></b></p>

                    </a>
                </Center>

            </td>
            <td width="80%">
                <h1 class="title"><?php echo $title; ?></h1>
                <br><br><br><br>
            </td>
            <td width="10%"><div align="center"><?php echo $time . "<br><br><br>" . $am->getViews($postID) . " views<br><br>" . $am->getLikes($postID) . " " . $am->getLikeName() .  "<br><Br><img src='../images/like.png' width='50%' height='auto' align='middle'/><br>" . $del; ?></div> </td>
        </tr>
        <tr>
            <td width="10%"></td>
            <td width="80%" height="100%" style="padding-left: 10px; text-align: center;"><p>
                    <?php echo $text; ?>
                </p></td></td>
        </tr>
        <tr>

            <td width="10%"></td>
            <td width="80%" style="padding-top: 13%;"><center><hr><div id="signature" style="padding-top: 10px; padding-bottom: 10px;"><center><br><Br><img src="../images/loading.gif" width="5%"/> </center></div></center> </td>
        </tr>
    </table>
    </div>
    <div class="post_container" style="margin-top: 100px; padding-top: 20px; padding-bottom: 20px;">
   <h3 class="title">Comments</h3>
    </div>
    <div id="comments"><center><br><Br><img src="../images/loading.gif" width="5%"/> </center></div>
    <br><Br>
    <?php

        if(isset($_SESSION['westbvlb_username']) and !empty($_SESSION['westbvlb_username']))
        {
            echo '   <div class="post_container"> <h3 class="title">Comment</h3>
    <center>
        <div class="post_comment">
            <form method="post" action="comment.php">
                <textarea cols="80" rows="8" name="text"></textarea>
                <input type="hidden" value="' . $postID . '" name="postID" /><br>
                <input type="submit" name="submit" value="Comment" />
            </form>
        </div>
    </center>
    </div>';
        }
    else{
        echo '<div class="post_container"> <p>You are not signed. You must sign in to comment</p></div>';
    }


    ?>

    <br><Br>


</body>
</html>

