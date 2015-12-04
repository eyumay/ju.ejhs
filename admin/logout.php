<?php 
	session_start();
	$_SESSION['username'] = '';
	$_SESSION['user_is_logged_in'] = '';
	$_SESSION['first_name'] = '';
	$_SESSION['last_name'] = '';
	$_SESSION['privilege'] = '';
	header("Location:login.php");

?>