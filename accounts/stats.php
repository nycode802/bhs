<?php
/**
 * Created by PhpStorm.
 * User: 
 * Date: 10/1/2015
 * Time: 9:02 PM
 */
include_once "../DatabaseManager.php";
$am = DatabaseManager::getInstance();
session_start();
$userID = $am->getUserID($_SESSION['westbvlb_username']);
$stats = $am->getStatArray($userID);
$statsPage = $am->getStat($userID, "banana_max:10");
$siteTitle = $am->getTextSetting("site_name");
$tagLine = $am->getTextSetting("stat_tagline");
$tagLine = html_entity_decode($tagLine);
$points = $am->getPoints($am->getUsername($userID));
$minPoints = $am->getActivePoints();
if($points >= $minPoints)
{
    $active = ($am->getTextSetting("active_text"));
}
else{
    $active = ($am->getTextSetting("inactive_text"));
}

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

        $statName = $am->getStatName($stat);

        // echo $am->getStat(1,$stat) . "<br>";
        $statsPage .= '<br>
      <div class="container" width="50%" style="width: 80%">
     <h2>'. $statName . '</h2>
    <div class="progress">
      <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="' . $statVal . '" aria-valuemin="0" aria-valuemax="'  . $statMax .'" style="width:' . $per .'%">
       <span style="color: black">' . $iP . ' (<span style="color:red;">' . $statVal . '/' . $statMax . '</span>)</span>
     </div>
      </div>
</div>

';
    }
}









//***_)$(*@#)$(*@)#($* TO FRONT END $)(*)#$(*%)@(*$)#(*)((
?>
<DOCTYPE HTML>
    <HTML>
    <HEAD>
        <title><?php echo $siteTitle; ?> | Stats</title>
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
        <h1><?php echo $am->getUsername($userID); ?>'s statistics</h1>
        <br><h2> <?php echo $active; ?></h2>
        <br><h3><?php echo $tagLine; ?></h3>
        <?php echo $statsPage; ?>
    </DIV>
    </BODY>
    </HTML>



</DOCTYPE>
