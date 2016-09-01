<?php

/**************************************************
*
*    Users Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	name - VARCHAR
*	username - VARCHAR
*	password - CHAR
*	creation_date - DATETIME
*	last_login - TIMESTAMP
*	admin - TINYINT
*
**************************************************/
require_once("query.php");

class Users {

//DB Interaction variables
private var $db=NULL;
private var $table="users";

//Data storage variables
public var $id=NULL;
public var $name=NULL;
public var $username=NULL;
public var $password=NULL;
public var $creation_date=NULL;
public var $last_login=NULL;
public var $admin=NULL;

//List of variables for sanitization
private var $varlist = array(
	"name"=>"filterName",
	"username"=>"filterUsername",
	"password"=>"filterPassword",
	"creation_date"=>"filterCreationDate",
	"last_login"=>"filterLastLogin",
	"admin"=>"filterAdmin");

/***************************************************

Constructor & Destructor

***************************************************/
public function __construct(){
    $this->db = Query::getInstance();
}

public function __destruct(){}


/**************************************************

Commit (Insert/Update) to DB Function(s)

**************************************************/
public function commit(){

    if($this->filterId($this->id)){
        return $this->updateRow();
    } else {
        return $this->insertRow();
    }
}

private function insertRow(){

    //Check for good data, first
    foreach($varlist as $vname=>$valFn){
        if(!$this->$valFn($this->$vname)) return false;
    }

    //Create the array of variables names and value calls
    $c_names = "";
    $v_calls = "";
    $values = array();
    foreach(array_keys($varlist) as $v){
        $c_names .= "$v";
        $v_calls .= ":$v";
        $values[":$v"] = $this->$v;

        if($v != end(array_keys($varlist)){
            $c_names .= ", ";
            $v_calls .= ", ";
        }
    }

    //Build the query
    $sql = "INSERT INTO $this->table ($c_names) VALUES ($v_calls)";

    return $this->db->insert($sql, $values);
}

private function updateRow(){

    //Check for good data, first
    foreach($varlist as $vname=>$valFn){
        if(!$this->$valFn($this->$vname)) return false;
    }

    //Create the array of variables names and value calls
    $c_str = "";
    $values = array(":id"=>$this->id);
    foreach(array_keys($varlist) as $v){
        $c_str .= "$v=:$v";
        $values[":$v"] = $this->$v;

        if($v != end(array_keys($varlist)){
            $c_str .= ", ";
        }
    }

    //Build the query
    $sql = "UPDATE $this->table SET $c_str WHERE id=:id";

    return $this->db->update($sql, $values);
}

/**************************************************

Delete Functions

**************************************************/
public function deleteByColumns($columns){

    //Create the values array
    $values = array();
    foreach($columns as $c=>$v){
        $values[":".$c]=$v;
    }

    //Create Query\n";
    $sql = "DELETE FROM $this->table WHERE ";
    $keys = array_keys($columns);
    foreach($keys as $column){
        $sql.= "$column=:$column";
        if(strcmp($column, end($keys))){
            $sql.= " AND ";
        }
    }

    return $this->db->delete($sql, $values);
}

public function deleteById($id){
    return $this->deleteByColumns(array("id"=>$id));
}

public function delete(){
    if($this->id) return $this->deleteById($this->id);

    return false;
}


/**************************************************

Query Functions

**************************************************/
public function getAll(){

    //Generate the query
    $sql = "SELECT * FROM $this->table";

    return $this->db->query($sql, array());
}

public function queryByColumns($columns){

    //Values Array
    $values = array();
    foreach($columns as $column=>$value){
        $values[":".$column]=$value;
    }

    //Generate the query
    $sql = "SELECT * FROM $this->table WHERE ";
    $keys = array_keys($columns);
    foreach($keys as $column){
        $sql.= "$column=:$column";
        if(strcmp($column, end($keys))){
            $sql.= " AND ";
        }
    }

    return $this->db->query($sql, $values);
}


public function getById($id){
	
    //Validate Inputs
    $id = $this->filterId($id); if($id === false){return false;}

    return Users::fromArray($this->queryByColumns(array("id"=>$id)));
}

public function getByName($name){
	
    //Validate Inputs
    $name = $this->filterName($name); if($name === false){return false;}

    return Users::fromArray($this->queryByColumns(array("name"=>$name)));
}

public function getByUsername($username){
	
    //Validate Inputs
    $username = $this->filterUsername($username); if($username === false){return false;}

    return Users::fromArray($this->queryByColumns(array("username"=>$username)));
}

public function getByPassword($password){
	
    //Validate Inputs
    $password = $this->filterPassword($password); if($password === false){return false;}

    return Users::fromArray($this->queryByColumns(array("password"=>$password)));
}

public function getByCreationDate($creation_date){
	
    //Validate Inputs
    $creation_date = $this->filterCreationDate($creation_date); if($creation_date === false){return false;}

    return Users::fromArray($this->queryByColumns(array("creation_date"=>$creation_date)));
}

public function getByLastLogin($last_login){
	
    //Validate Inputs
    $last_login = $this->filterLastLogin($last_login); if($last_login === false){return false;}

    return Users::fromArray($this->queryByColumns(array("last_login"=>$last_login)));
}

public function getByAdmin($admin){
	
    //Validate Inputs
    $admin = $this->filterAdmin($admin); if($admin === false){return false;}

    return Users::fromArray($this->queryByColumns(array("admin"=>$admin)));
}

public static function fromArray($array){

    $output = new array();

    foreach($array as $a){

        $new = new Users();
    
        if($array[id]) $new->id=$a[id];

        foreach($this->varlist as $v){
            $new->$v = $a[$v];
        }

        $output[] = $new;
    }

    return $output;
}


/**************************************************

Exists by Column(s) Function

**************************************************/
public function existsByColumns($columns){
    $results = $this->queryByColumns($columns);

    return count($results);
}


/**************************************************
 
Column Validation Function(s)

**************************************************/
function filterId($id){
    //Not allowed to be null
    if(Check::isNull($id)){
        echo "id cannot be null!"; return false;
    }

    if(Check::notInt($id)){
        echo "id was invalid!"; return false;
    }

    return intVal($id);
}



function filterName($name){
    //Allowed to be null, catch that first
    if(Check::isNull($name)){ return null; }

    if(Check::notString($name)){
        echo "name was invalid!"; return false;
    }

    return $name;
}



function filterUsername($username){
    //Not allowed to be null
    if(Check::isNull($username)){
        echo "username cannot be null!"; return false;
    }

    if(Check::notString($username)){
        echo "username was invalid!"; return false;
    }

    return $username;
}



function filterPassword($password){
    //Not allowed to be null
    if(Check::isNull($password)){
        echo "password cannot be null!"; return false;
    }

    if(Check::isNull($password)){
        echo "password was invalid!"; return false;
    }

    return $password;
}



function filterCreationDate($creation_date){
    //Not allowed to be null
    if(Check::isNull($creation_date)){
        echo "creation_date cannot be null!"; return false;
    }

    if(Check::isNull($creation_date)){
        echo "creation_date was invalid!"; return false;
    }

    return date("Y-m-d H:i:s", $creation_date);
}



function filterLastLogin($last_login){
    //Allowed to be null, catch that first
    if(Check::isNull($last_login)){ return null; }

    if(Check::isNull($last_login)){
        echo "last_login was invalid!"; return false;
    }

    return date("Y-m-d H:i:s", $last_login);
}



function filterAdmin($admin){
    //Not allowed to be null
    if(Check::isNull($admin)){
        echo "admin cannot be null!"; return false;
    }

    if(Check::notBool($admin)){
        echo "admin was invalid!"; return false;
    }

    return intVal($admin);
}



}//close class

?>
