<table width="100%" border="0" cellspacing="0" bordercolor="#0000CC">
  						<?php
						include 'library/config.php';
						include 'library/opendb.php';
						$journal_id = $_GET['journal_id'];
						$query =  "select * from journal_article WHERE category = 'case' and journal_id = '$journal_id' order by start_page_number ASC";
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
								$authors = $row['authors'];
								$upload_location = $row['upload_location'];
								$upload_name = $row['upload_name'];
								$upload_podcast = $row['podcast'];
						?>	

  <tr>
    <td width="2%" valign="top" bordercolor="#000099">»</td>
    <td colspan="2" valign="top" bordercolor="#000099">
<a href="<?php echo $upload_location."/".$upload_name; ?>" target="_blank"> 
	<strong><?php echo $title; ?></strong></a><br />
	<em><?php echo $authors; ?></em>
	<?php if($upload_podcast != ''){
	?><br />Listen podcast to this article, <br />
	<embed type="application/x-shockwave-flash" flashvars="audioUrl=<?php echo $upload_location."/".$upload_podcast;?>" src="http://www.google.com/reader/ui/3523697345-audio-player.swf" width="400" height="27" quality="best"></embed>
	<?php
	$upload_podcast = '';
	}
	?>	
	
	</td>
    <td width="1%" bordercolor="#000099">&nbsp;</td>
  </tr>
<?php
} 
?> 
<tr>
    <td valign="top" bordercolor="#000099">&nbsp;</td>
    <td width="58%" valign="top" bordercolor="#000099">&nbsp;</td>
    <td width="39%" valign="top" bordercolor="#000099">&nbsp;</td>
    <td bordercolor="#000099">&nbsp;</td>
  </tr>
<?php
}
?>
</table>