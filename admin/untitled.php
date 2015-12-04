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
include 'library/config.php';
include 'library/opendb.php';
if(isset($_GET['jarticleedit'])){
	$jarticleedit = $_GET['jarticleedit'];
	$result = mysql_query("SELECT * FROM journal_article where id='$jarticleedit'");
	if(!$result){
		echo "Error on DB".mysql_error();
	}
	else{
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$article_id = $row['id'];
		$upload_name = $row['upload_name'];
		$upload_dir = $row['upload_location'];
		$journal_id =$row['journal_id'];
		$title = $row['title'];
		$authors = $row['authors'];
		$category 	 = $row['category'];
	}
}

if(isset($_POST['upload_journal']))
{
	$errmsgCover = '';
	$msg = '';
	
	$errmsg = '';
	$title = '';
	$authors = '';
	$category = '';
	$upload_dir = '';
	$upload_name = '';
	
	$hiddenData = '';
	$change_vol = '';
	$change_num  = '';
	$change_month =  '';
	
	
	$hidden_month = '';
	$hidden_vol = '';
	$hidden_num = '';
	$reupload_the_same_file = false;
	
	$hidden_journal_id = '';
	$hiddenData = $_POST['hiddenData'];
	
	// Get Data		
	$coverpage_location = $_FILES['cover']['name'];
	$date = date('d-m-Y');
	$year = date('Y');	

	$title = $_POST['title'];
	$authors = $_POST['authors'];
	$category = $_POST['category'];	
	$hidden_num = $_POST['hidden_num'];
	$hidden_vol = $_POST['hidden_vol'];
	$hidden_month = $_POST['hidden_month'];	
	$hidden_journal_id = $_POST['hidden_journal_id'];
	$upload_name = $_POST['hidden_upload_name'];
	$upload_dir = $_POST['hidden_upload_dir'];
	$jarticleedit = $_POST['jarticleedit'];
	
// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	if($hiddenData != '')
	{
		$change_num = $_POST['change_num'];
		$change_vol = $_POST['change_vol'];
		$change_month = $_POST['change_month'];
		
		if($change_vol == 'null')
		{
			$errmsg .= "Volume edition should be selected <br />";
		}
		if($change_num == 'null')
		{
			$errmsg .= "Number of the Edition should be selected <br />";
		}
		if($change_month == 'null')
		{
			$errmsg .= "Month Edition should be selected <br />";
		}
		
	}
// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@		
	if($title == '')
	{
		$errmsg .= "Title should be provided <br />";
	}
	if($authors == '')
	{
		$errmsg .= "Authors should be provided <br />";
	}

	if($category == 'null')
	{
		$errmsg .= "Category for the journal article should be provided <br />";
	}	
	if($_FILES["cover"]["error"] > 0)
	{
		$info .=  "Article hasn't been updated";
	}	
	else{
		$upload_name = $_FILES["cover"]["name"];
		if(($_FILES["cover"]["type"] == "application/pdf") ){
			if($_FILES["cover"]["error"] > 0){
				$errmsgCover = " Error while uploading your file (Article) ".$_FILES["cover"]["error"]. "  <br />";
			}
			else{
				$errmsgCover = "";
				if (file_exists($upload_dir . $_FILES["cover"]["name"]))
				{
					$info .= $_FILES["cover"]["name"] . " already exists. <br />";
					
				}
			}
		}
		else {
			$errmsgCover = "Only PDF files are allowed !!! <br />";
		}		
	}
	
if(($errmsg == "") and ($errmsgCover == '') and ($hiddenData == ''))
{
	//store both				
	$result = mysql_query("
		UPDATE journal_article 
		SET journal_id = '$hidden_journal_id',
		title = '$title', 
		authors = '$authors',
		upload_name = '$upload_name', 
		upload_location = '$upload_dir', 
		category = '$category', 
		updated_at = '$date' 
		WHERE id = '$jarticleedit'" 
		);
	if($result){
		$msg .= "Journal article was saved to the Database <br />";
		move_uploaded_file($_FILES["cover"]["tmp_name"],  $upload_dir . $_FILES["cover"]["name"]);	
		$msg .= "upload was successful!<br />";					
	}
	else{
		$errmsg .=  "There was error on Database ".mysql_error()."<br />";
	}
}
elseif(($errmsg == '') and ($errmsgCover == '') and ($hiddenData !== ''))
{
	//store both				
	$result =mysql_query("select * from journal where num='$change_num' and volume = '$change_vol'") or die(mysql_error());
	$row = mysql_fetch_array($result, MYSQL_ASSOC);	  
	$journal_id = $row['id'];
	
	if(mysql_num_rows($result) > 0){
		$result = mysql_query("
		UPDATE journal_article 
		SET journal_id = '$journal_id',
		title = '$title', 
		authors = '$authors',
		upload_name = '$upload_name', 
		upload_location = '$upload_dir', 
		category = '$category', 
		updated_at = '$date' 
		WHERE id = '$jarticleedit'"
		);
		if($result){
			$msg .= "Journal Article Information was successfuly saved into the Database";
			move_uploaded_file($_FILES["cover"]["tmp_name"],  $upload_dir . $_FILES["cover"]["name"]);
			$msg .= "upload was successful!<br />";
							
		}
		else{
			$errmsg .= "There was error on Database ".mysql_error()."<br />";
		}
	}
	else{
		$errmsg .=  "The journal Volume ".$change_vol. " Number ".$change_num." is not available.<br /> Please select journal that is available or previously saved in the DATABASE!";
	}
}	
else {
	$errmsg .=  "couldn't perform anything!<br />";
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
            <td valign="top"><table width="100%" border="0" cellspacing="0">
              <tr>
                <td bgcolor="#F3F3F3">&nbsp;</td>
                <td bgcolor="#F3F3F3">&nbsp;</td>
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
<?php
if($errmsgCover!= '' or $errmsg != '')
{
?>
  <tr>
    <td colspan="3">
	<p class="error">
	<?php 
		echo $errmsgCover ."<br /> ";
		echo $errmsg ."<br /> ";
		$errmsgCover = '';
		$errmsg = '';
	?>
	</p>
	
	</td>
  </tr>
<?php
}
?>
<?php
if($msg != '' and ($errmsgCover== '' or $errmsg == ''))
{
?>
  <tr>
    <td colspan="3">
	<p class="success"> <strong >
	<?php 
		echo $msg ."<br /> ";
		echo $info."<br />";
		$msg = "";
		$info = '';
	?>
	</strong></p>
	
	</td>
  </tr>
<?php
}
?>
  <tr>
    <td width="13%">&nbsp;</td>
    <td width="79%">
	  <div id="readroot" style="display:none; background:#FFF8F4;">
				
					<input type="button" value="Remove"
						onclick="this.parentNode.parentNode.removeChild(this.parentNode);" />
					
		<table width="100%" border="0" cellspacing="0">

                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td width="2%">&nbsp;</td>
                        <td width="14%"> Journal Volume </td>
                        <td width="81%">
						<input type="hidden" value="3" name="hiddenData" id="hiddenData"/>
						<select name="change_month" id="change_month">
                            <option value="null">Select Month of Edition</option>
                            <option value="3">March</option>
                            <option value="7">July</option>
                            <option value="11">November</option>
                          </select>
                            <select name="change_vol" id="change_vol">
                              <option value="null">Select Journal Volume</option>
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                              <option value="7">7</option>
                              <option value="8">8</option>
                              <option value="9">9</option>
                              <option value="10">10</option>
                              <option value="11">11</option>
                              <option value="12">12</option>
                              <option value="13">13</option>
                              <option value="14">14</option>
                              <option value="15">15</option>
                              <option value="16">16</option>
                              <option value="17">17</option>
                              <option value="18">18</option>
                              <option value="19">19</option>
                              <option value="20">20</option>
                              <option value="21">21</option>
                              <option value="22">22</option>
                              <option value="23">23</option>
                              <option value="24">24</option>
                              <option value="25">25</option>
                              <option value="26">26</option>
                              <option value="27">27</option>
                              <option value="28">28</option>
                              <option value="29">29</option>
                              <option value="30">30</option>
                            </select>
                            <select name="change_num" id="change_num">
                              <option value="null">Select Journal Number</option>
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                            </select>
                            <label></label></td>
                        <td width="3%">&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
        </table>
	  </div>	</td>
    <td width="8%">&nbsp;</td>
  </tr>
</table>

<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <table width="100%" border="0" cellspacing="0">
    <tr>
      <td>&nbsp;</td>
      <td bordercolor="#F2F2F2" bgcolor="#F2F2F2">&nbsp;</td>
      <td bordercolor="#F2F2F2" bgcolor="#F2F2F2">
	  <?php
	  $result = mysql_query("select * from journal WHERE id = '$journal_id'") or die(mysql_error());
	  
	  if(mysql_num_rows($result) > 0)
	  {	  
		$row = mysql_fetch_array($result, MYSQL_ASSOC);	  
		$month = $row['month'];
		$nextYear = date('Y');
		$nextvolume = $row['volume'];
		$nextNum = $row['num'];
		$nextMonthNum = $row['month'];
		  
		if($month == 3){
			$nextMonth = "March";
		}
		elseif($month == 7){
			$nextMonth ="July";
		}
		else {
			$nextMonth = "November";
		}

	  }
		else{
			echo "This journal is not available";
		}
		
	 

	  ?>
	  <table width="372" border="0" cellspacing="0">
          <tr>
            <td><strong>You are uploading Journal: </strong></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="145"><strong><?php echo "Volume ".$nextvolume. " Number ".$nextNum; ?> </strong></td>
            <td width="152">&nbsp;</td>
          </tr>
          <tr>
            <td><strong> <?php echo $nextMonth. " ".$nextYear; ?> </strong></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><a href="javascript:;" onclick="moreFields();"><strong>C H A N G E </strong></a></td>
            <td>&nbsp;</td>
          </tr>
        </table>
		
	  <span id="writeroot"></span>   	</td>
      <td bordercolor="#F2F2F2" bgcolor="#F2F2F2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td bordercolor="#F2F2F2" bgcolor="#F2F2F2">&nbsp;</td>
      <td bordercolor="#F2F2F2" bgcolor="#F2F2F2"><hr width="500" /></td>
      <td bordercolor="#F2F2F2" bgcolor="#F2F2F2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="13%">&nbsp;</td>
      <td width="1%" bgcolor="#F2F2F2">&nbsp;</td>
      <td width="76%" bgcolor="#F2F2F2"><table width="100%" border="0" cellspacing="0">
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="right" bgcolor="#F9F9F9">Title</td>
          <td bgcolor="#F9F9F9">&nbsp;</td>
          <td bgcolor="#F9F9F9"><input name="title" type="text" id="title" size="40" value="<?=$title;?>"/></td>
        </tr>
        <tr>
          <td align="right">Authors</td>
          <td>&nbsp;</td>
          <td><label>
            <input name="authors" type="text" id="authors" size="40" value="<?=$authors;?>" />
          </label></td>
        </tr>
        <tr>
          <td align="right" bgcolor="#F9F9F9">Category of the article </td>
          <td bgcolor="#F9F9F9">&nbsp;</td>
          <td bgcolor="#F9F9F9"><select name="category" id="category">
            <option value="null">Select journal category</option>
            <option value="editorial">Editorial</option>
            <option value="original">Original Articles</option>
            <option value="brief">Brief Communications</option>
            <option value="case">Case Report</option>
          </select></td>
        </tr>
        <tr>
          <td width="38%" align="right">Journal cover page (PDF only) </td>
          <td width="2%">&nbsp;</td>
          <td width="60%"><label>
            <input name="cover" type="file" id="cover" />
          </label></td>
        </tr>
        <tr>
          <td bgcolor="#F9F9F9"><label></label></td>
          <td bgcolor="#F9F9F9">&nbsp;</td>
          <td bgcolor="#F9F9F9">
		  	<input type="hidden" id="hidden_vol" name="hidden_vol" value="<?=$nextvolume;?>"/> 
			<input type="hidden" id="hidden_num" name="hidden_num" value="<?=$nextNum;?>"/> 
			<input type="hidden" id="hidden_month" name="hidden_month" value="<?=$nextMonthNum;?>"/>		  
			<input type="hidden" id="hidden_journal_id" name="hidden_journal_id" value="<?=$journal_id;?>"/>
			<input type="hidden" id="hidden_upload_name" name="hidden_upload_name" value="<?=$upload_name;?>"/>
			<input type="hidden" id="hidden_upload_dir" name="hidden_upload_dir" value="<?=$upload_dir;?>"/>
			<input type="hidden" id="jarticleedit" name="jarticleedit" value="<?=$jarticleedit;?>"/>
			<p class="attachment"> 
			<em><strong>Attachments</strong></em><br />
			<a href="<?php echo $upload_dir."/".$upload_name;?>"> <?php echo $upload_name;?></a>
			</p>
			</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><label>
            <input name="upload_journal" type="submit" id="upload_journal" value="U p l o a d " />
          </label></td>
        </tr>
      </table></td>
      <td width="2%" bgcolor="#F2F2F2">&nbsp;</td>
      <td width="8%">&nbsp;</td>
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
