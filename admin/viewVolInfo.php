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
							$vol = $rowcover['volume'];
							$num = $rowcover['num']; 
							$month = $rowcover['month'];
							$year = $rowcover['year'];
							$issn = $rowcover['issn'];
							
							if($month == 1){
								$nextMonth = "Jan";
							}
							elseif($month == 2){
								$nextMonth = "Feb";
							}
																					
							elseif($month == 3){
								$nextMonth = "Mar";
							}
							elseif($month == 4){
								$nextMonth = "April";
							}
							elseif($month == 5){
								$nextMonth = "May";
							}
							elseif($month == 6){
								$nextMonth = "June";
							}																					
							elseif($month == 7){
								$nextMonth ="Jul";
							}
							elseif($month == 8){
								$nextMonth = "August - Special Edition";
								$volInfo = "Volume ".$vol;
							}
							elseif($month == 9){
								$nextMonth = "Sep";
							}
							elseif($month == 10){
								$nextMonth = "Oct";
							}
							elseif($month == 11){
								$nextMonth = "Nov";
							}																												
							else {
								$nextMonth = "Dec";
							}
							$volInfo = $nextMonth.", ".$year;
							$volInfo .= " Vol.".$vol." No.".$num . " ISSN". $issn."<br />";
						}
						?>