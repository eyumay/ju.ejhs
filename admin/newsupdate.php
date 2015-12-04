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
if(isset($_GET['del']))
{
    // remove the article from the database
    $query = "DELETE FROM news WHERE id = '{$_GET['del']}'";
    mysql_query($query) or die('Error : ' . mysql_error());
        
    // redirect to current page so when the user refresh this page
    // after deleting an article we won't go back to this code block
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
$errmsg = '';
$msg = ''; 
if(isset($_GET['editNews']))
{
	$editNewsID  = $_GET['editNews'];
	$query = "select * from news where id = '$editNewsID'";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	$title = $row['title'];
	$content = $row['content'];
	$redirect_link = $row['redirect_link'];
	$additional_link = $row['additional_link'];
}
if(isset($_POST['upload_news']))
{

	$errmsg = '';
	// Get data
	$title = $_POST['title'];
	$content = $_POST['content'];
	//$is_published = $_POST['is_published'];
	$redirect_link = $_POST['redirect_link'];
	$additional_link = $_POST['additional_link'];
	
	// Stripe white spaces
	$title  = trim($title);
	$content  = trim($content);
	$redirect_link = trim($redirect_link);
	$additional_link = trim($additional_link);
	
	$title  = addslashes($title);
	$content  = addslashes($content);
	$redirect_link = addslashes($redirect_link);
	$additional_link = addslashes($additional_link);
	
	
	// Check for error
	if($title == '')
	{
		//echo " Empty field : Title <br />";
		$errmsg = " Empty field : Title <br />";
	}
	else if($content == '' and $redirect_link == '')
	{
		//echo " Empty field : Content<br />";
		$errmsg = " You have to provide either content or redirect link (External Link)  <br />";
	}
	else if($errmsg == '')
	{
		// Generate a system date.
		$date	= date("Y-m-d");
		
		// Add slashes
		$title  = addslashes($title);
		$content  = addslashes($content);
		$query = "UPDATE news SET title = '$title', content = '$content', created_at ='$date' , updated_at ='$date' , redirect_link ='$redirect_link' , additional_link = '$additional_link' WHERE id = '$editNewsID'";
		if(!mysql_query($query))
		{
			echo "an error occured". mysql_error();
		}
		else
		{
			$msg = "You have added one an article! <br />";
			$title = '';
			$content = '';
			//$is_published = '';
			$additional_link = '';
			$redirect_link = '';
		}		
	}
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
					<p> Publish News @ EJHS </p>
					
					<?php
					if($errmsg != ''){
					?>
						<p style="color:#FF0000; border:#FF0000 solid 1px;"> 
						You have an error while publishing news: <?php echo $errmsg; $errmsg = '';?><br />
						</p>
					<?php
					}
					?>
					
					<?php
					if($msg != ''){
					?>
						<p style="color:#009900; border:#009900 solid 1px;"> 
						Success : You have succesfully updated the news <?php echo $msg; ?><br />
						</p>
					<?php
					}
					?>
					
					<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
					  <table width="560" height="422" border="0" cellspacing="0" style="border:#CCCCCC solid 1px;">
						<tr>
						  <td bgcolor="#CCCCCC">&nbsp;</td>
						  <td bgcolor="#CCCCCC">&nbsp;</td>
						</tr>
						<tr>
						  <td bgcolor="#F3F3F3">&nbsp;</td>
						  <td bgcolor="#F3F3F3">&nbsp;</td>
					    </tr>
						<tr>
						  <td width="145" bgcolor="#F3F3F3">Title</td>
						  <td width="409" bgcolor="#F3F3F3"><label>
							<input name="title" type="text" id="title" size="75" value="<?=$title?>"/>
						  </label></td>
						</tr>
						<tr>
						  <td bgcolor="#F3F3F3">&nbsp;</td>
						  <td bgcolor="#F3F3F3">&nbsp;</td>
					    </tr>
						<tr>
						  <td valign="top" bgcolor="#F3F3F3">Content</td>
						  <td bgcolor="#F3F3F3"><textarea name="content" cols="65" rows="15" id="content_id"><?php echo $content; ?></textarea><script language="JavaScript" type="text/javascript">
	  generate_wysiwyg('content_id');
	  </script>	
						 	  </td>
						</tr>
						<tr>
						  <td height="31" bgcolor="#F3F3F3">&nbsp;</td>
						  <td bgcolor="#F3F3F3"><label></label></td>
						</tr>
						<tr>
						  <td height="31" bgcolor="#F3F3F3">&nbsp;</td>
						  <td bgcolor="#F3F3F3">&nbsp;</td>
					    </tr>
						<tr>
                          <td colspan="2" bgcolor="#CCCCCC">Optional</td>
					    </tr>
						<tr>
						  <td bgcolor="#F3F3F3">Redirect Link </td>
						  <td bgcolor="#F3F3F3"><input name="redirect_link" type="text" id="redirect_link" size="75" value="<?=$redirect_link?>"/></td>
					    </tr>
						<tr>
						  <td bgcolor="#F3F3F3">&nbsp;</td>
						  <td bgcolor="#F3F3F3">&nbsp;</td>
					    </tr>
						<tr>
						  <td bgcolor="#F3F3F3">Additional Link </td>
						  <td bgcolor="#F3F3F3"><input name="additional_link" type="text" id="additional_link" size="75" value="<?=$additional_link?>"/></td>
					    </tr>
						<tr>
						  <td bgcolor="#F3F3F3">&nbsp;</td>
						  <td bgcolor="#F3F3F3">&nbsp;</td>
					    </tr>
						<tr>
						  <td bgcolor="#F3F3F3">&nbsp;</td>
						  <td bgcolor="#F3F3F3"><input name="upload_news" type="submit" id="upload_news" value="Update " /></td>
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
