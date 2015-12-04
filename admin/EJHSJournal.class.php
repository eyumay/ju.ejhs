<?php 

class EJHSJournal 
{

	public static function queryOneById($ID = NULL)
	{
		if(!is_null($ID))
		{
			## Create query
			$cResult = mysql_query("select * from journal WHERE id = '$ID'");
			if(!$cResult){
				echo "Error on DB".mysql_error();
			}
			else
			{
				//$row = mysql_fetch_array($cResult, MYSQL_ASSOC);
				return $cResult; 
			}
		}
		else
			return NULL; 		
	}
	
	public static function queryOneByVolNumYear($year = NULL, $vol = NULL, $num = NULL)
	{
		if( is_null($vol) || is_null($num) || is_null($year) )
			return NULL; 
		else
		{
			## Create query
			$cResult = mysql_query("select * from journal WHERE year = '$year' AND volume = '$vol' AND num = '$num' ");
			if(!$cResult)
				echo "Error on DB".mysql_error();
			else
				return $cResult; 
		}			 		
		
	}
	
	public static function queryOneByVolNum($vol = NULL, $num = NULL)
	{
		if( is_null($vol) || is_null($num) )
			return NULL; 
		else
		{
			## Create query
			$cResult = mysql_query("select * from journal WHERE volume = '$vol' AND num = '$num' ");
			if(!$cResult)
				echo "Error on DB - ".mysql_error();
			else
				return $cResult; 
		}			 		
		
	}	
	
	
	public static function checkIfExistsById( $ID = NULL )
	{
		$result = EJHSJournal::queryOneById($ID);
		
		##Count
		if(mysql_num_rows($result) == 1)
			return TRUE;
		else
			return FALSE; 
	}
	
	public static  function  checkIfExistsByVolNum($year = NULL, $vol = NULL, $num = NULL)
	{
		$result = EJHSJournal::queryOneByVolNumYear($year, $vol, $num);
		
		##Count
		if(mysql_num_rows($result) == 1)
			return TRUE;
		else
			return FALSE; 	
	}
	
	public static function getLastVolumeNumber()
	{
		$result	= mysql_query("SELECT * FROM journal ORDER BY volume DESC");					
		
		$row = mysql_fetch_array($result, MYSQL_ASSOC); 
		
		return $row['volume']; 
	}
	
	public static function insert($year_of_edition, $month, $vol, $num, $issn, $eissn, $upload_dir, $fileName)
	{
		$result = mysql_query("
		INSERT INTO journal (volume, num, month, year, issn, eissn, upload_dir, coverpage_location, created_at, updated_at) 
		VALUES ('$vol','$num','$month','$year_of_edition', '$issn', '$eissn', '$upload_dir', '$fileName','$date','$date')");
		
		return $result; 		
	}
	
	public static function upload($file = NULL, $upload_dir = NULL ) ## renames and uploads
	{
		if( is_null($upload_dir || is_null($file) ) )
			return FALSE;
		else {
			move_uploaded_file($file["cover"]["tmp_name"],  $upload_dir ."/". $file["cover"]["name"]);
			return TRUE; 
		}
	}
	
	public static function checkIfUploadLocationIsChanged($updateID, $new_upload_dir)
	{
		$row			= mysql_fetch_array(EJHSJournal::queryOneById($updateID), MYSQL_ASSOC); 		
		$old_upload_dir = $row['upload_dir'];
		
		if($old_upload_dir == $new_upload_dir)
			return FALSE;
		else
			return TRUE; 
	}
	
	public static function recursiveCopy($src,$dst) 
	{	    
	    @mkdir($dst);
		$dir = opendir($src);
	    while(false !== ( $file = readdir($dir)) ) {
	        if (( $file != '.' ) && ( $file != '..' )) {
	            if ( is_dir($src . '/' . $file) ) {
	                EJHSJournal::recursiveCopy($src.'/'.$file,$dst.'/'.$file);
	            }
	            else {
	                copy($src.'/'.$file,$dst.'/'.$file);
	            }
	        }
	    }
	    closedir($dir);
	}
	
	public static function getUploadDir($updateID = NULL)
	{
		$row			= mysql_fetch_array(EJHSJournal::queryOneById($updateID), MYSQL_ASSOC); 		
		
		return $row['upload_dir'];		
	}
	
	public static function deleteFiles($target) 
	{
		if(is_dir($target)){
			$files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned
			
			foreach( $files as $file )
			{
				EJHSJournal::deleteFiles( $file );      
			}
		  
			@rmdir( $target );
		} 
		elseif(is_file($target)) {
			unlink( $target );  
		}
	}
	
	
	public static function update($year_of_edition = NULL, $month = NULL, $vol = NULL, $num = NULL, $issn = NULL, $eissn = NULL, $new_upload_dir = NULL, $updateID = NULL, $coverName = NULL)
	{
		$date	= date('d-m-Y');
		
		$query	= "UPDATE journal SET ";
		if(!is_null($vol))
			$query	.= " volume='$vol', ";
		if(!is_null($num))
			$query	.= " num='$num', ";
		if(!is_null($month))
			$query	.= " month='$month', ";
		if(!is_null($year_of_edition))
			$query	.= " year='$year_of_edition', ";
		if(!is_null($issn))
			$query	.= " issn='$issn', ";
		if(!is_null($eissn))
			$query	.= " eissn='$eissn', ";				
		if(!is_null($new_upload_dir))
			$query	.= " upload_dir='$new_upload_dir',";														
		if(!is_null($coverName))
			$query	.= " coverpage_location='$coverName',";			
		    
		$query		.= " updated_at='$date' WHERE id = '$updateID' "		;
		
		if(!is_null($updateID)) {
			$result = mysql_query($query);
		
			return $result; 		
		}
		else
			return FALSE; 
	}	
	
	public static function renameAndUpload($file = NULL, $upload_dir = NULL, $newFileName = NULL )
	{
		$fullFileName = explode(".",$file["cover"]["name"]); 
		$ext =  $fullFileName[1]; 
		$coverPageName = $newFileName.'.'.$ext;
		//move_uploaded_file($_FILES["file"]["tmp_name"], "../img/imageDirectory/" . $newfilename;
		move_uploaded_file($file["cover"]["tmp_name"],  $upload_dir ."/". $coverPageName);
	}
	
	public static function checkIfJournalExists($vol = NULL, $num = NULL)	
	{
		$count = 0;
		$result		= mysql_query("select * from journal where volume='$vol' AND num='$num'");
						
		while ($row = mysql_fetch_assoc($result)) {
			$count++; 
		} 

		
		if($count == 0 )
			return FALSE;
		else
			return TRUE; 
	}
}
?> 
