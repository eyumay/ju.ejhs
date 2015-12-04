<?php 
session_start(); 
if(!$_SESSION['user_is_logged_in']){
	header("location:login.php");
}
if($_SESSION['privilege'] != "reviewer"){
	header("location:login.php");
}
include_once "includes/fckeditor/fckeditor.php"; 
include 'library/config.php';
include 'library/opendb.php';

require_once("email.class.php");
require_once("pager.class.php"); 

$info = NULL;
$msg1 = "
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<title>EJHS</title>
<style> 
		body {	
			width: 900px;
			margin: 0 auto;
			font-family: Lucida Grande, Tahoma, Arial, Helvetica, sans-serif; 
			font-size: 12px;
			line-height: 1.6em;
			color: #666;
			background-color: #FFF;
		}
		
		h1 {
			font-family: Arial, Helvetica, sans-serif;
			font-weight: normal;
			font-size: 32px;
			color: #CC6633;
			margin-bottom: 30px;
			background-color: #FFF;
		}
		
		h2 {
			color: #FFF;
			font-size: 16px;
			font-family: Arial, Helvetica, sans-serif;			
		}
		
		a {
			color:#CC6714;
			text-decoration: none;
		}

		a:hover {
			color:#CC6714;
			background-color: #F5F5F5;
		}

		
		form {
			padding:3px;
			margin-bottom:2px;
			font-size:12px;
		}
		
		form Label {
		color:#FFFFFF;
		}
		
		input {
			background-color: #FFF;
			color:#333333;
			border: 1px solid #CCC;
			font-size: 11px;
			padding: 3px;
		}
		
		.button {
			padding: 1px;
			border:1px solid #666666;
		}
			
</style>
</head>
<body>
<table width='720' border='0' align='center' cellpadding='0' cellspacing='0'>

  <tr>
    <td width='30'>&nbsp;</td>
    <td width='638'><p>Dear #MR# #FIRSTNAME# #LASTNAME#,</p>
	
      <p>
	  
	  ";
	  
	$msg2 =   " I would like to invite you to read this month's issues from our website. Here are the Table of Contents";
	$msg3 = "
	</p>
      <p>Abraham Haileamlak
        <br />
        Editor-in-chief<br />
 -------------------------------------------------------------------------------------<br />
If you want to unsubscribe from our mailing list, <br />
please send your
email address to <a href='mailto:ejhs@ju.edu.et'>ejhs@ju.edu.et</a>. </p>
    <p>Ethiopian Jounral of Health Sciences<br />
    website: <a href='http://www.ejhs.ju.edu.et'><em>http://www.ejhs.ju.edu.et/</em></a></p></td>
    <td width='30'>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
";

$msg2 .= "
<h3 align='center'> Ethiopian Journal of Health Sciences </h3>
";

$query =  "SElECT * from journal WHERE is_activated = 'activated' order by volume DESC, month DESC limit 1";							
$result = mysql_query($query);

if(!$result)
{
	echo "An error occured".mysql_error();
}
else
{
	$month = '';
	$prevCategory = ''; 
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	$journal_id = $row['id'];
	if($row['month'] == 1)
		$month = "January";
	if($row['month'] == 2)
		$month = "February";
	if($row['month'] == 3)
		$month = "March";
	if($row['month'] == 4)
		$month = "April";
	if($row['month'] == 5)
		$month = "May";
	if($row['month'] == 6)
		$month = "June";
	if($row['month'] == 7)
		$month = "July";
	if($row['month'] == 8 )
		$month = "August";
	if($row['month'] == 9)
		$month = "September";
	if($row['month'] == 10)
		$month = "October";
	if($row['month'] == 11)
		$month = "Nobember";
	if($row['month'] == 12)
		$month = "December";																						
		
	$volInfo = $month.', Volume '.$row['volume'].' Number-'.$row['num']; 
	
	$msg2 .= "<h3 align='center'>". $volInfo ." Content </h3> ";
	
	$fetch_articles = mysql_query("select * from journal_article where journal_id = '$journal_id' order by start_page_number ASC");
	if(mysql_num_rows($fetch_articles)>0)
	{
		while($row = mysql_fetch_array($fetch_articles,MYSQL_ASSOC))
		{
			$curCategory = $row['category'];
			
			
			if($curCategory != $prevCategory)
			{
				if($curCategory == "editorial")
					$category =  "<strong> Editorials </strong> <br />";
				if($curCategory == "original")
					$category =  "<strong> Original Articles </strong> <br />";
				if($curCategory == "brief")
					$category =  "<strong> Brief Communications </strong> <br />";								
				if($curCategory == "case")
					$category =  "<strong> Case Report </strong> <br />";
				if($curCategory == "review")
					$category =  "<strong> Review Articles </strong> <br />";
				if($curCategory == "bookreview")
					$category =  "<strong> Book Review </strong> <br />";												
				$prevCategory = $curCategory;
			}
			
			$msg2 .= "<div style='margin:20px;'> ".$category.$row['title']. "<br />";
			$id		= $row['id'];	
			$msg2 .= "<em>".$row['authors']. " [".$row['start_page_number']."-".$row['end_page_number']."]</em><br /></div>";
			$curCategory = $row['category'];
			$category = ''; 
		}
	}
}
$subject = "EJHS: Table of Contents for ".$volInfo;

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
                <td width="7%" bgcolor="#F3F3F3"><strong>»</strong></td>
                <td width="93%" bgcolor="#F3F3F3"><a href="newsadd.php"><strong>Add News </strong></a></td>
              </tr>
              <tr>
                <td bgcolor="#F3F3F3"><strong>»</strong></td>
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
                <td bgcolor="#F3F3F3"><a href="email_add.php"><strong>Add email address  </strong></a></td>
              </tr>
              <tr>
                <td bgcolor="#F3F3F3"><strong>&raquo;</strong></td>
                <td bgcolor="#F3F3F3"><a href="email_edit.php"><strong>Edit email address </strong></a></td>
              </tr>
              
              <tr>
                <td bgcolor="#F3F3F3"><strong>&raquo;</strong></td>
                <td bgcolor="#F3F3F3"><a href="email_send.php"><strong>Send Notification Msg  </strong></a></td>
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
            <td valign="top">
<table width="100%" border="0" cellspacing="0">
  <tr>
    <td width="13%">&nbsp;</td>
    <td width="79%">&nbsp;</td>
    <td width="8%">&nbsp;</td>
  </tr>
</table>
<h1> Send E-Mail </h1>
<?php
	if(isset($_POST['Submit'])){	
	// Validation	
		$em = new Email();
		$msg2 = $_POST['message'];
		$msg = $msg1.$msg2.$msg3;
		$subject = $_POST['subject'];
		$em->sendEmail($msg, $subject);
		$info = "Success! The message has been sent to multiple recipients, mailing list in EJHS Database.";
	}
?>

<?php 
if($info != NULL){ ?>
<p class="success"> <strong >
<?php 
echo $info;
$info = NULL;		
?>
</strong></p>
<?php
}
?>


<form id="form1" name="form1" method="post" action="">
  <table width="628" border="0" cellspacing="0">
    <tr>
      <td align="right" valign="top">Subject</td>
      <td colspan="2"><input name="subject" type="text" id="subject" value="<?=$subject;?>
        " size="85" /></td>
    </tr>
    <tr>
      <td align="right" valign="top">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td width="142" align="right" valign="top">Message</td>
      <td width="476" colspan="2"><label>
		<?php
			  $oFCKeditor = new FCKeditor('message');
			  $oFCKeditor->BasePath = "includes/fckeditor/";
			  $oFCKeditor->Value    = $msg2;
			  $oFCKeditor->Width    = 540;
			  $oFCKeditor->Height   = 400;
			  echo $oFCKeditor->CreateHtml();
		?>        
      </label></td>
      </tr>
    <tr>
      <td align="center">&nbsp;</td>
      <td colspan="2" align="left"><label>
        <input type="submit" name="Submit" value="Submit" />
      </label></td>
    </tr>
  </table>
</form>
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
