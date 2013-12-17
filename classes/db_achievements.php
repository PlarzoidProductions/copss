<?php

/**************************************************
*
*    Achievements Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	name - VARCHAR
*	points - INT
*	per_game - TINYINT
*	is_meta - TINYINT
*	game_count - INT
*	game_system_id - INT
*	game_size_id - INT
*	faction_id - INT
*	unique_opponent - TINYINT
*	unique_opponent_locations - TINYINT
*	played_theme_force - TINYINT
*	fully_painted - TINYINT
*	fully_painted_battle - TINYINT
*	event_id - INT
*
**************************************************/
require_once("query.php");

class Achievements {

var $db=NULL;
var $table="achievements";


/***************************************************

Constructor & Destructor

***************************************************/
public function __construct(){
    $this->db = Query::getInstance();
}

public function __destruct(){}


/**************************************************

Create Function

**************************************************/
public function create($name, $points, $per_game, $is_meta, $game_count, $game_system_id, $game_size_id, $faction_id, $unique_opponent, $unique_opponent_locations, $played_theme_force, $fully_painted, $fully_painted_battle, $event_id){

	//Validate the inputs
	if(!$this->checkName($name)){return false;}
	if(!$this->checkPoints($points)){return false;}
	if(!$this->checkPerGame($per_game)){return false;}
	if(!$this->checkIsMeta($is_meta)){return false;}
	if(!$this->checkGameCount($game_count)){return false;}
	if(!$this->checkGameSystemId($game_system_id)){return false;}
	if(!$this->checkGameSizeId($game_size_id)){return false;}
	if(!$this->checkFactionId($faction_id)){return false;}
	if(!$this->checkUniqueOpponent($unique_opponent)){return false;}
	if(!$this->checkUniqueOpponentLocations($unique_opponent_locations)){return false;}
	if(!$this->checkPlayedThemeForce($played_theme_force)){return false;}
	if(!$this->checkFullyPainted($fully_painted)){return false;}
	if(!$this->checkFullyPaintedBattle($fully_painted_battle)){return false;}
	if(!$this->checkEventId($event_id)){return false;}

	//Create the values Array
	$values = array(
		":name"=>$name,
 		":points"=>$points,
 		":per_game"=>$per_game,
 		":is_meta"=>$is_meta,
 		":game_count"=>$game_count,
 		":game_system_id"=>$game_system_id,
 		":game_size_id"=>$game_size_id,
 		":faction_id"=>$faction_id,
 		":unique_opponent"=>$unique_opponent,
 		":unique_opponent_locations"=>$unique_opponent_locations,
 		":played_theme_force"=>$played_theme_force,
 		":fully_painted"=>$fully_painted,
 		":fully_painted_battle"=>$fully_painted_battle,
 		":event_id"=>$event_id
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
				faction_id,
				unique_opponent,
				unique_opponent_locations,
				played_theme_force,
				fully_painted,
				fully_painted_battle,
				event_id
			) VALUES (
				:name,
				:points,
				:per_game,
				:is_meta,
				:game_count,
				:game_system_id,
				:game_size_id,
				:faction_id,
				:unique_opponent,
				:unique_opponent_locations,
				:played_theme_force,
				:fully_painted,
				:fully_painted_battle,
				:event_id)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

**************************************************/
public function deleteAchievements($id){

	//Validate the input
	if(!$this->checkName($name)){return false;}
	if(!$this->checkPoints($points)){return false;}
	if(!$this->checkPerGame($per_game)){return false;}
	if(!$this->checkIsMeta($is_meta)){return false;}
	if(!$this->checkGameCount($game_count)){return false;}
	if(!$this->checkGameSystemId($game_system_id)){return false;}
	if(!$this->checkGameSizeId($game_size_id)){return false;}
	if(!$this->checkFactionId($faction_id)){return false;}
	if(!$this->checkUniqueOpponent($unique_opponent)){return false;}
	if(!$this->checkUniqueOpponentLocations($unique_opponent_locations)){return false;}
	if(!$this->checkPlayedThemeForce($played_theme_force)){return false;}
	if(!$this->checkFullyPainted($fully_painted)){return false;}
	if(!$this->checkFullyPaintedBattle($fully_painted_battle)){return false;}
	if(!$this->checkEventId($event_id)){return false;}
	//Create the values array
	$values = array(":id"=>$id);

	//Create Query
	$sql = "DELETE FROM $this->table WHERE id=:id";

	return $this->db->delete($sql, $values);
}


/**************************************************

Update Record By ID Function(s)

**************************************************/
private function updateAchievementsById($id, $columns){

    //Values Array
    $values = array(":id"=>$id);
    foreach($columns as $column=>$value){
        $values[":".$column]=$value;
    }

    //Generate the query
    $sql = "UPDATE $this->table SET ";
    foreach(array_keys($columns) as $column){
        $sql.= "$column=:$column";
        if(strcmp($column, end($array_keys($columns)))){
            $sql.= ", ";
        }
    }
    $sql.= " WHERE id=:id";

    return $this->db->update($sql, $values);
}


/**************************************************

Query By Column Function(s)

**************************************************/
private function getByColumn($column, $value){

    //inputs are pre-verified by the mapping functions below, so we can trust them

    //Values Array
    $values = array(":$column"=>$value);

    //Generate the query
    $sql = "SELECT * FROM $this->table WHERE $column=:$column";
    
    return $this->db->query($sql, $values);
}


public function getById($id){
	
    //Validate Inputs
    if(!$this->checkId($id)){return false;}

    return $this->getByColumn("id", $id);
}


public function getByName($name){
	
    //Validate Inputs
    if(!$this->checkName($name)){return false;}

    return $this->getByColumn("name", $name);
}


public function getByPoints($points){
	
    //Validate Inputs
    if(!$this->checkPoints($points)){return false;}

    return $this->getByColumn("points", $points);
}


public function getByPerGame($per_game){
	
    //Validate Inputs
    if(!$this->checkPerGame($per_game)){return false;}

    return $this->getByColumn("per_game", $per_game);
}


public function getByIsMeta($is_meta){
	
    //Validate Inputs
    if(!$this->checkIsMeta($is_meta)){return false;}

    return $this->getByColumn("is_meta", $is_meta);
}


public function getByGameCount($game_count){
	
    //Validate Inputs
    if(!$this->checkGameCount($game_count)){return false;}

    return $this->getByColumn("game_count", $game_count);
}


public function getByGameSystemId($game_system_id){
	
    //Validate Inputs
    if(!$this->checkGameSystemId($game_system_id)){return false;}

    return $this->getByColumn("game_system_id", $game_system_id);
}


public function getByGameSizeId($game_size_id){
	
    //Validate Inputs
    if(!$this->checkGameSizeId($game_size_id)){return false;}

    return $this->getByColumn("game_size_id", $game_size_id);
}


public function getByFactionId($faction_id){
	
    //Validate Inputs
    if(!$this->checkFactionId($faction_id)){return false;}

    return $this->getByColumn("faction_id", $faction_id);
}


public function getByUniqueOpponent($unique_opponent){
	
    //Validate Inputs
    if(!$this->checkUniqueOpponent($unique_opponent)){return false;}

    return $this->getByColumn("unique_opponent", $unique_opponent);
}


public function getByUniqueOpponentLocations($unique_opponent_locations){
	
    //Validate Inputs
    if(!$this->checkUniqueOpponentLocations($unique_opponent_locations)){return false;}

    return $this->getByColumn("unique_opponent_locations", $unique_opponent_locations);
}


public function getByPlayedThemeForce($played_theme_force){
	
    //Validate Inputs
    if(!$this->checkPlayedThemeForce($played_theme_force)){return false;}

    return $this->getByColumn("played_theme_force", $played_theme_force);
}


public function getByFullyPainted($fully_painted){
	
    //Validate Inputs
    if(!$this->checkFullyPainted($fully_painted)){return false;}

    return $this->getByColumn("fully_painted", $fully_painted);
}


public function getByFullyPaintedBattle($fully_painted_battle){
	
    //Validate Inputs
    if(!$this->checkFullyPaintedBattle($fully_painted_battle)){return false;}

    return $this->getByColumn("fully_painted_battle", $fully_painted_battle);
}


public function getByEventId($event_id){
	
    //Validate Inputs
    if(!$this->checkEventId($event_id)){return false;}

    return $this->getByColumn("event_id", $event_id);
}


/**************************************************
 
Column Validation Function(s)

**************************************************/
function checkId($id){
    //Not allowed to be null
    if(Check::isNull($id)){
        echo "id cannot be null!"; return false;
    }

    if(Check::notInt($id)){
        echo "id was invalid!"; return false;
    }

    return true;
}



function checkName($name){
    //Not allowed to be null
    if(Check::isNull($name)){
        echo "name cannot be null!"; return false;
    }

    if(Check::notString($name)){
        echo "name was invalid!"; return false;
    }

    return true;
}



function checkPoints($points){
    //Not allowed to be null
    if(Check::isNull($points)){
        echo "points cannot be null!"; return false;
    }

    if(Check::notInt($points)){
        echo "points was invalid!"; return false;
    }

    return true;
}



function checkPerGame($per_game){
    //Not allowed to be null
    if(Check::isNull($per_game)){
        echo "per_game cannot be null!"; return false;
    }

    if(Check::notBool($per_game)){
        echo "per_game was invalid!"; return false;
    }

    return true;
}



function checkIsMeta($is_meta){
    //Not allowed to be null
    if(Check::isNull($is_meta)){
        echo "is_meta cannot be null!"; return false;
    }

    if(Check::notBool($is_meta)){
        echo "is_meta was invalid!"; return false;
    }

    return true;
}



function checkGameCount($game_count){
    if(Check::notInt($game_count)){
        echo "game_count was invalid!"; return false;
    }

    return true;
}



function checkGameSystemId($game_system_id){
    if(Check::notInt($game_system_id)){
        echo "game_system_id was invalid!"; return false;
    }

    return true;
}



function checkGameSizeId($game_size_id){
    if(Check::notInt($game_size_id)){
        echo "game_size_id was invalid!"; return false;
    }

    return true;
}



function checkFactionId($faction_id){
    if(Check::notInt($faction_id)){
        echo "faction_id was invalid!"; return false;
    }

    return true;
}



function checkUniqueOpponent($unique_opponent){
    if(Check::notBool($unique_opponent)){
        echo "unique_opponent was invalid!"; return false;
    }

    return true;
}



function checkUniqueOpponentLocations($unique_opponent_locations){
    if(Check::notBool($unique_opponent_locations)){
        echo "unique_opponent_locations was invalid!"; return false;
    }

    return true;
}



function checkPlayedThemeForce($played_theme_force){
    if(Check::notBool($played_theme_force)){
        echo "played_theme_force was invalid!"; return false;
    }

    return true;
}



function checkFullyPainted($fully_painted){
    if(Check::notBool($fully_painted)){
        echo "fully_painted was invalid!"; return false;
    }

    return true;
}



function checkFullyPaintedBattle($fully_painted_battle){
    if(Check::notBool($fully_painted_battle)){
        echo "fully_painted_battle was invalid!"; return false;
    }

    return true;
}



function checkEventId($event_id){
    if(Check::notInt($event_id)){
        echo "event_id was invalid!"; return false;
    }

    return true;
}



}//close class

?>
