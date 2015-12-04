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
	margin-top:0px;
	margin-bottom:0px;
	}
/* CONTAINER */

		#container {
			width: 100%;
			margin: 0 auto;
			font-family: Lucida Grande, Tahoma, Arial, Helvetica, sans-serif; /* Lucida Grande for the Macs, Tahoma for the PCs */
			font-size: 12px;
			line-height: 1.6em;
			color: #666;
			background-color: #FFF;
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
<table width="100%" border="0" cellspacing="0" bordercolor="#0000CC">
  						<?php
						include 'library/config.php';
						include 'library/opendb.php';
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
								$updated_at = $row['updated_at'];
					
						?>	

  <tr>
    <td width="2%" valign="top" bordercolor="#000099">»</td>
    <td width="58%" valign="top" bordercolor="#000099">
	<a href="showEJHSNewsItem.php?id=<?php echo $id; ?>" target="_blank">
	
	<?php echo stripslashes($title); ?></a><br />
	<em> Date updated: <?php echo $updated_at; ?> </em>
	<?php
		if($row['additional_link'] !== ""){
			?>
			<div><em><a href="<?php echo $row['additional_link'];?>" target="_blank"> <?php echo $row['additional_link'];?> </a></em></div>
		<?php
		}
		?>	</td>
    <td width="39%" valign="top" bordercolor="#000099"></td>
    <td width="1%" bordercolor="#000099">&nbsp;</td>
  </tr>
<?php
} 
?> 
<tr>
    <td valign="top" bordercolor="#000099">&nbsp;</td>
    <td valign="top" bordercolor="#000099">&nbsp;</td>
    <td valign="top" bordercolor="#000099">&nbsp;</td>
    <td bordercolor="#000099">&nbsp;</td>
  </tr>
  <tr>
    <td valign="top" bordercolor="#000099">&nbsp;</td>
    <td valign="top" bordercolor="#000099">&nbsp;</td>
    <td valign="top" bordercolor="#000099">&nbsp;</td>
    <td bordercolor="#000099">&nbsp;</td>
  </tr>
  <tr>
    <td valign="top" bordercolor="#000099">&nbsp;</td>
    <td valign="top" bordercolor="#000099">&nbsp;</td>
    <td valign="top" bordercolor="#000099">&nbsp;</td>
    <td bordercolor="#000099">&nbsp;</td>
  </tr>
  <tr>
    <td valign="top" bordercolor="#000099">&nbsp;</td>
    <td valign="top" bordercolor="#000099">&nbsp;</td>
    <td valign="top" bordercolor="#000099">&nbsp;</td>
    <td bordercolor="#000099">&nbsp;</td>
  </tr>
<?php
}
?>
</table>
</div>
</body>
</html>
