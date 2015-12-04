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

$errmsg			= '';
$msg			= '';
$password		= '';
$first_name		= ''; 


if(isset($_GET['editUser']))
{
	$editUserID  = $_GET['editUser'];
	$query = "select * from user where id = '$editUserID'";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	$first_name = $row['first_name'];

}
if(isset($_POST['change_pass']))
{

	$errmsg = '';
	// Get data
	$new_password = trim($_POST['new_password']);
	$confirm = trim($_POST['confirm']);
	//$is_published = $_POST['is_published'];


	// Check for error
	if($new_password == '')
	{
		//echo " Empty field : Title <br />";
		$errmsg = " Empty field : New Password <br />";
	}
	else if($confirm == '')
	{
		//echo " Empty field : Content<br />";
		$errmsg = " Empty Firld : Confirm New Password)  <br />";
	}
	else if($confirm != $new_password)
	{
		//echo " Empty field : Content<br />";
		$errmsg = " Passwords do not match  <br />";
	}	
	else if($errmsg == '')
	{
		// Generate a system date.
		$date	= date("Y-m-d");

		$query = "UPDATE user SET password = PASSWORD('$new_password') WHERE id = '$editUserID'";
		if(!mysql_query($query))
		{
			echo "an error occured". mysql_error();
		}
		else
		{
			$msg = "You have Successfuly updated the user Password! <br />";
			
			$_SESSION['notice'] = "User Password Was Updated Successfuly "; 
			header('location:useEdit.php');	

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
</style>
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
                <td colspan="2" rowspan="2" valign="top">
					<h3>Change Password </h3>
					<?php
					if($errmsg != ''){
					?>
						<p style="color:#FF0000; border:#FF0000 solid 1px;"> 
						<strong>You have an error while updating user Password</strong>: <?php echo $errmsg; $errmsg = '';?><br />
				  </p>
					<?php
					}
					?>
					
					<?php
					if($msg != ''){
					?>
						<p style="color:#009900; border:#009900 solid 1px;"><strong> 
						Success : You have succesfully updated the user Password <?php echo $msg; ?><br />
						</strong></p>
					<?php
					}
					?>
					
					<form id="form1" method="post" action="" enctype="multipart/form-data">
			<?php
			if($errmsg != ''){
			?>
<p style="color:#FF0000; border:#FF0000 solid 1px;"><strong> 
			You have an error while submitting: <?php echo $errmsg; ?><br />
			</strong></p>
			<?php
			}
			?>

              <table width="506" height="152" border="0" cellspacing="0" style="border:#CCCCCC solid 1px;">
                <tr>
                  <td bgcolor="#E6DFCF"><strong>User: <?php echo $first_name;  ?></strong></td>
                  <td bgcolor="#E6DFCF">&nbsp;</td>
                </tr>
                <tr>
                  <td width="219">Type in New Password </td>
                  <td width="283"><label>
                    <input name="new_password" type="password" id="new_password" size="40" value="<?=$password?>" />
                  </label></td>
                </tr>
                <tr>
                  <td>Confirm New Password </td>
                  <td><input name="confirm" type="password" id="confirm" size="40" value="<?=$password?>"/></td>
                </tr>
                <tr>
                  <td height="34">&nbsp;</td>
                  <td><label>
                    <input name="change_pass" type="submit" id="change_pass" value="Submit" />
                  </label>
                    <label for="Submit"></label>
                    <input type="reset" name="Reset" value="Reset" id="Submit" /> <a href="index.php">Cancel</a> </td>
                </tr>
                <tr>
                  <td height="34">&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
              </table>
    </form>                </td>
                <td width="2%">&nbsp;</td>
              </tr>
              <tr>
                <td height="35">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td width="48%">&nbsp;</td>
                <td width="48%">&nbsp;</td>
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
