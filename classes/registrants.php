<?php

/**************************************************
*
*    Registrants Class
*
***************************************************/
require_once("query.php");

class Registrants {

var $db=NULL;
var $table="registrants";


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
public function createRegistrants($player_id, $tournament_id, $wait_listed, $dropped, $faction){

	//Validate the inputs
	if(!Check::isInt($player_id)){return false;}
	if(!Check::isInt($tournament_id)){return false;}
	if(!Check::isBool($wait_listed)){return false;}
	if(!Check::isBool($dropped)){return false;}
	if(!Check::isInt($faction)){return false;}

	//Create the values Array
	$values = array(
		":player_id"=>$player_id,
 		":tournament_id"=>$tournament_id,
 		":wait_listed"=>$wait_listed,
 		":dropped"=>$dropped,
 		":faction"=>$faction
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				player_id,
				tournament_id,
				wait_listed,
				dropped,
				faction
			) VALUES (
				:player_id,
				:tournament_id,
				:wait_listed,
				:dropped,
				:faction)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

**************************************************/
public function deleteRegistrants($id){

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
private function getRegistrantsByColumn($column, $value){

	//inputs are pre-verified by the mapping functions below, so we can trust them

	//Values Array
	$values = array(":$column"=>$value);

	//Generate the query
	$sql = "SELECT * FROM $this->table WHERE $column=:$column";
    
	return $this->db->query($sql, $values);
}


public function getRegistrantsById($id){
	
	//Validate Inputs
	if(!Check::isInt($id)){return false;}

	return getRegistrantsByColumn("id", $id.);
}


public function getRegistrantsByPlayer_id($player_id){
	
	//Validate Inputs
	if(!Check::isInt($player_id)){return false;}

	return getRegistrantsByColumn("player_id", $player_id.);
}


public function getRegistrantsByTournament_id($tournament_id){
	
	//Validate Inputs
	if(!Check::isInt($tournament_id)){return false;}

	return getRegistrantsByColumn("tournament_id", $tournament_id.);
}


public function getRegistrantsByWait_listed($wait_listed){
	
	//Validate Inputs
	if(!Check::isBool($wait_listed)){return false;}

	return getRegistrantsByColumn("wait_listed", $wait_listed.);
}


public function getRegistrantsByDropped($dropped){
	
	//Validate Inputs
	if(!Check::isBool($dropped)){return false;}

	return getRegistrantsByColumn("dropped", $dropped.);
}


public function getRegistrantsByFaction($faction){
	
	//Validate Inputs
	if(!Check::isInt($faction)){return false;}

	return getRegistrantsByColumn("faction", $faction.);
}

}//close class

?>
