<?php 
session_start(); 
if(!$_SESSION['user_is_logged_in']){
	header("location:login.php");
}
if($_SESSION['privilege'] != "reviewer"){
	header("location:login.php");
}

#### ALL INCLUDED FILED
include_once "includes/fckeditor/fckeditor.php"; 
include 'library/config.php';
include 'library/opendb.php';
include 'EJHSJournalArticleCategory.class.php';

	$errmsgCover = '';
	$msg = '';
	$errmsgPodcast = '';
	
	$errmsg = '';
	$title = '';
	$abstract = '';
	$authors = '';
	$category = '';
	$start_page_number = '';
	$end_page_number = '';
	$keyword = '';
	$upload_dir = '';
	$upload_name = '';
	$errmsgPodcast = '';
	
	$hiddenData = '';
	$change_vol = '';
	$change_num  = '';
	$change_month =  '';
	
	$nextnum = ''; 
	
	
	$hidden_month = '';
	$hidden_vol = '';
	$hidden_num = '';
	$hidden_journal_id = '';

if(isset($_POST['upload_journal']))
{
	$errmsgCover = '';
	$msg = '';
	
	$errmsg = '';
	$title = '';
	$abstract = '';
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
	$hidden_journal_id = '';
	
	
	// Get Data	
	
	$date = date('d-m-Y');
	$year = date('Y');

	$upload_name = $_FILES["cover"]["name"];
	$upload_podcast = $_FILES["podcast"]["name"];
	$upload_dir = $_POST['hid_upload_location'];
	$title = $_POST['title'];
	$abstract =  mysql_real_escape_string(trim($_POST['abstract']));
	$authors = $_POST['authors'];
	$category = $_POST['category'];		
	$start_page_number = $_POST['start_page_number'];
	$end_page_number = $_POST['end_page_number'];
	$keyword = $_POST['keyword'];
	
	$hidden_num = $_POST['hidden_num'];
	$hidden_vol = $_POST['hidden_vol'];
	$hidden_month = $_POST['hidden_month'];	
	$hidden_journal_id = $_POST['hidden_journal_id'];	
	$hidden_year = $_POST['hid_year'];
	if(isset($_POST['hiddenData']))
	{
		$hiddenData = $_POST['hiddenData'];
	}
	
	$upload_name = trim($_FILES["cover"]["name"]);
	$title = trim($_POST['title']);
	$authors = trim($_POST['authors']);
	$category = trim($_POST['category']);	
	$url = '';
	
// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	
	if($hiddenData != '')
	{
		$store = true;
		
		$change_num = $_POST['change_num'];
		$change_vol = $_POST['change_vol'];
		$change_month = $_POST['change_month'];
		$upload_dir = "Volume-".$change_vol."-Num".$change_num;
		
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
	/*if($abstract == '')
	{
		$errmsg .= "Abstract should be provided <br />";
	}
	*/	
	if($authors == '')
	{
		$errmsg .= "Authors should be provided <br />";
	}

	if($category == 'null')
	{
		$errmsg .= "Category for the journal article should be provided <br />";
	}	
	if($start_page_number == '')
	{
		$errmsg .= "Starting page number should be provided! <br />";
	}		
	if($end_page_number == '')
	{
		$errmsg .= "Ending page number should be provided! <br />";
	}
	// Check for the upload pdf
	if($_FILES["cover"]["error"] > 0)
	{
		$errmsgCover =  "PDF article should be attached!";
	}	
	else{
		if(($_FILES["cover"]["type"] == "application/pdf") ){
			if($_FILES["cover"]["error"] > 0){
				$errmsgCover = " Error while uploading your file (Article) ".$_FILES["cover"]["error"]. "  <br />";
			}
			else{
				$errmsgCover = "";
				if (file_exists($upload_dir. "/" . $_FILES["cover"]["name"]))
				{
					$errmsgCover = $_FILES["cover"]["name"] . " already exists. <br />";
					
				}
			}
		}
		else {
			$errmsgCover = "Only PDF files are allowed !!! <br />";
		}		
	}
	
	// Check for upload of podcast (audio)
	if($_FILES["podcast"]["name"] != '')
	{
		if($_FILES["podcast"]["error"] > 0)
		{
			$errmsgPodcast =  "Audio podcast should be attached!";
		}	
		else{
			if(($_FILES["podcast"]["type"] == "audio/mp3") or ($_FILES["podcast"]["type"] == "audio/mpeg"))
			{
				if($_FILES["podcast"]["error"] > 0){
					$errmsgPodcast = " Error while uploading your file (Article) ".$_FILES["podcast"]["error"]. "  <br />";
				}
				else{
					$errmsgPodcast = "";
					if (file_exists($upload_dir. "/" . $_FILES["podcast"]["name"]))
					{
						$errmsgPodcast = $_FILES["podcast"]["name"] . " already exists. <br />";
						
					}
				}
			}
			else {
				$errmsgPodcast = "MP3 file types are allowed !!! <br />";
			}		
		}
	}
	
	
if(($errmsg == "") and ($errmsgCover == '') and ($hiddenData == '') and ($errmsgPodcast == ''))
{
	//store both				
	$result = mysql_query("
		INSERT INTO journal_article (journal_id, title, abstract, authors, upload_name, upload_location, category, start_page_number, end_page_number, keyword, podcast, created_at, updated_at) 
		VALUES ('$hidden_journal_id','$title', '$abstract', '$authors','$upload_name', '$upload_dir', '$category', '$start_page_number', '$end_page_number', '$keyword','$upload_podcast', '$date','$date')");
	if($result){
		$msg .= "Journal article Information was successfuly saved into the Database <br />";
		move_uploaded_file($_FILES["cover"]["tmp_name"],  $upload_dir ."/". $_FILES["cover"]["name"]);	
		move_uploaded_file($_FILES["podcast"]["tmp_name"],  $upload_dir ."/". $_FILES["podcast"]["name"]);	
		$msg .= "upload was successful!<br />";	
		$mysqlInsertId = mysql_insert_id(); 
		
		$url = "/issues/abstract.php?artId=".$mysqlInsertId;
		
		$resultSearchEngine = mysql_query("
		INSERT INTO searchengine(volume, number, month, year, title, authors, url, article_id)
		VALUES ( '$hidden_vol', '$hidden_num', '$hidden_month', '$hidden_year', '$title', '$authors', '$url', '$mysqlInsertId')
		");
		if($resultSearchEngine) {
			$msg .= "Search Engine Entry Successfuly Created<br />";
				$errmsgCover = '';
				$title = '';
				$abstract = '';
				$authors = '';
				$category = '';
				$start_page_number = '';
				$end_page_number = '';
				$keyword = '';
				$upload_dir = '';
				$upload_name = '';
				$errmsgPodcast = '';
				
				$hiddenData = '';
				$change_vol = '';
				$change_num  = '';
				$change_month =  '';
				
				$nextnum = ''; 
				
				
				$hidden_month = '';
				$hidden_vol = '';
				$hidden_num = '';
				$hidden_journal_id = '';	
			}			
		else
			$errmsg .=  "There was error while creating search engine entry".mysql_error()."<br />";
	}
	else{
		$errmsg .=  "There was error on Database ".mysql_error()."<br />";
	}
}
elseif(($errmsg == '') and ($errmsgCover == '') and ($hiddenData !== '') and ($errmsgPodcast == ''))
{
	//store both				
	$result_count_rows =mysql_query("select * from journal where num='$change_num' and volume = '$change_vol'") or die(mysql_error());
	
	if(mysql_num_rows($result_count_rows) > 0){	
		$row = mysql_fetch_array($result_count_rows, MYSQL_ASSOC);	  
		$journal_id = $row['id']."<br />";
		$result = mysql_query("
		INSERT INTO journal_article (journal_id, title, abstract, authors, upload_name, upload_location, category, start_page_number, end_page_number, keyword, podcast, created_at, updated_at) 
		VALUES ('$journal_id','$title', '$abstract', '$authors','$upload_name', '$upload_dir', '$category', '$start_page_number', '$end_page_number', '$keyword', '$upload_podcast', '$date','$date')");	
		if($result){
			$msg .= "Journal Article Information was successfuly saved into the Database";
			move_uploaded_file($_FILES["cover"]["tmp_name"],  $upload_dir ."/". $_FILES["cover"]["name"]);
			move_uploaded_file($_FILES["podcast"]["tmp_name"],  $upload_dir ."/". $_FILES["podcast"]["name"]);
			$msg .= "upload was successful!<br />";
			$msg .= "The file has been saved under ".$_SERVER['DOCUMENT_ROOT']."ejhs-update/admin/".$upload_dir;
			
			$mysqlInsertId = mysql_insert_id(); 
			
			$url = "/issues/abstract.php?artId=".$mysqlInsertId;			
			
			$resultSearchEngine = mysql_query("
			INSERT INTO searchengine(volume, number, month, title, authors, url, article_id)
			VALUES ( '$change_vol', '$change_num', '$change_month', '$title', '$authors', '$url', '$mysqlInsertId')
			");
			if($resultSearchEngine) {
				$msg .= "Search Engine Entry Successfuly Created<br />";
				
				$errmsgCover = '';
				$title = '';
				$abstract = '';
				$authors = '';
				$category = '';
				$start_page_number = '';
				$end_page_number = '';
				$keyword = '';
				
				$upload_dir = '';
				$upload_name = '';
				$errmsgPodcast = '';
				
				$hiddenData = '';
				$change_vol = '';
				$change_num  = '';
				$change_month =  '';
				
				$nextnum = ''; 
				
				
				$hidden_month = '';
				$hidden_vol = '';
				$hidden_num = '';
				$hidden_journal_id = '';
			}				
			else
				$errmsg .=  "There was while creating search engine entry".mysql_error()."<br />";			
		}
		else{
			$errmsg .= "There was error on Database ".mysql_error()."<br />";
		}

	}
	else{
		$errmsg .= "The journal Volume <strong>".$change_vol." Number ".$change_num. "</strong> is not available in Database<br />";
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
		echo $upload_dir ."<br />";
		$errmsgCover = '';
		$errmsg = '';
		echo $errmsgPodcast;
		$errmsgPodcast = '';
		echo $_FILES["podcast"]["type"];
		echo $_FILES["podcast"]["name"];
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
		$msg = "";
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
                        <td width="14%"> Journal Volume </td>
                        <td width="81%">
						<input type="hidden" value="3" name="hiddenData" id="hiddenData"/>
						<select name="change_month" id="change_month">
                            <option value="null">Select Month of Edition</option>
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
							  <option value="4">4</option>
							  <option value="5">Special Edition</option>
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
      <td bordercolor="#F2F2F2" bgcolor="#F2F2F2">
	  <?php
	  $result =mysql_query("SELECT * FROM journal ORDER BY volume DESC, num DESC") or die(mysql_error());
	  $row = mysql_fetch_array($result, MYSQL_ASSOC);	  
	  $month = $row['month'];
	  $nextyear = $row['year'];
	  $nextvolume = $row['volume'];
	  $nextnum = $row['num'];
	  $journal_id = $row['id'];
	  $upload_dir = $row['upload_dir'];
	  $journal_cover = $row['coverpage_location'];
	  $nextNum = $row['num'];
	  
		if($month == 3){
			$nextMonth = "March";
			$nextMonthNum = 3;
		}
		elseif($month == 7){
			$nextMonth ="November";
			$nextMonthNum = 11;
		}
		else {
			$nextMonth = "March";
			$nextMonthNum = 3;
		}
		
	 

	  ?>
	  <table width="387" border="0" cellspacing="0">
          <tr>
            <td><strong>You are uploading article for the following journal </strong></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="339"><strong><?php echo "Volume ".$nextvolume. " Number ".$nextNum; ?> </strong></td>
            <td width="29">&nbsp;</td>
          </tr>
          <tr>
            <td><strong>  </strong></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><a href="javascript:;" onclick="moreFields();"><strong>C H A N G E </strong></a></td>
            <td>&nbsp;</td>
          </tr>
        </table>
		
	  <span id="writeroot"></span>   	</td>
      </tr>
    <tr>
      <td bordercolor="#F2F2F2" bgcolor="#F2F2F2"><hr width="500" /></td>
      </tr>
    <tr>
      <td width="94%" bgcolor="#F2F2F2"><table width="100%" border="0" cellspacing="0">
        <tr>
          <td align="right">Category of the article </td>
          <td>&nbsp;</td>
          <td>
              <select name="category" id="category">
                <option value="null">Select Category</option>
                <?php $category = EJHSJournalArticleCategory::queryAll(); ?>
                <?php foreach($category as $key => $name) : ?>
                <option value="<?php echo $key; ?>"> <?php echo $name; ?> </option>
                <?php endforeach; ?>
              </select>          
			  </td>
        </tr>
        <tr>
          <td align="right">Title</td>
          <td>&nbsp;</td>
          <td><input name="title" type="text" id="title" value="<?=$title;?>" size="80" /></td>
        </tr>
        <tr>
          <td align="right">Authors</td>
          <td>&nbsp;</td>
          <td><label>
            <input name="authors" type="text" id="authors" value="<?=$authors;?>" size="80" />
          </label></td>
        </tr>
        <tr>
          <td align="right">Key words </td>
          <td>&nbsp;</td>
          <td><input name="keyword" type="text" id="keyword" value="<?=$keyword;?>" size="80" /></td>
        </tr>
        <tr>
          <td align="right">Starting Page Number </td>
          <td>&nbsp;</td>
          <td><input name="start_page_number" type="text" id="start_page_number" value="<?=$start_page_number;?>" size="5" /></td>
        </tr>
        <tr>
          <td align="right">Starting Page Number </td>
          <td>&nbsp;</td>
          <td><input name="end_page_number" type="text" id="end_page_number" value="<?=$end_page_number;?>" size="5" /></td>
        </tr>
        <tr>
          <td width="24%" align="right">Manuscript (PDF only) </td>
          <td width="1%">&nbsp;</td>
          <td width="75%"><label>
            <input name="cover" type="file" id="cover" />
          </label></td>
        </tr>
        <tr>
          <td align="right">Attach podcast  </td>
          <td>&nbsp;</td>
          <td><label>
            <input name="podcast" type="file" id="podcast" />
          </label></td>
        </tr>
        <tr>
          <td colspan="3" align="center">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" align="center">Copy and Paste Manuscript Abstract (Optional) </td>
        </tr>
        <tr>
          <td colspan="3" align="center">
              <?php
			  $oFCKeditor = new FCKeditor('abstract');
			  $oFCKeditor->BasePath = "includes/fckeditor/";
			  $oFCKeditor->Value    = "";
			  $oFCKeditor->Width    = 540;
			  $oFCKeditor->Height   = 400;
			  echo $oFCKeditor->CreateHtml();
			?>          </td>
          </tr>
        <tr>
          <td><label></label></td>
          <td>&nbsp;</td>
          <td bgcolor="#F2F2F2">
		  	<input type="hidden" id="hidden_vol" name="hidden_vol" value="<?=$nextvolume;?>"/> 
			<input type="hidden" id="hidden_num" name="hidden_num" value="<?=$nextNum;?>"/> 
			<input type="hidden" id="hidden_month" name="hidden_month" value="<?=$nextMonthNum;?>"/>		  
			<input type="hidden" id="hidden_journal_id" name="hidden_journal_id" value="<?=$journal_id;?>"/>
			<input type="hidden" id="hid_upload_location" name="hid_upload_location" value="<?=$upload_dir;?>"/>
			<input type="hidden" id="hid_year" name="hid_year" value="<?=$nextyear;?>"/>			</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><label>
            <input name="upload_journal" type="submit" id="upload_journal" value="U p l o a d " />
          </label></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>		  </td>
        </tr>
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
