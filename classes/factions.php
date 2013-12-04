<?php

/**************************************************
*
*    Factions Class
*
***************************************************/
require_once("query.php");

class Factions {

var $db=NULL;
var $table="factions";


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
public function createFactions($game_system_id, $name, $acronym){

	//Validate the inputs
	if(!Check::isInt($game_system_id)){return false;}
	if(!Check::isString($name)){return false;}
	if(!Check::isString($acronym)){return false;}

	//Create the values Array
	$values = array(
		":game_system_id"=>$game_system_id,
 		":name"=>$name,
 		":acronym"=>$acronym
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				game_system_id,
				name,
				acronym
			) VALUES (
				:game_system_id,
				:name,
				:acronym)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

**************************************************/
public function deleteFactions($id){

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
private function getFactionsByColumn($column, $value){

	//inputs are pre-verified by the mapping functions below, so we can trust them

	//Values Array
	$values = array(":$column"=>$value);

	//Generate the query
	$sql = "SELECT * FROM $this->table WHERE $column=:$column";
    
	return $this->db->query($sql, $values);
}


public function getFactionsById($id){
	
	//Validate Inputs
	if(!Check::isInt($id)){return false;}

	return getFactionsByColumn("id", $id.);
}


public function getFactionsByGame_system_id($game_system_id){
	
	//Validate Inputs
	if(!Check::isInt($game_system_id)){return false;}

	return getFactionsByColumn("game_system_id", $game_system_id.);
}


public function getFactionsByName($name){
	
	//Validate Inputs
	if(!Check::isString($name)){return false;}

	return getFactionsByColumn("name", $name.);
}


public function getFactionsByAcronym($acronym){
	
	//Validate Inputs
	if(!Check::isString($acronym)){return false;}

	return getFactionsByColumn("acronym", $acronym.);
}

}//close class

?>
