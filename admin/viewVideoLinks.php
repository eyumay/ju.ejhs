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
			font-size: 11px;
			line-height: 1.6em;
			color: #666;
			background:inherit;
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

-->
</style>
</head>

<body>
<div id="container"> 
<table width="100%" border="0" cellspacing="0" bordercolor="#0000CC">
<div> 
  						<?php																		
						$title = '' ;
						$description = ''; 
						$link = '';

						
						include 'library/config.php';
						include 'library/opendb.php';
						
						$fetch_articles = mysql_query("select * from video_links order by ID DESC");
						if(mysql_num_rows($fetch_articles)){
						while($row = mysql_fetch_array($fetch_articles,MYSQL_ASSOC))
						{
						?>
							<div style="margin:20px"> 
							<a href="<?php echo $row['link'] ?>" target="_blank"> <?php echo $row['title']; ?> </a> <br />
							<?php echo $row['description']; ?> <br />
							</div>
</div>
<?php 
}
}
else { ?>
<tr>
  <td valign="top" bordercolor="#000099">&nbsp;</td>
  <td valign="top" bordercolor="#000099">No Video has been published for now! </td>
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
