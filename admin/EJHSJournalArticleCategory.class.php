<?php 

class EJHSJournalArticleCategory {
	public static function queryAll()
	{
		$category = array(); 
		## Create query
		$cResult = mysql_query("select * from journal_article_category");
		if(!$cResult){
			echo "Error on DB".mysql_error();
		}
		else
		{
			while($row = mysql_fetch_array($cResult, MYSQL_ASSOC))
			{
				$category[$row['id']] = $row['name'];
			}
		}	
		if($category != '')
			return $category;
		else
			return NULL; 	
	
	}
	
	public static function queryOne($ID = NULL)
	{
		$categoryName = ''; 
		if(!is_null($ID))
		{
			## Create query
			$cResult = mysql_query("select * from journal_article_category WHERE id = '$ID'");
			if(!$cResult){
				echo "Error on DB".mysql_error();
			}
			else
			{
				$row = mysql_fetch_array($cResult, MYSQL_ASSOC);
				$categoryName = $row['name'];
			}	
			if($categoryName != '')
				return $categoryName;
			else
				return NULL; 
			}
		else
			return NULL; 		
	}
}
?> 
