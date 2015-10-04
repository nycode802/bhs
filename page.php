<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 10/3/2015
 * Time: 1:12 PM
 */
include_once "DatabaseManager.php";
$am= DatabaseManager::getInstance();
$page = "";
$pageID = $_GET['id'];
$title = $am->getPageName($pageID);
$userID = $am->getPageUserID($pageID);
$username = $am->getUsername($userID);
$content = $am->getPageContent($pageID);
$created = $am->getPageCreated($pageID);
$siteTitle = $am->getTextSetting("site_name");
session_start();
$permission = $am->getPermission($_SESSION['westbvlb_username']);
$color = $am->getStyleColor("dyn_page_color");
if($permission>=1)
{
    $delete = "<form method='post' action='admin/admin_delete_page.php'>
                <input type='hidden' name='id' value='$pageID' />
                <input type='submit' value='Delete' />
                </form>";
    $edit = "<form method='post' action='edit_page.php'>
            <input type='hidden' name='id' value='$pageID' />
            <input type='submit' value='Edit' />
</form>
";
}
?>
<DOCTYPE HTML>
    <HTML>
    <HEAD>
        <title><?php echo $siteTitle; ?> | Stat Settings</title>
        <link rel='icon' type='image/png' href='images/favicon-32x32.png' sizes='32x32' />
        <link rel='icon' type='image/png' href='images/favicon-16x16.png' sizes='16x16' />
        <link rel="stylesheet" type="text/css" href="styles.css" />
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
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
                updatePosts($("#posts"), '../posts/fetch_posts.php', true, '<?php echo $p; ?>');
                updateContent($("#newest_post"), '../posts/fetch_newest_post.php', true);
                updateContent($("#newest_comment"), '../posts/fetch_newest_comment.php', true);
                updateHeader($("#header"), '../backend/fetch_header.php', 1);
                // updateBackground($("#mainbody"), 'backend/random_background.php');
            });

            setInterval(function() {
                updatePosts($("#posts"), '../posts/fetch_posts.php', false, '<?php echo $p; ?>');
                updateContent($("#newest_post"), '../posts/fetch_newest_post.php', false);
                updateContent($("#newest_comment"), '../posts/fetch_newest_comment.php', false);
                updateHeader($("#header"), '../backend/fetch_header.php', 1);
            }, 1000);
        </script>
    </HEAD>
    <BODY class="mainbody"><DIV id="header"></DIV>
    <br><br><br>
    <DIV class="post_container" style="<?php echo $color;?> text-align: center;">
        <table width="100%" style="width: 100%; margin-left: auto; margin-right: auto; text-align: center;">
            <tr>
                <td width="50%"><?php echo $delete; ?></td>
                <td><?echo $created; ?></td>
                <td width="50%"><?php echo $edit; ?></td>
            </tr>
        </table>
        <center>
            <img src="profile/pictures/<?php echo strtolower($username);?>.jpg" width="250px" height="auto" style="border-radius: 20px;"/>
            <br><h4><?php echo $username; ?></h4><br>

            <br><hr width="50%">
            <h1><?php echo $title; ?></h1>
        </center>
        <br><br>
        <div class="dyn_page">
            <?php echo $content;?>
        </div>

    </DIV>
    </BODY>
    </HTML>
</DOCTYPE>


