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

//fetch queryd
if(isset($_GET['del']))
{
    // remove the article from the database
	$id_to_delete = $_GET['del'];
	$delete = mysql_query("SELECT * FROM journal WHERE id = '$id_to_delete'");
	$file_dir = $row['upload_dir'];
	$file =  $row['coverpage_location'];
	exec("rmdir ".${file_dir}."/".${file});
	$msg =  $file_dir."/".$file;
    $query = "DELETE FROM journal WHERE id = '{$_GET['del']}'";
    mysql_query($query) or die('Error : ' . mysql_error());
        
    // redirect to current page so when the user refresh this page
    // after deleting an article we won't go back to this code block
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
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

if( isset($_GET['filter_journal']) && ($_GET['year'] != '' || $_GET['num'] != '' || $_GET['vol'] != '' ) )
{
	$vol		= $_GET['vol'];
	$num		= $_GET['num'];
	$year		= $_GET['year'];	
	
	
	$query		= "SELECT * FROM journal where 1 ";		
	
	if($_GET['year'] != '' )
		$query	.= " AND year = ".$year;
	if($_GET['vol'] != '' )
		$query	.= " AND volume = ".$vol;	
	if($_GET['num'] != '' )
		$query	.= " AND num = ".$num;
	
	$result = mysql_query($query);
	if(!$result){
		echo "Error on DB".mysql_error();
	}
}
else
{
	$result = mysql_query("SELECT * FROM journal ORDER BY volume DESC, num DESC");
	if(!$result){
		echo "Error on DB".mysql_error();
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
        window.location.href = 'journal_edit.php?del=' + id;
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
								<?php unset($_SESSION['notice1']); ?>						
							</td>
				  		</tr>
					<?php endif; ?>	
					<?php if(isset($_SESSION['error'])): ?> 
					  <tr bgcolor="#FFA4A6">
						<td> 
							<?php echo $_SESSION['error']; ?> 
							<?php unset($_SESSION['error']); ?>						</td>
				  </tr>
					<?php endif; ?>	
			  </table>
<h3> &nbsp; &nbsp; Manage Journals </h3>

<form action="" method="get">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>&nbsp;</td>
        <td colspan="2">Filter Journal With One or All Fields </td>
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

<p><a href="journal_add.php">+Add New Journal</a> </p>
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <table width="100%" border="0" cellspacing="0">
    <tr>
      <td width="1%" bordercolor="#F2F2F2">&nbsp;</td>
      <td width="90%" bordercolor="#F2F2F2">
        <table width="97%" border="0" cellspacing="0">
          <tr>
            <td width="1%">&nbsp;</td>
            <td width="37%"><strong>Journal Volume </strong></td>
            <td width="62%" align="center"><strong>Actions</strong></td>
          </tr>
		  <?php
		  $bgcolor = ''; 
		  while($row = mysql_fetch_array($result, MYSQL_ASSOC))
		  {
		  	if($bgcolor == "#FBFBFB")
				$bgcolor = "#F2F2F2";
			else
				$bgcolor = "#FBFBFB";
		  ?>
          <tr style="border-bottom:1px solid #333333;">
            <td bgcolor="<?php echo $bgcolor;?>">&raquo;</td>
            <td bgcolor="<?php echo $bgcolor;?>">
				<?php $volume = "Volume - ".$row['volume']." Number ".$row['num']; echo $volume;?>, <?php echo $row['year']; ?>
			</td>
            <td bgcolor="<?php echo $bgcolor;?>">
			<a href="journal_update.php">&nbsp;<img src="img/b_edit.png" width="16" height="16" border="0" /></a>
			<a href="journal_update.php?updateID=<?php echo $row['id']; ?>">Edit</a> 
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<img src="img/b_drop.png" />
			<a href="javascript:delArticle('<?php echo $row['id'];?>', '<?php echo $volume;?>');">Delete</a>&nbsp;
				<?php 
				if($row['is_activated'] != ''){
				?>
					<a href="<?php $_SERVER['PHP_SELF']; ?>?activate=false&activate_id=<?php echo $row['id'];?>">
					<img src="img/EnableIcon.jpg" width="24" height="27" border="0" />Activated</a>
				<?php
				}
				else{
					?>
					<a href="<?php $_SERVER['PHP_SELF']; ?>?activate=true&activate_id=<?php echo $row['id'];?>">
					<img src="img/DisableIcon.jpg" width="24" height="27" border="0" />Disactivated</a>				<?php
				}
				?>			
				<a href="journal_detail.php?detailID=<?php echo $row['id']; ?>"><img src="img/list.jpg" alt="list" width="19" height="19" />View Detail</a>				</td>
          </tr>
		  <?php
		  }
		  ?>
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
