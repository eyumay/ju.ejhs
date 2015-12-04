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
include 'EJHSJournalArticle.class.php';

if(isset($_GET['activate']) and isset($_GET['activate_id']))
{
    // remove the article from the database	
	$activate = $_GET['activate'];
	$activate_id = $_GET['activate_id'];
	if($activate == 'true'){
		$setActivate	= 'activated';	
		$_SESSION['notice1']	= "Journal Activation Was Successful.";
	}
	else{
		$setActivate = '';	
		$_SESSION['notice1']	= "Journal Deactivation Was Successful.";
	}
	$activateJournal = "UPDATE journal SET is_activated = '$setActivate' WHERE id='$activate_id'";
    mysql_query($activateJournal) or die('Error : ' . mysql_error());	
	$msg = "Journal Activation was successful";
        
    // redirect to current page so when the user refresh this page
    // after deleting an article we won't go back to this code block	
    header('Location: ' . $_SERVER['PHP_SELF'].'?detailID='.$_GET['activate_id']);
    exit();
}

if(isset($_GET['del']))
{

	if(EJHSJournalArticle::deleteById( $_GET['del'] ))	
	{	
				
		$_SESSION['notice1'] = 'File Deletion Was Successful.' ;
		header('location:journal_detail.php?detailID='.$_GET['detailID']);
		exit();
	}
	else
	{
		$_SESSION['error']	= "File Deletion Was Not Sccuessful.";
		header('Location: ' . $_SERVER['PHP_SELF'].'?detailID='.$_GET['detailID'].'&her2e=here');
		exit(); 		
	}
}

if( isset($_GET['detailID']) && EJHSJournal::checkIfExistsById($_GET['detailID']))
{
	## Get Journal By ID
	$detailID		= $_GET['detailID'];
	$jResult		= EJHSJournal::queryOneById($detailID);
	$jRow			= mysql_fetch_array($jResult, MYSQL_ASSOC);
	
	$aResult		= EJHSJournalArticle::getArticlesUnderJournal($detailID);
		
}
else
{
	## redirect to Journal Edit
	$_SESSION['notice1'] = "Requested Journal Information Unavailable.";
	header('location: journal_edit.php');
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
<script language="JavaScript">
function delArticle(id, title, detailID)
{
    if (confirm("Are you sure you want to delete '" + title + "'"))
    {
        window.location.href = 'journal_detail.php?del=' + id + '&detailID=' + detailID;
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
				
					<?php if(isset($_SESSION['notice1'])): ?> 
					  	<tr bgcolor="#B7FDAA">
							<td>  
							
								<?php echo $_SESSION['notice1']; ?> 
								<?php unset($_SESSION['notice1']); ?>							</td>
				  		</tr>
					<?php endif; ?>	
					<?php if(isset($_SESSION['error'])): ?> 
					  <tr bgcolor="#FFA4A6">
						<td> 
							<?php echo $_SESSION['error']; ?> 
							<?php unset($_SESSION['error']); ?>						</td>

					<?php endif; ?>	
				
			  </table>
<h3> &nbsp; &nbsp; Manage Journal: Volume - <?php echo $jRow['volume']; ?>, Number - <?php echo $jRow['num']; ?> </h3>

<form action="journal_edit.php" method="get">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F2F2F2">
      <tr>
        <td>&nbsp;</td>
        <td colspan="2">Filter Journal With One or All Fields </td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="2">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="6%">&nbsp;</td>
        <td width="26%">
			Year: 		  
			<?php $yearChoices	= EJHSJournalForm::getYearFormChoices(); ?>		  
			<select name="year">
			  <optgroup style="font-size:11px; width:100px; ">
			  <option value="">  Select Year   </option>
			  <?php foreach( $yearChoices as $key=>$value): ?> 
		  	<option value="<?php echo $key; ?>"  > 
			<?php echo $value; ?> </option>
		  <?php endforeach; ?>
		  </optgroup>
		  </select>		</td>
        <td width="29%">
		Volume: <?php $volumeChoices	= EJHSJournalForm::getVolumeFormChoices(); ?>
            <select name="vol" id="vol">
              <optgroup style="font-size:11px; width:100px; ">
              <option value="" > Select Volume </option>
              <?php foreach( $volumeChoices as $key=>$value): ?>
              <option value="<?php echo $key; ?>"> <?php echo $value; ?> </option>
              <?php endforeach; ?>
              </optgroup>
            </select>		</td>
        <td width="39%">		
		Number: 
		<?php $numberChoices	= EJHSJournalForm::getNumberFormChoices(); ?>
            <select name="num" id="num">
              <optgroup style="font-size:11px; width:100px; ">
              <option value="" > Select Number </option>
              <?php foreach( $numberChoices as $key=>$value): ?>
              <option value="<?php echo $key; ?>" > <?php echo $value; ?> </option>
              <?php endforeach; ?>
              </optgroup>
            </select>		</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="2">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input name="filter_journal" type="submit" id="filter_journal" value="Filter Journal " /></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
</form>

<p>
<a href="journal_update.php">&nbsp;<img src="img/b_edit.png" alt="edit" width="16" height="16" border="0" /></a> <a href="journal_update.php?updateID=<?php echo $jRow['id']; ?>">Edit Journal Information </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="journal_add.php"></a>

<a href="journal_update.php">&nbsp;<img src="img/b_edit.png" alt="edit" width="16" height="16" border="0" /></a><a href="journal_cover_add.php?updateID=<?php echo $jRow['id']; ?>"> Upload Cover Page </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="journal_add.php"></a>
  <?php 
				if($jRow['is_activated'] != ''){
				?>
  <a href="<?php $_SERVER['PHP_SELF']; ?>?activate=false&amp;activate_id=<?php echo $jRow['id'];?>"> <img src="img/EnableIcon.jpg" width="24" height="27" border="0" />Activated</a>
  <?php
				}
				else{
					?>
  <a href="<?php $_SERVER['PHP_SELF']; ?>?activate=true&amp;activate_id=<?php echo $jRow['id'];?>"> <img src="img/DisableIcon.jpg" width="24" height="27" border="0" />Disactivated</a>
  <?php
				}
				?>
</p>
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <table width="100%" border="0" cellspacing="0">
    <tr>
      <td width="1%" bordercolor="#F2F2F2">&nbsp;</td>
      <td width="90%" bordercolor="#F2F2F2">
        <table width="97%" border="0" cellspacing="0">
          <tr>
            <td width="1%">&nbsp;</td>
            <td width="53%"><strong>Available Articles Under This Journal </strong></td>
            <td width="46%" align="center"><strong>Actions</strong></td>
          </tr>
		  <?php
		  $bgcolor = ''; 
		  while($row = mysql_fetch_array($aResult, MYSQL_ASSOC))
		  {
		  	if($bgcolor == "#FBFBFB")
				$bgcolor = "#F2F2F2";
			else
				$bgcolor = "#FBFBFB";
		  ?>
          <tr style="border-bottom:1px solid #333333;">
            <td bgcolor="<?php echo $bgcolor;?>">&raquo;</td>
            <td bgcolor="<?php echo $bgcolor;?>">
				<a href="<?php echo $row['upload_location'].'/'.$row['upload_name']; ?>"> <img src="img/pdf.png" width="20" height="24" /> <?php echo $row['upload_name']; ?> </a>			</td>
            <td bgcolor="<?php echo $bgcolor;?>">
			 
			
			<img src="img/b_drop.png" />
			<a href="javascript:delArticle('<?php echo $row['id'];?>', '<?php echo $row['upload_name'];?>', '<?php echo $_GET['detailID']; ?>');">Delete</a>&nbsp;
				
					<a href="<?php $_SERVER['PHP_SELF']; ?>?activate=false&activate_id=<?php echo $row['id'];?>"></a></td>
          </tr>

		  <?php
		  }
		  ?>
          <tr style="border-bottom:1px solid #333333;">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr style="border-bottom:1px solid #333333;">
            <td>&nbsp;</td>
            <td><strong>Journal Cover</strong> </td>
            <td>&nbsp;</td>
          </tr>
          <tr style="border-bottom:1px solid #333333;">
            <td>&nbsp;</td>
            <td>  
				<img src="<?php echo $jRow['upload_dir'].'/'.$jRow['coverpage_location']; ?>" width="90" height="110"/>
			</td>
            <td>&nbsp;</td>
          </tr>		  
        </table></td>
      <td width="1%" bordercolor="#F2F2F2">&nbsp;</td>
      <td width="1%">&nbsp;</td>
    </tr>
  </table>
</form>
 <a href="journal_add.php">+Add New Journal</a></td>
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
