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

if(isset($_GET['del']))
{

	$aResult		= EJHSJournalArticle::queryOneById($_GET['del']);
	$aRow			= mysql_fetch_array($aResult, MYSQL_ASSOC);	
	
	$jResult		= EJHSJournal::queryOneById($aRow['journal_id']);
	$jRow			= mysql_fetch_array($jResult, MYSQL_ASSOC);		

	if(EJHSJournalArticle::deleteById( $_GET['del'] ))	
	{	
				
		$_SESSION['notice'] = 'File Deletion Was Successful.' ;
		header('location:journal_article_list.php?artID='.$jRow['id'].'&vol='.$jRow['volume'].'&num='.$jRow['num'].'&year='.$jRow['year'].'');
		exit();
	}
	else
	{
		$_SESSION['error']	= "File Deletion Was Not Sccuessful.";
		header('Location: ' . $_SERVER['PHP_SELF'].'?detailID='.$_GET['detailID'].'&her2e=here');
		exit(); 		
	}
}

if( isset($_GET['detailID']) && EJHSJournalArticle::checkIfExists($_GET['detailID']))
{
	## Get Journal By ID
	$detailID		= $_GET['detailID'];
	
	
	
	$aResult		= EJHSJournalArticle::queryOneById($detailID);
	$aRow			= mysql_fetch_array($aResult, MYSQL_ASSOC);	
	
	$jResult		= EJHSJournalArticle::queryOneById($aRow['journal_id']);
	$jRow			= mysql_fetch_array($jResult, MYSQL_ASSOC);	
}
else
{
	## redirect to Journal Edit
	$_SESSION['notice1'] = "Requested Journal Article Information Unavailable.";
	header('location: journal_article_edit.php');
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
        window.location.href = 'journal_article_detail.php?del=' + id + '&detailID=' + detailID;
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
            <td height="415">&nbsp;</td>
            <td valign="top">
			
			<?php include 'left-link.html'; ?>
			
			</td>
            <td valign="top">
				<table width="100%" border="0" cellspacing="0">
				
					<?php if(isset($_SESSION['notice'])): ?> 
					  	<tr bgcolor="#B7FDAA">
							<td>  
							
								<?php echo $_SESSION['notice']; ?> 
								<?php unset($_SESSION['notice']); ?>							</td>
				  		</tr>
					<?php endif; ?>	
					<?php if(isset($_SESSION['error'])): ?> 
					  <tr bgcolor="#FFA4A6">
						<td> 
							<?php echo $_SESSION['error']; ?> 
							<?php unset($_SESSION['error']); ?>						</td>

					<?php endif; ?>	
				
			  </table>
<h3> &nbsp; &nbsp; <?php echo  $aRow['title']; ?> </h3>

<p>
<a href="journal_update.php">&nbsp;<img src="img/b_edit.png" alt="edit" width="16" height="16" border="0" /></a> <a href="journal_article_update.php?jarticleedit=<?php echo $jRow['id']; ?>">Edit Article Information </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="journal_add.php"></a>

<a href="journal_update.php">&nbsp;<img src="img/b_edit.png" alt="edit" width="16" height="16" border="0" /></a><a href="journal_article_upload_pdf.php?updateID=<?php echo $jRow['id']; ?>"> Upload Article PDF </a>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
<img src="img/b_drop.png" />
			<a href="javascript:delArticle('<?php echo $aRow['id'];?>', '<?php echo $aRow['upload_name'];?>', '<?php echo $_GET['detailID']; ?>');">Delete</a></p>
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <table width="100%" border="0" cellspacing="0">
    <tr>
      <td width="1%" bordercolor="#F2F2F2">&nbsp;</td>
      <td width="90%" bordercolor="#F2F2F2">
        <table width="97%" border="0" cellspacing="0">
          <tr>
            <td width="1%">&nbsp;</td>
            <td width="17%" valign="top"><strong> Title </strong></td>
            <td width="82%" align="left"> <?php echo $aRow['title']; ?> </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td valign="top"><strong>Authors</strong></td>
            <td align="left"> <?php echo $aRow['authors']; ?> </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td valign="top"><strong>Keywords</strong></td>
            <td align="left"> <?php echo $aRow['keyword']; ?> </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td valign="top"><strong>Category</strong></td>
            <td align="left"> <?php echo $aRow['category']; ?> </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td valign="top"><strong>Page Numbers </strong></td>
            <td align="left"> <?php echo $aRow['start_page_number']; ?> - <?php echo $aRow['end_page_number']; ?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td valign="top"><strong>Uploaded File </strong></td>
            <td align="left" valign="middle">
				<img src="img/PDF-logo.jpg" width="24" height="24" /> 
				<a href="<?php echo $aRow['upload_location'].'/'.$aRow['upload_name']; ?>" > <?php echo $aRow['upload_name']; ?> </a>			</td>
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

		  <?php
		  }
		  ?>		  
        </table></td>
      <td width="1%" bordercolor="#F2F2F2">&nbsp;</td>
      <td width="1%">&nbsp;</td>
    </tr>
  </table>
</form>
 
<p> &nbsp;</p>
<a href="journal_update.php">&nbsp;<img src="img/b_edit.png" alt="edit" width="16" height="16" border="0" /></a> 
<a href="journal_article_update.php?jarticleedit=<?php echo $jRow['id']; ?>">Edit Article Information </a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="journal_add.php"></a> 
<a href="journal_update.php">&nbsp;<img src="img/b_edit.png" alt="edit" width="16" height="16" border="0" /></a>
<a href="journal_article_upload_pdf.php?updateID=<?php echo $jRow['id']; ?>"> Upload Article PDF </a> 
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
<img src="img/b_drop.png" alt="edit" /> 
<a href="javascript:delArticle('<?php echo $aRow['id'];?>', '<?php echo $aRow['upload_name'];?>', '<?php echo $_GET['detailID']; ?>');">Delete</a>

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
