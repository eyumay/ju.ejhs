<?php
    // use pager values to fetch data 
	$id = $_GET['id'];
	include 'library/config.php';
	include 'library/opendb.php';
    $query = "select * from news WHERE id = '$id'"; 
    $result = mysql_query($query); 

//check if the id is set
	if($result=="")
	{
		$msg = "No News";
	}
	else{
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
	}
	if($row['redirect_link'] !== "")
	{
		$redirect = $row['redirect_link'];
		header("Location:$redirect");
	}
	else {
		
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<style type="text/css">
<!--

/* BODY */
body {
	/*background:#A14A05;*/
	background-image:url(../img/bg-content.gif);
	margin-top:30px;
	margin-bottom:0px;
	background:inherit;
	}
/* CONTAINER */

		#container {
			width: 100%;
			margin: 0 auto;
			font-family: Lucida Grande, Tahoma, Arial, Helvetica, sans-serif; /* Lucida Grande for the Macs, Tahoma for the PCs */
			font-size: 12px;
			line-height: 1.6em;
			color: #666;
			background:none;
		}
		
/* GENERAL MOJO AND MULA */
		
		h1 {
			font-family: Arial, Helvetica, sans-serif;
			font-weight: normal;
			font-size: 32px;
			color: #CC6633;
			margin-bottom: 30px;
			background-color: #FFF;
		}
		
		h2 {
			color: #666666;
			font-size: 16px;
			font-family: Arial, Helvetica, sans-serif;
			background-color: #FFF;
		}
		
		a {
			color:#CC6714;
			text-decoration: none;
		}

		a:hover {
			color:#CC6714;
			background-color: #F5F5F5;
		}


.style1 {
	color: #FFFFFF;
	font-weight: bold;
}
.style2 {
	color: #CCCCCC;
	font-weight: bold;
}

-->
</style>
</head>

<body>
<div id="container"> 

<table width="100%" border="0" align="center" cellspacing="0">
  <tr>
    <td width="56%" bordercolor="#000099"><span><?php 
	$title = '';
	$title = $row['title'];
	$title = str_replace("\"","", $title);
	$title = str_replace("\\","\"", $title);?>	
	
	<h3> <?php echo $title; ?></h3></span></td>
    </tr>
  <tr>
    <td valign="top" bordercolor="#000099">&nbsp;</td>
    </tr>
  <tr>
    <td valign="top" bordercolor="#000099"> <?php 
	$content = '';
	$content = $row['content'];
	$content = str_replace("\"","", $content);
	$content = str_replace("\\","\"", $content);	
	echo $content; ?>  </td>
    </tr>
  <tr>
    <td valign="top" bordercolor="#000099">&nbsp;</td>
    </tr>
  <tr>
    <td align="center" valign="top" bordercolor="#000099">Last published at : <?php echo $row['created_at']; ?></td>
    </tr>
  <tr>
    <td align="center" valign="top" bordercolor="#000099">&nbsp;</td>
    </tr>
  <tr>
    <td align="center" valign="top" bordercolor="#000099">&nbsp;</td>
    </tr>
  <tr>
    <td align="center" valign="top" bordercolor="#000099">&nbsp;</td>
    </tr>
</table>

</div>
</body>
</html>
<?php
	}
?>
