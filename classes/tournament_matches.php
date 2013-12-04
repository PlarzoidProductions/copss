<?php

/**************************************************
*
*    Tournament_matches Class
*
***************************************************/
require_once("query.php");

class Tournament_matches {

var $db=NULL;
var $table="tournament_matches";


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
public function createTournament_matches($tournament_id, $round){

	//Validate the inputs
	if(!Check::isInt($tournament_id)){return false;}
	if(!Check::isInt($round)){return false;}

	//Create the values Array
	$values = array(
		":tournament_id"=>$tournament_id,
 		":round"=>$round
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				tournament_id,
				round
			) VALUES (
				:tournament_id,
				:round)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

**************************************************/
public function deleteTournament_matches($id){

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
private function getTournament_matchesByColumn($column, $value){

	//inputs are pre-verified by the mapping functions below, so we can trust them

	//Values Array
	$values = array(":$column"=>$value);

	//Generate the query
	$sql = "SELECT * FROM $this->table WHERE $column=:$column";
    
	return $this->db->query($sql, $values);
}


public function getTournament_matchesById($id){
	
	//Validate Inputs
	if(!Check::isInt($id)){return false;}

	return getTournament_matchesByColumn("id", $id.);
}


public function getTournament_matchesByTournament_id($tournament_id){
	
	//Validate Inputs
	if(!Check::isInt($tournament_id)){return false;}

	return getTournament_matchesByColumn("tournament_id", $tournament_id.);
}


public function getTournament_matchesByRound($round){
	
	//Validate Inputs
	if(!Check::isInt($round)){return false;}

	return getTournament_matchesByColumn("round", $round.);
}

}//close class

?>
