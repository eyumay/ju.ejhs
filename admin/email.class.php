<?php
class Email {
	var $error;
	var $success;
	var $first_name;
	var $last_name;
	var $title;
	var $email;
	var $is_activated;
	var $empty_fields;
	var $query;

	
	function Email(){
		mysql_connect('localhost', 'ejhs2', '1234@#$QWER');
		mysql_select_db('ejhs2');
	}
	
	public function setFirstName($fname){
		$this->first_name = $fname;	
	}
	public function setLastName($lname){
		$this->last_name = $lname;	
	}	
	
	public function setTitle($ttl){
		$this->title = $ttl;
	}
	
	public function setEmail($mail){
		$this->email =  $mail;
	}
	
	public function toggleActivatedDisactivated() {
		
	}
	
	public function showActivatedDisactivated($bool) {
		if($bool == 0)
			return "Disactivated";
		else 
			return "Activated";
	}
	
	public function setIsActivated($bool){
		$this->is_activated = $bool;
	}
	
	public function save($query_save){ 
		$result = mysql_query($query_save);		 
		if($result)
		 	$this->success = "Success";
		else
			$this->error = "Error ocurred ".mysql_error();
	}
	
	public function  getFirstName(){
		return $this->first_name;
	}
	
	function getLastName(){
		return $this->last_name;
	}	
	
	public function getTitle(){
		return $this->title;
	}
	
	public function getEmail(){
		return $this->email;
	}
	
	public function getIsActivated(){
		if($this->is_activated == 1)
			return $this->is_activated;
		else 
			return 0;	
	}
	
	public function isValidEmail($email){
      $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$";     
      if (eregi($pattern, $email)){
         return true;
      }
      else {
         return false;
      }   
	}
	
	public function checkEmpty(){
		if($this->getFirstName() == "")
			$this->empty_fields =  "First Name Should be Entered <br />";
		if($this->getLastName() == "")
			$this->empty_fields .=  "Last Name Should be Entered <br />";
		if($this->getEmail() == "")
			$this->empty_fields .=  "Valid email address is required <br />";	
		if(!$this->isValidEmail($this->getEmail()))
			$this->empty_fields .=  "Valid email address is required:  Example,  something@example.com<br />";			
	}
	
	public function update() {
	
	}
	
	public function display($query) {
		$result = mysql_query($query);
		return $result;
	}
	public function fetchRow($query) {
		$result = mysql_query($query);
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		$this->setFirstName($row['first_name']);
		$this->setLastName($row['last_name']);
		$this->setEmail($row['email_address']);
		$this->setTitle($row['title']);
		$this->setIsActivated($row['isActivated']);	
	}
	
	public function fetchRows($query) {
		$result = mysql_query($query);
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		return $row;
	}
	public function sendEmail($textmsg, $subject){
		$query_sub = "SELECT * from subscribers";
		$result = mysql_query($query_sub) or die(mysql_error());			
		while($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			//text message
			//replace
			$search = array("#MR#", "#FIRSTNAME#", "#LASTNAME#");
			
			if(($row['first_name'] == "") and ($row['last_name'] == ""))
				$replace = array("", "Sir / ", "Madam");
			else
				$replace = array($row['Title'], $row['first_name'], $row['last_name']);
			//Display 
			$msg = str_replace($search, $replace, $textmsg);
			//send msg
			
			$headers = 'From: ejhs@ju.edu.et' . "\r\n" .
    				   'Reply-To: ejhs.ju.edu.et' . "\r\n" .
   					   'MIME-Version: 1.0' . "\r\n".
					   'Content-type: text/html; charset=iso-8859-1' . "\r\n";

			$to = $row['email_address'];
			
			//mail($to, $subject, $msg, $headers);
			
			echo $subject." ". $msg;
		}
	}
}	
?>
