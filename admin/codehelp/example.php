<?php
include_once('db.php');
    $model = new PersonModel();
    
    if (isset($submit)) {
       $model->setData($id, $name, $addr, $country, $status);
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
    
    include('header.php');
    echo '<p><font class=title>Person</font></p>';

    if (isset($find)) {
        $result=$model->result($find);
        $view = new PersonView(&$model);
        $view->browse($result);
        die();
    }
    
    if(isset($id))$model->select($id);
    $view = new PersonView(&$model);
    
    if (!isset($action)) $action=0;
  	$view->edit($action);
    

class PersonModel {
var $id;
var $name;
var $addr;
var $country;
var $status;

 
function setData($id, $name, $addr, $country, $status) {
   $this->id = strtoupper($id);
   $this->name = strtoupper($name);
   $this->addr = strtoupper($addr);
   $this->country = strtoupper($country);
   $this->status = strtoupper($status);

}

function select($id) { 
   $this->id = $id; 
   $sql = 'SELECT * FROM person WHERE p_id= "'.$this->id.'"';
   $result = mysql_query($sql);
   $row = mysql_fetch_array($result);
   $this->name = $row['p_name'];
   $this->addr = $row['p_addr'];
   $this->country = $row['p_country'];
   $this->status = $row['p_status'];

}

function insert() { 
   $sql = 'INSERT INTO person SET 
   p_id = "'.$this->id.'", 
   p_name = "'.$this->name.'", 
   p_addr = "'.$this->addr.'", 
   p_country = "'.$this->country.'", 
   p_status = "'.$this->status.'"';
   mysql_query($sql);
   $this->id = mysql_insert_id();
}

function update() { 
   $sql = 'UPDATE person SET 
   p_name= "'.$this->name.'", 
   p_addr= "'.$this->addr.'", 
   p_country= "'.$this->country.'", 
   p_status= "'.$this->status.'" 
   WHERE p_id = "'.$this->id.'"';
   mysql_query($sql);
}

function result($find) { 
   $sql = 'SELECT * FROM person ';
   if ($find>'')$sql.='WHERE p_name LIKE "'.$find.'%"';
   $result=mysql_query($sql);
   return $result;}

}

class PersonView {
var $model;

 
function PersonView($model) { 
   $this->model = $model;
}

function show() { 
   echo '<a href='.$PHP_SELF.'?id='.$this->model->id.'&action=1>Editar</a> | 
   <a href='.$PHP_SELF.'?action=2>Nuevo </a>
   <table border="0" cellspacing="1" width="50%">
   <tr><th colspan=2>Person</th></tr>
   <tr><td>Id</td><td>'.$this->model->id.'</td></tr>
   <tr><td>Name</td><td>'.$this->model->name.'</td></tr>
   <tr><td>Address</td><td>'.$this->model->addr.'</td></tr>
   <tr><td>Country</td><td>'.$this->model->country.'</td></tr>
   <tr><td>Status</td><td>'.$this->model->status.'</td></tr>
   </table>';
}

function edit($action) { 
   echo '<form method=post action='.$PHP_SELF.'>
   <input type=text name=find> <input type=submit name=search value=Buscar>
   </form>
   <form method=post action='.$PHP_SELF.'?action='.$action.'>
   <input type=hidden name=id value='.$this->model->id.'>
   <input type=submit name=submit value=Guardar>
   <table border="0" cellspacing="1" width="50%">
   <tr><th colspan=2>Person</th></tr>
   <tr><td>Id</td><td>'.$this->model->id.'</td></tr>
   <tr><td>Name</td><td>
      <input type=text name=name value="'.$this->model->name.'"></td></tr>
   <tr><td>Address</td><td>
      <input type=text name=addr value="'.$this->model->addr.'"></td></tr>
   <tr><td>Country</td><td>
      <input type=text name=country value="'.$this->model->country.'"></td></tr>
   <tr><td>Status</td><td>
      <input type=text name=status value="'.$this->model->status.'"></td></tr>
   </table></form>';
}
function browse($result) { 
   echo '<table border="0" cellspacing="1" width="100%">
   &lttr>
   <th>Name</th>
   <th>Address</th>
   <th>Country</th>
   <th>Status</th>
   </tr>';
   while ($row=mysql_fetch_array($result)) {
      echo '<tr><td><a href='.$PHP_SELF.'?id='.$row['p_id'].'&action=1>'.$row['p_name'].'</a></td>
      <td>'.$row['p_addr'].'</td>
      <td>'.$row['p_country'].'</td>
      <td>'.$row['p_status'].'</td>';
   }
   echo '</table>';
}
}
?>
