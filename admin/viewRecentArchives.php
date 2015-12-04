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
	font-family: Lucida Grande, Tahoma, Arial, Helvetica, sans-serif; /* Lucida Grande for the Macs, Tahoma for the PCs */
	}
/* CONTAINER */

		#container {
			width: 100%;
			margin: 0 auto;
			font-family: Lucida Grande, Tahoma, Arial, Helvetica, sans-serif; /* Lucida Grande for the Macs, Tahoma for the PCs */
			font-size: 11px;
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

#container div.center {
margin-left:40px;
}

-->
</style>

</head>

<body>
<div id="container">
<?php
	include 'library/config.php';
	include 'library/opendb.php';
	$prevyear = null; 
	$query =  "SELECT * FROM journal WHERE is_activated = 'activated' GROUP BY year DESC, volume, num DESC";
	$result = mysql_query($query);
	if(!$result)
	{
		echo "An error occured".mysql_error();
	}
	else
	{
		while($row = mysql_fetch_array($result,MYSQL_ASSOC))
		{
			$vol	= $row['volume'];
			$month = $row['month'];
			$num = 	$row['num'];
			$id		= $row['id'];	
			$curryear = $row['year'];
			switch($month){
				case 1: 
					$monthName = "January";
					break;
				case 2: 
					$monthName = "February";
					break;				
				case 3: 
					$monthName = "March";
					break;
				case 4: 
					$monthName = "April";
					break;
				case 5: 
					$monthName = "May";
					break;															
				case 6: 
					$monthName = "June";
					break;					
				case 7: 
					$monthName = "July";
					break;
				case 8: 
					$monthName = "August";
					$num = " - Special Edition ";
					break;					
				case 9: 
					$monthName = "September";
					break;
				case 10: 
					$monthName = "October";
					break;															
				case 11: 
					$monthName = "November";
					break;
				case 12: 
					$monthName = "December";		
					break;	
				default:
					break;	
			}
			if($curryear !== $prevyear){																
?>	

  
 
    <div class="center"> <?php echo $curryear; ?> </div>
 
  <?php } ?>
  <div class="center"> 
  <a href="viewAllJournalArticles.php?journal_id=<?php echo $id; ?>" target="_parent">Volume <?php echo $vol; ?>, Number <?php echo $num. " " . $monthName. " " . $curryear; ?> </a> </div>

<?php
$prevyear = $curryear;
}

}
?>

</div>
</body>
</html>