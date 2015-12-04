<?php
include 'library/config.php';
include 'library/opendb.php';
	// Retrieve data from Query String
$first_name = $_GET['first_name'];
$last_name = $_GET['last_name'];
$email = $_GET['email'];
	// Escape User Input to help prevent SQL Injection
$first_name = mysql_real_escape_string(trim($first_name));
$last_name = mysql_real_escape_string(trim($last_name));
$email = mysql_real_escape_string(trim($email));
	//build query

if($email != '')
{
	if(check_email_address($email))
	{
		##check if already subscribed;
		$query = mysql_query("select * from subscribers where email_address = '$email' ");
		if(mysql_num_rows($query) > 0)
			echo "<div style='background:#ACF0AE; font-weight:bold; color:#FFFFFF;' > ...  Oops, are already subscribed </div>";
		else {
			$query = "INSERT INTO subscribers (first_name, last_name, email_address) VALUES ('$first_name', '$last_name', '$email')";
			
			$qry_result = mysql_query($query) or die(mysql_error());
			
			if($qry_result)
				echo "<div style='background:#ACF0AE; font-weight:bold; color:#FFFFFF;' > Successfuly Subsribed to EJHS. </div>"; 
		 }
	}
	else
		echo "<div style='background:#FF9191; font-weight:bold; color:#FFFFFF;' > Email must be valid one</div>";
}
else
	echo "<div style='background:#FF9191; font-weight:bold; color:#FFFFFF;' > Subscription was not successful, Email is required to subscribe </div>";

function check_email_address($email) {
	if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i", $email))
		return TRUE;
	else
		return FALSE; 
}
	
	
?>