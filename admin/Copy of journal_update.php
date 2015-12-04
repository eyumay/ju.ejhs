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

$month 			= ''; 
$errmsg			= '';
$errmsgCover	= ''; 
$msg			= '';
$upload_dir		= ''; 
if(isset($_GET['jedit'])){
	$jedit = $_GET['jedit'];
	$result = mysql_query("SELECT * FROM journal where id='$jedit'");
	if(!$result){
		echo "Error on DB".mysql_error();
	}
	else{
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$jID = $row['id'];
		$jupload_location = $row['coverpage_location'];
		$jupload_dir = $row['upload_dir'];
		$jvolume = $row['volume'];
		$jmonth = $row['month'];
		$jnum = $row['num'];
		$jyear = $row['year'];
		$jissn = $row['issn'];
		$jeissn = $row['eissn'];
	}
}
if(isset($_POST['upload_journal']))
{
	$errmsgCover = '';
	$msg = '';
	$hddenData = '';
	$errmsg = '';
	$reupload_the_same_file = false;

	// Get Data
	$jid = $_POST['journl_id'];
	$jupload_location = $_POST['jupload_location'];
	$jupload_dir = $_POST['jupload_dir'];
	$vol = $_POST['vol'];
	$month = $_POST['month'];
	$num = $_POST['num'];
	$year = date('Y');	
	$issn = $_POST['issn'];
	$eissn = $_POST['eissn'];
	$coverpage_location = $upload_dir.$_FILES['cover']['name'];
	$date = date('d-m-Y');
	$hddenData = $_POST['hddenData'];
	
	if(file_exists("Volume-".$vol."-Num".$num))
	{
		$upload_dir = "Volume-".$vol."-Num".$num;
	}
	else
	{
		$create_dir="Volume-".$vol."-Num".$num;
		exec("mkdir ".${create_dir});
		$upload_dir = $create_dir;
	}
// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	if($hddenData != '')
	{
		if($vol == 'null')
		{
			$errmsg .= "Volume edition should be selected <br />";
		}
		if($num == 'null')
		{
			$errmsg .= "Number of the Edition should be selected <br />";
		}
		if($month == 'null')
		{
			$errmsg .= "Month of Edition should be selected <br />";
		}
		if($issn == '')
		{			
			$errmsg .= " ISSN Field should be entered. <br />";
		}			
		/*$isAvailable = mysql_query("SELECT * from journal WHERE volume = '$vol' AND num = '$num'");
		if(mysql_num_rows($isAvailable) > 0)
		{
			$errmsg .= "This journal already exists in the DATABASE";
		}*/		
		
	}
// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@	
	
// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	if($_FILES["cover"]["error"] > 0)
	{
		$info = "cover page has not been updated";
		$reupload_the_same_file = true;
	}
	else {
		if(($_FILES["cover"]["type"] == "image/jpeg") || ($_FILES["cover"]["type"] == "image/gif")){
			if($_FILES["cover"]["error"] > 0){
				$errmsgCover = " Error while uploading your file (Article) ".$_FILES["cover"]["error"]. "  <br />";
			}
			else{
				$errmsgCover = "";
				$msg = "upload was successful!";
				if (file_exists($upload_dir. $_FILES["cover"]["name"]))
				{
					$info = $_FILES["cover"]["name"] . " already exists. ";
					
				}
			}
		}
		else {
			$errmsgCover = "Only GIF / JPEG files are allowed !!!";
		}		
	}
	
if(($errmsg == "") and ($errmsgCover == ''))
{
	//store bothe
	if($reupload_the_same_file){
		$coverpage_location = $jupload_location;
	}
	$result = mysql_query("UPDATE journal SET volume = '$vol', num='$num', month='$month', issn='$issn', eissn='$eissn', upload_dir = '$jupload_dir', coverpage_location='$coverpage_location', updated_at='$date' where id = '$jid'");
	if($result){
		$msg .=  "successful Query";
			move_uploaded_file($_FILES["cover"]["tmp_name"],  $upload_dir ."/". $_FILES["cover"]["name"]);
			$msg .= "upload was successful!<br />";				
	}
	else{
		$errmsg .=  "There was error on Database ".mysql_error();
	}
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
if($errmsgCover != '' or $errmsg != '')
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
	<p class="success">
	<?php 
		echo $msg ."<br /> ";
		echo $info ."<br /> ";		
		$msg = "";
		$info = "";
	?>
	</p>
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
                        <td width="14%"> *Journal Volume </td>
                        <td width="81%">
						<input type="hidden" value="3" name="hddenData" id="hddenData"/>
						<select name="month" id="month">
                            <option value="null">Select Month of Edition</option>
							<option value="1">January</option>
							<option value="2">February</option>
                            <option value="3">March</option>
							<option value="4">April</option>
							<option value="5">May</option>
							<option value="6">June</option>
                            <option value="7">July</option>
							<option value="8">August</option>
							<option value="9">September</option>
							<option value="10">October</option>
                            <option value="11">November</option>
							<option value="12">December</option>
                          </select>
                            <select name="vol" id="vol">
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
                            <select name="num" id="num">
                              <option value="null">Select Journal Number</option>
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
							  <option value="4">4</option>
							  <option value="5">Special Edition</option>
                            </select>
                            <label></label></td>
                        <td width="3%">&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>*ISSN No: </td>
                        <td><label>
						<input type="text" id="issn" name="issn" />
                          </label></td>
                        <td>&nbsp;</td>
                      </tr>		
                      <tr>
                        <td>&nbsp;</td>
                        <td>eISSN No: </td>
                        <td><label>
						<input type="text" id="eissn" name="eissn" />
                          </label></td>
                        <td>&nbsp;</td>
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
	  <table width="372" border="0" cellspacing="0">
          <tr>
            <td><strong> You are updating Journal: </strong></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="145"><strong><?php echo "ISSN: ".$jissn; ?> </strong></td>
            <td width="152"> <strong> &nbsp; </strong> </td>
          </tr>		  
          <tr>
            <td width="145"><strong><?php echo "eISSN: ".$jeissn; ?> </strong></td>
            <td width="152"> <strong> &nbsp; </strong> </td>
          </tr>			  
          <tr>
            <td width="145"><strong>
			<?php echo "Volume ".$jvolume. " Number ".$jnum; 
				if($month == 3){
					$nextMonth = "March";
				}
				elseif($month == 7){
					$nextMonth ="July";
				}
				else {
					$nextMonth = "November";
				}
			
			?> </strong></td>
            <td width="152">&nbsp;</td>
          </tr>
          <tr>
            <td><strong> <?php echo $nextMonth. " ".$jyear; ?> </strong></td>
            <td>&nbsp;
			<input type="hidden" id="vol" name="vol" value="<?=$jvolume;?>"/> 
			<input type="hidden" id="num" name="num" value="<?=$jnum;?>"/> 
			<input type="hidden" id="month" name="month" value="<?=$jmonth;?>"/>
			<input type="hidden" id="year" name="year" value="<?=$jyear;?>"/>
			<input type="hidden" id="journl_id" name="journl_id" value="<?=$jID;?>" />
			<input type="hidden" id="jupload_location" name="jupload_location" value="<?=$jupload_location;?>"/>			
			<input type="hidden" id="jupload_dir" name="jupload_dir" value="<?=$jupload_dir;?>"/>
						 
</td>
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
          <td width="46%">Journal cover page </td>
          <td width="2%">&nbsp;</td>
          <td width="52%"><label>
            <input name="cover" type="file" id="cover" /><br />
            <a href="<?php echo $jupload_dir ."/".$jupload_location; ?>"> 
			<input type="hidden" id="upload_location" name="upload_location" value="<?=$jupload_location;?>"/>
			<?php echo $jupload_location; ?>
			 </a>
          </label></td>
        </tr>
        <tr>
          <td><label></label></td>
          <td>&nbsp;</td>
          <td bgcolor="#F2F2F2">
		  </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><label>
			<input type="hidden" id="id" name="id" value="<?=$nextMonthNum;?>"/>
			<input name="upload_journal" type="submit" id="upload_journal" value="U p d a t e" />
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
