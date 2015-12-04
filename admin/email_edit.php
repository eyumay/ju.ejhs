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
require_once("pager.class.php"); 

//fetch queryd
if(isset($_GET['del']))
{
    // remove the article from the database
	$id_to_delete = $_GET['del'];
	mysql_query("DELETE FROM subscribers WHERE id = '$id_to_delete'") or die('Error : ' . mysql_error());
       
    // redirect to current page so when the user refresh this page
    // after deleting an article we won't go back to this code block
    //header('Location: ' . $_SERVER['PHP_SELF']);
    //exit;
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
        window.location.href = 'email_edit.php?del=' + id;
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
<h1> Edit email addresses </h1>
<?php 
	if(isset($_GET['edit'])) {
		$get = $_GET['email_id'];
		$query = "select * from subscribers where id = '$get'";
		$obj_email = new Email();
		$obj_email->fetchRow($query);
		
		$first_name		= $obj_email->getFirstName();
		$last_name		= $obj_email->getLastName();
		$email			= $obj_email->getEmail();
		$is_activated	= $obj_email->getIsActivated();
		
		include "email_form.php";
	} 
	
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
		$email_id = $_GET['email_id'];
		$query= "UPDATE subscribers SET
		first_name = '".$em->getFirstName()."', 
		last_name = '".$em->getLastName()."', 
		title = '".$em->getTitle()."', 
		email_address = '".$em->getEmail()."', 
		is_activated = '".$em->getIsActivated()."' where id='$email_id'";
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
	?>
  <table width="522" border="1" cellspacing="0">
    <tr>
      <td width="173" align="center"><strong>Full name </strong></td>
      <td width="181" align="center"><strong>
        <label>Email Address </label>
      </strong></td>
      <td width="154" align="center"><strong>Actions</strong></td>
    </tr>
<?php 
	include "library/config.php";
	include "library/opendb.php";

	$sql = "SELECT * FROM subscribers where 1"; 
	$pager = new pager($sql,'page',15); 
	while($row = mysql_fetch_array($pager->result))  
	{	
		$pager->sno++;
?>
    <tr>
      <td><?php echo $row['Title']." ".$row['first_name']. " ". $row['last_name']; ?></td>
      <td><?php echo $row['email_address']; ?></td>
      <td><a href="journal_article_update.php">&nbsp;<img src="img/b_edit.png" alt="edit" width="16" height="16" border="0" /></a> 
	  <a href="<?php echo $_SERVER['PHP_SELF']; ?>?email_id=<?php echo $row['id']; ?>&edit=<?php echo "YES";?>"?> Edit </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
	  <img src="img/b_drop.png" alt="del" />&nbsp; <a href="javascript:delArticle('<?php echo $row['id'];?>', '<?php echo $row['Title']." ".$row['first_name']. " ". $row['last_name'];?>');">Delete</a>	  </td>
    </tr>
    <?php 
	}
	?>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><?php echo $pager->show();  ?></td>
      <td>&nbsp;</td>
    </tr>	
</table>


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
