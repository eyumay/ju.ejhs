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
    $query = "DELETE FROM journal_article_category WHERE id = '{$_GET['del']}'";
    mysql_query($query) or die('Error : ' . mysql_error());
        
    // redirect to current page so when the user refresh this page
    // after deleting an article we won't go back to this code block
	$_SESSION['notice'] = "Article Category Delete Was Successful"; 
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
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
        window.location.href = 'journal_article_category_list.php?del=' + id;
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
</table>

<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
	  <?php  $bgcolor = null; ?>	
	  <h2> Articles Category List </h2>
	  <a href="journal_article_category_add.php">+ Add New Category      </a>
	  <table width="100%" border="0" cellspacing="0">
    <tr>
      <td>&nbsp;</td>
	  
      <td bordercolor="#F2F2F2" bgcolor="#F2F2F2">
	  

 	  <table width="100%" border="0" cellspacing="0">

            <td width="1%">&nbsp;</td>
            <td width="56%">&nbsp;</td>
            <td align="center"><strong>Actions</strong></td>
            </tr>
		  <?php
		  	$result = mysql_query("SELECT * FROM journal_article_category ORDER BY name ASC");
			if(!$result){
			echo "Error on DB".mysql_error();
			}
		  $bgcolor = '';

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
			?>			
			<tr style="border-bottom:1px solid #333333;" bgcolor="#FFF">
			<td bgcolor="#FFF" colspan="3">			</td>
		  	</tr>	

          <tr style="border-bottom:1px solid #333333;" bgcolor="<?php echo $bgcolor;?>">
            <td valign="top" bgcolor="<?php echo $bgcolor;?>">&raquo;</td>
            <td bgcolor="<?php echo $bgcolor;?>">
			<?php $name = $row['name']; ?>
			<?php echo $name; ?>  </td> 
            <td align="center" bgcolor="<?php echo $bgcolor;?>">
			<a href="journal_article_update.php">&nbsp;<img src="img/b_edit.png" width="16" height="16" border="0" /></a>
			<a href="journal_article_category_edit.php?id=<?php echo $row['id']; ?>">Edit</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<img src="img/b_drop.png" />&nbsp;
			<a href="javascript:delArticle('<?php echo $row['id'];?>', '<?php echo $name;?>');">Delete</a></td>
            </tr>
		  <?php
		  }
		  ?>
        </table>
	</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="4%">&nbsp;</td>
      <td width="87%" bgcolor="#F2F2F2"><table width="100%" border="0" cellspacing="0">

      </table></td>
      <td width="9%">&nbsp;</td>
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
