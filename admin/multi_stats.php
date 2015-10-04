<?php
include_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$siteTitle = $am->getTextSetting("site_name");
$bgStyle = $am->getStyleColor("admin_color");
session_start();
$permission = $am->getPermission($_SESSION['westbvlb_username']);
$currentUsers = array();
if(isset($_POST['selected_users']))
{
    $currentUsers = unserialize($_POST['selected_users']);
}
$selectedUser = $_POST['userid'];
if($permission<1)
{
    header('Location: ../account_scripts/user_logout.php');
}
array_push($currentUsers, $selectedUser);
$page .="
<center>
<table width='20%' style='text-align: left; border-color: black; border-width: 2px;'>";
if(count($currentUsers)>1)
{
    $page .= "<tr style='margin-bottom: 15px;'>
<td><u>First Name</u><br></td>
<td><u>Last Name</u><br></td>
</tr>";
}

$page.= "<br>";
foreach($currentUsers as $userID)
{
    $username = $am->getUsername($userID);
    $uFirst = $am->getFirstName($username);
    $uLast = $am->getLastName($username);
    $page.="<tr>
        <td>$uFirst</td>
        <td>$uLast</td>
</tr>";
}

$page .="</center></table><br><br><br><hr width='50%' /> ";
$currentUsers = serialize($currentUsers);
$page .= "<h3>Add a user</h3><br><form method='post' action='multi_stats.php'>
          <input type='hidden' name='selected_users' value='$currentUsers' />
          <select name='userid'> ";
            $allUsers = $am->getUsers();
$allUsers = explode(",",$allUsers);
$currentUsers = unserialize($currentUsers);
foreach($allUsers as $user)
{
    $userID = $am->getUserID($user);
    $uFirst = $am->getFirstName($user);
    $uLast = $am->getLastName($user);
    if(!in_array($userID,$currentUsers))
    {
        $page .= "<option value='$userID'>$uFirst $uLast</option>";
    }
}
$currentUsers = serialize($currentUsers);
        $page .= "</select><input type='submit' value='Add User' /> </form>

<br><Br><form method='post' action='multi_stats.php'>
            <input type='submit' value='Reset Users' />
</form>   <hr width='50%' /><br><Br><h4>Update a statistic</h4>";
        $page .= "<form method='post' action='set_multi_stats.php'>
                <input type='hidden' name='users' value='$currentUsers' />
                <h5>Select a statistic</h5>
                <select name='stat_name'>";
        foreach ($am->getStatArray(-1) as $stat) {
            $statName = $am->getStatName($stat);
            if(!strpos($statName,"_max"))
            {
                $page .="<option value='$statName'>$statName</option>";
            }
        }


$page .= "</select><br><br>
<h5>Select a value</h5>
<input type='number' name='stat_value' step='.1' />
<h5>Select a type</h5>
<select name='m'>
<option value='0'>Set Value</option>
<option value='1'>Add Value</option>
</select><br><br><input type='submit' name='submit' value='Update' />

</form><br><br><hr width='50%' /> ";
$page .= "<h4>Update a true/false statistic</h4>";
$page .= "<form method='post' action='set_multi_stats.php'>
                <input type='hidden' name='users' value='$currentUsers' />
                <h5>Select a statistic</h5>
                <select name='stat_name'>";
foreach ($am->getStatArray(-1) as $stat) {
    $statName = $am->getStatName($stat);
    if(!strpos($statName,"_max"))
    {
        $page .="<option value='$statName'>$statName</option>";
    }
}


$page .= "</select><br><br>
<h5>Select true/false</h5>
<select name='stat_value'>
<option value='1337'>True</option>
<option value='1227'>False</option>
</select>
<h5>Select a type</h5>
<select name='m'>
<option value='0'>Set Value</option>
<option value='1'>Add Value</option>
</select><br><br><input type='submit' name='submit' value='Update' />

</form>";

?>
<DOCTYPE HTML>
    <HTML>
    <HEAD>
        <title><?php echo $siteTitle; ?> | Stat Settings</title>
        <link rel='icon' type='image/png' href='../images/favicon-32x32.png' sizes='32x32' />
        <link rel='icon' type='image/png' href='../images/favicon-16x16.png' sizes='16x16' />
        <link rel="stylesheet" type="text/css" href="../styles.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
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
    <BODY class="mainbody">
    <DIV id="header"></DIV>
    <br><br><br>
    <DIV class="post_container" style="<?php echo $bgStyle;?> text-align: center;">
        <?php echo $page; ?>
    </DIV>
    </BODY>
    </HTML>



</DOCTYPE>
