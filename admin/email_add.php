<?php 
session_start(); 
if(!$_SESSION['user_is_logged_in']){
	header("location:login.php");
}
if($_SESSION['privilege'] != "reviewer"){
	header("location:login.php");
}
include 'library/config.php';
include 'library/opendb.php';
require_once("email.class.php");

//fetch queryd
if(isset($_GET['del']))
{
    // remove the article from the database
	$id_to_delete = $_GET['del'];
	$delete = mysql_query("SELECT * FROM journal WHERE id = '$id_to_delete'");
	$file_dir = $row['upload_dir'];
	$file =  $row['coverpage_location'];
	exec("rmdir ".${file_dir}."/".${file});
	$msg =  $file_dir."/".$file;
    $query = "DELETE FROM journal WHERE id = '{$_GET['del']}'";
    mysql_query($query) or die('Error : ' . mysql_error());
        
    // redirect to current page so when the user refresh this page
    // after deleting an article we won't go back to this code block
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
if(isset($_GET['activate']) and isset($_GET['activate_id']))
{
    // remove the article from the database	
	$activate = $_GET['activate'];
	$activate_id = $_GET['activate_id'];
	if($activate == 'true'){
		$setActivate = 'activated';	
	}
	else{
		$setActivate = '';	
	}
	$activateJournal = "UPDATE journal SET is_activated = '$setActivate' WHERE id='$activate_id'";
    mysql_query($activateJournal) or die('Error : ' . mysql_error());	
	$msg = "Journal Activation was successful";
        
    // redirect to current page so when the user refresh this page
    // after deleting an article we won't go back to this code block
    //header('Location: ' . $_SERVER['PHP_SELF']);
    //exit;
}

$result = mysql_query("SELECT * FROM journal ORDER BY volume DESC, num DESC");
if(!$result){
	echo "Error on DB".mysql_error();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>E J H S | About Ethiopian Journal of Health Sciences</title>
<link href="admin-style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
#customizeLink a{
			padding: 0px;
			border-bottom: 0px solid #CCC;
}
#customizeLink a:hover{
			background:none;
			background-color: #F5F5F5;
			color:#CC6714;
}

		 #subnavlist {
			margin-left: 10px;
			padding-left: 50px;
			list-style-type:circle;			
		}	
		#subnavlist a {
			display:block;
			padding: 0px;
			border-bottom:none;
			border:none;
		}
	 	#navigation p {
			border:none;
			margin-top:20px;
			margin-bottom:0px;
			padding:0px;
		}

.style1 {
	color: #FFFFFF;
	font-weight: bold;
}
.success {
	color:#FFFFFF;
	background:#006600;
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	font-weight:bolder;
}
.error {
	color:#FFFFFF;
	background:#FF0000;
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	font-weight:bolder;
}
.attachment {
	background:#FFFFFF;	
}


</style>
<script language="JavaScript">
function delArticle(id, title)
{
    if (confirm("Are you sure you want to delete '" + title + "'"))
    {
        window.location.href = 'journal_edit.php?del=' + id;
    }
}
</script>

</head>
<body>
<div id="container">
	<div id="header">
	</div>
	<div id="content" style="width:890; margin:0;">
	  <table width="100%" border="0" cellspacing="0" style="border:#575352 solid 1px">
          <tr>
            <td width="21" bgcolor="#575352">&nbsp;</td>
            <td width="167" bgcolor="#575352"><br />			</td>
            <td width="704" bgcolor="#575352">&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td valign="top">
			<?php include 'left-link.html'; ?>
			</td>
            <td valign="top">
<table width="100%" border="0" cellspacing="0">
  <tr>
    <td width="13%">&nbsp;</td>
    <td width="79%">&nbsp;</td>
    <td width="8%">&nbsp;</td>
  </tr>
</table>
<h1>Register new e-mails</h1>
<?php
if(isset($_POST['Submit'])){
	
	// Validation
	
	$em = new Email();
	$em->setFirstName(trim($_POST['first_name']));
	$em->setLastName(trim($_POST['last_name']));
	$em->setEmail(trim($_POST['email']));	
	$em->setTitle(trim($_POST['title']));	
	$em->setIsActivated(trim($_POST['is_active']));		
	
	$em->checkEmpty();						   	
	if(!isset($em->empty_fields)) {
		$query= "INSERT INTO subscribers
		(first_name, last_name, title, email_address, is_activated) VALUES ('".$em->getFirstName()."', '".$em->getLastName()."', '".$em->getTitle()."', '".$em->getEmail()."', '".$em->getIsActivated()."')";
		$em->save($query);
	}
	else {
		//echo  "The following fields are required to be filled  <br />";
		echo $em->empty_fields;
		$em->empty_fields = ""; 
	}
}
	if(isset($em->error)){
		echo $em->error;
		$first_name = $em->getFirstName();
		$last_name = $em->getLastName();
		$email = $em->getEmail();
		$is_activated = $em->getIsActivated();
		$em->display();
	}
	else
		echo $em->success;
	
include "email_form.php";
?>


</td>
          </tr>
      </table>
    </p>
  </div>
  <div id="footer"> 
    <p>Copyright © Jimma University 2009, Research & Publications Office. All rights reserved.
	 This journal or any parts cannot be reproduced in any form without written permission from the University.</p>
  </div>
</div>
</body>
</html>
