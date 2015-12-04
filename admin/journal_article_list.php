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
include 'EJHSJournalArticleCategory.class.php';

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
        window.location.href = 'journal_article_list.php?del=' + id;
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
<?php if(isset($_SESSION['notice'])): ?> 
  <tr bgcolor="#B7FDAA">
    <td> 
		<?php echo $_SESSION['notice']; ?> 
		<?php unset($_SESSION['notice']); ?> 
	</td>
    </tr>
<?php endif; ?>	
<?php if(isset($_SESSION['error'])): ?> 
  <tr bgcolor="#B7FDAA">
    <td bgcolor="#FFA4A6"> 
		<?php echo $_SESSION['error']; ?> 
		<?php unset($_SESSION['error']); ?>	</td>
    </tr>
<?php endif; ?>	
</table>

<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
	  <?php 
	  $bgcolor = null; 
	  	$artID = $_GET['artID'];
	$journalId	= $_GET['artID'];  ## artId is to refer to journal talble ID
	// FETCH JOURNAL INFORMATION FOR LATTER INDEXING
	$query		= "SELECT * FROM journal WHERE id = '$journalId' ";
	$jResult	= mysql_query($query); 
	$jRow		= mysql_fetch_array($jResult, MYSQL_ASSOC); 
	
	$year 		= $jRow['year']; 
	$volume 	= $jRow['volume']; 
	$number	 	= $jRow['num']; 
	$month	 	= $jRow['month'];		
	  ?>
	  <h2> Volume <?php echo $volume;?>, Number <?php echo $number;?> List, <?php echo $year;?>  </h2>
	  <a href="journal_article_add_under_selected.php?artID=<?php echo $artID; ?> ">+ Add New Article      </a>
	  <table width="100%" border="0" cellspacing="0">
    <tr>
      <td>&nbsp;</td>
	  
      <td bordercolor="#F2F2F2" bgcolor="#F2F2F2">
	  

 	  <table width="100%" border="0" cellspacing="0">

            <td width="1%">&nbsp;</td>
            <td width="51%">&nbsp;</td>
            <td width="15%"><strong>Page Numbers</strong> </td>
            <td width="33%"><strong>Actions</strong></td>
          </tr>
		  <?php
		  	$result = mysql_query("SELECT * FROM journal_article WHERE journal_id = $artID ORDER BY start_page_number ASC");
			if(!$result){
			echo "Error on DB".mysql_error();
			}
		  $bgcolor			= '';
		  $curCategory		= '';
		  $prevCategory		= '';
		  $categoryName		= ''; 
		  while($row = mysql_fetch_array($result, MYSQL_ASSOC))
		  {
		  	if($bgcolor == "#FBFBFB")
			{
				$bgcolor = "#F2F2F2";
			}
			else
			{
				$bgcolor = "#FBFBFB";
			}
			$curCategory = $row['category'];			
			if($curCategory != $prevCategory){
			?>			
			<tr style="border-bottom:1px solid #333333;" bgcolor="#FFF">
			<td bgcolor="#FFF" colspan="4">
				<?php $categoryName	= EJHSJournalArticleCategory::queryOne($curCategory); ?>
				<?php $prevCategory	= $curCategory; ?>
				<strong> <?php echo $categoryName; ?> </strong>			</td>
		  	</tr>	
			<?php		
			}
		  	?>

          <tr style="border-bottom:1px solid #333333;" bgcolor="<?php echo $bgcolor;?>">
            <td valign="top" bgcolor="<?php echo $bgcolor;?>">&raquo;</td>
            <td bgcolor="<?php echo $bgcolor;?>">
			<?php $title = $row['title']; ?>
			<?php echo $title; ?> <br />		
			<em> <?php echo $row['authors']; ?>	</em></td> 
            <td align="center" bgcolor="<?php echo $bgcolor;?>">
				<?php 
					$start_page_number = $row['start_page_number']; 
					$end_page_number = $row['end_page_number']; 
				echo $start_page_number. " - " . $end_page_number;
				?>			</td>
            <td bgcolor="<?php echo $bgcolor;?>">
			<a href="journal_article_update.php">&nbsp;<img src="img/b_edit.png" width="16" height="16" border="0" /></a>
			<a href="journal_article_update.php?jarticleedit=<?php echo $row['id']; ?>">Edit</a> 
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<img src="img/b_drop.png" />&nbsp;
			<a href="javascript:delArticle('<?php echo $row['id'];?>', '<?php echo $title;?>');">Delete</a> &nbsp;&nbsp;
			<img src="img/list.jpg" width="16" height="18" />
			<a href="journal_article_detail.php?detailID=<?php echo $row['id']; ?>">View More</a>			</td>
          </tr>
		  <?php
		  }
		  ?>
        </table>	</td>
      </tr>
    <tr>
      <td width="4%">&nbsp;</td>
      <td width="94%" bgcolor="#F2F2F2"><table width="100%" border="0" cellspacing="0">

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
