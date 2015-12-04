<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>E J H S | About Ethiopian Journal of Health Sciences</title>
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
	  <h2>Archived News </h2>
			<iframe id="NewsWindow0" style="border: medium none" name="I1" marginWidth="1" marginHeight="0" frameBorder="0" width="790" height="500" align="left" src="showEJHSNews.php" scrolling="no">
                        </iframe>
    </div>
	<div id="footer"> 
    <p>Copyright © Jimma University 2011, Research & Publications Office. All rights reserved.
	 This journal or any parts cannot be reproduced in any form without written permission from the University.</p>
  </div>
</div>
</body>
</html>
