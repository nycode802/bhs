<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/15/2015
 * Time: 12:16 AM
 */
$amount = 1;
include_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
if(isset($_POST['a']))
{
    $amount = $_POST['a'];
}
$a = 0;
while($a<$amount)
{
    if(!isset($tp))
    {
        $tp .= "..";
    }
    else{
        $tp = "/..";
    }
    $a+=1;
}
session_start();
$username = $_SESSION['westbvlb_username'];
$session_token = $_SESSION['westbvlb_user_token'];
if(isset($username))
{
    $userToken = $am->getUserToken($username);
    if($session_token!=$userToken)
    {
        $_SESSION['westbvlb_username'] = NULL;
        $_SESSION['westbvlb_user_token'] = NULL;
    }
}

$msg = "<a href='$tp/accounts/login.php'>Login</a> | <a href='$tp/accounts/register.php'>Register</a>";
$post = "";

$permissions = $am->getPermission($username);
if(isset($username))
{
    if($permissions>1)
    {
        $rest = "  <a href='$tp/accounts/admin.php'>Admin</a> | ";
    }
    if($permissions>=1)
    {
        $rest .= " <a href='$tp/admin/stats.php'> Admin Stats</a> | <a href='$tp/accounts/stats.php'>Personal Stats</a> | <a href='$tp/pages.php'>Pages</a> | ";
    }
    else{
        $rest = "  <a href='$tp/accounts/stats.php'>Stats</a> | <a href='$tp/pages.php'>Pages</a> | ";
    }
    $first = $am->getFirstName($username);
    $last = $am->getLastName($username);
    $msg = "<p>Greetings , " . $first . " " . $last .   " (<a href='$tp/accounts/settings.php'>Settings</a> | <a href='$tp/accounts/user.php?user=$username'>Profile</a> | " . $rest .  "<a href='$tp/account_scripts/user_logout.php'>Logout</a>)</p>" ;
    if($permissions!=0)
    {
        $post = "<a href='$tp/posts/create_post.php'>Make a post</a>";
    }
    $points = $am->getPoints($username);
    $pointName = $am->getTextSetting("point_name");
    $p = "You have $points $pointName.";
}
$splitter = explode(",",$am->getColor("header_color"));
$r = $splitter[0];
$g = $splitter[1];
$b = $splitter[2];
$style = "background-color: rgba($r,$g,$b,.7);";
$header = "<div class='header' style='$style'>
    <table width='100%'>
        <tr>
            <td><p><a href='$tp/feed.php'>Home</a></p></td>
            <td><p style='color: white;'>" . $msg . "</p></td>
            <td><p>" . $post . "</p></td>
            <td><p>$p</p></td>
        </tr>
    </table>
</div>
";
echo $header;