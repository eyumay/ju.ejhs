		  <?php
//include the library files that opens and closes the database
include 'library/config.php'; 
include 'library/opendb.php'; 
	
class Pager_podcast 
{ 
	function getPagerData($numHits, $limit, $page) 
    { 
    	$numHits  = (int) $numHits; 
        $limit    = max((int) $limit, 1); 
        $page     = (int) $page; 
        $numPages = ceil($numHits / $limit); 

        $page = max($page, 1); 
        $page = min($page, $numPages); 

        $offset = ($page - 1) * $limit; 

        $ret = new stdClass; 

        $ret->offset   = $offset; 
        $ret->limit    = $limit; 
        $ret->numPages = $numPages; 
        $ret->page     = $page; 
		
		

        return $ret; 
	} 
} 
	
	
    $page = $_GET['page']; 
    $limit = 7; 
    $result = mysql_query("select count(*) from podcast"); 
    $total = mysql_result($result, 0, 0); 

    // work out the pager values 
    $pager  = Pager_podcast::getPagerData($total, $limit, $page); 
    $offset = $pager->offset; 
    $limit  = $pager->limit; 
    $page   = $pager->page; 

    // use pager values to fetch data 
	$is_published="1";
    $query = "select * from podcast WHERE is_published = '$is_published' order by id desc limit $offset, $limit"; 
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
	  <p>        
	  <?php echo $row['title']." "; ?><a href="../podcasts/podcast_description.php?id=<?php echo $row['id'];?>"> 
	  ... Read More on Description  </a> | <a href="../podcasts/uploads/download.php?type=<?php echo $row['file_mime_type'];?>&amp;filename=<?php echo $row['file_name']; ?>">Download</a></p>
          <?php
	}
	}
include 'library/closedb.php';	
?>