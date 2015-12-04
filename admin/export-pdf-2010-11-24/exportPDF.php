<?php
	error_reporting(E_ALL ^ E_NOTICE);
	include_once('exportPDF.class.php');
	$oPDF = new ExportPDF();

	$arr = array(
		array('emp_id' => 1, 'emp_name' => 'Allyne', 'dept_name' => 'HumanResource'),
		array('emp_id' => 5, 'emp_name' => 'Philippes', 'dept_name' => 'HumanResource'),
		array('emp_id' => 9, 'emp_name' => 'Nicholas Swabber', 'dept_name' => 'HumanResource'),
		array('emp_id' => 2, 'emp_name' => 'Thomas Parre', 'dept_name' => 'Engineering'),
		array('emp_id' => 6, 'emp_name' => 'Iohn Brocke', 'dept_name' => 'Engineering'),
		array('emp_id' => 3, 'emp_name' => 'Michel Polyson', 'dept_name' => 'QualityAnalyst'),
		array('emp_id' => 7, 'emp_name' => 'Randall Mayne', 'dept_name' => 'QualityAnalyst'),
		array('emp_id' => 10, 'emp_name' => 'Haunce Walters', 'dept_name' => 'QualityAnalyst'),
		array('emp_id' => 4, 'emp_name' => 'William Farthowe', 'dept_name' => 'Database'),
		array('emp_id' => 8, 'emp_name' => 'Charles Stevenson', 'dept_name' => 'Database'),
		array('emp_id' => 11, 'emp_name' => 'John Arundell', 'dept_name' => 'Sales')
	) ;
	//print_r($arr);
	$labels = 'EmployeeID, EmployeeName'; 
	$fields = array('emp_id', 'emp_name');
	$title = 'Employees and Departments';
	$glabel = array('Department');
	$gfield = 'dept_name';
	$oPDF->PDFExport($arr, $labels, $fields, $title, $glabel, $gfield);
?>
