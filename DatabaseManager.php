<?php

/**
 * Created by PhpStorm.
 * User: 
 * Date: 9/20/2015
 * Time: 11:37 AM
 */
date_default_timezone_set("America/New_York");
class DatabaseManager
{

    var $host;
    var $user;
    var $pass;
    var $database;
    private $_connection;

    function __construct($host, $user, $pass, $database)
    {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->database = $database;
        $this->connect();
    }

    private static $_instance;

    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new DatabaseManager("localhost", "username", "password", "database");
        }
        return self::$_instance;
    }

    function getCommentCount($postID)
    {
        $sql = "SELECT comments FROM posts WHERE id='$postID'";
        while ($row = mysql_fetch_array(mysql_query($sql))) {
            $comments = unserialize($row['comments']);
            if (empty($row['comments'])) {
                return 0;
            }
            return count($comments);
        }
    }

    function setupDatabase()
    {
        $sql = "CREATE TABLE `users` (
 `id` INT(11) NOT NULL AUTO_INCREMENT,
 `username` VARCHAR(15) NOT NULL,
 `password` VARCHAR(15) NOT NULL,
 `email` VARCHAR(30) NOT NULL,
 `phone` VARCHAR(15) NOT NULL,
 `signature` MEDIUMTEXT NOT NULL,
 `description` MEDIUMTEXT NOT NULL,
 `posts` VARCHAR(15) NOT NULL,
 `permission` INT(11) NOT NULL,
 `points` INT(15) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1";
        mysql_query($sql);
        $sql = "CREATE TABLE `permissions` (
 `rank` VARCHAR(20) NOT NULL,
 `level` INT(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1";
        mysql_query($sql);
        $sql = "CREATE TABLE `posts` (
 `id` INT(15) NOT NULL AUTO_INCREMENT,
 `type` INT(5) NOT NULL,
 `title` VARCHAR(120) NOT NULL,
 `text` MEDIUMTEXT NOT NULL,
 `likes` MEDIUMTEXT,
 `comments` MEDIUMTEXT NOT NULL,
 `time` VARCHAR(20) NOT NULL,
 `views` MEDIUMTEXT NOT NULL,
 `userid` VARCHAR(15) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=latin1";
        mysql_query($sql);
        $sql = "CREATE TABLE `comments` (
 `id` INT(15) NOT NULL AUTO_INCREMENT,
 `userid` VARCHAR(11) NOT NULL,
 `text` MEDIUMTEXT NOT NULL,
 `time` VARCHAR(20) NOT NULL,
 `postid` INT(11) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=latin1";
        mysql_query($sql);
    }

    function connect()
    {
        $this->_connection = mysql_connect($this->host, $this->user, $this->pass);

        if (!mysql_select_db($this->database)) {
            $this->setupDatabase();
        }
    }

    public function getConnection()
    {
        return $this->_connection;
    }

    function getCommentID()
    {
        $sql = "SELECT id FROM comments ORDER BY id DESC";
        $data = mysql_query($sql);
        while ($row = mysql_fetch_array($data)) {
            return $row['id'];
        }
    }

    function verifyLogin($username, $password)
    {
        $sql = "SELECT * FROM users WHERE username='" . $username . "' AND password='" . md5($password) . "'";
        $response = mysql_query($sql);
        if (mysql_num_rows($response) > 0 and $this->isActive($this->getUserID($username)) == 0) {
            $this->login($username);
            return true;
        }
        return false;
    }
    function login($username)
    {
        $userID = $this->getUserID($username);
        $time = date('m/d/Y g:i A');
        $sql = "UPDATE users SET last_login='$time' WHERE id='$userID'";
        mysql_query($sql);
    }

    function getUserToken($username)
    {
        $sql = "SELECT session_token FROM users WHERE username='$username'";
        $response = mysql_query($sql);
        while ($row = mysql_fetch_array($response)) {
            return $row['session_token'];
        }
    }

    function generateUserToken($username)
    {
        $milliseconds = round(microtime(true) * 1000);
        $enc = md5($milliseconds);
        $sql = "UPDATE users SET session_token='$enc' WHERE username='$username'";
        mysql_query($sql);
        return $enc;
    }

    function checkProfile($username)
    {
        $username = strtolower($username);
        if (!file_exists("../profile/pictures/$username.jpg")) {
            $file = "../profile/pictures/default.jpg";
            $newfile = "../profile/pictures/" . $username . ".jpg";
            copy($file, $newfile);
        }
    }

    function getJoinDate($username)
    {
        $sql = "SELECT join_date FROM users WHERE username='$username'";
        $response = mysql_query($sql);
        while ($row = mysql_fetch_array($response)) {
            return $row['join_date'];
        }
    }
    function getLastLogin($username)
    {
        $sql = "SELECT last_login FROM users WHERE username='$username'";
        $response = mysql_query($sql);
        while ($row = mysql_fetch_array($response)) {
            return $row['last_login'];
        }
    }
    function createPage($userid, $content, $name)
    {
        $content = htmlspecialchars($content, ENT_QUOTES);
        $name = htmlspecialchars($name, ENT_QUOTES);
        $time = date('m/d/Y');
        $sql = "INSERT INTO pages (id, userid, page_content, page_name, date_created) VALUES (NULL, '$userid', '$content', '$name', '$time')";
        mysql_query($sql);
    }
    function editPage($pageID, $content, $name)
    {
        $content = htmlspecialchars($content, ENT_QUOTES);
        $name = htmlspecialchars($name, ENT_QUOTES);
        $sql = "UPDATE pages SET page_content='$content', page_name='$name' WHERE id='$pageID'";
        mysql_query($sql);
    }
    function deletePage($pageID)
    {
        $sql = "DELETE FROM pages WHERE id='$pageID'";
        mysql_query($sql);
    }
    function getPageName($pageID)
    {
        $sql = "SELECT page_name FROM pages WHERE id='$pageID'";
        $rep = mysql_query($sql);
        while($row = mysql_fetch_array($rep))
        {
            return html_entity_decode($row['page_name'], ENT_QUOTES);
        }
    }
    function getPageUserID($pageID)
    {
        $sql = "SELECT userid FROM pages WHERE id='$pageID'";
        $rep = mysql_query($sql);
        while($row = mysql_fetch_array($rep))
        {
            return $row['userid'];
        }
    }
    function getPages()
    {
        $pages=array();
        $sql = "SELECT id FROM pages";
        $response = mysql_query($sql);
        while($row = mysql_fetch_array($response))
        {
            array_push($pages, $row['id']);
        }
        return $pages;
    }
    function getPageContent($pageID)
    {
        $sql = "SELECT page_content FROM pages WHERE id='$pageID'";
        $rep = mysql_query($sql);
        while($row = mysql_fetch_array($rep))
        {
            return html_entity_decode(html_entity_decode(html_entity_decode($row['page_content'], ENT_QUOTES), ENT_QUOTES), ENT_QUOTES);
        }
    }
    function getPageCreated($pageID)
    {
        $sql = "SELECT date_created FROM pages WHERE id='$pageID'";
        $rep = mysql_query($sql);
        while($row = mysql_fetch_array($rep))
        {
            return $row['date_created'];
        }
    }
    function getStatArray($userID)
    {
        $sql = "SELECT stats FROM users WHERE id='$userID'";
        $response = mysql_query($sql);
        while ($row = mysql_fetch_array($response)) {
            return unserialize($row['stats']);
        }
    }

    function getStatName($stat)
    {
        return explode("=", $stat)[0];
    }

    function getStat($userID, $name)
    {
        $stats = $this->getStatArray($userID);
        foreach ($stats as $stat) {
            if (($this->getStatName($stat)) == $name) {
                return $this->getStatValue($stat);
            }
        }

    }

    function getStatValue($stat)
    {
        return explode("=", $stat)[1];
    }

    function getStatValueFromName($name, $userID)
    {
        $array = $this->getStatArray($userID);
        foreach ($array as $f) {
            $fName = $this->getStatName($f);
            if (strcmp($name, $fName)) {
                return $this->getStatValue($f);
            }
        }
    }

    function getStatMax($stat, $userID)
    {
        return $this->getStatValueFromName($this->getStatName($stat) . "_max", $userID);
    }

    function deleteStat($userID, $name)
    {
        $stats = $this->getStatArray($userID);
        $array = array();
        if (!empty($stats)) {
            if (isset($stats)) {
                foreach ($stats as $stat) {
                    $boom = explode("=", $stat);
                    $bName = $boom[0];
                    $bValue = $boom[1];
                    if ($bName != $name) {
                        array_push($array, $bName . "=" . $bValue);
                    }
                }
            }
        }
        $array = serialize($array);
        $this->setStatArray($userID, $array);
    }

    function setUserStatus($userID, $status)
    {
        $sql = "UPDATE users SET status='$status' WHERE id='$userID'";
        mysql_query($sql);
    }

    function isActive($userID)
    {
        $sql = "SELECT status FROM users WHERE id='$userID'";
        $rep = mysql_query($sql);
        while($row = mysql_fetch_array($rep))
        {
            return $row['status'];
        }
    }

    function setStat($userID, $name, $value)
    {

        $stats = $this->getStatArray($userID);
        $array = array();
        if (!empty($stats)) {
            if (isset($stats)) {
                foreach ($stats as $stat) {
                    $boom = explode("=", $stat);
                    $bName = $boom[0];
                    $bValue = $boom[1];
                    if ($bName != $name) {
                        array_push($array, $bName . "=" . $bValue);
                    }
                }
            }
        }

        array_push($array, $name . "=" . $value);
        $array = serialize($array);
        $this->setStatArray($userID, $array);
    }

    function setStatArray($userID, $serializedArray)
    {
        $sql = "UPDATE users SET stats='$serializedArray' WHERE id='$userID'";
        mysql_query($sql);
    }

    function getUserComments($username)
    {
        $userID = $this->getUserID($username);
        $sql = "SELECT * FROM comments WHERE userid='$userID' ORDER BY id DESC";
        $response = mysql_query($sql);
        $comments = "";
        while ($row = mysql_fetch_array($response)) {
            $username = $this->getUsername($userID);
            $text = html_entity_decode($row['text']);
            $time = $row['time'];
            $postID = $row['postid'];
            $postName = $this->getPostTitle($postID);
            $containerColor = $this->getColor("user_page_color");
            $splitter = explode(",", $containerColor);
            $r = $splitter[0];
            $g = $splitter[1];
            $b = $splitter[2];
            $style = "background-color: rgba($r,$g,$b,.7);";
            $comments .= "
            <div class='post_container' style='border-radius: 2px; margin-top: 5px; margin-bottom: 5px; $style' onclick=\"location.href='../posts/post.php?id=$postID'\">
            <table width='100%'>
            <tr>
            <td width='75%'>       <h4>Commented on $postName</h4>
            <br><p>$text</p></td>
            <td width='25%'>$time</td>
            </tr>
            </table>
            </div>
            ";
        }
        return $comments;
    }

    function getUserPosts($username)
    {
        $posts = "";
        $userID = $this->getUserID($username);
        $sql = "SELECT * FROM posts WHERE userid='$userID' ORDER BY id DESC";
        $data = mysql_query($sql);
        while ($row = mysql_fetch_array($data)) {
            $postID = $row['id'];
            $title = $row['title'];
            $text = $row['text'];
            $likes = $this->getLikes($postID);
            $comments = $this->getCommentCount($postID);
            $time = $row['time'];
            $views = $this->getViews($postID);
            $likeName = $this->getLikeName();
            $containerColor = $this->getColor("user_page_color");
            $splitter = explode(",", $containerColor);
            $r = $splitter[0];
            $g = $splitter[1];
            $b = $splitter[2];
            $style = "background-color: rgba($r,$g,$b,.7);";
            $str = "<div class='post_container' style='border-radius: 2px; margin-top: 0px; margin-bottom: 15px; text-align: center; $style' onclick='location.href=\"../posts/post.php?id=$postID\"'>
                <table width='100%'>
                <tr>
                <td width='75%'><h3>$title</h3><br>$text</td>
                <td width='25%'>$time<br>$likes $likeName<br>$comments comment(s)<br>$views view(s)</td>
                </tr>
                </table>
                </div>";
            $posts .= $str;
        }
        return $posts;
    }
    function uploadFile($file)
    {
        $target_dir = "../files/";
        $target_file = $target_dir . basename($file["name"]);
        $uploadOk = 1;
        if (file_exists($target_file)) {
            return '/files/' . basename( $file["name"]);
            $uploadOk = 0;
        }
        if ($file["size"] > 500000) {
            return "ErrSize";
            $uploadOk = 0;
        }
        if ($uploadOk == 0) {
            return "Err";
        } else {
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
               // echo "The file ". basename( $file["name"]). " has been uploaded.";
                return '/files/' . basename( $file["name"]);
            } else {
               // echo "Sorry, there was an error uploading your file.";
            }
        }
    }
    function resetStats()
    {
        $defStats = $this->getStatArray(-1);
        foreach(explode(",", $this->getUsers()) as $user)
        {
            $userID = $this->getUserID($user);
            $this->setStatArray($userID, serialize($defStats));
        }
    }
    function setupStats($userID)
    {
        $this->setStatArray($userID, $this->getStatArray(-1));
    }
    function getOpacity()
    {
        $sql = "SELECT value FROM settings WHERE name='opacity'";
        $response = mysql_query($sql);
        while ($row = mysql_fetch_array($response))
        {
            return $row['value'];
        }
    }
    function getStyleColor($name)
    {
        $splitter = explode(",",$this->getColor($name));
        $r = $splitter[0];
        $g = $splitter[1];
        $b = $splitter[2];
        $o = $this->getOpacity();
        $color = "background-color: rgba($r,$g,$b,$o);";
        return $color;
    }
    function getTextSetting($name)
    {
        $sql = "SELECT value FROM settings WHERE name='$name'";
        $response = mysql_query($sql);
        while($row = mysql_fetch_array($response))
        {
            return htmlspecialchars_decode(html_entity_decode($row['value']), ENT_QUOTES);
        }
    }
    function getButtonCSS()
    {
        $style = $this->getStyleColor("button_color");
        $CSS = "input[type=submit]
        {
        $style;
        }

        ";
        return $CSS;
    }
    function getTextAreaHTML()
    {
        $style = $this->getStyleColor("text_box_color");
        $CSS= "input[type=text], textarea, input[type=password], input[type=email]{
        $style;
        }
        ";
        return $CSS;
    }
    function fetchPosts($mode, $page)
    {

        $posts = "<div class='post_container' style='" . $this->getStyleColor("post_container") . " box-shadow: 0px 0px 0px #000000; padding-top: 2px; padding-bottom: 2px;'>";

        $size = 0;
        $per = 10;
        if($mode==0)
        {
            $m = "id DESC";
        }
        else{
            $m = "length(likes) DESC, views DESC, time ASC";
        }
        $start = $per * $page;
        $sql = "SELECT * FROM posts ORDER BY " . $m . " LIMIT $start,$per";
        $response = mysql_query($sql);
        while($row = mysql_fetch_array($response))
        {
            $commentCount = $this->getCommentCount($row['id']);

            $title = $row['title'];
            $preview = substr($row['text'],  0, 135);
            if($preview==false)
            {
                $preview = $row['text'];
            }
            else{
                $preview = $preview . "...";
            }
            $preview = html_entity_decode($preview);
            $title = html_entity_decode($title);
            $username = $this->getUsername($row['userid']);
            $time = $row['time'];
            $type = $row['type'];
            $cookies = $this->getLikes($row['id']);
            $views = $this->getViews($row['id']);
            $likeName = $this->getLikeName();
            if($type==3)
            {
                $preview = "<img src='" . $row['text'] . "' width='95%' height='auto' />";
            }
            $rank = $this->getRank($this->getPermission($username));
            $style = $this->getStyleColor("user_page_color");
            $posts = $posts .
                "   <div class='post_container' style='padding: 0px; width: 95%; $style'>  <div class='post'>
        <a href='posts/post.php?id=" . $row['id'] . "'>
        <table width='100%'>
            <tr>
                <td width='20%' style='margin-left: 5px; margin-right: 5px;'><center><a href='accounts/user.php?user=$username'><img src='profile/pictures/" . strtolower($username).".jpg' style='width: 50%; height: auto; border-radius: 40px;'/><br><br>$username</a><br>$rank</center></td>
                <td width='60%' style='text-align: center;'><h3>" . $title . "</h3><p>" . $preview . "</p></td>
                <td width='20%'>$time<br></td>
            </tr>


        </table>
        <table width='100%'>
          <tr>
            <td width='20%'></td>
            <td width='20%'>$commentCount comments</td>
            <td width='20%'>$cookies $likeName</td>
            <td width='20%'>$views views</td>
            <td width='20%'></td>
          </tr>
        </table>
        </a>
    </div></div>";
            $size=$size+1;
        }
        if(!(isset($mode)))
        {
            $mode = 0;
        }
        $mm = $_GET['m'];
        $pp = $page;
        $send = "";
        if($size==$per)
        {
            $send .= "<table width='100%'>
    <tr>
";

            if($_GET['p']>=1)
            {

                $send .= "<td><div class='post_container' style='text-align: center;' onclick='location.href=\"feed.php?m=$mm&p=" . ($pp - 1).  "\"'> <a href='feed.php?m=" . $_GET['m'] . "&p=" . ($page - 1) . "'>Back</a></div></td>";
            }
            $send .= "<td><div class='post_container' style='text-align: center;' onclick='location.href=\"feed.php?m=" . $_GET['m'] . "&p=" . ($page + 1) . "\"'><a href='feed.php?m=" . $_GET['m'] . "&p=" . ($page + 1) . "'>Next</a></div></td>";
        }
        else{
            $send .= "<td><div class='post_container' style='text-align: center;' onclick='location.href=\"feed.php?m=$mm&p=" . ($pp - 1) . "\"'> <a href='feed.php?m=" .  $_GET['m'] . "&p=" . ($page - 1) . "'>Back</a></div></td>";
        }
        $posts .= $send;
        $posts.="</div>";
        return $posts;
    }
    function getUnlikeText()
    {
        $sql = "SELECT value FROM settings WHERE name='unlike_text'";
        $response = mysql_query($sql);
        while($row = mysql_fetch_array($response))
        {
            return $row['value'];
        }
    }
    function getLikeText()
    {
        $sql = "SELECT value FROM settings WHERE name='like_text'";
        $response = mysql_query($sql);
        while($row = mysql_fetch_array($response))
        {
            return $row['value'];
        }
    }
    function getRank($permission)
    {
        $sql = "SELECT rank FROM permissions WHERE level='$permission'";
        $data = mysql_query($sql);
        while($row = mysql_fetch_array($data))
        {
            return $row['rank'];
        }
    }
    function setupAccount($username,$password,$email,$phone, $first, $last)
    {
        $sql = "SELECT * FROM users WHERE username='$username'";
        $response = mysql_query($sql);
        if(mysql_num_rows($response)==0)
        {
            $password = md5($password);
            $time = date('m/d/Y');
            $sql = "INSERT INTO users(id,join_date, status, username,password,email,phone, first_name, last_name, signature, description, posts, permission, session_token, stats, last_login) VALUES (NULL, '$time', '0', '$username','$password','$email', '$phone', '$first', '$last','', '', '', '0', '','', 'Never')";
            mysql_query($sql);
            $this->checkProfile($username);
            $this->setupStats($this->getUserID($username));
            header('Location: ../feed.php');
        }
        else{
            header('Location: ../accounts/login.php');
        }
    }
    function createStats($userID)
    {

    }
    function getFirstName($username)
    {
        $sql = "SELECT first_name FROM users WHERE username='$username'";
        while($row = mysql_fetch_array(mysql_query($sql)))
        {
            return $row['first_name'];
        }
    }
    function getLastName($username)
    {
        $sql = "SELECT last_name FROM users WHERE username='$username'";
        while($row = mysql_fetch_array(mysql_query($sql)))
        {
            return $row['last_name'];
        }
    }
	function setFirstName($userID, $first)
	{
		$sql = "UPDATE users SET first_name='$first' WHERE id='$userID'";
		$response = mysql_query($sql);
	}
	function setLastName($userID, $last)
	{
		$sql = "UPDATE users SET last_name='$last' WHERE id='$userID'";
		$response = mysql_query($sql);
	}
	function setUserText($userID, $name, $value)
	{
		$sql = "UPDATE users SET $name='$value' WHERE id='$userID'";
		mysql_query($sql);
	}
	function setPassword($userID, $password)
	{
		$password = md5($password);
		$sql = "UDPATE users SET password='$password' WHERE id='$userID'";
		mysql_query($sql);
	}
	function getActivePoints()
    {
        return intval($this->getTextSetting("active_points"));
    }
    function isValidAdminSession($session)
    {
        $sql = "SELECT * FROM users";
        $response = mysql_query($sql);
        while($row = mysql_fetch_array($response))
        {
            if($row['session_token']==$session and $row['permission']>=1)
            {
                return true;
            }
        }

        return false;
    }
    function resetPicture($username)
    {
        $username = strtolower($username);
        $file = "../profile/pictures/default.jpg";
        $newfile = "../profile/pictures/" . $username . ".jpg";
        copy($file, $newfile);
    }
    function renameUser($username, $toUser)
    {
        $id = $this->getUserID($username);
        mysql_query("UPDATE users SET username='$toUser' WHERE id='$id'");
        $this->checkProfile($username);
        $username = strtolower($username);
        $toUser = strtolower($toUser);
        $file = "../profile/pictures/$username.jpg";
        $newfile = "../profile/pictures/" . $toUser . ".jpg";
        copy($file, $newfile);
    }
    function getLikeName()
    {
        $sql = "SELECT value FROM settings WHERE name='like_name'";
        while($row = mysql_fetch_array(mysql_query($sql)))
        {
            return $row['value'];
        }
    }
    function getUserDescription($username)
    {
        $sql = "SELECT description FROM users WHERE username='$username'";
        $response = mysql_query($sql);
        while($row = mysql_fetch_array($response))
        {
            echo html_entity_decode($row['description']);
        }
    }
    function setLikeName($likeName)
    {
        $sql = "UPDATE settings SET value='$likeName' WHERE name='like_name'";
        mysql_query($sql);
    }
    function updateSetting($name, $value)
    {
        $sql = "UPDATE settings SET value='$value' WHERE name='$name'";
        mysql_query($sql);
    }
    function getColor($name)
    {
        $sql = "SELECT value FROM settings WHERE name='$name'";
        while($row = mysql_fetch_array(mysql_query($sql)))
        {
            return $row['value'];
        }
    }
    function updateColor($name, $color)
    {
        $sql = "UPDATE settings SET value='$color' WHERE name='$name'";
        mysql_query($sql);
    }
    function getCorrectUsername($username)
    {
        $sql = "SELECT username FROM users WHERE username='$username'";
        while($row = mysql_fetch_array(mysql_query($sql)))
        {
            return $row['username'];
        }
    }
    function deleteAccount($username)
    {
        $userID = $this->getUserID($username);
        mysql_query("DELETE FROM comments WHERE userid='$userID'");
        mysql_query("DELETE FROM posts WHERE userid='$userID'");
        mysql_query("DELETE FROM users WHERE id='$userID'");
    }
    function doesPostExist($id)
    {
        $sql = "SELECT * FROM posts WHERE id='$id'";
        $data = mysql_query($sql);
        if(mysql_affected_rows()==0)
        {
            return false;
        }
        return true;
    }
    function getRanks()
    {
        $sql = "SELECT * FROM permissions";
        $data = mysql_query($sql);
        $array = array();
        while($row = mysql_fetch_array($data))
        {
            array_push($array, $row['rank'] . ":" . $row['level']);
        }

        return $array;
    }
    function deleteComment($commentID, $postID)
    {
        $sql = "DELETE FROM comments WHERE id='$commentID'";
        mysql_query($sql);
        $sql = "SELECT comments FROM posts WHERE id='$postID'";
        $response = (mysql_query($sql));
        while($row = mysql_fetch_array($response))
        {
            $comments = $row['comments'];
        }
        $comments = unserialize($comments);
        array_splice($comments,$commentID);
        $comments = serialize($comments);
        $sql = "UPDATE posts SET comments='$comments' WHERE id='$postID'";
        mysql_query($sql);

    }
    function getRawViews($postID)
    {
        $sql = "SELECT views FROM posts WHERE id='$postID'";
        $response = mysql_query($sql);
        while($row = mysql_fetch_array($response))
        {
            return ($row['views']);
        }
    }
    function getViews($postID)
    {
        $sql = "SELECT views FROM posts WHERE id='$postID'";
        $response = mysql_query($sql);
        while($row = mysql_fetch_array($response))
        {
            return count(unserialize($row['views']));
        }
    }
    function addView($postID, $userID)
    {
        $views = unserialize($this->getRawViews($postID));
        if(empty($views))
        {
            $views = array();
        }
        if(!in_array($userID, $views))
        {
            array_push($views, $userID);
        }
        $views = serialize($views);
        mysql_query("UPDATE posts SET views='$views' WHERE id='$postID'");
    }
    function getUsers()
    {
        $sql = "SELECT username FROM users";
        $data = mysql_query($sql);
        $users = "";
        while($row = mysql_fetch_array($data))
        {
            $users .= $row['username'] . ",";
        }

        return $users;
    }
    function deletePost($postID)
    {
        $sql = "SELECT comments FROM posts WHERE id='$postID'";
        $response = mysql_query($sql);
        while($row = mysql_fetch_array($response))
        {
            $comments = explode(",", $row['comments']);
            foreach($comments as $comment)
            {
                mysql_query("DELETE FROM comments WHERE id='$comment'");
            }
        }

        $sql = "DELETE FROM posts WHERE id='$postID'";
        mysql_query($sql);
    }
    function setPermission($username, $permission)
    {
        $sql = "UPDATE users SET permission='$permission' WHERE username='$username'";
        mysql_query($sql);
    }
    function getPermission($username)
    {
        $sql = "SELECT permission FROM users WHERE username='$username'";
        $data = mysql_query($sql);
        while($row = mysql_fetch_array($data))
        {
            return $row['permission'];
        }
    }
    function setDescription($username, $description)
    {
        $sql = "UPDATE users SET description='$description' WHERE username='$username'";
        mysql_query($sql);
    }
    function getPassword($username)
    {
        $sql = "SELECT password FROM users WHERE username='$username'";
        mysql_query($sql);
    }
    function getPhoneNumber($username)
    {
        $sql = "SELECT phone FROM users WHERE username='$username'";
        mysql_query($sql);
    }
    function setSignature($username, $signature)
    {
        $sql = "UPDATE users SET signature='$signature' WHERE username='$username'";
        mysql_query($sql);
    }
    function getUserID($username)
    {
        $sql = "SELECT id FROM users WHERE username='$username'";
        while($row = mysql_fetch_array(mysql_query($sql)))
        {
            return $row['id'];
        }
    }
    function getCommentTime($commentID)
    {
        $sql = "SELECT time FROM comments WHERE id='$commentID'";
        $response = mysql_query($sql);
        while($row = mysql_fetch_array($response))
        {
            return $row['time'];
        }
    }
    function getUserSignature($username)
    {
        $sql = "SELECT * FROM users WHERE username='$username'";
        $response = mysql_query($sql);
        while($row = mysql_fetch_array($response))
        {
            return html_entity_decode($row['signature']);
        }
        return "";
    }
    function getCommentPosterID($commentID)
    {
        $sql = "SELECT userid FROM comments WHERE id='$commentID'";
        $response = mysql_query($sql);
        while($row = mysql_fetch_array($response))
        {
            return $row['userid'];
        }
    }
    function getUsername($userID)
    {
        $sql = "SELECT username FROM users WHERE id='$userID'";
        $response = mysql_query($sql);
        while($row = mysql_fetch_array($response))
        {
            return $row['username'];
        }
    }
    function getCommentText($commentID)
    {
        $sql = "SELECT text FROM comments WHERE id='$commentID'";
        $response = mysql_query($sql);
        while($row = mysql_fetch_array($response))
        {
            return html_entity_decode($row['text']);
        }
    }
    function getLikes($postID)
    {
        $sql = "SELECT likes FROM posts WHERE id='$postID'";
        $response = mysql_query($sql);
        while($row = mysql_fetch_array($response))
        {
            $likes = (unserialize($row['likes']));
            if(empty($row['likes']))
            {
                return 0;
            }
            else{
                return count($likes);
            }
            //$likes = count($likes);
        }
    }
    function getLikers($postID)
    {
        $sql = "SELECT likes FROM posts WHERE id='$postID'";
        $respone = mysql_query($sql);
        while($row = mysql_fetch_array($respone))
        {
            return (unserialize($row['likes']));
        }
    }
    function isLiked($userID, $postID)
    {
        if(empty($this->getLikers($postID)))
        {
            return false;
        }
        return in_array($userID, $this->getLikers($postID));
    }
    function like($postID, $userID)
    {
        if(!empty($this->getLikers($postID)))
        {
            $likers = ($this->getLikers($postID));
        }
        else{
            $likers = array();
        }

        if(!in_array($userID, $likers))
        {
            array_push($likers, $userID);
        }

        $likers = serialize($likers);
        $sql = "UPDATE posts SET likes='$likers' WHERE id='$postID'";
        mysql_query($sql);
    }
    function unlike($postID, $userID)
    {
        if($this->isLiked($userID,$postID))
        {
            $likers = $this->getLikers($postID);
            $index = array_search($userID,$likers);
            if($index !== FALSE){
                unset($likers[$index]);
            }
            $likers = serialize($likers);
            mysql_query("UPDATE posts SET likes='$likers' WHERE id='$postID'");
        }
    }

    function getVideoID($text)
    {
        if(strpos($text,"youtube.com"))
        {
            parse_str( parse_url( $text, PHP_URL_QUERY ), $my_array_of_vars );
            return $my_array_of_vars['v'];
        }
        else if(strpos($text,"youtu.be"))
        {
            $text = preg_replace('#^https?://#', '', rtrim($text,'/'));
            $text = preg_replace('#^http?://#', '', rtrim($text,'/'));
            $link = explode("/", $text);
            return $link[1];
        }
        return "";
    }
    function createPost($type, $title, $text, $likes, $time, $userID)
    {
        $sql = "INSERT INTO posts (id, type, title, text, likes, comments, time, userid, views) VALUES (NULL, '$type', '$title', '$text', '$likes', '0', '$time', '$userID', '0')";
        mysql_query($sql);
    }
    function getPostType($postID)
    {
        $sql = "SELECT type FROM posts WHERE id='$postID'";
        $response = mysql_query($sql);
        while($row = mysql_fetch_array($response))
        {
            return $row['type'];
        }
    }
    function getPostTitle($postID)
    {
        $sql = "SELECT title FROM posts WHERE id='$postID'";
        $response = mysql_query($sql);
        while($row = mysql_fetch_array($response))
        {
            return $row['title'];
        }
    }
    function getPostTime($postID)
    {
        $sql = "SELECT time FROM posts WHERE id='$postID'";
        $response = mysql_query($sql);
        while($row = mysql_fetch_array($response))
        {
            return $row['time'];
        }
    }
    function setPostText($postID, $text)
    {
        $sql = "UPDATE posts SET text='$text' WHERE id='$postID'";
        mysql_query($sql);
    }
    function setPostTitle($postID, $title)
    {
        $sql = "UPDATE posts SET title='$title' WHERE id='$postID'";
        mysql_query($sql);
    }
    function getPostText($postID)
    {
        $sql = "SELECT text FROM posts WHERE id='$postID'";
        $response = mysql_query($sql);
        while($row = mysql_fetch_array($response))
        {
            return $row['text'];
        }
    }
    function getPostUserID($postID)
    {
        $sql = "SELECT userid FROM posts WHERE id='$postID'";
        $response = mysql_query($sql);
        while($row = mysql_fetch_array($response))
        {
            return $row['userid'];
        }
    }
    function getPoints($username)
    {
        $userID = $this->getUserID($username);
        $sql = "SELECT points FROM users WHERE id='$userID'";
        $response = mysql_query($sql);
        while($row = mysql_fetch_array($response))
        {
            return $row['points'];
        }
    }
    function setPoints($username, $points)
    {
        $userID = $this->getUserID($username);
        $sql = "UPDATE users SET points='$points' WHERE id='$userID'";
        mysql_query($sql);
    }
    function addPoints($username, $points)
    {
        $cPoints = $this->getPoints($username);
        $cPoints = $cPoints + $points;
        $this->setPoints($username, $cPoints);
    }

}