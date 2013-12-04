<?php

/**************************************************
*
*    Players Class
*
***************************************************/
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
	if(!Check::isString($first_name)){return false;}
	if(!Check::isString($last_name)){return false;}
	if(!Check::isInt($country_id)){return false;}
	if(!Check::isInt($state_id)){return false;}

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
	if(!Check::isInt($id)){return false;}

	return getPlayersByColumn("id", $id.);
}


public function getPlayersByFirst_name($first_name){
	
	//Validate Inputs
	if(!Check::isString($first_name)){return false;}

	return getPlayersByColumn("first_name", $first_name.);
}


public function getPlayersByLast_name($last_name){
	
	//Validate Inputs
	if(!Check::isString($last_name)){return false;}

	return getPlayersByColumn("last_name", $last_name.);
}


public function getPlayersByCountry_id($country_id){
	
	//Validate Inputs
	if(!Check::isInt($country_id)){return false;}

	return getPlayersByColumn("country_id", $country_id.);
}


public function getPlayersByState_id($state_id){
	
	//Validate Inputs
	if(!Check::isInt($state_id)){return false;}

	return getPlayersByColumn("state_id", $state_id.);
}

}//close class

?>
