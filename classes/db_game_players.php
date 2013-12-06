<?php

/**************************************************
*
*    Game_players Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	player_id - INT
*	game_id - INT
*	faction_id - INT
*	theme_force - TINYINT
*	fully_painted - TINYINT
*	winner - TINYINT
*
**************************************************/
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
	if(Check::notInt($player_id)){return false;}
	if(Check::notInt($game_id)){return false;}
	if(Check::notInt($faction_id)){return false;}
	if(Check::notBool($theme_force)){return false;}
	if(Check::notBool($fully_painted)){return false;}
	if(Check::notBool($winner)){return false;}

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

Update Record By ID Function(s)

**************************************************/
private function updateGame_playersById($id, $columns){

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
    if(Check::notInt($id)){return false;}

    return getGame_playersByColumn("id", $id.);
}


public function getGame_playersByPlayerId($player_id){
	
    //Validate Inputs
    if(Check::notInt($player_id)){return false;}

    return getGame_playersByColumn("player_id", $player_id.);
}


public function getGame_playersByGameId($game_id){
	
    //Validate Inputs
    if(Check::notInt($game_id)){return false;}

    return getGame_playersByColumn("game_id", $game_id.);
}


public function getGame_playersByFactionId($faction_id){
	
    //Validate Inputs
    if(Check::notInt($faction_id)){return false;}

    return getGame_playersByColumn("faction_id", $faction_id.);
}


public function getGame_playersByThemeForce($theme_force){
	
    //Validate Inputs
    if(Check::notBool($theme_force)){return false;}

    return getGame_playersByColumn("theme_force", $theme_force.);
}


public function getGame_playersByFullyPainted($fully_painted){
	
    //Validate Inputs
    if(Check::notBool($fully_painted)){return false;}

    return getGame_playersByColumn("fully_painted", $fully_painted.);
}


public function getGame_playersByWinner($winner){
	
    //Validate Inputs
    if(Check::notBool($winner)){return false;}

    return getGame_playersByColumn("winner", $winner.);
}

}//close class

?>
