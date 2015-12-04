  						<?php
						include 'library/config.php';
						include 'library/opendb.php';
						$journal_id = $_GET['journal_id'];
						$query =  "select * from journal where id = '$journal_id'";
						$result_cover = mysql_query($query);
						if(!$result_cover)
						{
							echo "An error occured".mysql_error();
						}
						else
						{
							$rowcover = mysql_fetch_array($result_cover,MYSQL_ASSOC);
							$upload_dir = $rowcover['upload_dir'];
							$coverpage_location = $rowcover['coverpage_location']; 
							$cover = $upload_dir."/".$coverpage_location;
						}
						?>