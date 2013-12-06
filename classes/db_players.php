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
*	country_id - INT
*	state_id - INT
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
    $this->db = new Query();
}

public function __destruct(){}


/**************************************************

Create Function

**************************************************/
public function createPlayers($first_name, $last_name, $country_id, $state_id){

	//Validate the inputs
	if(Check::notString($first_name)){return false;}
	if(Check::notString($last_name)){return false;}
	if(Check::notInt($country_id)){return false;}
	if(Check::notInt($state_id)){return false;}

	//Create the values Array
	$values = array(
		":first_name"=>$first_name,
 		":last_name"=>$last_name,
 		":country_id"=>$country_id,
 		":state_id"=>$state_id
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				first_name,
				last_name,
				country_id,
				state_id
			) VALUES (
				:first_name,
				:last_name,
				:country_id,
				:state_id)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

**************************************************/
public function deletePlayers($id){

	//Validate the input
	if(Check::isInt($id)){return false;}

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
        if(strcmp($column, end($array_keys($columns))){
            $sql.= ", ";
        }
    }
    $sql.= " WHERE id=:id";

    return $this->db->update($sql, $values);
}


/**************************************************

Query By Column Function(s)

**************************************************/
private function getPlayersByColumn($column, $value){

    //inputs are pre-verified by the mapping functions below, so we can trust them

    //Values Array
    $values = array(":$column"=>$value);

    //Generate the query
    $sql = "SELECT * FROM $this->table WHERE $column=:$column";
    
    return $this->db->query($sql, $values);
}


public function getPlayersById($id){
	
    //Validate Inputs
    if(Check::notInt($id)){return false;}

    return getPlayersByColumn("id", $id.);
}


public function getPlayersByFirstName($first_name){
	
    //Validate Inputs
    if(Check::notString($first_name)){return false;}

    return getPlayersByColumn("first_name", $first_name.);
}


public function getPlayersByLastName($last_name){
	
    //Validate Inputs
    if(Check::notString($last_name)){return false;}

    return getPlayersByColumn("last_name", $last_name.);
}


public function getPlayersByCountryId($country_id){
	
    //Validate Inputs
    if(Check::notInt($country_id)){return false;}

    return getPlayersByColumn("country_id", $country_id.);
}


public function getPlayersByStateId($state_id){
	
    //Validate Inputs
    if(Check::notInt($state_id)){return false;}

    return getPlayersByColumn("state_id", $state_id.);
}

}//close class

?>
