		  <?php
//include the library files that opens and closes the database
include 'library/config.php'; 
include 'library/opendb.php'; 

    // use pager values to fetch data 
	$is_published="1";
	$id = $_GET['id'];
    $query = "select * from podcast WHERE id = '$id'"; 
    $result = mysql_query($query); 

//check if the id is set
	if($result=="")
	{
	?>
	       <p> No News </p>
	      <?php 
	}
	else{
	while($row = mysql_fetch_array($result, MYSQL_ASSOC))
	{
?> 
	  <h2> <?php echo stripslashes($row['title']); ?> </h2>
	  <p style="border:0px solid #FFFFFF;">        
	  <?php echo stripslashes($row['description']); ?></p>
<p> <strong> This journal </strong><br>

    <a href="../podcasts/uploads/download.php?type=<?php echo $row['file_mime_type'];?>&amp;filename=<?php echo $row['file_name']; ?>">Download</a> | <a href="<?php echo $_SERVER['PHP_SELF']; ?>?file_name=<?php echo $row['file_name']; ?>&embed=yes&id=<?php echo $_GET['id']; ?>">Play in Browser</a>
  <?php
	}
	}
include 'library/closedb.php';	
?>
