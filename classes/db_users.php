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
public function create($username, $password, $creation_date, $last_login, $admin){

	//Validate the inputs
	if(!$this->checkUsername($username)){return false;}
	if(!$this->checkPassword($password)){return false;}
	if(!$this->checkCreationDate($creation_date)){return false;}
	if(!$this->checkLastLogin($last_login)){return false;}
	if(!$this->checkAdmin($admin)){return false;}

	//Create the values Array
	$values = array(
		":username"=>$username,
 		":password"=>$password,
 		":creation_date"=>$creation_date,
 		":last_login"=>$last_login,
 		":admin"=>$admin
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				username,
				password,
				creation_date,
				last_login,
				admin
			) VALUES (
				:username,
				:password,
				:creation_date,
				:last_login,
				:admin)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

**************************************************/
public function deleteUsers($id){

	//Validate the input
	if(!$this->checkUsername($username)){return false;}
	if(!$this->checkPassword($password)){return false;}
	if(!$this->checkCreationDate($creation_date)){return false;}
	if(!$this->checkLastLogin($last_login)){return false;}
	if(!$this->checkAdmin($admin)){return false;}
	//Create the values array
	$values = array(":id"=>$id);

	//Create Query
	$sql = "DELETE FROM $this->table WHERE id=:id";

	return $this->db->delete($sql, $values);
}


/**************************************************

Update Record By ID Function(s)

**************************************************/
private function updateUsersById($id, $columns){

    //Values Array
    $values = array(":id"=>$id);
    foreach($columns as $column=>$value){
        $values[":".$column]=$value;
    }

    //Generate the query
    $sql = "UPDATE $this->table SET ";
    foreach(array_keys($columns) as $column){
        $sql.= "$column=:$column";
        if(strcmp($column, end($array_keys($columns)))){
            $sql.= ", ";
        }
    }
    $sql.= " WHERE id=:id";

    return $this->db->update($sql, $values);
}


/**************************************************

Query By Column Function(s)

**************************************************/
private function getByColumn($column, $value){

    //inputs are pre-verified by the mapping functions below, so we can trust them

    //Values Array
    $values = array(":$column"=>$value);

    //Generate the query
    $sql = "SELECT * FROM $this->table WHERE $column=:$column";
    
    return $this->db->query($sql, $values);
}


public function getById($id){
	
    //Validate Inputs
    if(!$this->checkId($id)){return false;}

    return $this->getByColumn("id", $id);
}


public function getByUsername($username){
	
    //Validate Inputs
    if(!$this->checkUsername($username)){return false;}

    return $this->getByColumn("username", $username);
}


public function getByPassword($password){
	
    //Validate Inputs
    if(!$this->checkPassword($password)){return false;}

    return $this->getByColumn("password", $password);
}


public function getByCreationDate($creation_date){
	
    //Validate Inputs
    if(!$this->checkCreationDate($creation_date)){return false;}

    return $this->getByColumn("creation_date", $creation_date);
}


public function getByLastLogin($last_login){
	
    //Validate Inputs
    if(!$this->checkLastLogin($last_login)){return false;}

    return $this->getByColumn("last_login", $last_login);
}


public function getByAdmin($admin){
	
    //Validate Inputs
    if(!$this->checkAdmin($admin)){return false;}

    return $this->getByColumn("admin", $admin);
}


/**************************************************
 
Column Validation Function(s)

**************************************************/
function checkId($id){
    //Not allowed to be null
    if(Check::isNull($id)){
        echo "id cannot be null!"; return false;
    }

    if(Check::notInt($id)){
        echo "id was invalid!"; return false;
    }

    return true;
}



function checkUsername($username){
    //Not allowed to be null
    if(Check::isNull($username)){
        echo "username cannot be null!"; return false;
    }

    if(Check::notString($username)){
        echo "username was invalid!"; return false;
    }

    return true;
}



function checkPassword($password){
    //Not allowed to be null
    if(Check::isNull($password)){
        echo "password cannot be null!"; return false;
    }

    if(Check::isNull($password)){
        echo "password was invalid!"; return false;
    }

    return true;
}



function checkCreationDate($creation_date){
    //Not allowed to be null
    if(Check::isNull($creation_date)){
        echo "creation_date cannot be null!"; return false;
    }

    if(Check::isNull($creation_date)){
        echo "creation_date was invalid!"; return false;
    }

    return true;
}



function checkLastLogin($last_login){
    if(Check::isNull($last_login)){
        echo "last_login was invalid!"; return false;
    }

    return true;
}



function checkAdmin($admin){
    //Not allowed to be null
    if(Check::isNull($admin)){
        echo "admin cannot be null!"; return false;
    }

    if(Check::notBool($admin)){
        echo "admin was invalid!"; return false;
    }

    return true;
}



}//close class

?>
