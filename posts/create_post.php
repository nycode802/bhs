<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/14/2015
 * Time: 11:37 PM
 */
$video = '<p>Accepted Types: YouTube.com/watch?v=, and YouTu.be/</p>
 <form method="post" action="send_post.php">
            <h4>Title</h4>
            <input type="text" name="westbvlb_title" /><br><br>
            <h4>Video URL</h4>
            <input type="hidden" name="westbvlb_type" value="2" />
            <input type="text" name="westbvlb_text" /> <br><br><Br>
            <input type="submit" name="submit" value="Post" />
        </form>';
$text = ' <form method="post" action="send_post.php">
            <h4>Title</h4>
            <input type="hidden" name="westbvlb_type" value="1" />
            <input type="text" name="westbvlb_title" /><br><br>
            <h4>Text</h4>
            <textarea name="westbvlb_text" cols="40" rows="8"> </textarea><br><br><Br>
            <input type="submit" name="submit" value="Post" />
        </form>';
$image = '
<p>Must be an image location</p>
<form method="post" action="send_post.php">
            <h4>Title</h4>
            <input type="text" name="westbvlb_title" /><br><br>

            <input type="hidden" name="westbvlb_type" value="3" />
             <h4>Image Preview</h4><br>
            <img src="" width="250px" height="auto" id="preview"/><br>
             <h4>Image URL</h4>
            <input type="text" name="westbvlb_text"  onchange="textupdate();" onkeyup="textupdate();" onpaste="textupdate();" oninput="textupdate();" id="image_loc"/> <br><br><Br>
            <input type="submit" name="submit" value="Post" />
        </form>';
$fileUpload = "<form method='post' action='send_post.php' enctype='multipart/form-data'>
            <h4>Title</h4>
            <input type='text' name='westbvlb_title' />
            <Br>
            <h4>Text</h4>
            <textarea name='westbvlb_text' cols='40' rows='8'> </textarea><br><br><Br>
            <input type='file' name='westbvlb_file' >
            <input type='hidden' name='westbvlb_type' value='4' />
            <input type='submit' name='submit' value='Upload' />
            </form>";
$type = $_POST['type'];
include_once '../DatabaseManager.php';
$am = DatabaseManager::getInstance();
session_start();
$permission = $am->getPermission($_SESSION['westbvlb_username']);
if($permission==0)
{
    header('Location: ../index.php');
}
$siteName = $am->getTextSetting("site_name");
?>
<html>
<head>
    <style>
        <?php echo $am->getButtonCSS(); ?>
        <?php echo $am->getTextAreaHTML(); ?>
    </style>
    <title><?php echo $siteName; ?> | Create Post</title>
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

        setInterval(function() {
            updateContent($("#header"), '../backend/fetch_header.php');
        }, 500);
        function textupdate()
        {
            $('#preview').css('background-image', 'url("' + $('#image_loc').val() + '")');
            $('#preview').css('background-size', 'cover');
            $('#preview').css('width', '50%');
            $('#preview').css('height', 'auto');
        }
    </script>
</head>
<body class="mainbody">
<div id="header"></div>
<br><br><br>
<div class="post_container">
    <div class="create_post">
        <h1 class="title">Write a <i><b>FOREVER</b></i> post</h1>
        <form action="create_post.php" method="post">
            <select name="type" style="width: 50%; border-radius: 10px; padding: 5px; text-align: center;">
                <option value="1">Text Post</option>
                <option value="2">Video Post</option>
                <option value="3">Image Post</option>
                <option value="4">File Upload</option>
            </select><Br><br>
            <input type="submit" name="submit" value="Select Type" />
        </form>
        <br><hr>
        <?php if(isset($type)){if($type==1){echo $text;}else if($type==2){echo $video;} else if($type==3){echo $image;} else if($type==4){echo $fileUpload;}} else{ echo $text;} ?>
        <i>You may use basic HTML formatting.</i>
    </div>

</div>
</body>
</html>
