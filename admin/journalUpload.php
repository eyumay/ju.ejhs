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

if(isset($_POST['upload']))
{

	$errmsgInputFields = '';
	$errmsgJournal = '';
	$errmsgCover = '';
	// Get data
	$title = $_POST['title'];
	$authors = $_POST['authors'];
	$category = $_POST['category'];
	$num = $_POST['num'];
	$vol = $_POST['vol'];
	$month = $_POST['month'];
	
	$journal_location = $_FILES["journal"]["name"];
	$coverpage_location = $_FILES["cover"]["name"];
	
	
	// Check for error
	if($title == '')
	{
		//echo " Empty field : Title <br />";
		$errmsgInputFields = " Empty field : Journal Title <br />";
	}
	else if($authors == '')
	{
		//echo " Empty field : Content<br />";
		$errmsgInputFields = " Authors of the Journal must be provided!  <br />";
	}
	else if($category == 'null')
	{
		//echo " Empty field : Content<br />";
		$errmsgInputFields = " Article Category must be provided!  <br />";
	}	
	else if($vol == 'null' or $num == 'null' or $month == 'null')
	{
		//echo " Empty field : Content<br />";
		$errmsgInputFields = " Journal volume / number / Month  <br />";
	}
	else {
		$errmsgInputFields = "";
	}
	
	
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	if($_FILES["journal"]["type"] == "application/pdf"){
		if($_FILES["journal"]["error"] > 0){
			$errmsgJournal = " Error while uploading your file (Article) ".$_FILES["journal"]["error"]. "  <br />";
		}
		else{
			$errmsgJournal = "";
		}
	}
	else {
		$errmsgJournal = "Only PDF files are allowed !!!";
	}
	
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@	
	/*if(($_FILES["cover"]["type"] ==  "image/jpeg") or ($_FILES["cover"]["type"] ==  "image/gif") or ($_FILES["cover"]["type"] ==  "image/pjpeg")){	 
		if($_FILES["cover"]["error"] > 0){
			$errmsgCover = "Error while uploading";
		}
		else{
			$errmsgCover = "";
		}
	}
	else{
		$errmsgCover = " File types other than picture (JPEG or GIF) are not allowed !!!! <br />";
	}	*/
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	
		
	if($errmsgInputFields == '' and $errmsgJournal == '')
	{
		
		/*echo "went alright";
		echo $_FILES["journal"]["type"] . "<br /> ";
		echo $_FILES["journal"]["error"] . "<br /> ";
		echo $_FILES["cover"]["type"] . "<br /> ";
		echo $_FILES["cover"]["error"] . "<br /> ";
		echo $journal_location;
		echo $coverpage_location;*/
		if (file_exists("../ejhs_journal/2010/" . $_FILES["journal"]["name"]))
		{
			$errmsgJournal = $_FILES["journal"]["name"] . " already exists. ";
			
		}
		/*else if (file_exists("../ejhs_journal/2010/" . $_FILES["cover"]["name"]))
		{
			$errmsgCover = $_FILES["cover"]["name"] . " already exists. ";
		}*/
		else
		{
			
			// Generate a system date.
			$date	= date("Y-m-d");
			// Add slashes
			$title  = addslashes($title);
			$authors  = addslashes($authors);
			//Get Volume
			$volume = "Volume ".$vol." Number ".$num;
			$query = "INSERT INTO journal 
				(title, authors, created_at, updated_at, category, volume, month, journal_location, coverpage_location) 
			VALUES ('$title', '$authors', '$date', '$date', '$category', '$volume', '$month', 
			'$journal_location', '$coverpage_location')";
			if(!mysql_query($query))
			{
				echo "an error occured". mysql_error();
			}
			else
			{

				move_uploaded_file($_FILES["journal"]["tmp_name"],  "../ejhs_journal/2010/" . $_FILES["journal"]["name"]);
				move_uploaded_file($_FILES["cover"]["tmp_name"],  "../ejhs_journal/2010/" . $_FILES["cover"]["name"]);				
				
				$msg = "You have added one an article! <br />";

				$msg .= "<em> Files are successfully uploaded -  !! </em> ";				
				
				$title = '';
				$authors = '';
				$category = '';
				$volume = '';
				$month = '';
				$journal_location = '';
				$coverpage_location = '';
			}		
		}
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>E J H S | About Ethiopian Journal of Health Sciences</title>
</script>
<script language="JavaScript">
function delArticle(id, title)
{
    if (confirm("Are you sure you want to delete '" + title + "'"))
    {
        window.location.href = 'newsedit.php?del=' + id;
    }
}
</script>

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
</style>
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
            <td valign="top"><table width="100%" border="0">
              <tr>
                <td width="2%">&nbsp;</td>
                <td width="48%">&nbsp;</td>
                <td width="48%">&nbsp;</td>
                <td width="2%">&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td colspan="2" rowspan="2" valign="top">
				
					<h1> Upload Journal  .... </h1>
					<?php
					if($errmsgInputFields != '' or $errmsgJournal != '' or $errmsgCover != ''){
					?>
						<p style="color:#FF0000; border:#FF0000 solid 1px;"> 
						You have an error while Uploading Journal: 
						<?php 
							echo $errmsgInputFields . "<br /> " ;
							echo $errmsgJournal . "<br /> " ;
							echo $errmsgCover . "<br /> " ;
						;?><br />
						</p>
					<?php
					}
					?>
					
					<?php
					if($msg != ''){
					?>
						<p style="color:#009900; border:#009900 solid 1px;"> 
						Success : You have succesfully uploaded <?php echo $msg; ?><br />
						</p>
					<?php
					}
					?>
					
                    <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data">
                      <table width="100%" border="0" cellspacing="1">
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="2"><strong>Journal Information </strong></td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td width="1%">&nbsp;</td>
                          <td width="18%">Title</td>
                          <td width="80%"><label>
                            <input name="title" type="text" id="title" value="<?=$title;?>" size="70" />
                          </label></td>
                          <td width="1%">&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>Authors</td>
                          <td><input name="authors" type="text" id="authors" value="<?=$authors;?>" size="70" /></td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>Category</td>
                          <td><label>
                            <select name="category" id="category">
                              <option value="null">Select journal category</option>
                              <option value="editorial">Editorial</option>
                              <option value="original">Original Articles</option>
                              <option value="brief">Brief Communications</option>
                              <option value="case">Case Report</option>
                            </select>
                          </label></td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>Journal Volume</td>
                          <td><select name="month" id="month">
                            <option value="null">Select Month of Edition</option>
                            <option value="March">March</option>
                            <option value="July">July</option>
                            <option value="November">November</option>
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
                                                                                    </select>
                          <label></label></td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td colspan="2"><strong>Attach Files </strong></td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>Attach Journal (PDF, DOC, HTML) </td>
                          <td valign="top"><label>
                            <input name="journal" type="file" id="journal" size="50" />
                          </label></td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td valign="top">&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td><label>
                            <input name="upload" type="submit" id="upload" value="upload" />
                          </label></td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                        </tr>
                        <?php
						$query =  "select * from news order by id desc";
						$result = mysql_query($query);
						if(!$result)
						{
							echo "An error occured".mysql_error();
						}
						else
						{
							while($row = mysql_fetch_array($result,MYSQL_ASSOC))
							{
								$title	= $row['title'];
								$id		= $row['id'];	
								  
								Edit  
								?>
                        <?php
							}
					
						}
						?>
                      </table>
                  </form>
                </td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td height="35">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table></td>
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
