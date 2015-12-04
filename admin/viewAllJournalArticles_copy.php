<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>E J H S | Recent Journals</title>
<link href="../style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
#customizeLink a{
			padding: 0px;
			border-bottom: 0px solid #CCC;
}
#customizeLink a:hover{
			background:none;
			background-color: #F5F5F5;
			color:#CC6714;
}

		 #subnavlist {
			margin-left: 10px;
			padding-left: 50px;
			list-style-type:circle;			
		}	
		#subnavlist a {
			display:block;
			padding: 0px;
			border-bottom:none;
			border:none;
		}
	 	#navigation p {
			border:none;
			margin-top:20px;
			margin-bottom:0px;
			padding:0px;
		}
		#content {
			width: 800px;
			margin-left: 50px;
			margin-right: 50px;
		}
		
		#content p {
			padding-bottom: 10px;
			border-bottom: 1px solid #DDDDDD; /* A faint grey line below the text */
		}
		

.style1 {
	color: #FFFFFF;
	font-weight: bold;
}
</style>
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

</head>
<body>
<div id="container">
	<div id="header">
	</div>
	<div id="searchEJHS" >
  <form name="form1" id="form1" method="get" action="../sphider/search.php?search=1">
          <label for="query" id="Label"> Search Ethiopian Journal of Health Sciences: </label>
		  <input type="text" name="query" value="" id="query"/>
          <input name="search" type="hidden" value="1" />
          <input class=" button" type="submit" name="go" value="GO" />
      </form>
  </div>
	<?php 
	include '../library/config.php';	
	include '../library/opendb.php';
		
	include '../menu_top_internal_links.php'; ?>
	<div id="content">
	  <h1>All Articles </h1>
	  <table width="798" border="0" align="center" cellspacing="0">
  <tr>
    <td colspan="2"><h2>
      <?php include 'viewVolInfo.php'; echo $volInfo; ?>
</h2></td>
    <td width="98" rowspan="5" valign="top">
	<?php 
	include 'viewCoverpage.php'; 
	?>
	<img style="float:right" src="<?php echo $cover;?>" alt="<?php echo $volInfo; ?>" width="120" height="155" /></td>
    <td width="36">&nbsp;</td>
  </tr>
  <tr>
    <td width="228"><h2>Editorials</h2></td>
    <td width="428">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><?php include 'viewAllJournal1Editorials.php' ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><h2>Original Articles </h2></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">
      <?php include 'viewAllJournal2OriginalArticles.php' ?>    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4"><h2>Brief Communications </h2></td>
    </tr>
  <tr>
    <td colspan="4"><?php include 'viewAllJournal3BriefCommunications.php' ?></td>
    </tr>
  <tr>
    <td><h2>Case Report</h2></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">
      <?php include 'viewAllJournal4CaseReport.php' ?>    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4"><h2>Review Articles </h2></td>
  </tr>
  <tr>
    <td colspan="4"><?php include 'viewAllJournal5ReviewArticles.php' ?></td>
  </tr>
  <tr>
    <td colspan="4"><h2>Book Review  </h2></td>
  </tr>
  <tr>
    <td colspan="4"><?php include 'viewAllJournal6BookReview.php' ?></td>
  </tr>
  
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
</table>

  </div>
	<div id="footer"> 
    <p>Copyright © Jimma University 2011, Research & Publications Office. All rights reserved.
	 This journal or any parts cannot be reproduced in any form without written permission from the University.</p>
  </div>
</div>
</body>
</html>
