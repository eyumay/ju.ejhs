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

$errmsg		= '';
$msg		= '';

if(isset($_GET['editUser']))
{
	$editUserID  = $_GET['editUser'];
	$query = "select * from user where id = '$editUserID'";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	$first_name = $row['first_name'];
	$middle_name = $row['middle_name'];
	$last_name = $row['last_name'];
	$email_primary = $row['email_primary'];
	$email_secondary = $row['email_secondary'];	
}
else {
	$_SESSION['error'] = "Error While Trying to Update User Information"; 
	header('location:useEdit.php');		
}
if(isset($_POST['user_register']))
{

	$errmsg = '';
	// Get data
	$first_name = trim($_POST['first_name']);
	$middle_name = trim($_POST['middle_name']);
	//$is_published = $_POST['is_published'];
	$last_name =trim($_POST['last_name']);
	$email_primary = trim($_POST['email_primary']);
	$email_secondary = trim($_POST['email_secondary']);
	
	$editUserID  = $_GET['editUser'];

	// Check for error
	if($first_name == '')
	{
		//echo " Empty field : Title <br />";
		$errmsg = " Empty field : first_name <br />";
	}
	else if($editUserID == '' && is_int($editUserID) ) 
	{
		//echo " Empty field : Title <br />";
		$errmsg = " Internal Error, Unable to save User Data <br />";
	}	
	else if($middle_name == '')
	{
		//echo " Empty field : Content<br />";
		$errmsg = " Middle Name Should Be Provided<br />";
	}
	else if($errmsg == '')
	{
		// Generate a system date.
		$date	= date("Y-m-d");

		$query = "UPDATE user SET first_name = '$first_name', middle_name = '$middle_name', last_name='$last_name', created_at ='$date' , updated_at ='$date' , email_primary ='$email_primary' , email_secondary = '$email_secondary' WHERE id = '$editUserID'";
		if(!mysql_query($query))
		{
			
			$_SESSION['error'] = "Internal Error: ".mysql_error(); 
			header('location:useEdit.php');	
			
		}
		else
		{
			$_SESSION['notice'] = "User Information Was Updated Successfuly "; 
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

.style1 {
	color: #FFFFFF;
	font-weight: bold;
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
                <td width="48%">&nbsp;</td>
                <td width="48%">&nbsp;</td>
                <td width="2%">&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td colspan="2" rowspan="2" valign="top">
					<p> Update user Info @ EJHS </p>
					
					<?php
					if($errmsg != ''){
					?>
						<p style="color:#FF0000; border:#FF0000 solid 1px;"> 
						You have an error while updating user info: <?php echo $errmsg; $errmsg = '';?><br />
						</p>
					<?php
					}
					?>
					
					<?php
					if($msg != ''){
					?>
						<p style="color:#009900; border:#009900 solid 1px;"> 
						Success : You have succesfully updated the user info <?php echo $msg; ?><br />
						</p>
					<?php
					}
					?>
					
					<form id="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'].'?editUser='.$_GET['editUser']; ?>" enctype="multipart/form-data">
			<?php
			if($errmsg != ''){
			?>
<p style="color:#FF0000; border:#FF0000 solid 1px;"> 
			You have an error while submitting: <?php echo $errmsg; ?><br />
			</p>
			<?php
			}
			?>

              <table width="506" height="152" border="0" cellspacing="0" style="border:#CCCCCC solid 1px;">
                <tr>
                  <td bgcolor="#E6DFCF">Personal </td>
                  <td bgcolor="#E6DFCF">&nbsp;</td>
                </tr>
                <tr>
                  <td width="219">First Name </td>
                  <td width="283"><label>
                    <input name="first_name" type="text" id="first_name" size="40" value="<?=$first_name ?>" />
                  </label></td>
                </tr>
                <tr>
                  <td>Middle Name </td>
                  <td><input name="middle_name" type="text" id="middle_name" size="40" value="<?=$middle_name; ?>"/></td>
                </tr>
                <tr>
                  <td>Last Name </td>
                  <td><input name="last_name" type="text" id="last_name" size="40" value="<?=$last_name; ?>"/></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr bgcolor="#E6DFCF">
                  <td>Contact address </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>Primary Email Address </td>
                  <td><input name="email_primary" type="text" id="email_primary" size="40" value="<?=$email_primary ?>"/></td>
                </tr>
                <tr>
                  <td>Secondary Email Address </td>
                  <td><input name="email_secondary" type="text" id="email_secondary" size="40" value="<?=$email_secondary ?>"/></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td height="34">&nbsp;</td>
                  <td><label>
                    <input name="user_register" type="submit" id="user_register" value="Submit" />
                  </label>
                    <label for="Submit"></label>
                    <input type="reset" name="Reset" value="Reset" id="Submit" /> <a href="index.php">Cancel</a> </td>
                </tr>
                <tr>
                  <td height="34">&nbsp;</td>
                  <td>&nbsp;</td>
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
