<?php

/**************************************************
*
*    Achievements Class
*
***************************************************/
require_once("query.php");

class Achievements {

var $db=NULL;
var $table="achievements";


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
public function createAchievements($name, $points, $per_game, $is_meta, $game_count, $game_system_id, $game_size_id, $tournament_id, $event_id, $unique_opponent, $unique_opponent_location, $played_theme_force, $fully_painted, $fully_painted_battle){

	//Validate the inputs
	if(!Check::isString($name)){return false;}
	if(!Check::isBool($points)){return false;}
	if(!Check::isBool($per_game)){return false;}
	if(!Check::isBool($is_meta)){return false;}
	if(!Check::isInt($game_count)){return false;}
	if(!Check::isInt($game_system_id)){return false;}
	if(!Check::isInt($game_size_id)){return false;}
	if(!Check::isInt($tournament_id)){return false;}
	if(!Check::isInt($event_id)){return false;}
	if(!Check::isBool($unique_opponent)){return false;}
	if(!Check::isBool($unique_opponent_location)){return false;}
	if(!Check::isBool($played_theme_force)){return false;}
	if(!Check::isBool($fully_painted)){return false;}
	if(!Check::isBool($fully_painted_battle)){return false;}

	//Create the values Array
	$values = array(
		":name"=>$name,
 		":points"=>$points,
 		":per_game"=>$per_game,
 		":is_meta"=>$is_meta,
 		":game_count"=>$game_count,
 		":game_system_id"=>$game_system_id,
 		":game_size_id"=>$game_size_id,
 		":tournament_id"=>$tournament_id,
 		":event_id"=>$event_id,
 		":unique_opponent"=>$unique_opponent,
 		":unique_opponent_location"=>$unique_opponent_location,
 		":played_theme_force"=>$played_theme_force,
 		":fully_painted"=>$fully_painted,
 		":fully_painted_battle"=>$fully_painted_battle
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				name,
				points,
				per_game,
				is_meta,
				game_count,
				game_system_id,
				game_size_id,
				tournament_id,
				event_id,
				unique_opponent,
				unique_opponent_location,
				played_theme_force,
				fully_painted,
				fully_painted_battle
			) VALUES (
				:name,
				:points,
				:per_game,
				:is_meta,
				:game_count,
				:game_system_id,
				:game_size_id,
				:tournament_id,
				:event_id,
				:unique_opponent,
				:unique_opponent_location,
				:played_theme_force,
				:fully_painted,
				:fully_painted_battle)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

**************************************************/
public function deleteAchievements($id){

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
private function getAchievementsByColumn($column, $value){

	//inputs are pre-verified by the mapping functions below, so we can trust them

	//Values Array
	$values = array(":$column"=>$value);

	//Generate the query
	$sql = "SELECT * FROM $this->table WHERE $column=:$column";
    
	return $this->db->query($sql, $values);
}


public function getAchievementsById($id){
	
	//Validate Inputs
	if(!Check::isInt($id)){return false;}

	return getAchievementsByColumn("id", $id.);
}


public function getAchievementsByName($name){
	
	//Validate Inputs
	if(!Check::isString($name)){return false;}

	return getAchievementsByColumn("name", $name.);
}


public function getAchievementsByPoints($points){
	
	//Validate Inputs
	if(!Check::isBool($points)){return false;}

	return getAchievementsByColumn("points", $points.);
}


public function getAchievementsByPer_game($per_game){
	
	//Validate Inputs
	if(!Check::isBool($per_game)){return false;}

	return getAchievementsByColumn("per_game", $per_game.);
}


public function getAchievementsByIs_meta($is_meta){
	
	//Validate Inputs
	if(!Check::isBool($is_meta)){return false;}

	return getAchievementsByColumn("is_meta", $is_meta.);
}


public function getAchievementsByGame_count($game_count){
	
	//Validate Inputs
	if(!Check::isInt($game_count)){return false;}

	return getAchievementsByColumn("game_count", $game_count.);
}


public function getAchievementsByGame_system_id($game_system_id){
	
	//Validate Inputs
	if(!Check::isInt($game_system_id)){return false;}

	return getAchievementsByColumn("game_system_id", $game_system_id.);
}


public function getAchievementsByGame_size_id($game_size_id){
	
	//Validate Inputs
	if(!Check::isInt($game_size_id)){return false;}

	return getAchievementsByColumn("game_size_id", $game_size_id.);
}


public function getAchievementsByTournament_id($tournament_id){
	
	//Validate Inputs
	if(!Check::isInt($tournament_id)){return false;}

	return getAchievementsByColumn("tournament_id", $tournament_id.);
}


public function getAchievementsByEvent_id($event_id){
	
	//Validate Inputs
	if(!Check::isInt($event_id)){return false;}

	return getAchievementsByColumn("event_id", $event_id.);
}


public function getAchievementsByUnique_opponent($unique_opponent){
	
	//Validate Inputs
	if(!Check::isBool($unique_opponent)){return false;}

	return getAchievementsByColumn("unique_opponent", $unique_opponent.);
}


public function getAchievementsByUnique_opponent_location($unique_opponent_location){
	
	//Validate Inputs
	if(!Check::isBool($unique_opponent_location)){return false;}

	return getAchievementsByColumn("unique_opponent_location", $unique_opponent_location.);
}


public function getAchievementsByPlayed_theme_force($played_theme_force){
	
	//Validate Inputs
	if(!Check::isBool($played_theme_force)){return false;}

	return getAchievementsByColumn("played_theme_force", $played_theme_force.);
}


public function getAchievementsByFully_painted($fully_painted){
	
	//Validate Inputs
	if(!Check::isBool($fully_painted)){return false;}

	return getAchievementsByColumn("fully_painted", $fully_painted.);
}


public function getAchievementsByFully_painted_battle($fully_painted_battle){
	
	//Validate Inputs
	if(!Check::isBool($fully_painted_battle)){return false;}

	return getAchievementsByColumn("fully_painted_battle", $fully_painted_battle.);
}

}//close class

?>
