  						<?php						
							$rowcover = mysql_fetch_array($result, MYSQL_ASSOC);	
							
							$vol = $rowcover['volume'];
							$num = $rowcover['num']; 
							$month = $rowcover['month'];
							$year = $rowcover['year'];
							$issn = $rowcover['issn'];
							$coverpage = $rowcover['coverpage_location'];
							
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
						?>