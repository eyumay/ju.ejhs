		  <?php
    // use pager values to fetch data 
	$is_published="1";
	$id = $_GET['id'];
    $query = "select * from news WHERE id = '$id'"; 
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
	  <?php echo stripslashes($row['content']); ?></p>
          <?php
	}
	}
?>