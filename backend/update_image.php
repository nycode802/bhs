<?php
$target_dir = "../images//";
$target_file = $target_dir . $_POST['name'];
$ok=1;
$error = 0;
$ext = (explode(".", $_FILES['uploaded']['name']));
$ext = end($ext);
if ($uploaded_size > 350000)  {   $ok=0; $error = 1; }
//This is our limit file type condition
if (!strcasecmp($ext, "php"))  {   $ok=0; $error = 2;  }
if(!strcasecmp($ext, "png") or !strcasecmp($ext, "jpg") or !strcasecmp($ext, "jpeg") or !strcasecmp($ext, "gif")) {} else{$ok = 0; $error = 2;}
if ($ok==0)  {
    header('Location: ../accounts/admin.php');
}
//If everything is ok we try to upload it
else  {  if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target_file))  {
    header('Location: ../accounts/admin.php');
}
else
{
    header('Location: ../accounts/admin.php');
}
}