<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 10/3/2015
 * Time: 5:02 PM
 */
include_once "DatabaseManager.php";
$am = DatabaseManager::getInstance();
session_start();
$username = $_SESSION['westbvlb_username'];
$userID = $am->getUserID($username);
$permission = $am->getPermission($username);
$siteTitle = $am->getTextSetting("site_name");
if($permission==0)
{
    header('Location: feed.php');
}
?>
<DOCTYPE HTML>
    <HTML>
    <HEAD>
        <title><?php echo $siteTitle; ?> | Create Page</title>
        <link rel='icon' type='image/png' href='../images/favicon-32x32.png' sizes='32x32' />
        <link rel='icon' type='image/png' href='../images/favicon-16x16.png' sizes='16x16' />
        <link rel="stylesheet" type="text/css" href="../styles.css" />
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
    <DIV class="post_container" style="<?php echo $bgStyle;?> text-align: center;">
        <form method="post" action="admin/admin_create_page.php">
            <h3>Page Name</h3>
            <input type="text" name="page_name" />
            <br><Br>
            <h3>Page Content</h3>
            <textarea cols="40" rows="10" name="page_content"></textarea>
            <input type="hidden" name="userid" value="<?php echo $userID; ?>" />
            <br><br><input type="submit" value="Create Page" />
        </form>
    </DIV>
    </BODY>
    </HTML>


</DOCTYPE>

