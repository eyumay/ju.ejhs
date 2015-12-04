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
    $query = "DELETE FROM searchengine WHERE id = '{$_GET['del']}'";
    mysql_query($query) or die('Error : ' . mysql_error());
        
    // redirect to current page so when the user refresh this page
    // after deleting an article we won't go back to this code block
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
//string $title='';
$title = '';
$article_id = '';
$volume = '';
$number = '';
$month = '';
$year = '';
$authors = '';
$keyword = '';
$url = '';

$errmsg = '';
$msg = ''; 
if(isset($_POST['upload_link']))
{

	$errmsg = '';
	// Get data
	$title = $_POST['title'];
	$article_id = $_POST['article_id'];
	$volume = $_POST['volume'];
	$number = $_POST['number'];
	$month = $_POST['month'];
	$year = $_POST['year'];
	$authors = $_POST['authors'];
	$keyword = $_POST['keyword'];
	//$is_published = $_POST['is_published'];
	
	// Stripe white spaces
	$title  = mysql_real_escape_string(trim($title));
	$article_id  = mysql_real_escape_string(trim($article_id));
	
	// Check for error
	if($title == '')
	{
		//echo " Empty field : Title <br />";
		$errmsg = " Empty field : Title <br />";
	}
	
	if($article_id == '')
	{
		//echo " Empty field : Title <br />";
		$errmsg = " Empty field : Article Id <br />";
	}
	if($volume == '')
	{
		//echo " Empty field : Title <br />";
		$errmsg = " Empty field : Volume <br />";
	}
	if($number == '')
	{
		//echo " Empty field : Title <br />";
		$errmsg = " Empty field : number <br />";
	}
	if($month == '')
	{
		//echo " Empty field : Title <br />";
		$errmsg = " Empty field : month <br />";
	}
	if($year == '')
	{
		//echo " Empty field : Title <br />";
		$errmsg = " Empty field : year <br />";
	}
	if($authors == '')
	{
		//echo " Empty field : Title <br />";
		$errmsg = " Empty field : Authors <br />";
	}	
	if($keyword == '')
	{
		//echo " Empty field : Title <br />";
		$errmsg = " Empty field : keyword <br />";
	}					

	else if($errmsg == '')
	{
		// Generate a system date.
		$date	= date("Y-m-d");
		
		// Add slashes
		
		$url = "/issues/abstract.php?artId=".$article_id;
		
		$query = "insert into searchengine (title, article_id, volume, number, month, year, authors, keyword, url) values ('$title', '$article_id', '$volume', '$number', '$month', '$year', '$authors', '$keyword', '$url')";
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
			header('Location: searchengineEdit.php');
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
            <td valign="top"><table width="100%" border="0" cellspacing="0">
              <tr>
                <td bgcolor="#F3F3F3">&nbsp;</td>
                <td bgcolor="#FDFDFD">&nbsp;</td>
              </tr>
              <tr>
                <td bgcolor="#F3F3F3"><strong>&raquo;</strong></td>
                <td bgcolor="#F3F3F3"><a href="index.php"><strong> Home</strong></a></td>
              </tr>
              <tr>
                <td bgcolor="#F3F3F3">&nbsp;</td>
                <td bgcolor="#F3F3F3">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2" bgcolor="#575352"><span class="style1">Manage News </span></td>
              </tr>
              <tr>
                <td width="7%" bgcolor="#F3F3F3"><strong>&raquo;</strong></td>
                <td width="93%" bgcolor="#F3F3F3"><a href="newsadd.php"><strong>Add News </strong></a></td>
              </tr>
              <tr>
                <td bgcolor="#F3F3F3"><strong>&raquo;</strong></td>
                <td bgcolor="#F3F3F3"><a href="newsedit.php"><strong>Edit</strong></a></td>
              </tr>
              <tr>
                <td bgcolor="#F3F3F3">&nbsp;</td>
                <td bgcolor="#F3F3F3">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2" bgcolor="#575352"><span class="style1">Manage users </span></td>
              </tr>
              <tr>
                <td bgcolor="#F3F3F3"><strong>&raquo;</strong></td>
                <td bgcolor="#F3F3F3"><a href="useAdd.php"><strong>Add user </strong></a></td>
              </tr>
              <tr>
                <td bgcolor="#F3F3F3"><strong>&raquo;</strong></td>
                <td bgcolor="#F3F3F3"><a href="useedit.php"><strong>Edit user </strong></a></td>
              </tr>
              <tr>
                <td bgcolor="#F3F3F3">&nbsp;</td>
                <td bgcolor="#F3F3F3">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2" bgcolor="#575352"><span class="style1">Manage Journals </span></td>
              </tr>
              <tr>
                <td bgcolor="#F3F3F3"><strong>&raquo;</strong></td>
                <td bgcolor="#F3F3F3"><a href="journal_add.php"><strong>Upload Journal </strong></a></td>
              </tr>
              <tr>
                <td bgcolor="#F3F3F3"><strong>&raquo;</strong></td>
                <td bgcolor="#F3F3F3"><a href="journal_edit.php"><strong>Edit Journal </strong></a></td>
              </tr>
              <tr>
                <td bgcolor="#F3F3F3">&nbsp;</td>
                <td bgcolor="#F3F3F3">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2" bgcolor="#F3F3F3">Manage articles under a journal </td>
              </tr>
              <tr>
                <td bgcolor="#F3F3F3"><strong>&raquo;</strong></td>
                <td bgcolor="#F3F3F3"><a href="journal_article_add.php"><strong>Upload article </strong></a></td>
              </tr>
              <tr>
                <td bgcolor="#F3F3F3"><strong>&raquo;</strong></td>
                <td bgcolor="#F3F3F3"><a href="journal_article_edit.php"><strong>Edit article </strong></a></td>
              </tr>
              <tr>
                <td colspan="2" bgcolor="#575352"><span class="style1">Email addressese </span></td>
              </tr>
              <tr>
                <td bgcolor="#F3F3F3"><strong>&raquo;</strong></td>
                <td bgcolor="#F3F3F3"><a href="email_add.php"><strong>Add email address </strong></a></td>
              </tr>
              <tr>
                <td bgcolor="#F3F3F3"><strong>&raquo;</strong></td>
                <td bgcolor="#F3F3F3"><a href="email_edit.php"><strong>Edit email address </strong></a></td>
              </tr>
              <tr>
                <td bgcolor="#F3F3F3"><strong>&raquo;</strong></td>
                <td bgcolor="#F3F3F3"><a href="email_send.php"><strong>Send Notification Msg </strong></a></td>
              </tr>
              <tr>
                <td bgcolor="#F3F3F3">&nbsp;</td>
                <td bgcolor="#F3F3F3">&nbsp;</td>
              </tr>
              <tr>
                <td bgcolor="#F3F3F3">&nbsp;</td>
                <td bgcolor="#F3F3F3"><a href="logout.php"><strong>Logout</strong></a></td>
              </tr>
            </table></td>
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
						  <td height="49" valign="top" bgcolor="#F3F3F3">Article Id </td>
						  <td bgcolor="#F3F3F3"><input name="article_id" type="text" id="article_id" size="75" value="<?=$article_id?>"/></td>
						</tr>
						<tr>
						  <td height="31" bgcolor="#F3F3F3">Volume</td>
						  <td bgcolor="#F3F3F3"><input name="volume" type="text" id="volume" size="75" value="<?=$volume?>"/></td>
					    </tr>
						<tr>
						  <td height="31" bgcolor="#F3F3F3">Number</td>
						  <td bgcolor="#F3F3F3"><input name="number" type="text" id="number" size="75" value="<?=$number?>"/></td>
					    </tr>
						<tr>
						  <td height="31" bgcolor="#F3F3F3">Month</td>
						  <td bgcolor="#F3F3F3"><input name="month" type="text" id="month" size="75" value="<?=$month?>"/></td>
					    </tr>
						<tr>
						  <td height="31" bgcolor="#F3F3F3">Year</td>
						  <td bgcolor="#F3F3F3"><input name="year" type="text" id="year" size="75" value="<?=$year?>"/></td>
					    </tr>
						<tr>
						  <td height="31" bgcolor="#F3F3F3">Authors</td>
						  <td bgcolor="#F3F3F3"><input name="authors" type="text" id="authors" size="75" value="<?=$authors?>"/></td>
					    </tr>
						<tr>
						  <td height="31" bgcolor="#F3F3F3">Key words </td>
						  <td bgcolor="#F3F3F3"><input name="keyword" type="text" id="keyword" size="75" value="<?=$keyword?>"/></td>
					    </tr>
						<tr>
						  <td height="31" bgcolor="#F3F3F3">&nbsp;</td>
						  <td bgcolor="#F3F3F3">&nbsp;</td>
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
