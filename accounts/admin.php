<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/20/2015
 * Time: 3:20 PM
 */
session_start();
$username = $_SESSION['westbvlb_username'];
require_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$perm = $am->getPermission($username);
$mode = $_GET['m'];
$siteName = $am->getTextSetting("site_name");
$post_container = $am->getStyleColor("admin_color");
if($perm<=1)
{
    header('Location: ../index.php');
}
if($mode==1)
{
if($perm==2)
{
    $username = $am->getUsername($am->getUserID($_GET['u']));
    $users = $am->getUsers();
    $users = explode(",",$users);
    $page = "<p><a href='admin.php?m=0'>Website Settings</a></p>";
	$userID = $am->getUserID($username);
    if(isset($username))
    {
        $joined = $am->getJoinDate($username);
        $lastLogin = $am->getLastLogin($username);
        $page .= "<h2>" . $am->getFirstName($username) . " " . $am->getLastName($username) . "</h2><br>";
        $page .= "<h3>$username</h3><br><h4>Last logged in $lastLogin<br>Joined $joined</h4>";
        $page .= "<form method='post' action='../admin/delete_user.php'>
                 <input type='hidden' value='$username' name='westbvlb_username' />
                 <input type='submit' value='Delete User' name='submit' />
                 </form>
    <br><br>  <hr width='50%'/><br><br>";
        $page .= "<form method='post' action='../admin/rename_user.php'>
                  <input type='hidden' name='westbvlb_username' value='$username'/>
                  <input type='text' name='westbvlb_changed' /><br>
                  <input type='submit' name='submit' value='Change Username'/>
                  </form><br><br><hr width='50%'/><br><br>";
        $page .="<form method='post' action='../admin/admin_update_description.php'>
                <input type='hidden' value='$username' name='westbvlb_username' />
                <textarea name='westbvlb_description'></textarea>
                <br>
                <input type='submit' name='submit' value='Change Description'/>
                </form><br><br><hr width='50%'/><br><br>";
        $page .="<form method='post' action='../admin/admin_update_signature.php'>
                <input type='hidden' value='$username' name='westbvlb_username' />
                <textarea name='westbvlb_signature'></textarea>
                <br>
                <input type='submit' name='submit' value='Change Signature'/>
                </form><br><br><hr width='50%'/><br><br>";
        $page .="<form method='post' action='../admin/reset_profile_picture.php'>
                 <input type='hidden' value='$username' name='westbvlb_username' />
                 <input type='submit' value='Reset Profile Picture' />
                 </form><br><br><hr width='50%' /> ";
        $page .="";
				 $page .= "<form method='post' action='../admin/admin_update_user_text.php'>
				 <input type='hidden' name='id' value='$userID' />
				 <select name='setting'>
				 <option value='first_name'>First Name</option>
				 <option value='last_name'>Last Name</option>
				 </select>
				 <input type='text' name='value'/>
				 <input type='submit' name='submit' value='Update Name Setting' />
				 </form>
				 <br><br><hr width='50%' />";
		$page .= "<form method='post' action='../admin/admin_update_password.php'>
				<input type='hidden' name='id' value='$userID' />
				<input type='password' name='password' />
				<input type='submit' name='submit' value='Change Password' />
		</form><br><br><hr width='50%' />";
		$page .= "<form method='post' action='../admin/admin_update_permission.php'>
                 <input type='hidden' name='westbvlb_username' value='$username' />
                 <select name='westbvlb_permission'><br><br><hr width='50%' />";
        foreach($am->getRanks() as $rank)
        {
            $name = explode(":",$rank)[0];
            $level = explode(":",$rank)[1];
            $page .= "<option value='$level'>$name</option>";
        } 
        $page .="</select><br><input type='submit' value='Set Permission' /></form><br><br><hr width='50%' /><h3>Set Inactive</h3><form method='post' action='../admin/update_activity.php'>
        <input type='hidden' name='id' value='$userID' />
       <input type='checkbox' name='status' />


        <label><input type='submit' name='submit' value='Update Status' ></label>
</form> ";


    }
    else
    {
        $page .= "<form method='get' action='admin.php'>
    <input type='hidden' name='m' value='1' />
<select name='u'>
";
        foreach($users as $user)
        {
            if(!empty($user))
            {

                $uF = $am->getFirstName($user);
                $uL = $am->getLastName($user);
                $page .= "<option value='$user'>$uF $uL</option>";
            }

        }
        $page .= "</select><br>
    <input type='submit' value='Select' />
    </form>
    <hr width='50%' />
    <a href='../admin/stats.php' target=\"_self\">Stat Settings</a>
";
    }



}else{
    header('Location: ../feed.php');
}
}
else{
    if(isset($username))
    {
        $opacity = $am->getOpacity();
        $page = "<p><a href='admin.php?m=1'>Account Settings</a></p>";
        $page .= "
  <hr width='50%'>
    <h4>Update an image</h4>
<form enctype='multipart/form-data' action='../backend/update_image.php' method='POST'><br> Please choose a file:     <br>  <input name='uploaded' type='file' class='fileChooser'>
    <br><br>
    <select name='name'>
    <option value='background.jpg'>Page Background</option>
    <option value='logo.png'>Page Logo</option>
    <option value='like.png'>Like Icon</option>
    </select>
    <br>
    <input type='submit' value='Upload' class='uploadButton'> 
    </form><br><br>
    <hr width='50%'>
    <h4>Update a color</h4>
    <form action='../backend/update_color.php' method='post'>
    <input type='color' id='' name='color' class='' onchange='clickColor(0, -1, -1, 5)' value='#ff0000'>
    <select name='name'>
    <option value='header_color'>Header</option>
    <option value='post_container'>Post Container</option>
    <option value='user_page_color'> Posts / Comments Color</option>
    <option value='newest_post_color'>Newest Post Color</option>
    <option value='newest_comment_color'>Newest Comment Color</option>
    <option value='title_color'>Title Color</option>
    <option value='button_color'>Button Color</option>
    <option value='text_box_color'>Text Box Color</option>
    <option value='admin_color'>Admin Panel Color</option>
    <option value='tagbar_container'>Tagbar Container Color</option>
    <option value='dyn_page_color'>Dynamic Page Color</option>
    <br>

    </select>
    <input type='submit' name='submit' value='Change Color' />
    </form>
    <hr width='50%'>
    <h4>Update a text setting</h4>
    <form action='../backend/update_setting.php' method='post'>
    <select name='name'>
    <option value='like_name'>Like Text</option>
    <option value='title_text'>Title Text</option>
    <option value='site_name'>Site Name</option>
    <option value='point_name'>Point Name</option>
    <option value='site_tagline'>Site Tagline</option>
    <option value='stat_tagline'>Statistic Page Tagline</option>
    <option value='active_text'>Active Member Text</option>
    <option value='inactive_text'>Inactive Member Text</option>
    </select>
    <br>
    <textarea name='value' value='' rows='8' cols='40'></textarea>
    <br>
    <input type='submit' name='submit' value='Set' />
    </form><hr width='50%' />
    <H4>Button Texts</H4>
    <form method='post' action='../backend/update_setting.php'>
    <select name='name'>
    <option value='like_text'>Like Button Text</option>
    <option value='unlike_text'>Unlike Button Text</option>
    </select>
    <input type='text' maxlength='8' name='value'/>
    <br>
    <input type='submit' name='submit' value='Update' />
    </select>
    </form><br><br><hr width='50%' />
    <H4>Opacity</H4>
    <form method='post' action='../backend/update_setting.php'>
    <input type='hidden' name='name' value='opacity' />
    <input type='number' name='value' step='.1' min='0' max='1' value='$opacity'/>
    <br><input type='submit' name='submit' value='Set Opacity' />
</form><br><Br><hr width='50%'>
<h4>Minimum Active Points</h4>
<form method='post' action='../backend/update_setting.php'>
<input type='number' name='value' value=''/>
<input type='hidden' name='name' value='active_points' />
<br>
<input type='submit' value='Set Active Points' />
</form>
    ";

    }
}


?>
<html>
<head>
    <style>
        <?php echo $am->getButtonCSS(); ?>
        <?php echo $am->getTextAreaHTML(); ?>
    </style>
    <link rel='icon' type='image/png' href='../images/favicon-32x32.png' sizes='32x32' />
    <link rel='icon' type='image/png' href='../images/favicon-16x16.png' sizes='16x16' />
    <title><?php echo $siteName; ?> | Admin</title>
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
    </script>
</head>

<body class="mainbody">
<div id="header"></div><br><br><Br><br>
<div class="post_container" style="text-align: center; <?php echo $post_container; ?>">
    <div class="post">
        <h1>Admin Settings</h1>
        <hr width="50%" style=""/>
        <br>
        <?php echo $page; ?>
    </div>
</div>
</body>
</html>
