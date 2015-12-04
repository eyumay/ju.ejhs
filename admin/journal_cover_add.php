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
include 'EJHSJournal.class.php';
include 'EJHSJournalForm.class.php'; 
include 'EJHSFormValidator.class.php'; 

	$year_of_edition	= '';
	$month				= '';
	$vol				= '';
	$num				= '';
	$issn				= '';
	$eissn				= ''; 
	$errmsg = '';
	
	$updateID		= $_GET['updateID']; 
	$oneResult		= EJHSJournal::queryOneById($updateID);
	$oneResultRow	= mysql_fetch_array($oneResult, MYSQL_ASSOC);
	$upload_dir		= $oneResultRow['upload_dir'];	
	$existingCover	= $oneResultRow['coverpage_location']; 
	$vol			= $oneResultRow['volume']; 
	$num			= $oneResultRow['num']; 
	$year			= $oneResultRow['year']; 	

if(isset($_POST['upload_journal']))
{	
		$updateID		= $_GET['updateID']; 
		$oneResult		= EJHSJournal::queryOneById($updateID);
		$oneResultRow	= mysql_fetch_array($oneResult, MYSQL_ASSOC);
		$upload_dir		= $oneResultRow['upload_dir'];	
		$existingCover	= $oneResultRow['coverpage_location']; 
		$volume			= $oneResultRow['volume']; 
		$num			= $oneResultRow['num']; 
		$year			= $oneResultRow['year']; 
		
		## VALIDATE UPLOADED FILE FOR JPED
		if(!EJHSFormValidator::validateJPEG($_FILES))
			$errmsg .= "Error With Attached File: Please Make Sure It Is JPEG or GIF Image File Format. <br /> ";
		else
		{
			if(EJHSFormValidator::checkIfFileExists($upload_dir, $_FILES) ) 
				$errmsg .= "File Already Exists <br /> ";		
		}	

	
		
		$fileName = $_FILES['cover']['name'];		
	
		
		if( $errmsg == "") ## There have been NO ERRORS
		{		
			$result	= EJHSJournal::update(NULL, NULL, NULL, NULL, NULL, NULL, NULL, $updateID, $fileName);						
			
			if($result){			
				EJHSJournal::upload($_FILES, $upload_dir);
				unlink($upload_dir.'/'.$existingCover); 
				
				$_SESSION['notice1']	= "Coverpage Upload Was Successful "; 
				header('location: journal_detail.php?detailID='.$updateID); 
				exit(); 
			}
			else
				$errmsg .=  "Journal Add Was Not Successful Due To Error On Database: ".mysql_error();
			
		}
		else
			$errmsg .=  "Journal Add Was Not Successful ";			
	
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


<form action="<?php echo $_SERVER['PHP_SELF'].'?updateID='.$_GET['updateID']; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <table width="100%" border="0" cellspacing="0">
  
 	<?php if(isset($_SESSION['notice'] )): ?>
    <tr  bgcolor="#B7FDAA" >
      <td colspan="5">
	  	<?php echo $_SESSION['notice']; ?>
		<?php unset($_SESSION['notice']); ?>	  </td>
     </tr>
	<?php endif; ?>

 	<?php if( $errmsg !='' ): ?>
    <tr  bgcolor="#FFA4A6" >
		<td colspan="5">
	  		<?php echo $errmsg;  ?>
		</td>
     </tr>
	<?php endif; ?>	 
	 
    <tr>
      <td colspan="5"><h3>Add /Update Journal Cover Page: Volume - <?php echo $vol; ?>, Number - <?php echo $num; ?>, <?php echo $year; ?></h3></td>
      </tr>
    <tr>
      
      <td>Please Select Covel Page </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="84%"><table width="100%" border="0" cellspacing="0">
        <tr>
          <td align="right">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>			

        <tr>
          <td width="23%" align="right">Journal cover page * </td>
          <td width="2%">&nbsp;</td>
          <td width="75%"><label>
            <input name="cover" type="file" id="cover" />
          </label></td>
        </tr>
        <tr>
          <td><label></label></td>
          <td>&nbsp;</td>
          <td>		  </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><label>
            <input name="upload_journal" type="submit" id="upload_journal" value="U p l o a d " />
            <a href="journal_detail.php?detailID=<?php echo $_GET['updateID']; ?>"> Back To Journals Detail       </a></label></td>
        </tr>
      </table></td>
      <td width="1%">&nbsp;</td>
      <td width="5%">&nbsp;</td>
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
