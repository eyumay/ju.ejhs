<?php 
session_start(); 
if(!$_SESSION['user_is_logged_in']){
	header("location:login.php");
}
if($_SESSION['privilege'] != "reviewer"){
	header("location:login.php");
}
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
	// Get Data	
	
	$journalId	= $_GET['artID'];  ## artId is to refer to journal talble ID
	// FETCH JOURNAL INFORMATION FOR LATTER INDEXING
	$query		= "SELECT * FROM journal WHERE id = '$journalId' ";
	$jResult	= mysql_query($query); 
	$jRow		= mysql_fetch_array($jResult, MYSQL_ASSOC); 
	
	$year 		= $jRow['year']; 
	$volume 	= $jRow['volume']; 
	$number	 	= $jRow['num']; 
	$month	 	= $jRow['month']; 
	$upload_dir	= $jRow['upload_dir']; 		
	
	$date = date('d-m-Y');
	$year = date('Y');

	$upload_name = $_FILES["cover"]["name"];
	$upload_podcast = $_FILES["podcast"]["name"];
	$title = $_POST['title'];
	$abstract =  mysql_real_escape_string(trim($_POST['abstract']));
	$authors = $_POST['authors'];
	$category = $_POST['category'];		
	$start_page_number = $_POST['start_page_number'];
	$end_page_number = $_POST['end_page_number'];
	$keyword = $_POST['keyword'];
	
	$upload_name = trim($_FILES["cover"]["name"]);
	$title = trim($_POST['title']);
	$authors = trim($_POST['authors']);
	$category = trim($_POST['category']);	
	$url = '';
	
// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
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
	
	
if(($errmsg == "") && ($errmsgCover == '') && ($errmsgPodcast == ''))
{	
	//store both				
	$result = mysql_query("
		INSERT INTO journal_article (journal_id, title, abstract, authors, upload_name, upload_location, category, start_page_number, end_page_number, keyword, podcast, created_at, updated_at) 
		VALUES ('$journalId','$title', '$abstract', '$authors','$upload_name', '$upload_dir', '$category', '$start_page_number', '$end_page_number', '$keyword','$upload_podcast', '$date','$date')");
	if($result){
		$msg .= "Journal article Information was successfuly saved into the Database <br />";
		move_uploaded_file($_FILES["cover"]["tmp_name"],  $upload_dir ."/". $_FILES["cover"]["name"]);	
		move_uploaded_file($_FILES["podcast"]["tmp_name"],  $upload_dir ."/". $_FILES["podcast"]["name"]);	
		$msg .= "upload was successful!<br />";	
		$mysqlInsertId = mysql_insert_id(); 
		
		$url = "/issues/abstract.php?artId=".$mysqlInsertId;
		
		$resultSearchEngine = mysql_query("
		INSERT INTO searchengine(volume, number, month, year, title, authors, url, article_id)
		VALUES ( '$volume', '$number', '$month', '$year', '$title', '$authors', '$url', '$mysqlInsertId')
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
				
				$_SESSION['notice'] = "Article Upload Was Successful"; 
				header('location:journal_article_list.php?artID='.$_GET['artID']);
			}			
		else
			$errmsg .=  "There was error while creating search engine entry".mysql_error()."<br />";
	}
	else{
		$errmsg .=  "There was error on Database ".mysql_error()."<br />";
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
	</td>
    <td width="8%">&nbsp;</td>
  </tr>
</table>

<form action="journal_article_add_under_selected.php?artID=<?php echo $_GET['artID'];  ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <table width="100%" border="0" cellspacing="0">
    <tr>
      <td bordercolor="#F2F2F2" bgcolor="#F2F2F2">
	  <?php
	  $journalId = $_GET['artID']; 
	  $result =mysql_query("SELECT * FROM journal where id = '$journalId' ") or die(mysql_error());
	  $row = mysql_fetch_array($result, MYSQL_ASSOC);	  
	  $month = $row['month'];
	  $year = $row['year'];
	  $volume = $row['volume'];
	  $num = $row['num'];
	  $journal_id = $row['id'];
	  $upload_dir = $row['upload_dir'];
	  $journal_cover = $row['coverpage_location'];
	  
	  
	  if($month == 1){
			$showMonth = "January";
      }
	  if($month == 2){
			$showMonth = "February";
      }
	  if($month == 3){
			$showMonth = "March";
      }
	  if($month == 4){
			$showMonth = "April";
      }
	  if($month == 5){
			$showMonth = "May";
      }
	  if($month == 6){
			$showMonth = "June";
      }
	  if($month == 7){
			$showMonth = "July";
      }
	  if($month == 8){
			$showMonth = "August";
      }
	  if($month == 9){
			$showMonth = "September";
      }	  	  	  	
	  if($month == 10){
			$showMonth = "October";
      }
	  if($month == 11){
			$showMonth = "November";
      }
	  if($month == 12){
			$showMonth = "December";
      }	  	  	    	  	  	  	  			 
	  ?>
	  <table width="341" border="0" align="center" cellspacing="0">
          <tr>
            <td align="center"><strong>You are updating article for the following journal </strong></td>
            </tr>
          <tr>
            <td width="339" align="center"><strong><?php echo "Volume ".$volume. " Number ".$num; ?> </strong></td>
            </tr>
          <tr>
            <td align="center"><strong> <?php echo $showMonth. " ".$year; ?> </strong></td>
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
          <td><select name="category" id="category">
              <option value="null">Select Category</option>
              <?php $category = EJHSJournalArticleCategory::queryAll(); ?>
              <?php foreach($category as $key => $name) : ?>
              <option value="<?php echo $key; ?>"> <?php echo $name; ?> </option>
              <?php endforeach; ?>
          </select></td>
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
          <td align="right">Abstract</td>
          <td>&nbsp;</td>
          <td><br>
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
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><label>
            <input name="upload_journal" type="submit" id="upload_journal" value="U p l o a d " />
          </label></td>
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
