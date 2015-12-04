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
$title='';
$errmsg='';
$msg='';

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
if(isset($_GET['editNews']))
{
	$editNewsID  = $_GET['editNews'];
	$query = "select * from video_links where id = '$editNewsID'";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	$title = $row['title'];
	$description = $row['description'];
	$redirect_link = $row['link'];

}
if(isset($_POST['upload_news']))
{

	$errmsg = '';
	// Get data
	$title = $_POST['title'];
	$description = $_POST['description'];
	//$is_published = $_POST['is_published'];
	$redirect_link = $_POST['redirect_link'];
	
	// Stripe white spaces
	$title  = mysql_real_escape_string($title);
	$description  = mysql_real_escape_string($description);
	$redirect_link = mysql_real_escape_string($redirect_link);
	
	
	// Check for error
	if($title == '')
	{
		//echo " Empty field : Title <br />";
		$errmsg = " Empty field : Title <br />";
	}
	else if($redirect_link == '')
	{
		//echo " Empty field : Content<br />";
		$errmsg = " Redirect Links is required  <br />";
	}
	else if($errmsg == '')
	{
		$query = "UPDATE video_links SET title = '$title', description = '$description', link ='$redirect_link' WHERE id = '$editNewsID'";
		if(!mysql_query($query))
		{
			echo "an error occured". mysql_error();
		}
		else
		{
			$msg = "You updated video links! <br />";
			$title = '';
			$description = '';

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
					<h2> Update Video Links </h2>
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
						Success : You have succesfully updated video links,  <?php echo $msg; ?><br />
						</p>
					<?php
					}
					?>
					
					<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
					  <table width="560" height="350" border="0" cellspacing="0" style="border:#CCCCCC solid 1px;">
						<tr>
						  <td bgcolor="#CCCCCC">&nbsp;</td>
						  <td bgcolor="#CCCCCC">&nbsp;</td>
						</tr>
						<tr>
						  <td width="145" height="54" bgcolor="#F3F3F3">Title</td>
						  <td width="409" bgcolor="#F3F3F3"><label>
							<input name="title" type="text" id="title" size="75" value="<?=$title?>"/>
						  </label></td>
						</tr>
						<tr>
						  <td height="49" valign="top" bgcolor="#F3F3F3">Description</td>
						  <td bgcolor="#F3F3F3"><script language="JavaScript" type="text/javascript">
	  generate_wysiwyg('content_id');
	  </script>	
						 	  <input name="description" type="text" id="description" size="75" value="<?=$description?>"/></td>
						</tr>
						<tr>
						  <td height="52" bgcolor="#F3F3F3">Redirect Link </td>
						  <td bgcolor="#F3F3F3"><input name="redirect_link" type="text" id="redirect_link" size="75" value="<?=$redirect_link?>"/></td>
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
