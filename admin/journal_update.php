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
include 'EJHSJournalArticle.class.php';
include 'EJHSJournalForm.class.php'; 
include 'EJHSFormValidator.class.php'; 

$year_of_edition	= '';
$month				= '';
$vol				= '';
$num				= '';
$issn				= '';
$eissn				= ''; 
$errmsg 			= '';
$upload_dir			= ''; 
$updateID			= ''; 


if(isset($_POST['upload_journal']))
{
	// Get Data	
	$num				= $_POST['num'];
	$vol				= $_POST['vol'];
	$month				= $_POST['month'];
	$year_of_edition 	= $_POST['year'];
	$issn				= $_POST['issn'];	
	$eissn				= $_POST['eissn'];
	$journalExists		= TRUE; ;
	$updateID			= $_GET['updateID'];								
	$date				= date('d-m-Y');		
	
	if($vol == '')
		$errmsg .= "Volume should be selected <br />";
	if($num == '')
		$errmsg .= "Number should be selected <br />";
	if($month == '')
		$errmsg .= "Month should be selected <br />";
	if($year_of_edition == '')
		$errmsg .= "Year of Edition should be selected <br />";
	if($issn == '')
		$errmsg .= " ISSN Field should be entered. <br />";

	
	if($errmsg == '')
	{								
		$new_upload_dir	= "Volume-".$vol."-Num".$num;		
		## Journal Location Not Changed, and New Journal Information Does Not Exist
		if(!EJHSJournal::checkIfJournalExists($vol, $num))
		{
			$journalExists		= FALSE; 
			$create_dir			= "Volume-".$vol."-Num".$num;
			$old_upload_dir		= EJHSJournal::getUploadDir($updateID); ##OLD UPLOAD DIR 							
			EJHSJournal::recursiveCopy($old_upload_dir, $new_upload_dir); 
			EJHSJournal::deleteFiles($old_upload_dir); ## DELETE OLD DIR WITH CONTENTS 					
		}
			
 		
		if($journalExists)## Don't Save Volume, and Number Information If Journal Exists
		{
			$vol				= NULL;
			$num				= NULL; 
			//$new_upload_dir		= NULL; 
		}
		
		if( EJHSJournal::update($year_of_edition, $month, $vol, $num, $issn, $eissn, $new_upload_dir, $updateID) &&
			EJHSJournalArticle::updateArticleUploadDir($new_upload_dir, $updateID ) )
		{
			$_SESSION['notice1'] = "Journal Update Was Successful. ";
			header('location: journal_edit.php');				
		}
		else
			echo mysql_error() . "It Seems Like There Are Errors"; 	
						
	}			
				
}

if(!isset($_GET['updateID']) )
{
	$_SESSION['error'] = "Unable To Start Update: UpdateID Error.";
	header('location: journal_edit.php');
}
else
{	
	$updateID	= $_GET['updateID'];
	
	$result		= EJHSJournal::queryOneById($updateID);
	
	$row		= mysql_fetch_array($result, MYSQL_ASSOC);
	
	$year_of_edition	= $row['year'];
	$month				= $row['month'];
	$vol				= $row['volume'];
	$num				= $row['num'];
	$issn				= $row['issn'];
	$eissn				= $row['eissn'];
	$cover				= $row['coverpage_location'];  
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
      <td colspan="5"><h3>Update Journal: Volume <?php echo $vol;?> - Number <?php echo $num;  ?></h3></td>
      </tr>
    <tr>
      
      <td>Please Select Journal Information Using the Form Below </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="84%"><table width="100%" border="0" cellspacing="0">
        <tr>
          <td width="23%" align="right">Year * </td>
          <td width="2%">&nbsp;</td>
          
		  <td width="75%">
		  <?php $yearChoices	= EJHSJournalForm::getYearFormChoices(); ?>
		  
		  <select name="year">
		  <optgroup style="font-size:11px; width:100px; ">
		  <option value="">  Select Year   </option>
		  <?php foreach( $yearChoices as $key=>$value): ?> 
		  	<option value="<?php echo $key; ?>" <?php if($value == $year_of_edition) { ?> selected="selected" <?php } ?> > <?php echo $value; ?> </option>
		  <?php endforeach; ?>
		  </optgroup>
		  </select>		  </td>
        </tr>
        <tr>
          <td align="right">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="right">Volume * </td>
          <td>&nbsp;</td>
          <td><?php $volumeChoices	= EJHSJournalForm::getVolumeFormChoices(); ?>
            <select name="vol" id="vol">
              <optgroup style="font-size:11px; width:100px; ">
              <option value="" > Select Volume </option>
              <?php foreach( $volumeChoices as $key=>$value): ?>
              <option value="<?php echo $key; ?>" <?php if($value == $vol) { ?> selected="selected" <?php } ?>> <?php echo $value; ?> </option>
              <?php endforeach; ?>
              </optgroup>
            </select>			</td>
        </tr>
        <tr>
          <td align="right">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="right">Number * </td>
          <td>&nbsp;</td>
          <td><?php $numberChoices	= EJHSJournalForm::getNumberFormChoices(); ?>
            <select name="num" id="num">
              <optgroup style="font-size:11px; width:100px; ">
              <option value="" > Select Number </option>
              <?php foreach( $numberChoices as $key=>$value): ?>
              <option value="<?php echo $key; ?>" <?php if($value == $num) { ?> selected="selected" <?php } ?>> <?php echo $value; ?> </option>
              <?php endforeach; ?>
              </optgroup>
            </select>			</td>
        </tr>
        <tr>
          <td align="right">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>	
        <tr>
          <td align="right">Month * </td>
          <td>&nbsp;</td>
          <td><?php $monthChoices	= EJHSJournalForm::getMonthFormChoices(); ?>
            <select name="month" id="month">
              <optgroup style="font-size:11px; width:100px; ">
              <option value="" > Select Month  </option>
              <?php foreach( $monthChoices as $key=>$value): ?>
              <option value="<?php echo $key; ?>" <?php if($key == $month) { ?> selected="selected" <?php } ?> > <?php echo $value; ?> </option>
              <?php endforeach; ?>
              </optgroup>
            </select>			</td>
        </tr>
        <tr>
          <td align="right">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="right">Journal ISSN * </td>
          <td>&nbsp;</td>
          <td><input name="issn" type="text" id="issn" value="<?=$issn;?>" /></td>
        </tr>
        <tr>
          <td align="right">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="right">Journal eISSN </td>
          <td>&nbsp;</td>
          <td><input name="eissn" type="text" id="eissn" value="<?=$eissn;?>" /></td>
        </tr>
        <tr>
          <td align="right">&nbsp;</td>
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
          <td><label>
            <input name="upload_journal" type="submit" id="upload_journal" value="U P D A T E " />
            <a href="journal_edit.php"> Back To Journals List       </a></label></td>
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
