		  <?php
class Pager 
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
    $limit = 2; 
    $result = mysql_query("select count(*) from news"); 
    $total = mysql_result($result, 0, 0); 

    // work out the pager values 
    $pager  = Pager::getPagerData($total, $limit, $page); 
    $offset = $pager->offset; 
    $limit  = $pager->limit; 
    $page   = $pager->page; 

    // use pager values to fetch data 
	$is_published="1";
    $query = "select * from journal WHERE is_published = '$is_published' order by id desc limit $offset, $limit"; 
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
	  <?php echo $row['description']." "; ?><a href="journal_detail.php?id=<?php echo $row['id'];?>"> 
	  ... Read More </a></p>
          <?php
	}
	}
?>