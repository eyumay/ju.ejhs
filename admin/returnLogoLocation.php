  						<?php
						include 'library/config.php';
						include 'library/opendb.php';
						$query =  "select * from journal WHERE category = 'case' order by id desc";
						$result = mysql_query($query);
						if(!$result)
						{
							echo "An error occured".mysql_error();
						}
						else
						{
							while($row = mysql_fetch_array($result,MYSQL_ASSOC))
							{
								$title	= $row['title'];
								$id		= $row['id'];	
								$authors = $row['authors'];
								$journal_location = $row['journal_location']
					
						?>	
