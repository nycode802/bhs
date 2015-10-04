<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/30/2015
 * Time: 9:06 PM
 */
include_once '../DatabaseManager.php';
$am = DatabaseManager::getInstance();
$postID = $_GET['id'];
$title = $am->getPostTitle($postID);
$text = $am->getPostText($postID);
$siteName = $am->getTextSetting("site_name");
?>
<!DOCTYPE HTML>
    <html>
    <head>
        <style>
            <?php echo $am->getButtonCSS(); ?>
            <?php echo $am->getTextAreaHTML(); ?>
        </style>
        <link rel='icon' type='image/png' href='../images/favicon-32x32.png' sizes='32x32' />
        <link rel='icon' type='image/png' href='../images/favicon-16x16.png' sizes='16x16' />
        <title><?php echo $siteName; ?> | Home</title>
        <link rel="stylesheet" type="text/css" href="../styles.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
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
                updateHeader($("#header"), '../backend/fetch_header.php', 0);
                // updateBackground($("#mainbody"), 'backend/random_background.php');
            });
            setInterval(function() {
                updateHeader($("#header"), '../backend/fetch_header.php', 0);
            }, 1000);
        </script>
    </head>
    <body class="mainbody">
    <div id="header"> </div><br><br><br><Br>
    <div class="post_container">
        <center>
            <form method="post" action="update_post.php">
                <h3>Title</h3>
                <input type="hidden" name="id" value="<?php echo $postID; ?>" />
                <input type="text" name="title" value="<?php echo $title; ?>" />
                <br>
                <h3>Text</h3>
                <textarea name="text" cols="40" rows="8"><?php echo $text; ?></textarea>
                <input type="submit" name="update" value="Update" />
            </form>
        </center>

    </div>
    </body>
    </html>
