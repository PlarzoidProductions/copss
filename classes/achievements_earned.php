<?php

/**************************************************
*
*    Achievements_earned Class
*
***************************************************/
require_once("query.php");

class Achievements_earned {

var $db=NULL;
var $table="achievements_earned";


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
public function createAchievements_earned($player_id, $game_id, $achievement_id){

	//Validate the inputs
	if(!Check::isInt($player_id)){return false;}
	if(!Check::isInt($game_id)){return false;}
	if(!Check::isInt($achievement_id)){return false;}

	//Create the values Array
	$values = array(
		":player_id"=>$player_id,
 		":game_id"=>$game_id,
 		":achievement_id"=>$achievement_id
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				player_id,
				game_id,
				achievement_id
			) VALUES (
				:player_id,
				:game_id,
				:achievement_id)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

**************************************************/
public function deleteAchievements_earned($id){

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
private function getAchievements_earnedByColumn($column, $value){

	//inputs are pre-verified by the mapping functions below, so we can trust them

	//Values Array
	$values = array(":$column"=>$value);

	//Generate the query
	$sql = "SELECT * FROM $this->table WHERE $column=:$column";
    
	return $this->db->query($sql, $values);
}


public function getAchievements_earnedById($id){
	
	//Validate Inputs
	if(!Check::isInt($id)){return false;}

	return getAchievements_earnedByColumn("id", $id.);
}


public function getAchievements_earnedByPlayer_id($player_id){
	
	//Validate Inputs
	if(!Check::isInt($player_id)){return false;}

	return getAchievements_earnedByColumn("player_id", $player_id.);
}


public function getAchievements_earnedByGame_id($game_id){
	
	//Validate Inputs
	if(!Check::isInt($game_id)){return false;}

	return getAchievements_earnedByColumn("game_id", $game_id.);
}


public function getAchievements_earnedByAchievement_id($achievement_id){
	
	//Validate Inputs
	if(!Check::isInt($achievement_id)){return false;}

	return getAchievements_earnedByColumn("achievement_id", $achievement_id.);
}

}//close class

?>
