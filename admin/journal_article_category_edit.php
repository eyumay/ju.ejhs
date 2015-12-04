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
$name		= ''; 
$ID			= ''; 
if(isset($_GET['id']))
{
	## fetch
	$ID = $_GET['id'];
	$result = mysql_query("SELECT * FROM journal_article_category where id='$ID'");
	if(!$result){
		echo "Error on DB".mysql_error();
	}
	else{
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$name = $row['name'];
	}
}
else
{
	## Error Occured
	$_SESSION['error'] = "Internal Error Occured While Processing Request, Please Redo Actions.";
	## Redirect
	header('location: journal_article_category_list'); 
}

if(isset($_POST['save_category']))
{	
	// Get Data	
	$name		= trim($_POST['name']);
	$ID			= trim($_POST['ID']); 

	
// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@		
	if($name == '')
	{
		$errmsg .= "Name should be provided <br />";
	}
	if($ID == '')
	{
		$errmsg .= "Internal Error - ID <br />";
	}	
	
	if($errmsg == '')
	{
		//store both				
		$result = mysql_query("
			UPDATE journal_article_category  SET name = '$name' where id = '$ID' "); 	
		if($result)
		{					
			$_SESSION['notice'] = "Category Was Updated Successfuly "; 
			header('location:journal_article_category_list.php');		
		}
		else
			$errmsg .=  "There was error while Updating Article Category".mysql_error()."<br />";
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
<script language="javascript">
var counter = 0;

function moreFields() {
	counter++;
	var newFields = document.getElementById('readroot').cloneNode(true);
	newFields.id = '';
	newFields.style.display = 'block';
	var newField = newFields.childNodes;
	for (var i=0;i<newField.length;i++) {
		var theName = newField[i].name
		if (theName)
			newField[i].name = theName + counter;
	}
	var insertHere = document.getElementById('writeroot');
	insertHere.parentNode.insertBefore(newFields,insertHere);
}
//window.onload = moreFields;
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
<?php
if( $errmsg != '')
{
?>
  <tr>
    <td colspan="3">
	<p class="error">
	<?php echo $errmsg ."<br /> "; ?>
	</p>
	
	</td>
  </tr>
<?php
}
?>

  <tr>
    <td width="13%">&nbsp;</td>
    <td width="79%">
	</td>
    <td width="8%">&nbsp;</td>
  </tr>
</table>

<form action="journal_article_category_edit.php" method="post" name="form1" id="form1">
  <table width="100%" border="0" cellspacing="0">
    <tr>
      <td bordercolor="#F2F2F2" bgcolor="#F2F2F2">
	  
	    <h3><span id="writeroot"></span> Edit Article Category </h3></td>
      </tr>
    <tr>
      <td bordercolor="#F2F2F2" bgcolor="#F2F2F2"><hr width="500" /></td>
      </tr>
    <tr>
      <td width="94%" bgcolor="#F2F2F2"><table width="100%" border="0" cellspacing="0">
        <tr>
          <td width="24%">&nbsp;</td>
          <td width="1%">&nbsp;</td>
          <td width="75%">&nbsp;</td>
        </tr>
        <tr>
          <td align="right">Category Name </td>
          <td>&nbsp;</td>
          <td><input name="name" type="text" id="name" value="<?=$name;?>" size="40" />
            <input name="ID" type="hidden" id="ID" value="<?=$ID?>" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><label>
            <input name="save_category" type="submit" id="save_category" value="Save" />
          </label></td>
        </tr>
      </table></td>
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
