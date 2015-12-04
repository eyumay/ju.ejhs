<?php 

class EJHSJournalForm {

	public static function  getYearFormChoices()
	{
		$year		= array();
		$start 		= 1950;
		$end		= date('Y'); 	
		
		for($i = $start; $i <= $end+20; $i++)
			$year[$i]	= $i;
			
		return $year; 
	}
	
	
	public static  function getVolumeFormChoices()
	{
		$choices	= array();
		$start 		= 1;
		$end		= EJHSJournal::getLastVolumeNumber(); 	
		
		for($i = $start; $i <= $end+10; $i++)
			$choices[$i]	= $i;
			
		return $choices; 	
	}
	
	public static  function getNumberFormChoices()
	{
		$choices	= array();
		$choices[0]	= "Special Edition"; 
		$start 		= 1;
		$end		= 30; 	
		
		for($i = $start; $i <= $end; $i++)
			$choices[$i]	= $i;
			
		return $choices; 		
	}
	
	public static function  getMonthFormChoices()
	{
		$choices	= array(); 
		
		$choices[1]		= 'January';
		$choices[2]		= 'February';
		$choices[3]		= 'March';
		$choices[4]		= 'April';
		$choices[5]		= 'May';
		$choices[6]		= 'June';
		$choices[7]		= 'July';
		$choices[8]		= 'August';
		$choices[9]		= 'September';
		$choices[10]	= 'October';
		$choices[11]	= 'November';
		$choices[12]	= 'December';	
		
		return $choices; 
	}			

}
?> 
