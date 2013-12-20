<?php

/**************************************************
*
*    Players Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	first_name - VARCHAR
*	last_name - VARCHAR
*	email - VARCHAR
*	country - INT
*	state - INT
*	creation_date - DATETIME
*
**************************************************/
require_once("query.php");

class Players {

var $db=NULL;
var $table="players";


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
public function create($first_name, $last_name, $email, $country, $state, $creation_date){

	//Validate the inputs
	if(!$this->checkFirstName($first_name)){return false;}
	if(!$this->checkLastName($last_name)){return false;}
	if(!$this->checkEmail($email)){return false;}
	if(!$this->checkCountry($country)){return false;}
	if(!$this->checkState($state)){return false;}
	if(!$this->checkCreationDate($creation_date)){return false;}

	//Create the values Array
	$values = array(
		":first_name"=>$first_name,
 		":last_name"=>$last_name,
 		":email"=>$email,
 		":country"=>$country,
 		":state"=>$state,
 		":creation_date"=>$creation_date
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				first_name,
				last_name,
				email,
				country,
				state,
				creation_date
			) VALUES (
				:first_name,
				:last_name,
				:email,
				:country,
				:state,
				:creation_date)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

**************************************************/
public function deletePlayers($id){

	//Validate the input
	if(!$this->checkFirstName($first_name)){return false;}
	if(!$this->checkLastName($last_name)){return false;}
	if(!$this->checkEmail($email)){return false;}
	if(!$this->checkCountry($country)){return false;}
	if(!$this->checkState($state)){return false;}
	if(!$this->checkCreationDate($creation_date)){return false;}
	//Create the values array
	$values = array(":id"=>$id);

	//Create Query
	$sql = "DELETE FROM $this->table WHERE id=:id";

	return $this->db->delete($sql, $values);
}


/**************************************************

Update Record By ID Function(s)

**************************************************/
private function updatePlayersById($id, $columns){

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

Query Everything

**************************************************/
public function getAll(){

    //Generate the query
    $sql = "SELECT * FROM $this->table";

    return $this->db->query($sql, array());
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


public function getByFirstName($first_name){
	
    //Validate Inputs
    if(!$this->checkFirstName($first_name)){return false;}

    return $this->getByColumn("first_name", $first_name);
}


public function getByLastName($last_name){
	
    //Validate Inputs
    if(!$this->checkLastName($last_name)){return false;}

    return $this->getByColumn("last_name", $last_name);
}


public function getByEmail($email){
	
    //Validate Inputs
    if(!$this->checkEmail($email)){return false;}

    return $this->getByColumn("email", $email);
}


public function getByCountry($country){
	
    //Validate Inputs
    if(!$this->checkCountry($country)){return false;}

    return $this->getByColumn("country", $country);
}


public function getByState($state){
	
    //Validate Inputs
    if(!$this->checkState($state)){return false;}

    return $this->getByColumn("state", $state);
}


public function getByCreationDate($creation_date){
	
    //Validate Inputs
    if(!$this->checkCreationDate($creation_date)){return false;}

    return $this->getByColumn("creation_date", $creation_date);
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



function checkFirstName($first_name){
    //Not allowed to be null
    if(Check::isNull($first_name)){
        echo "first_name cannot be null!"; return false;
    }

    if(Check::notString($first_name)){
        echo "first_name was invalid!"; return false;
    }

    return true;
}



function checkLastName($last_name){
    //Not allowed to be null
    if(Check::isNull($last_name)){
        echo "last_name cannot be null!"; return false;
    }

    if(Check::notString($last_name)){
        echo "last_name was invalid!"; return false;
    }

    return true;
}



function checkEmail($email){
    if(Check::notString($email)){
        echo "email was invalid!"; return false;
    }

    return true;
}



function checkCountry($country){
    //Not allowed to be null
    if(Check::isNull($country)){
        echo "country cannot be null!"; return false;
    }

    if(Check::notInt($country)){
        echo "country was invalid!"; return false;
    }

    return true;
}



function checkState($state){
    if(Check::notInt($state)){
        echo "state was invalid!"; return false;
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



}//close class

?>
