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
						$showCover			= TRUE ;
						$showVolInfo		= FALSE; 
						$curCategory		= '';
						$prevCategory		= '';
						$title				= '';
						$abstract			= '';
						$id					= '';
						$authors			= '';
						$upload_location	= '';
						$upload_name		= '';
						$categoryName		= '';
						
						include 'library/config.php';
						include 'library/opendb.php';
						include 'EJHSJournalArticleCategory.class.php';
						
						if(isset($_GET['journal_id'])) 			
						{			
							$journal_id = $_GET['journal_id'];
							$query =  "SElECT * from journal WHERE is_activated = 'activated' AND id = '$journal_id' ";														
						}
						else	
						{
						   $showVolInfo = TRUE;						
						   $query =  "SElECT * from journal WHERE is_activated = 'activated' order by volume DESC, month DESC limit 1";							
						   $result = mysql_query($query);
						   include 'viewVolInfo-index.php';
						 }

						$result = mysql_query($query);
						
						?>
						
						<?php if ($showVolInfo): ?>
							<h2 style="margin-top:0px; margin-bottom:0px;"> <?php echo $volInfo; ?></h2>
						<?php endif; ?>
						
						<?php
						if(!$result)
						{
							echo "An error occured".mysql_error();
						}
						else
						{
							$row = mysql_fetch_array($result, MYSQL_ASSOC);
							$journal_id = $row['id'];
							$fetch_articles = mysql_query("select * from journal_article where journal_id = '$journal_id' order by start_page_number ASC");
							if(mysql_num_rows($fetch_articles)>0){
							while($row = mysql_fetch_array($fetch_articles,MYSQL_ASSOC))
							{
								$title	= $row['title'];
								$abstract = $row['abstract'];
								$id		= $row['id'];	
								$authors = $row['authors'];
								$upload_location = $row['upload_location'];
								$upload_name = $row['upload_name'];
								$curCategory = $row['category'];
					
						?>

	<div id="showArticles" name="showArticles" style="margin-bottom:10px;">
		<?php
		if($showCover){ 
		?>
		<img src="<?php echo $upload_location."/".$coverpage; ?>" height="125" width="95" style="float:right; margin:20px" /><br />
		<?php
		}
		$showCover = FALSE ;
		if($curCategory != $prevCategory){
			$categoryName = EJHSJournalArticleCategory::queryOne($curCategory);
		
		?>
			<?php echo $categoryName; ?> 
		<?php												
		$prevCategory = $curCategory;
		}
		?>
		<?php echo $title; ?><br />
		<em> <?php echo $authors; ?></em><br />
		<?php if($abstract != ''): ?>
		<a href="../issues/abstract.php?artId=<?php echo $id ?>"> Abstract </a> | 
		<?php endif; ?>
		<a href="<?php echo $upload_location."/".$upload_name; ?>" target="_blank"> View in PDF</a> | 
		<a href="download.php?filename=<?php echo $upload_location."/".$upload_name; ?>&type=application/pdf" target="_blank">Download </a> 
	</div>
<?php
}
}
else{ 
?> 
</div>
<tr>
  <td valign="top" bordercolor="#000099">&nbsp;</td>
  <td valign="top" bordercolor="#000099">No journal has been published for now! </td>
  <td valign="top" bordercolor="#000099">&nbsp;</td>
  <td bordercolor="#000099">&nbsp;</td>
</tr>
<?php
}
}
?>
</table>
</div>
</body>
</html>
