<?php 
$artId = $_GET['artId'];
?>
<!DOCTYPE html>
<html dir="ltr" lang="en-US"><head><!-- Created by Artisteer v4.0.0.58475 -->
    <meta charset="utf-8">
    <title>EJHS | Ethiopian Journal of Health Sciences </title>
    <meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width"> <link rel="icon" type="image/png" href="../images/favicon.png" /> <link rel="icon" type="image/png" href="../images/favicon.png" /> <link rel="icon" type="image/png" href="../images/favicon.png" />

    <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <link rel="stylesheet" href="style.css" media="screen">
    <!--[if lte IE 7]><link rel="stylesheet" href="style.ie7.css" media="screen" /><![endif]-->
    <link rel="stylesheet" href="style.responsive.css" media="all">


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
<iframe id="the_iframe" onLoad="calcHeight();" style="border: medium none" marginWidth="1" marginHeight="0" frameBorder="0" width="100%" align="left" src="../admin/abstract-detail.php?artId=<?php echo $artId; ?> " scrolling="No" allowtransparency="true"></iframe>
</div>
</div>
</body>

</html>						