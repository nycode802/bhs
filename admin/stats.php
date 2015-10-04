<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 10/1/2015
 * Time: 9:02 PM
 */
include_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
$siteTitle = $am->getTextSetting("site_name");
$bgStyle = $am->getStyleColor("admin_color");
$user = $_GET['id'];
$username = $am->getUsername($user);
$first = $am->getFirstName($username);
$last = $am->getLastName($username);
session_start();
$session = $_SESSION['westbvlb_user_token'];
$permission = $am->getPermission($_SESSION['westbvlb_username']);
if($permission<1)
{
    header('Location: ../account_scripts/user_logout.php');
}
if(isset($user) && !empty($user))
{
    $points = $am->getPoints($username);
    $pointName = $am->getTextSetting("point_name");
    $page = "<h3>Statistics for <u>$first $last</u></h3><hr width='50%' style='margin-bottom: 15px;'/><br><p>This user currently has <u>$points $pointName</u></p>";
    $page .= "
                <h4>Change User Points</h4>
              <form method='post' action='add_points.php'>
              <input type='hidden' name='session' value='$session' />
              <input type='hidden' name='userid' value='$user' />
              <input type='number' name='points' step='.1'/><br>
              <input type='submit' name='submit' value='Add Points' />
              </form><br>
              <span style='color: red;'><p><i>Type a negative number to subtract points</i></p></span>
              <br><br><hr width='50%' /><br><h4>Update a statistic</h4>
              <form method='post' action='update_stat.php'>
              <select name='stat'>";
    foreach($am->getStatArray($user) as $stat){
        if(!strpos($stat, "_max"))
        {
            $page .="<option value='$stat'>" . $am->getStatName($stat) . "</option>";
        }
    }


$page .= "</select>
<br><br>
<input type='number' name='value' />
<input type='hidden' name='id' value='" . $user . "' />
<br><br>
<input type='submit' value='Add Statistical Value' />
<br><br>
<input type='submit' formaction='set_stat.php' value='Set Statistical Value' />
</form>";
    $page .= "
              <br><br><hr width='50%' /><br><h4>Update a true/false statistic</h4>
              <form method='post' action='update_stat.php'>
              <select name='stat'>";
    foreach($am->getStatArray($user) as $stat){
        if(!strpos($stat, "_max"))
        {
            $page .="<option value='$stat'>" . $am->getStatName($stat) . "</option>";
        }
    }


    $page .= "</select>
<br><br>
<select name='value'>
<option value='1337'>True</option>
<option value='1227'>False</option>
</select>
<input type='hidden' name='id' value='" . $user . "' />
<br><br>
<input type='submit' formaction='set_stat.php' value='Set Value' />
</form>";
    $tagline = $am->getTextSetting("stat_tagline");


$page .= "<br><br>
        <form method='post' action='admin_reset_stats.php'>
        <input type='hidden' name='id' value='$userID'/>
        <input type='submit' value='Reset User Statistics' />
</form>

<hr width='50%' /><h3>Stats</h3><h4>$tagline</h4><br>";

    $userID = $user;
    $stats = $am->getStatArray($userID);
    foreach($stats as $stat)
    {
        if(!strpos($stat,"_max"))
        {
            $statVal = $am->getStat($userID,$am->getStatName($stat));
            $statMax = $am->getStat($userID, $am->getStatName($stat) . "_max") - 1;

            if($statVal==1227)
            {
                $iP = "FALSE";
                $per = 0;
            }
            else if($statVal==1337)
            {
                $iP = "TRUE";
                $per = 100;
            }
            else{
                $per = $statVal / $statMax * 100;
                $iP = intval($per) . "%";
            }
            // echo $am->getStat(1,$stat) . "<br>";
            $statName = $am->getStatName($stat);
            $statsPage .= '
      <div class="container" width="50%" style="width: 80%">
     <h4>'. $statName . '</h4>
    <div class="progress">
      <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="' . $statVal . '" aria-valuemin="0" aria-valuemax="'  . $statMax .'" style="width:' . $per .'%">
       <span style="color: black">' . $iP . ' (<span style="color:red;">' . $statVal . '/' . $statMax . '</span>)</span>
     </div>
      </div>
</div>

';
        }
    }
    $page .= $statsPage;




}
else{
    $page .= "<form method='get' action='stats.php'>
<select name='id'>
";
    $users = $am->getUsers();
    $users = explode(",",$users);
    foreach($users as $user)
    {
        if(!empty($user))
        {

            $uF = $am->getFirstName($user);
            $uL = $am->getLastName($user);
            $page .= "<option value='" . $am->getUserID($user) . "'>$uF $uL</option>";
        }

    }
    $page .= "</select><br>
    <input type='submit' value='Select' />
    </form>
";
    $page .="<hr width='50%' /><br><h3>Create a stat</h3>";
    $page .="<form method='post' action='create_statistic.php'>
            <input type='text' name='name' />
            <h4>Max</h4>
            <input type='number' name='max' />
            <h4>Starting number</h4>
            <input type='number' name='min' />
            <input type='submit' name='Submit' value='Create' />
            </form>
            <br><br><hr width='50%' /><h3>Create True / False Stat</h3>
            <form method='post' action='create_statistic.php'>
            <input type='hidden' name='min' value='1227' />
            <input type='hidden' name='max' value='1337' />
            <input type='text' name='name' />
            <input type='submit' value='Create a True / False Statistic' />
            </form>


            <br><br><hr width='50%' /><br><h3>Delete a stat</h3>
            <form method='post' action='delete_statistic.php'>
            <select name='name'>
            ";
    foreach($am->getStatArray(-1) as $stat){
        if(!strpos($stat, "_max"))
        {
            $page .="<option value='$stat'>" . $am->getStatName($stat) . "</option>";
        }
    }
    $page .= "</select><input type='submit' name='submit' value='Delete' /> </form>
    <br><br>

";
    if($permission==2)
    {
        $page .= "<h3>Reset stats</h3>
    <form method='post' action='admin_reset_stats.php'>
    <input type='submit' value='Submit' />
    </form>";
    }
}
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
    <br><br>
    <DIV class="post_container" style="<?php echo $bgStyle;?> text-align: center;">
        <h1 onclick="location.href='multi_stats.php'">Multi User Stats</h1>
    <?php echo $page; ?>
    </DIV>
    </BODY>
    </HTML>



</DOCTYPE>
