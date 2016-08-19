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

var $db=NULL;
var $table="users";


/***************************************************

Constructor & Destructor

***************************************************/
public function __construct(){
    $this->db = Query::getInstance();
}

public function __destruct(){}


/**************************************************

Create Function

**************************************************/
public function create($name, $username, $password, $last_login, $admin){

	//Validate the inputs
	$name = $this->filterName($name); if($name === false){return false;}
	$username = $this->filterUsername($username); if($username === false){return false;}
	$password = $this->filterPassword($password); if($password === false){return false;}
	$last_login = $this->filterLastLogin($last_login); if($last_login === false){return false;}
	$admin = $this->filterAdmin($admin); if($admin === false){return false;}

	//Create the values Array
	$values = array(
		":name"=>$name,
 		":username"=>$username,
 		":password"=>$password,
 		":last_login"=>$last_login,
 		":admin"=>$admin
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				name,
				username,
				password,
				creation_date,
				last_login,
				admin
			) VALUES (
				:name,
				:username,
				:password,
				NOW(),
				:last_login,
				:admin)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

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


/**************************************************

Update Record By ID Function(s)

**************************************************/
public function updateUsersById($id, $columns){

    //Values Array
    $values = array(":id"=>$id);
    foreach($columns as $column=>$value){
        $values[":".$column]=$value;
    }

    //Generate the query
    $sql = "UPDATE $this->table SET ";
    $keys = array_keys($columns);
    foreach($keys as $column){
        $sql.= "$column=:$column";
        if(strcmp($column, end($keys))){
            $sql.= ", ";
        }
    }
    $sql.= " WHERE id=:id";

    return $this->db->update($sql, $values);
}


/**************************************************

Query Everything

**************************************************/
public function getAll(){

    //Generate the query
    $sql = "SELECT * FROM $this->table";

    return $this->db->query($sql, array());
}


/**************************************************

Query by Column(s) Function

**************************************************/
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

    return $this->queryByColumns(array("id"=>$id));
}


public function getByName($name){
	
    //Validate Inputs
    $name = $this->filterName($name); if($name === false){return false;}

    return $this->queryByColumns(array("name"=>$name));
}


public function getByUsername($username){
	
    //Validate Inputs
    $username = $this->filterUsername($username); if($username === false){return false;}

    return $this->queryByColumns(array("username"=>$username));
}


public function getByPassword($password){
	
    //Validate Inputs
    $password = $this->filterPassword($password); if($password === false){return false;}

    return $this->queryByColumns(array("password"=>$password));
}


public function getByCreationDate($creation_date){
	
    //Validate Inputs
    $creation_date = $this->filterCreationDate($creation_date); if($creation_date === false){return false;}

    return $this->queryByColumns(array("creation_date"=>$creation_date));
}


public function getByLastLogin($last_login){
	
    //Validate Inputs
    $last_login = $this->filterLastLogin($last_login); if($last_login === false){return false;}

    return $this->queryByColumns(array("last_login"=>$last_login));
}


public function getByAdmin($admin){
	
    //Validate Inputs
    $admin = $this->filterAdmin($admin); if($admin === false){return false;}

    return $this->queryByColumns(array("admin"=>$admin));
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
