<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 10/3/2015
 * Time: 1:12 PM
 */
include_once "DatabaseManager.php";
$am= DatabaseManager::getInstance();
session_start();
$permission = $am->getPermission($_SESSION['westbvlb_username']);
if($permission!=0)
{
    $create = "<form method='post' action='create_page.php'><input type='submit' formaction='create_page.php' value='Create Page'/></form> ";
}
$page = "";
foreach($am->getPages() as $pageID)
{
    $title = $am->getPageName($pageID);
    $userID = $am->getPageUserID($pageID);
    $username = $am->getUsername($userID);
    $content = html_entity_decode($am->getPageContent($pageID));
    $created = $am->getPageCreated($pageID);
    $style = $am->getStyleColor("dyn_page_color");
    $page .= "<div class='post_container' style='$style'>
    <h3><a href='page.php?id=$pageID'>$title</a></h3>
    </div>
";
}
$siteTitle = $am->getTextSetting("site_name");
?>
<DOCTYPE HTML>
    <HTML>
    <HEAD>
        <title><?php echo $siteTitle; ?> | Pages</title>
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
        <h1><?php echo $siteTitle;?>'s Pages</h1><br>
        <br>
        <div class="dyn_page"> <?php echo $create;?></div>
        <hr width="50%" />
        <?php echo $page; ?>
    </DIV>
    </BODY>
    </HTML>



</DOCTYPE>

