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
*	vip - TINYINT
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
public function create($first_name, $last_name, $email, $country, $state, $vip){

	//Validate the inputs
	$first_name = $this->filterFirstName($first_name); if($first_name === false){return false;}
	$last_name = $this->filterLastName($last_name); if($last_name === false){return false;}
	$email = $this->filterEmail($email); if($email === false){return false;}
	$country = $this->filterCountry($country); if($country === false){return false;}
	$state = $this->filterState($state); if($state === false){return false;}
	$vip = $this->filterVip($vip); if($vip === false){return false;}

	//Create the values Array
	$values = array(
		":first_name"=>$first_name,
 		":last_name"=>$last_name,
 		":email"=>$email,
 		":country"=>$country,
 		":state"=>$state,
 		":vip"=>$vip
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				first_name,
				last_name,
				email,
				country,
				state,
				vip,
				creation_date
			) VALUES (
				:first_name,
				:last_name,
				:email,
				:country,
				:state,
				:vip,
				NOW())";

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
public function updatePlayersById($id, $columns){

    //Values Array
    $values = array(":id"=>$id);
    foreach($columns as $column=>$value){
        $values[":".$column]=$value;
    }

    //Generate the query
    $sql = "UPDATE $this->table SET ";
    foreach(array_keys($columns) as $column){
        $sql.= "$column=:$column";
        if(strcmp($column, end(array_keys($columns)))){
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


public function getByFirstName($first_name){
	
    //Validate Inputs
    $first_name = $this->filterFirstName($first_name); if($first_name === false){return false;}

    return $this->queryByColumns(array("first_name"=>$first_name));
}


public function getByLastName($last_name){
	
    //Validate Inputs
    $last_name = $this->filterLastName($last_name); if($last_name === false){return false;}

    return $this->queryByColumns(array("last_name"=>$last_name));
}


public function getByEmail($email){
	
    //Validate Inputs
    $email = $this->filterEmail($email); if($email === false){return false;}

    return $this->queryByColumns(array("email"=>$email));
}


public function getByCountry($country){
	
    //Validate Inputs
    $country = $this->filterCountry($country); if($country === false){return false;}

    return $this->queryByColumns(array("country"=>$country));
}


public function getByState($state){
	
    //Validate Inputs
    $state = $this->filterState($state); if($state === false){return false;}

    return $this->queryByColumns(array("state"=>$state));
}


public function getByVip($vip){
	
    //Validate Inputs
    $vip = $this->filterVip($vip); if($vip === false){return false;}

    return $this->queryByColumns(array("vip"=>$vip));
}


public function getByCreationDate($creation_date){
	
    //Validate Inputs
    $creation_date = $this->filterCreationDate($creation_date); if($creation_date === false){return false;}

    return $this->queryByColumns(array("creation_date"=>$creation_date));
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



function filterFirstName($first_name){
    //Not allowed to be null
    if(Check::isNull($first_name)){
        echo "first_name cannot be null!"; return false;
    }

    if(Check::notString($first_name)){
        echo "first_name was invalid!"; return false;
    }

    return $first_name;
}



function filterLastName($last_name){
    //Not allowed to be null
    if(Check::isNull($last_name)){
        echo "last_name cannot be null!"; return false;
    }

    if(Check::notString($last_name)){
        echo "last_name was invalid!"; return false;
    }

    return $last_name;
}



function filterEmail($email){
    //Allowed to be null, catch that first
    if(Check::isNull($email)){ return null; }

    if(Check::notString($email)){
        echo "email was invalid!"; return false;
    }

    return $email;
}



function filterCountry($country){
    //Not allowed to be null
    if(Check::isNull($country)){
        echo "country cannot be null!"; return false;
    }

    if(Check::notInt($country)){
        echo "country was invalid!"; return false;
    }

    return intVal($country);
}



function filterState($state){
    //Allowed to be null, catch that first
    if(Check::isNull($state)){ return null; }

    if(Check::notInt($state)){
        echo "state was invalid!"; return false;
    }

    return intVal($state);
}



function filterVip($vip){
    //Allowed to be null, catch that first
    if(Check::isNull($vip)){ return null; }

    if(Check::notBool($vip)){
        echo "vip was invalid!"; return false;
    }

    return intVal($vip);
}



function filterCreationDate($creation_date){
    //Not allowed to be null
    if(Check::isNull($creation_date)){
        echo "creation_date cannot be null!"; return false;
    }

    if(Check::isNull($creation_date)){
        echo "creation_date was invalid!"; return false;
    }

    return $creation_date;
}



}//close class

?>
