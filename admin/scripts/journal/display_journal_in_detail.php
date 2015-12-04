		  <?php
    // use pager values to fetch data 
	$is_published="1";
	$id = $_GET['id'];
    $query = "select * from journal WHERE id = '$id'"; 
    $result = mysql_query($query); 

//check if the id is set
	if($result=="")
	{
	?>
	       <p> No Journal </p>
	      <?php 
	}
	else{
	while($row = mysql_fetch_array($result, MYSQL_ASSOC))
	{
?> 
	  <h2> <?php echo $row['title']; ?> </h2>
	  <p>        
	  <?php echo $row['description']; ?></p>
	  <h2>This journal</h2>
	  <p> <a href="uploads/download.php?type=<?php echo $row['file_mime_type'];?>&filename=<?php echo $row['file_name']; ?>">Download</a></p>
          <?php
	}
	}
?>