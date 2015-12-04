<?php	
if(isset($_POST['upload_news']))
{

	$errmsg = '';
	// Get data
	$title = $_POST['title'];
	$content = $_POST['content'];
	$is_published = $_POST['is_published'];
	
	// Stripe white spaces
	$title  = trim($title);
	$content  = trim($content);
	
	// Add slashes
	$title  = addslashes($title);
	$content  = addslashes($content);
	
	
	// Check for error
	if($title == '')
	{
		echo " Empty field : Title <br />";
		$errmsg = " Empty field : Title <br />";
	}
	if($content == '')
	{
		echo " Empty field : Content<br />";
		$errmsg = " Empty field : Title <br />";
	}
	if($module == 'null')
	{
		echo " Empty field : Module<br />";
		$errmsg = " Empty field : Title <br />";
	}

	if($errmsg == '')
	{
		// Generate a system date.
		$date	= date("Y-m-d");
		// From session
		$faculty	= $_SESSION['faculty'];
		$department	= $_SESSION['department'];

		$query = "insert into news (title, content, is_published, created_at, updated_at) values ('$title', '$content', '$is_published', '$date', '$date')";
		if(!mysql_query($query))
		{
			echo "an error occured". mysql_error();
		}
		else
		{
			$msg = "You have added one Subarticle <br />";
			//$msg .= "Faculty Name : $fname<br />";
			echo $msg;
		}
		
	}
}
?>