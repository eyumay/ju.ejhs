<!DOCTYPE html>
<html dir="ltr" lang="en-US"><head><!-- Created by Artisteer v4.0.0.58475 -->
    <meta charset="utf-8">
    <title>EJHS | Ethiopian Journal of Health Sciences </title>
    <meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">

    <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <link rel="stylesheet" href="../style.css" media="screen">
    <!--[if lte IE 7]><link rel="stylesheet" href="style.ie7.css" media="screen" /><![endif]-->
    <link rel="stylesheet" href="../style.responsive.css" media="all">


    <script src="jquery.js"></script>
    <script src="script.js"></script>
    <script src="script.responsive.js"></script>
	<script type="text/javascript">
	
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-24694861-1']);
	  _gaq.push(['_trackPageview']);
	
	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	
	</script>
	<script type="text/javascript">
	<!--
	function calcHeight()
	{
	  //find the height of the internal page
	  var the_height=
		document.getElementById('the_iframe').contentWindow.
		  document.body.scrollHeight;
	
	  //change the height of the iframe
	  document.getElementById('the_iframe').height=
		  the_height;
	}
	//-->
	</script>	

</head>
<body>
<div id="art-main">
 <div class="art-layout-cell art-content clearfix"><article class="art-post art-article">
 
<?php 
$artId = $_GET['artId']; 

if($artId != '')
{
	include 'library/config.php';
	include 'library/opendb.php';
	$query =  "SElECT * from journal_article WHERE id = '$artId'";
	$result = mysql_query($query);
	
	if(!$result)
	{
		echo "An error occured".mysql_error();
	}
	else
	{
		$row = mysql_fetch_array($result);
		$journal_id = $row['journal_id'];
		
		$queryJournal =  "SElECT * from journal WHERE id= '$journal_id'";	
		$resultJournal = mysql_query($queryJournal);	
			
		if(mysql_num_rows($resultJournal)>0)
		{
			$rowJournal = mysql_fetch_array($resultJournal);
			$title	= $row['title'];
			$abstract = $row['abstract'];
			$id		= $row['id'];	
			$authors = $row['authors'];
			$upload_location = $row['upload_location'];
			$upload_name = $row['upload_name'];
			$curCategory = $row['category'];
			
?>
			<?php 
				$abstract = str_replace("\"","", $abstract);
				$string = str_replace("\\","\"", $abstract); 
				echo $abstract; 
			?>			
<?php 			
		}
		else
		{
			echo "Journal does not exist!";
		}
	}
}
else
{
	echo "Invalid Request Attempted!";
}
?>
		<a href="download.php?filename=<?php echo $upload_location."/".$upload_name; ?>&type=application/pdf" target="_blank">Download Full Article </a> 
</div>
</div>
</body>

</html>		