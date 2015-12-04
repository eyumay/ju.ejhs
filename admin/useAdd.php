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

$errmsg				= '';
$msg				= '';
$first_name			= '';
$middle_name		= '';
$last_name			= ''; 
$email_primary		= '';
$email_secondary	= '';
$password			= '';
$confirm_password	= '';

$display_form = true;

function check_email_address($email) {
	// First, we check that there's one @ symbol, and that the lengths are right
	if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
	// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
	return false;
	}
	// Split it into sections to make life easier
	$email_array = explode("@", $email);
	$local_array = explode(".", $email_array[0]);
	for ($i = 0; $i < sizeof($local_array); $i++) {
	if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
	return false;
	}
	} 
	if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
	$domain_array = explode(".", $email_array[1]);
	if (sizeof($domain_array) < 2) {
	return false; // Not enough parts to domain
	}
	for ($i = 0; $i < sizeof($domain_array); $i++) {
	if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
	return false;
	}
	}
	}
	return true;
}


if(isset($_POST['user_register']))
{
	$errmsg = '';
	// Get data
	$first_name = $_POST['first_name'];
	$middle_name = $_POST['middle_name'];
	$last_name = $_POST['last_name'];

	$email_primary = $_POST['email_primary'];
	$email_secondary = $_POST['email_secondary'];
	$password = $_POST['password'];
	$confirm_password = $_POST['confirm_password'];
	
	
	// Check for error
	if($first_name == '')
	{
		$errmsg = " Empty field : First Name <br />";
	}
	else if($middle_name == '')
	{
		$errmsg = " Empty field : Middle Name <br />";
	}
	else if($password == '')
	{
		$errmsg = " Empty field : Password <br />";
	}
	else if($password != $confirm_password )
	{
		$errmsg = " Empty field : Passwords do not match! <br />";
	}
	
	if($errmsg == '')
	{
		// Check if the email address doesnot exist
		$result_for_email_address = mysql_query("select * from user where email_primary = '$email_primary'");
		if(mysql_num_rows($result_for_email_address) > 0)
		{
			$errmsg = "Primary email address already exists in the database";
		}
		else
		{
			// Generate a system date.
			$date	= date("Y-m-d");
			$query = "insert into user (first_name, middle_name, last_name, email_primary, email_secondary, password, created_at, updated_at, privilege_id) values ('$first_name', '$middle_name', '$last_name', '$email_primary', '$email_secondary', PASSWORD('$password'), '$date', '$date', 3)";
			if(!mysql_query($query))
			{
				echo "an error occured". mysql_error();
			}
			else
			{
				$_SESSION['notice'] = "User Information Has Been Saved Successfuly "; 
				header('location:useEdit.php');	
					$errmsg = '';
				// Get data
				$first_name = '';
				$middle_name = '';
				$last_name = '';

				$email_primary = '';
				$email_secondary = '';

				$password = '';
				$confirm_password = '';
				$display_form = false;				
			}
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

<div id="main-copy">
			
			<?php
			if($msg != ''){
			?>
			<div style="color:#009900; border:#009900 solid 1px;">
			<h2> <?php echo $msg; ?></h2> 
			<p> 			
			A confirmation message with login information has been sent to your prefered email address. Click <a href="login.php">here</a> to proceed.
			</p>
			</div>
			<?php
			}
if($display_form){			
			?>
			
			<form id="form1" method="post" action="" enctype="multipart/form-data">
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
                <tr bgcolor="#E6DFCF">
                  <td>Login informaiton </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>password</td>
                  <td><input name="password" type="password" id="password" size="40" value="<?=$password ?>"/></td>
                </tr>
                <tr>
                  <td>Confirm your password </td>
                  <td><input name="confirm_password" type="password" id="confirm_password" size="40" value="<?=$confirm_password; ?>"/></td>
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
<?php
}?>	
		  </p>
         
  </div>				
				
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
