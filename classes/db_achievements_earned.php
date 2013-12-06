<?php

/**************************************************
*
*    Achievements_earned Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	player_id - INT
*	game_id - INT
*	achievement_id - INT
*
**************************************************/
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
	if(Check::notInt($player_id)){return false;}
	if(Check::notInt($game_id)){return false;}
	if(Check::notInt($achievement_id)){return false;}

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

Update Record By ID Function(s)

**************************************************/
private function updateAchievements_earnedById($id, $columns){

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
    if(Check::notInt($id)){return false;}

    return getAchievements_earnedByColumn("id", $id.);
}


public function getAchievements_earnedByPlayerId($player_id){
	
    //Validate Inputs
    if(Check::notInt($player_id)){return false;}

    return getAchievements_earnedByColumn("player_id", $player_id.);
}


public function getAchievements_earnedByGameId($game_id){
	
    //Validate Inputs
    if(Check::notInt($game_id)){return false;}

    return getAchievements_earnedByColumn("game_id", $game_id.);
}


public function getAchievements_earnedByAchievementId($achievement_id){
	
    //Validate Inputs
    if(Check::notInt($achievement_id)){return false;}

    return getAchievements_earnedByColumn("achievement_id", $achievement_id.);
}

}//close class

?>
