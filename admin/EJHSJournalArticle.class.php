<?php 

class EJHSJournalArticle 
{

	public static function queryOneById($ID = NULL)
	{
		if(!is_null($ID))
		{
			## Create query
			$cResult = mysql_query("select * from journal_article WHERE id = '$ID'");
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

	public static function updateArticleUploadDir($uploadDir = NULL, $journalId = NULL)
	{
		if(!is_null($journalId) ) {
			$cResult = mysql_query("UPDATE journal_article SET upload_location='$uploadDir' WHERE journal_id = '$journalId'");	
			
			return $cResult; 	
		}
		else
			return FALSE; 
	}
	
	public static function checkIfExists($ID = NULL)
	{
		$result = EJHSJournalArticle::queryOneById($ID);			
		$count = 0;						
		while ($row = mysql_fetch_assoc($result)) {
			$count++; 
		} 

		
		if($count == 0 )
			return FALSE;
		else
			return TRUE;			
	}
	
	
	public static function getArticlesUnderJournal($ID = NULL)
	{
		if(!is_null($ID))
		{
			## Create query
			$cResult = mysql_query("select * from journal_article WHERE journal_id = '$ID'");
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
	
	public static function deleteUpload($upload_dir = NULL, $fileName = NULL)
	{		
		$file_to_delete = $upload_dir.'/'.$fileName;
		unlink($file_to_delete);	
	}
	
	public static function checkIfUploadExists($upload_dir = NULL, $fileName = NULL)
	{		
		$fileExists	= FALSE; 
        $dirHandle = opendir($upload_dir);
		

        while ($file = readdir($dirHandle)) {

            if($file == $fileName) {
                $fileExists = TRUE;
            }
        }

        closedir($dirHandle);
		
		if($fileExists)
			return TRUE;
		else
			return FALSE;	
	}	
	
	public static function deleteById( $ID = NULL)
	{
		$result			= EJHSJournalArticle::queryOneById($ID); 
		$row			= mysql_fetch_array($result, MYSQL_ASSOC);				
		
		EJHSJournalArticle::deleteUpload($row['upload_location'], $row['upload_name']); 		
		if(EJHSJournalArticle::checkIfUploadExists($row['upload_location'], $row['upload_name']) )
			EJHSJournalArticle::deleteUpload($row['upload_location'], $row['upload_name']); 
		
		$query = "DELETE FROM journal_article WHERE id = '$ID'";
		$result = mysql_query($query) or die('Error : ' . mysql_error());		
		
		if($result)
			return TRUE;
		else
			return FALSE; 				
	}	
	
}
?> 
