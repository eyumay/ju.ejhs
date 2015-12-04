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

	$errmsgCover = '';
	$msg = '';
	$hddenData = '';
	$errmsg = '';

if(isset($_POST['upload_journal']))
{
	$errmsgCover = '';
	$msg = '';
	$hddenData = '';
	$errmsg = '';
	// Get Data
	
	$num				= $_POST['num'];
	$vol				= $_POST['vol'];
	$month				= $_POST['month'];
	$year_of_edition 	= $_POST['year'];
	$issn				= $_POST['issn'];	
	$eissn				= $_POST['eissn'];
	
	if(file_exists("Volume-".$vol."-Num".$num))
	{
		$upload_dir = "Volume-".$vol."-Num".$num;
	}
	else
	{
		$create_dir="Volume-".$vol."-Num".$num;
		mkdir($create_dir);
		$upload_dir = $create_dir;
	}
	
	$coverpage_location = $_FILES['cover']['name'];
	$date = date('d-m-Y');
	$year = date('Y');

// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	
	if(isset($_POST['hddenData']))
	{
		$hddenData = $_POST['hddenData'];
		$year = $year_of_edition;
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
			$errmsg .= "Month Edition should be selected <br />";
		}
		if($year_of_edition == 'null')
		{			
			$errmsg .= "Year of Edition should be selected <br />";
		}
		if($issn == '')
		{			
			$errmsg .= " ISSN Field should be entered. <br />";
		}		
		$isAvailable = mysql_query("SELECT * from journal WHERE volume = '$vol' AND num = '$num'");
		if(mysql_num_rows($isAvailable) > 0)
		{
			$errmsg .= "This journal already exists in the DATABASE";
		}
		
	}
// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@	
	
// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	if($_FILES["cover"]["error"] > 0)
	{
		$errmsgCover =  "Cover page should be uploaded";
	}
	else{
		if(($_FILES["cover"]["type"] == "image/pjpg") || ($_FILES["cover"]["type"] == "image/jpeg")|| ($_FILES["cover"]["type"] == "image/gif")){
			if($_FILES["cover"]["error"] > 0){
				$errmsgCover = " Error while uploading your file (Article) ".$_FILES["cover"]["error"]. "  <br />";
			}
			else{
				$errmsgCover = "";
				if (file_exists($upload_dir. $_FILES["cover"]["name"]))
				{
					$errmsgCover = $_FILES["cover"]["name"] . " already exists. ";
					
				}
			}
		}
		else {
			$errmsgCover = "Only GIF / JPEG / PJPEG files are allowed !!! ".$_FILES["cover"]["type"] ;
		}		
	}
	
if(($errmsg == "") and ($errmsgCover == ''))
{
	//store both
	
	$result = mysql_query("
	INSERT INTO journal (volume, num, month, year, issn, eissn, upload_dir, coverpage_location, created_at, updated_at) 
	VALUES ('$vol','$num','$month','$year', '$issn', '$eissn', '$upload_dir', '$coverpage_location','$date','$date')");
					
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
if($msg != '' and ($errmsgCover == '' or $errmsg == ''))
{
?>
  <tr>
    <td colspan="3">
	<p class="success">
	<?php 
		echo $msg ."<br /> ";
		echo $errmsgCover ."<br /> ";		
		$msg = "";
		$errmsgCover = "";
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
				
					<input type="button" value="Remove"	onclick="this.parentNode.parentNode.removeChild(this.parentNode);" />
					
		<table width="100%" border="0" cellspacing="0">

                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td width="2%">&nbsp;</td>
                        <td width="14%"> *Volume </td>
                        <td width="81%">
						<input type="hidden" value="3" name="hddenData" id="hddenData"/>
						<select name="month" id="month">
                            <option value="null">Select Month</option>
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
                              <option value="null">Select Volume</option>
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
                              <option value="null">Select Number</option>
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
                        <td>*Year of edition </td>
                        <td><label>
                          <select name="year" id="year">
                            <option value="null">Select year of Edition</option>
                            <option value="1990">1990</option>
                            <option value="1991">1991</option>
                            <option value="1992">1992</option>
                            <option value="1993">1993</option>
                            <option value="1994">1994</option>
                            <option value="1995">1995</option>
                            <option value="1996">1996</option>
                            <option value="1997">1997</option>
                            <option value="1998">1998</option>
                            <option value="1999">1999</option>
                            <option value="2000">2000</option>
                            <option value="2001">2001</option>
                            <option value="2002">2002</option>
                            <option value="2003">2003</option>
                            <option value="2004">2004</option>
                            <option value="2005">2005</option>
                            <option value="2006">2006</option>
                            <option value="2007">2007</option>
                            <option value="2008">2008</option>
                            <option value="2009">2009</option>
                            <option value="2010">2010</option>
                            <option value="2011">2011</option>
                            <option value="2012">2012</option>
                            <option value="2013">2013</option>
                            <option value="2014">2014</option>
                            <option value="2015">2015</option>
                            <option value="2016">2016</option>
                            <option value="2017">2017</option>
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                          </select>
                        </label></td>
                        <td>&nbsp;</td>
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
	  include 'library/config.php';
	  include 'library/opendb.php';
	  $result		= mysql_query("SELECT * FROM journal ORDER BY volume DESC, num DESC limit 1;") or die(mysql_error());
	  $row			= mysql_fetch_array($result, MYSQL_ASSOC);	  
	  $month 		= $row['month'];
	  $nextYear 	= date('Y');
	  $nextvolume 	= $row['volume'];
	  $issn			= $row['issn'];
	  $eissn		= $row['eissn'];
	  
		if($month == 3){
			$nextMonth = "July";
			$nextMonthNum = 7;
			$nextNum = 2;
		}
		elseif($month == 7){
			$nextMonth ="November";
			$nextMonthNum = 11;
			$nextNum = 3;
		}
		else {
			$nextMonth = "March";
			$nextMonthNum = 3;
			$nextNum = 1;
			$nextvolume = $row['volume'] + 1;	
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
            <td width="145"><strong><?php echo "ISSN: ".$issn; ?> </strong></td>
            <td width="152"> <strong> &nbsp; </strong> </td>
          </tr>		  
          <tr>
            <td width="145"><strong><?php echo "eISSN: ".$eissn; ?> </strong></td>
            <td width="152"> <strong> &nbsp; </strong> </td>
          </tr>			  
          <tr>
            <td><strong> <?php echo $nextMonth. " ".$nextYear; ?> </strong></td>
            <td>&nbsp;
			<input type="hidden" id="vol" name="vol" value="<?=$nextvolume;?>"/> 
			<input type="hidden" id="num" name="num" value="<?=$nextNum;?>"/> 
			<input type="hidden" id="month" name="month" value="<?=$nextMonthNum;?>"/> 
			<input type="hidden" id="issn" name="issn" value="<?=$issn;?>"/> 
			<input type="hidden" id="eissn" name="eissn" value="<?=$eissn;?>"/>
			<input type="hidden" id="year" name="year" value="<?=$year;?>"/> 
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
      <td width="8%">&nbsp;</td>
      <td width="2%" bgcolor="#F2F2F2">&nbsp;</td>
      <td width="84%" bgcolor="#F2F2F2"><table width="100%" border="0" cellspacing="0">
        <tr>
          <td width="46%">Journal cover page </td>
          <td width="2%">&nbsp;</td>
          <td width="52%"><label>
            <input name="cover" type="file" id="cover" />
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
            <input name="upload_journal" type="submit" id="upload_journal" value="U p l o a d " />
          </label></td>
        </tr>
      </table></td>
      <td width="1%" bgcolor="#F2F2F2">&nbsp;</td>
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
