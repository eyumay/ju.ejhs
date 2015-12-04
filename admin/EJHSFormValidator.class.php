<?php 

class EJHSFormValidator {

	public static function validatePDF($upload) 
	{
		
	}
	
	public static function validatePODCAST($upload)
	{
	}
	
	public static function validateJPEG($upload = NULL)
	{
		if(is_null($upload)) 
			return FALSE; 
		else 
		{
			if($upload["cover"]["error"] > 0)
			{
				return FALSE; 
			}
			else
			{
				if(($upload["cover"]["type"] == "image/pjpg") || 
					($upload["cover"]["type"] == "image/jpeg")|| ($upload["cover"]["type"] == "image/gif"))
					return TRUE;
				else
					return FALSE; 
			}	
		}	
	}
	
	public static function checkIfFileExists($upload_dir, $upload)
	{
		if (file_exists($upload_dir. $upload["cover"]["name"]))
			return TRUE;
		else
			return FALSE; 
	}
	public static function checkIfFileIsUploaded($file = NULL) 
	{	
		// bail if there were no upload forms
	   if(empty($file))
			return FALSE;
		
		if( !empty($file["cover"]['tmp_name']) && is_uploaded_file( $file["cover"]['tmp_name'] ))
			return true;
		
		// return false if no files were found
	   return false;
	}	
}
?> 
