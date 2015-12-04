<?php
session_start();
include 'library/config.php';
include 'library/opendb.php';	

$errmsg = '';
$username  = '';
if(isset($_POST['login']))
{

	// Get data
	$username		= $_POST['username'];
	$password		= $_POST['password'];
	// Stripe white spaces
	$username  = trim($username);
	$password  = trim($password);
	
	$username  = mysql_real_escape_string($username);
	$password  = mysql_real_escape_string($password);	
	
	// Check for error
	if($username == '')
	{
		//echo " Empty field : Title <br />";
		$errmsg = "Empty field : USER NAME <br />";
	}
	else if($password == '')
	{
		//echo " Empty field : Content<br />";
		$errmsg = " Empty field : PASSWORD <br />";
	}
	else if($errmsg == '')
	{
		$result  = mysql_query("SELECT * FROM user WHERE email_primary = '$username' AND password = PASSWORD('$password')");
		if($result){
			if(mysql_num_rows($result) > 0){
				// Fetch user info
				$row = mysql_fetch_array($result);
				$priv_id = $row['privilege_id'];
				$_SESSION['username'] = $username;
				$_SESSION['user_is_logged_in'] = true;
				$_SESSION['first_name'] = $row['first_name'];
				$_SESSION['last_name'] = $row['last_name'];
				$result_for_privilege = mysql_query("select priv_type from privilege where id = '$priv_id'") or 
										die("Internal Error ". mysql_error());
				$row_privilege = mysql_fetch_array($result_for_privilege);
				if($row_privilege['priv_type'] == "reviewer"){
					$_SESSION['privilege'] = "reviewer";
					header("Location:index.php");
				}
				else{
					$errmsg = "Couldn't log you in, please contact the webmaster at webmaster@ju.edu.et";
				}				

			}
			else{
				$errmsg = "invalid user name / password ";
			}
		}	
		else{
			echo mysql_error();
		}
	}
}
elseif(isset($_POST['request']))
{
	// Get email address
	$email = $_POST['email'];
	// Check if the email address is valid
	if($email == ''){
		$errmsg = "You need to provide us your email address!";
	}
	elseif(!check_email_address($email)){
		$errmsg = "Please enter a valid email address!";
	}
	else{
		// Search for the email address in DATABASE
		$result = mysql_query("select * from user where email_primary = '$email'");	
		if(!$result){
			$errmsg = mysql_error();
		}
		else{
			if(mysql_num_rows($result) > 0){
			$msg =  "Your request is Succesfully submitted.  ";
			
			$headers = 'From: Ethiopian Journal of Health Sciences <ejhs@ju.edu.et>';
			$subject = "Account information on EJHS";
			$Data	= " Your login name is #LOGIN# and 
						Your password at http://www.ju.edu.et/ejhs is #PASSWORD#
						Thank you for being a member of Ethiopian Journal of Health Sciences!
						";
			$search = array('#LOGIN#','#PASSWORD#');
			$row = mysql_fetch_array($result);
			$replace = array($row['email_primary'], $row['password']);
			if(!mail($email, $subject, str_replace($search, $replace, $Data), $headers)){
				$msg .=  " And a confirmation message will be sent with in 24 hours  ";
			}
			else{
				$msg .=  " And your login information has been sent to your E-mail address!";
			}	
		}
		}
		// Send message for account detail
	}
}
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
            <td width="86" bgcolor="#575352"><br />			</td>
            <td width="785" bgcolor="#575352">&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td valign="top"><table width="100%" border="0">
              <tr>
                <td width="7%">&nbsp;</td>
                <td width="7%">&nbsp;</td>
                <td width="86%" align="left">
				
					<blockquote>
					  <?php
						if($errmsg != ''){
						?>
						<p style="color:#FF0000; border:#FF0000 solid 1px;"> 
						You have error : <?php echo $errmsg; ?><br />
						  </p>
						<?php
						}
						?>
					  <form id="form1" name="form1" method="post" action="">
					    <table width="385" height="95" border="0" cellspacing="0">
					      <tr>
					        <td width="10" bgcolor="#EFEFEF">&nbsp;</td>
						    <td width="70" rowspan="2" bgcolor="#EFEFEF"><img src="img/key.gif" alt="Key" width="48" height="48" /></td>
						    <td width="70" height="28" bgcolor="#EFEFEF">User name </td>
						    <td width="227" bgcolor="#EFEFEF"><label>
						      <input name="username" type="text" id="username" value="<?=$username;?>" />
					        </label></td>
						  </tr>
					      <tr>
					        <td bgcolor="#EFEFEF">&nbsp;</td>
						    <td height="31" bgcolor="#EFEFEF">Password</td>
						    <td bgcolor="#EFEFEF"><label>
						      <input name="password" type="password" id="password" value="" />
						      <input name="login" type="submit" id="login" value="Login" />
					        </label></td>
						  </tr>
					      <tr>
					        <td height="36" colspan="4" bgcolor="#EFEFEF"><a href="#">forgot password</a>?<br /></td>
					      </tr>
				        </table>
					  </form>
				  </blockquote></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
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
