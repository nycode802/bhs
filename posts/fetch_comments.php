<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/15/2015
 * Time: 1:23 AM
 */
require_once "../DatabaseManager.php";
$postID = $_GET['postID'];
$user = "westbvlb_admin";
$pass = "@Mommy828";
$host = "localhost";
$db = "westbvlb_system";
$am = DatabaseManager::getInstance();
mysql_connect($host,$user,$pass);
mysql_select_db($db);
$sql = "SELECT comments FROM posts WHERE id='$postID'";
$query = mysql_query($sql);
while($row = mysql_fetch_array($query))
{
    $comments = $row['comments'];
}
$comments =(unserialize($comments));
$return = "";
if(!empty($comments))
{
    foreach($comments as $comment)
    {
        $txt =$am->getCommentText($comment);
        if(isset($comment) and !empty($comment) and !empty($txt))
        {
            $sig =  $am->getUserSignature($am->getUsername($am->getCommentPosterID($comment)));

            if(!empty($sig))
            {
                //  $return = $return . getUserSignature(getCommentPosterID($comment));
                $sig = "<br><center>" . $sig . "</center><br>";
            }
            session_start();
            if($am->getPermission($_SESSION['westbvlb_username'])>=1)
            {
                $adminPerms = "<br><br><br><form method='post' action='../admin/delete_comment.php'>
            <input type='hidden' name='commentid' value='$comment' />
             <input type='hidden' name='postid' value='$postID' />
            <input type='submit' value='X' name='submit' />
            </form>
";
            }
            $return = $return . "<div class='post_container' style='margin-top: 15px; padding-top: 10px; padding-bottom: 10px;'><div class='comment'>
        <table width='100%'>

        <tr>
        <td width='10%'><center><a href='../accounts/user.php?user=" . $am->getUsername($am->getCommentPosterID($comment)) ."'><img src='../profile/pictures/" . strtolower($am->getUsername($am->getCommentPosterID($comment))). ".jpg' style='width: 50%; height: auto; margin-left: auto;' class=\"profile_picture\"/><Br><br>" . $am->getUsername($am->getCommentPosterID($comment)) . "<br>" . $am->getRank($am->getPermission($am->getUsername($am->getCommentPosterID($comment)))) . "</a></center></td>
        <td width='90%'>" . $am->getCommentText($comment) . "" . "</td>
        <td width='10%'>" . $am->getCommentTime($comment). $adminPerms . "</td>
        </tr>
        </table>" . $sig . "
        </div> </div>";
        }


    }
}

echo $return;
