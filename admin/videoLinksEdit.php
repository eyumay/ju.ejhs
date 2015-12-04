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
if(isset($_GET['del']))
{
    // remove the article from the database
    $query = "DELETE FROM video_links WHERE id = '{$_GET['del']}'";
    mysql_query($query) or die('Error : ' . mysql_error());
        
    // redirect to current page so when the user refresh this page
    // after deleting an article we won't go back to this code block
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
if(isset($_POST['upload_news']))
{

	$errmsg = '';
	// Get data
	$title = $_POST['title'];
	$content = $_POST['content'];
	//$is_published = $_POST['is_published'];
	$redirect_link = $_POST['redirect_link'];
	$additional_link = $_POST['additional_link'];
	
	// Stripe white spaces
	$title  = trim($title);
	$content  = trim($content);
	$redirect_link = trim($redirect_link);
	$additional_link = trim($additional_link);
	

	
	
	// Check for error
	if($title == '')
	{
		//echo " Empty field : Title <br />";
		$errmsg = " Empty field : Title <br />";
	}
	else if($content == '' and $redirect_link == '')
	{
		//echo " Empty field : Content<br />";
		$errmsg = " You have to provide either content or redirect link (External Link)  <br />";
	}
	else if($errmsg == '')
	{
		// Generate a system date.
		$date	= date("Y-m-d");
		
		// Add slashes
		$title  = addslashes($title);
		$content  = addslashes($content);
		$query = "insert into news (title, content, created_at, updated_at, redirect_link, additional_link) values ('$title', '$content', '$date', '$date', '$redirect_link', '$additional_link')";
		if(!mysql_query($query))
		{
			echo "an error occured". mysql_error();
		}
		else
		{
			$msg = "You have added one an article! <br />";
			$title = '';
			$content = '';
			//$is_published = '';
			$additional_link = '';
			$redirect_link = '';
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
        window.location.href = 'videoLinksEdit.php?del=' + id;
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
				
					<h2> Edit Uploaded Multimedia/Video Links </h2>
					<table width="100%" border="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
    <td><a href="videoLinksAdd.php">Add New Video Link</a> </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="3%" bgcolor="#D8D7D6">&nbsp;</td>
    <td width="44%" bgcolor="#D8D7D6">Title of the News </td>
    <td width="2%" bgcolor="#D8D7D6">&nbsp;</td>
    <td width="10%" bgcolor="#D8D7D6">Actions</td>
    <td width="41%" bgcolor="#D8D7D6">&nbsp;</td>
  </tr>
  
  						<?php
						$query =  "select * from video_links order by id desc";
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
								$description		= $row['description'];	
								$id = $row['id']; 
								
								$title = str_replace("\"","", $title);
								$title = str_replace("\\","\"", $title);	
								
								$description = str_replace("\"","", $description);
								$description = str_replace("\\","\"", $description);																	  								
								?>
								
						
				

  <tr>
    <td valign="top">»</td>
    <td valign="top"> <?php echo $row['title']; ?></td>
    <td valign="top">&nbsp;</td>
    <td valign="top"> <a href= "videoLinksUpdate.php?editNews=<?php echo $id;?>">  Edit </a></td>
    <td valign="top"><a href="javascript:delArticle('<?php echo $id;?>', '<?php echo $title;?>');">Delete</a></td>
  </tr>
  								<?php
							}
					
						}
						?>	
</table>
						
						
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
