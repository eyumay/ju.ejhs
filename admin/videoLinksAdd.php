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
include_once "includes/fckeditor/fckeditor.php"; 
if(isset($_GET['del']))
{
    // remove the article from the database
    $query = "DELETE FROM video_links WHERE id = '{$_GET['del']}'";
    mysql_query($query) or die('Error : ' . mysql_error());
        
    // redirect to current page so when the user refresh this page
    // after deleting an article we won't go back to this code block
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
//string $title='';
$title = '';
$description = '';
$link = '';

$errmsg = '';
$msg = ''; 
if(isset($_POST['upload_link']))
{

	$errmsg = '';
	// Get data
	$title = $_POST['title'];
	$description = $_POST['description'];
	//$is_published = $_POST['is_published'];
	$link = $_POST['link'];
	
	// Stripe white spaces
	$title  = mysql_real_escape_string(trim($title));
	$description  = mysql_real_escape_string(trim($description));
	$link = trim($link);
	
	
	// Check for error
	if($title == '')
	{
		//echo " Empty field : Title <br />";
		$errmsg = " Empty field : Title <br />";
	}

	else if($errmsg == '')
	{
		// Generate a system date.
		$date	= date("Y-m-d");
		
		// Add slashes
		$query = "insert into video_links (title, description, link) values ('$title', '$description', '$link')";
		if(!mysql_query($query))
		{
			echo "an error occured". mysql_error();
		}
		else
		{
			$msg = "Uploaded Video Link! <br />";
			$title = '';
			$description = '';
			//$is_published = '';
			$link = '';
			header('Location: videoLinksEdit.php');
			exit;			
		}		
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<link href="admin-style.css" rel="stylesheet" type="text/css" />
<title>E J H S | About Ethiopian Journal of Health Sciences</title>
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
</style>
<script language="JavaScript" type="text/javascript" src="wysiwyg.js"></script>
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
            <td valign="top"><table width="100%" border="0">
              <tr>
                <td width="2%">&nbsp;</td>
                <td width="48%">&nbsp;</td>
                <td width="48%">&nbsp;</td>
                <td width="2%">&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td colspan="2" rowspan="2" valign="top">
					<h3> Upload Video Links </h3>
					<?php
					if($errmsg != ''){
					?>
						<p style="color:#FF0000; border:#FF0000 solid 1px;"> 
						You have an error while publishing videos: <?php echo $errmsg; $errmsg = '';?><br />
						</p>
					<?php
					}
					?>
					
					<?php
					if($msg != ''){
					?>
						<p style="color:#009900; border:#009900 solid 1px;"> 
						Success : You have succesfully published video link <?php echo $msg; ?><br />
						</p>
					<?php
					}
					?>
					
					<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
					  <table width="560" height="279" border="0" cellspacing="0" style="border:#CCCCCC solid 1px;">
						<tr>
						  <td height="43" bgcolor="#CCCCCC">&nbsp;</td>
						  <td bgcolor="#CCCCCC">&nbsp;</td>
						</tr>
						<tr>
						  <td width="145" height="40" bgcolor="#F3F3F3">Title</td>
						  <td width="409" bgcolor="#F3F3F3"><label>
							<input name="title" type="text" id="title" size="75" value="<?=$title?>"/>
						  </label></td>
						</tr>
						<tr>
						  <td height="49" valign="top" bgcolor="#F3F3F3">Description</td>
						  <td bgcolor="#F3F3F3"><input name="description" type="text" id="description" size="75" value="<?=$description?>"/></td>
						</tr>
						<tr>
						  <td height="31" bgcolor="#F3F3F3">Links to video </td>
						  <td bgcolor="#F3F3F3"><input name="link" type="text" id="link" size="75" value="<?=$link?>"/></td>
					    </tr>
						<tr>
						  <td bgcolor="#F3F3F3">&nbsp;</td>
						  <td bgcolor="#F3F3F3"><input name="upload_link" type="submit" id="upload_link" value="Submit" /></td>
					    </tr>
					  </table>
					</form>					
                </td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td height="35">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table></td>
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
