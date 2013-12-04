<?php

/**************************************************
*
*    Tournament Class
*
***************************************************/
require_once("query.php");

class Tournament {

var $db=NULL;
var $table="tournament";


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
public function createTournament($game_system_id, $game_size_id, $max_num_players, $num_lists, $divide_and_conquer, $scoring_mode, $has_time_extensions, $finals_tables, $large_event){

	//Validate the inputs
	if(!Check::isInt($game_system_id)){return false;}
	if(!Check::isInt($game_size_id)){return false;}
	if(!Check::isInt($max_num_players)){return false;}
	if(!Check::isInt($num_lists)){return false;}
	if(!Check::isInt($divide_and_conquer)){return false;}
	if(!Check::notNull($scoring_mode)){return false;}
	if(!Check::isBool($has_time_extensions)){return false;}
	if(!Check::isBool($finals_tables)){return false;}
	if(!Check::isBool($large_event)){return false;}

	//Create the values Array
	$values = array(
		":game_system_id"=>$game_system_id,
 		":game_size_id"=>$game_size_id,
 		":max_num_players"=>$max_num_players,
 		":num_lists"=>$num_lists,
 		":divide_and_conquer"=>$divide_and_conquer,
 		":scoring_mode"=>$scoring_mode,
 		":has_time_extensions"=>$has_time_extensions,
 		":finals_tables"=>$finals_tables,
 		":large_event"=>$large_event
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				game_system_id,
				game_size_id,
				max_num_players,
				num_lists,
				divide_and_conquer,
				scoring_mode,
				has_time_extensions,
				finals_tables,
				large_event
			) VALUES (
				:game_system_id,
				:game_size_id,
				:max_num_players,
				:num_lists,
				:divide_and_conquer,
				:scoring_mode,
				:has_time_extensions,
				:finals_tables,
				:large_event)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

**************************************************/
public function deleteTournament($id){

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
private function getTournamentByColumn($column, $value){

	//inputs are pre-verified by the mapping functions below, so we can trust them

	//Values Array
	$values = array(":$column"=>$value);

	//Generate the query
	$sql = "SELECT * FROM $this->table WHERE $column=:$column";
    
	return $this->db->query($sql, $values);
}


public function getTournamentById($id){
	
	//Validate Inputs
	if(!Check::isInt($id)){return false;}

	return getTournamentByColumn("id", $id.);
}


public function getTournamentByGame_system_id($game_system_id){
	
	//Validate Inputs
	if(!Check::isInt($game_system_id)){return false;}

	return getTournamentByColumn("game_system_id", $game_system_id.);
}


public function getTournamentByGame_size_id($game_size_id){
	
	//Validate Inputs
	if(!Check::isInt($game_size_id)){return false;}

	return getTournamentByColumn("game_size_id", $game_size_id.);
}


public function getTournamentByMax_num_players($max_num_players){
	
	//Validate Inputs
	if(!Check::isInt($max_num_players)){return false;}

	return getTournamentByColumn("max_num_players", $max_num_players.);
}


public function getTournamentByNum_lists($num_lists){
	
	//Validate Inputs
	if(!Check::isInt($num_lists)){return false;}

	return getTournamentByColumn("num_lists", $num_lists.);
}


public function getTournamentByDivide_and_conquer($divide_and_conquer){
	
	//Validate Inputs
	if(!Check::isInt($divide_and_conquer)){return false;}

	return getTournamentByColumn("divide_and_conquer", $divide_and_conquer.);
}


public function getTournamentByScoring_mode($scoring_mode){
	
	//Validate Inputs
	if(!Check::notNull($scoring_mode)){return false;}

	return getTournamentByColumn("scoring_mode", $scoring_mode.);
}


public function getTournamentByHas_time_extensions($has_time_extensions){
	
	//Validate Inputs
	if(!Check::isBool($has_time_extensions)){return false;}

	return getTournamentByColumn("has_time_extensions", $has_time_extensions.);
}


public function getTournamentByFinals_tables($finals_tables){
	
	//Validate Inputs
	if(!Check::isBool($finals_tables)){return false;}

	return getTournamentByColumn("finals_tables", $finals_tables.);
}


public function getTournamentByLarge_event($large_event){
	
	//Validate Inputs
	if(!Check::isBool($large_event)){return false;}

	return getTournamentByColumn("large_event", $large_event.);
}

}//close class

?>
