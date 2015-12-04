<?php

class codehelp {
    var $name;
    var $db;
	var $prefix;
    var $details = array();

	// Constructor
	function codehelp($name){
		$this->name = $name;
	}
	
	function setdb($db, $prefix) {
		$this->db = $db;
		$this->prefix = $prefix;
	}
	function addDetails ($var, $fieldtype, $pk, $display, $vartype ) {
		$this->details[] = array(
			"var"=>$var,
			"fieldtype"=>$fieldtype, 
			"pk"=>$pk, 
			"display"=>$display,
			"vartype"=>$vartype);		
	}
	
	// this is for testing only
	function debug(){
		print_r ($this->details);
	}

	//public
	function generateSQLSchema(){
		$echo = 'CREATE TABLE '.$this->db.' ( ';
		for ($x=0; $x<count($this->details); $x++){
			$echo .= $this->prefix.$this->details[$x]['var'].' '. $this->details[$x]['fieldtype'];
			if ($this->details[$x]['pk']==1){
				$echo .= ' NOT NULL AUTO_INCREMENT ';
				$pk .= $this->prefix.$this->details[$x]['var'].', ';
			}
			$echo .= ', ';
		}
		$pk = substr($pk,0,strlen($pk)-2);
		$echo .= 'PRIMARY KEY ('.$pk.'));';
		echo $echo;
	}
	
	
	// private function
	function generateShow(){
		$echo .= '&lt;a href=\'.$PHP_SELF.\'?id=\'.$this->model->id.\'&action=1&gt;Editar&lt;/a&gt; | <br>';
		$echo .= '   &lt;a href=\'.$PHP_SELF.\'?action=2&gt;Nuevo &lt;/a&gt;<br>';
		$echo .= '   &lt;table border="0" cellspacing="1" width="50%"&gt;<br>';
		$echo .= '   &lt;tr&gt;&lt;th colspan=2&gt;'.$this->name.'&lt;/th&gt;&lt;/tr&gt;<br>';
		for ($x=0; $x<count($this->details); $x++){
			$mark = strpos($this->details[$x]['vartype'], '+');
			$type = $mark > 0 ? substr($this->details[$x]['vartype'],0 ,$mark): $this->details[$x]['vartype'];
			switch ($type){
			case 'browse':
				$echo .= '   &lt;tr&gt;&lt;td&gt;'.$this->details[$x]['display'].'&lt;/td&gt;&lt;td&gt;f_busca($this->model->'.$this->details[$x]['var'].')&lt;/td&gt;&lt;/tr&gt;<br>';
    			break;
			case 'option':
				$echo .= '   &lt;tr&gt;&lt;td&gt;'.$this->details[$x]['display'].'&lt;/td&gt;&lt;td&gt;f_menu($this->model->'.$this->details[$x]['var'].')&lt;/td&gt;&lt;/tr&gt;<br>';
    			break;
			default:
				$echo .= '   &lt;tr&gt;&lt;td&gt;'.$this->details[$x]['display'].'&lt;/td&gt;&lt;td&gt;\'.$this->model->'.$this->details[$x]['var'].'.\'&lt;/td&gt;&lt;/tr&gt;<br>';
    			break;
   			}
		}
		$echo .= '   &lt;/table&gt;';
		return $echo;
	}
	// private function
	function generateEdit(){
        $echo .= '&lt;form method=post action=\'.$PHP_SELF.\'&gt;<br>';
        $echo .= '   &lt;input type=text name=find&gt; &lt;input type=submit name=search value=Buscar&gt;<br>';
        $echo .= '   &lt;/form&gt;<br>';
		$echo .= '   &lt;form method=post action=\'.$PHP_SELF.\'?action=\'.$action.\'&gt;<br>';
		$echo .= '   &lt;input type=hidden name=id value=\'.$this->model->id.\'&gt;<br>';
		$echo .= '   &lt;input type=submit name=submit value=Guardar&gt;<br>';
		$echo .= '   &lt;table border="0" cellspacing="1" width="50%"&gt;<br>';
		$echo .= '   &lt;tr&gt;&lt;th colspan=2&gt;'.$this->name.'&lt;/th&gt;&lt;/tr&gt;<br>';
		for ($x=0; $x<count($this->details); $x++){
			$mark = strpos($this->details[$x]['vartype'], '+');
			$type = $mark > 0 ? substr($this->details[$x]['vartype'],0 ,$mark): $this->details[$x]['vartype'];
			switch ($type){
			case 'no':
				$echo .= '   &lt;tr&gt;&lt;td&gt;'.$this->details[$x]['display'].'&lt;/td&gt;&lt;td&gt;\'.$this->model->'.$this->details[$x]['var'].'.\'&lt;/td&gt;&lt;/tr&gt;<br>';
				break;
			case 'browse':
    			$echo .= '   &lt;tr&gt;&lt;td&gt;'.$this->details[$x]['display'].'&lt;/td&gt;&lt;td&gt;
      &lt;input type=text name='.$this->details[$x]['var'].' value="\'.$this->model->'.$this->details[$x]['var'].'.\'"&gt; Buscar&lt;/td&gt;&lt;/tr&gt;<br>';
    			break;
			case 'option':
    			$echo .= '   &lt;tr&gt;&lt;td&gt;'.$this->details[$x]['display'].'&lt;/td&gt;&lt;td&gt;
      &lt;input type=text name='.$this->details[$x]['var'].' value="\'.$this->model->'.$this->details[$x]['var'].'.\'"&gt; Opcion&lt;/td&gt;&lt;/tr&gt;<br>';
    			break;
			default:
    			$echo .= '   &lt;tr&gt;&lt;td&gt;'.$this->details[$x]['display'].'&lt;/td&gt;&lt;td&gt;
      &lt;input type=text name='.$this->details[$x]['var'].' value="\'.$this->model->'.$this->details[$x]['var'].'.\'"&gt;&lt;/td&gt;&lt;/tr&gt;<br>';
    			break;
   			}
		}
		$echo .= '   &lt;/table&gt;&lt;/form&gt;';
		return $echo;
	}
	// private function
	function generateList() {
		$echo .='echo \'&lt;table border="0" cellspacing="1" width="100%"&gt;<br>';
		$echo .='   &lttr&gt<br>';
		for ($x=1; $x<count($this->details); $x++){
			$echo .= '   &lt;th&gt;'.$this->details[$x]['display'].'&lt;/th&gt;<br>';
		}
		$echo .='   &lt/tr&gt\';<br>';
		$echo .= '   while ($row=mysql_fetch_array($result)) {<br>';
		$echo .= '      echo \'&lt;tr&gt;&lt;td&gt;&lt;a href=\'.$PHP_SELF.\'?id=\'.$row[\''.$this->prefix.'id\'].\'&action=1&gt;';
		$echo .= '\'.$row[\''.$this->prefix.$this->details[1]['var'].'\'].\'&lt;/a&gt;&lt;/td&gt;';
		for ($x=2; $x<count($this->details); $x++){
			$echo .= '<br>      &lt;td&gt;\'.$row[\''.$this->prefix.$this->details[$x]['var'].'\'].\'&lt;/td&gt;';
		}
		$echo .= '\';<br>';
		$echo .= '   }<br>';
		$echo .='   echo \'&lt;/table&gt;\';';
		return $echo;
	}

	// private function
	function generateSqlSelect(){
		$echo = 'SELECT * FROM '.$this->db.' WHERE '.$this->prefix.$this->details[0]['var'].'= "\'.$this->id.\'"';
		return $echo;
	}
	// private function
	function generateSqlInsert(){
		$echo = 'INSERT INTO '.$this->db.' SET <br>';
		for ($x=0; $x<count($this->details); $x++){
			$echo .= '   '.$this->prefix.$this->details[$x]['var'].' = "\'.$this->'.$this->details[$x]['var'].'.\'", <br>';
		}
		$echo = substr($echo,0,strlen($echo)-6);
		return $echo;
	}
	// private function
	function generateSqlUpdate(){
		$echo = 'UPDATE '.$this->db.' SET <br>';
		for ($x=1; $x<count($this->details); $x++){
			$echo .= '   '.$this->prefix.$this->details[$x]['var'].'= "\'.$this->'.$this->details[$x]['var'].'.\'", <br>';
		}
		$echo = substr($echo,0,strlen($echo)-6);
		$echo .= ' <br>   WHERE '.$this->prefix.'id = "\'.$this->id.\'"';
		return $echo;
	}

	// public
	function generateModelClass(){
		$echo = '<pre><code>class '.$this->name.'Model {<br>';
		for ($x=0; $x<count($this->details); $x++){
			$echo .= 'var $'.$this->details[$x]['var'].';<br>';
		}
		$echo .= '<br> <br>';
		$echo .= 'function setData($id';
		for ($x=1; $x<count($this->details); $x++){
			$echo .= ', $'.$this->details[$x]['var'];
		}
		$echo .= ') {<br>';
		for ($x=0; $x<count($this->details); $x++){
			$echo .= '   $this->'.$this->details[$x]['var'].' = strtoupper($'.$this->details[$x]['var'].');<br>';
		}
		$echo .= '<br>';
		$echo .= '}<br><br>';

		$echo .= 'function select($id) { <br>';
		$echo .= '   $this->id = $id; <br>';
		$echo .= '   $sql = \''.$this->generateSqlSelect().'\';<br>';
		$echo .= '   $result = mysql_query($sql);<br>   $row = mysql_fetch_array($result);<br>';
		for ($x=1; $x<count($this->details); $x++){
			$echo .= '   $this->'.$this->details[$x]['var'].' = $row[\''.$this->prefix.$this->details[$x]['var'].'\'];<br>';
		}
		$echo .= '<br>';
		$echo .= '}<br><br>';
		$echo .= 'function insert() { <br>';
		$echo .= '   $sql = \''.$this->generateSqlInsert().'\';<br>';
		$echo .= '   mysql_query($sql);<br>   $this->id = mysql_insert_id();<br>';
		$echo .= '}<br><br>';
		$echo .= 'function update() { <br>';
		$echo .= '   $sql = \''.$this->generateSqlUpdate().'\';<br>';
		$echo .= '   mysql_query($sql);<br>';
		$echo .= '}<br><br>';
		$echo .= 'function result($find) { <br>';
		$echo .= '   $sql = \'SELECT * FROM '.$this->db.' \';<br>';
		$echo .= '   if ($find>\'\')$sql.=\'WHERE '.$this->prefix.$this->details[1]['var'].' LIKE "\'.$find.\'%"\';<br>';
		$echo .= '   $result=mysql_query($sql);<br>';
		$echo .= '   return $result;';
		$echo .= '}<br><br>';
		$echo .= '}</code></pre>';
		return $echo;
	}
	
	// public
	function generateViewClass(){
		$echo = '<pre><code>class '.$this->name.'View {<br>';
		$echo .= 'var $model;<br>';
		$echo .= '<br> <br>';
		$echo .= 'function '.$this->name.'View($model) { <br>';
		$echo .= '   $this->model = $model;<br>';
		$echo .= '}<br><br>';
		$echo .= 'function show() { <br>';
		$echo .= '   echo \''.$this->generateShow().'\';<br>';
		$echo .= '}<br><br>';
		$echo .= 'function edit($action) { <br>';
		$echo .= '   echo \''.$this->generateEdit().'\';<br>';
		$echo .= '}<br>';
		$echo .= 'function browse($result) { <br>';
		$echo .= '   '.$this->generateList().'<br>';
		$echo .= '}<br>';

		$echo .= '}</code></pre>';
		return $echo;
	}
	
	//public
	function generatePageController() {
		$echo = '<pre><code>include_once(\'db.php\');
    $model = new '.$this->name.'Model();
    
    if (isset($submit)) {
       $model->setData($id';
		for ($x=1; $x<count($this->details); $x++){
			$echo .= ', $'.$this->details[$x]['var'];
		}
	 	$echo.=');
    	switch ($action) {
    	case 1:
    		$model->update();
    	break;
    	default:
    		$model->insert();
    	break;
    	}
    	header("Location: $PHP_SELF?id=$model->id&action=1");
    }
    
    include(\'header.php\');
    echo \'&lt;p&gt;&lt;font class=title&gt;'.$this->name.'&lt;/font&gt;&lt;/p&gt;\';

    if (isset($find)) {
        $result=$model->result($find);
        $view = new '.$this->name.'View(&$model);
        $view->browse($result);
        die();
    }
    
    if(isset($id))$model->select($id);
    $view = new '.$this->name.'View(&$model);
    
    if (!isset($action)) $action=0;
  	$view->edit($action);
    </code></pre>';
		return $echo;
	}
}



?>