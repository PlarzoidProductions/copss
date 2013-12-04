<?php

/**************************************************
*
*    Game_players Class
*
***************************************************/
require_once("query.php");

class Game_players {

var $db=NULL;
var $table="game_players";


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
public function createGame_players($player_id, $game_id, $faction_id, $theme_force, $fully_painted, $winner){

	//Validate the inputs
	if(!Check::isInt($player_id)){return false;}
	if(!Check::isInt($game_id)){return false;}
	if(!Check::isInt($faction_id)){return false;}
	if(!Check::isBool($theme_force)){return false;}
	if(!Check::isBool($fully_painted)){return false;}
	if(!Check::isBool($winner)){return false;}

	//Create the values Array
	$values = array(
		":player_id"=>$player_id,
 		":game_id"=>$game_id,
 		":faction_id"=>$faction_id,
 		":theme_force"=>$theme_force,
 		":fully_painted"=>$fully_painted,
 		":winner"=>$winner
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				player_id,
				game_id,
				faction_id,
				theme_force,
				fully_painted,
				winner
			) VALUES (
				:player_id,
				:game_id,
				:faction_id,
				:theme_force,
				:fully_painted,
				:winner)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

**************************************************/
public function deleteGame_players($id){

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
private function getGame_playersByColumn($column, $value){

	//inputs are pre-verified by the mapping functions below, so we can trust them

	//Values Array
	$values = array(":$column"=>$value);

	//Generate the query
	$sql = "SELECT * FROM $this->table WHERE $column=:$column";
    
	return $this->db->query($sql, $values);
}


public function getGame_playersById($id){
	
	//Validate Inputs
	if(!Check::isInt($id)){return false;}

	return getGame_playersByColumn("id", $id.);
}


public function getGame_playersByPlayer_id($player_id){
	
	//Validate Inputs
	if(!Check::isInt($player_id)){return false;}

	return getGame_playersByColumn("player_id", $player_id.);
}


public function getGame_playersByGame_id($game_id){
	
	//Validate Inputs
	if(!Check::isInt($game_id)){return false;}

	return getGame_playersByColumn("game_id", $game_id.);
}


public function getGame_playersByFaction_id($faction_id){
	
	//Validate Inputs
	if(!Check::isInt($faction_id)){return false;}

	return getGame_playersByColumn("faction_id", $faction_id.);
}


public function getGame_playersByTheme_force($theme_force){
	
	//Validate Inputs
	if(!Check::isBool($theme_force)){return false;}

	return getGame_playersByColumn("theme_force", $theme_force.);
}


public function getGame_playersByFully_painted($fully_painted){
	
	//Validate Inputs
	if(!Check::isBool($fully_painted)){return false;}

	return getGame_playersByColumn("fully_painted", $fully_painted.);
}


public function getGame_playersByWinner($winner){
	
	//Validate Inputs
	if(!Check::isBool($winner)){return false;}

	return getGame_playersByColumn("winner", $winner.);
}

}//close class

?>
