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
    $query = "DELETE FROM journal WHERE id = '{$_GET['del']}'";
    mysql_query($query) or die('Error : ' . mysql_error());
        
    // redirect to current page so when the user refresh this page
    // after deleting an article we won't go back to this code block
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
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
        window.location.href = 'journalEdit.php?del=' + id;
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
				
					<h1> Edit uploaded Journals </h1>
					<table width="100%" border="0" cellspacing="1">
  <tr>
    <td width="3%" bgcolor="#575352">&nbsp;</td>
    <td width="44%" bgcolor="#575352"><span class="style1">Title and Author of the Journal </span></td>
    <td width="2%" bgcolor="#575352">&nbsp;</td>
    <td width="10%" bgcolor="#575352"><strong>Actions</strong></td>
    <td width="41%" bgcolor="#575352">&nbsp;</td>
  </tr>
  
  						<?php
						$query =  "select * from journal order by id desc";
						$result = mysql_query($query);
						if(!$result)
						{
							echo "An error occured".mysql_error();
						}
						else
						{
							while($row = mysql_fetch_array($result,MYSQL_ASSOC))
							{
								$title		= $row['title'];
								$authors	= $row['authors'];
								$id			= $row['id'];	
								  
								Edit  
								?>
								
						
				

  <tr>
    <td valign="top" bgcolor="#F3F3F3">»</td>
    <td valign="top" bgcolor="#F3F3F3"> <strong><?php echo $row['title']; ?></strong></td>
    <td valign="top" bgcolor="#F3F3F3">&nbsp;</td>
    <td valign="top" bgcolor="#F3F3F3"> <a href= "journalUpdate.php?editJournal=<?php echo $id;?>">  Edit </a></td>
    <td valign="top" bgcolor="#F3F3F3"><a href="javascript:delArticle('<?php echo $id;?>', '<?php echo $title;?>');">Delete</a></td>
  </tr>
  <tr>
    <td valign="top" bgcolor="#F3F3F3">&nbsp;</td>
    <td valign="top" bgcolor="#F3F3F3"><em><strong><?php echo $row['authors']; ?></strong></em></td>
    <td valign="top" bgcolor="#F3F3F3">&nbsp;</td>
    <td valign="top" bgcolor="#F3F3F3">&nbsp;</td>
    <td valign="top" bgcolor="#F3F3F3">&nbsp;</td>
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
