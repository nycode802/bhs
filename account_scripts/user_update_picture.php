<?php
$target_dir = "../profile/pictures/";
$target_file = $target_dir . strtolower($_POST['westbvlb_username'] . ".jpg");
$ok=1;
$error = 0;
$ext = (explode(".", $_FILES['uploaded']['name']));
$ext = end($ext);
if ($uploaded_size > 350000)  {   $ok=0; $error = 1; }
//This is our limit file type condition
if (!strcasecmp($ext, "php"))  {   $ok=0; $error = 2;  }
if(!strcasecmp($ext, "png") or !strcasecmp($ext, "jpg") or !strcasecmp($ext, "jpeg") or !strcasecmp($ext, "gif")) {} else{$ok = 0; $error = 2;}
if ($ok==0)  {
header('Location: ../accounts/settings.php?error=' . $error);
}
//If everything is ok we try to upload it
else  {  if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target_file))  {
    header( 'Location: ../accounts/settings.php' ) ;
}
else
{
    header('Location: ../accounts/settings.php?error=' . 3);
}
}