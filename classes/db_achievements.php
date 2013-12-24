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
	$name = $this->filterName($name); if($name === false){return false;}
	$points = $this->filterPoints($points); if($points === false){return false;}
	$per_game = $this->filterPerGame($per_game); if($per_game === false){return false;}
	$is_meta = $this->filterIsMeta($is_meta); if($is_meta === false){return false;}
	$game_count = $this->filterGameCount($game_count); if($game_count === false){return false;}
	$game_system_id = $this->filterGameSystemId($game_system_id); if($game_system_id === false){return false;}
	$game_size_id = $this->filterGameSizeId($game_size_id); if($game_size_id === false){return false;}
	$faction_id = $this->filterFactionId($faction_id); if($faction_id === false){return false;}
	$unique_opponent = $this->filterUniqueOpponent($unique_opponent); if($unique_opponent === false){return false;}
	$unique_opponent_locations = $this->filterUniqueOpponentLocations($unique_opponent_locations); if($unique_opponent_locations === false){return false;}
	$played_theme_force = $this->filterPlayedThemeForce($played_theme_force); if($played_theme_force === false){return false;}
	$fully_painted = $this->filterFullyPainted($fully_painted); if($fully_painted === false){return false;}
	$fully_painted_battle = $this->filterFullyPaintedBattle($fully_painted_battle); if($fully_painted_battle === false){return false;}
	$event_id = $this->filterEventId($event_id); if($event_id === false){return false;}

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
        if(strcmp($column, end(array_keys($columns)))){
            $sql.= ", ";
        }
    }
    $sql.= " WHERE id=:id";

    return $this->db->update($sql, $values);
}


/**************************************************

Query Everything

**************************************************/
public function getAll(){

    //Generate the query
    $sql = "SELECT * FROM $this->table";

    return $this->db->query($sql, array());
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
    $id = $this->filterId($id); if($id === false){return false;}

    return $this->getByColumn("id", $id);
}


public function getByName($name){
	
    //Validate Inputs
    $name = $this->filterName($name); if($name === false){return false;}

    return $this->getByColumn("name", $name);
}


public function getByPoints($points){
	
    //Validate Inputs
    $points = $this->filterPoints($points); if($points === false){return false;}

    return $this->getByColumn("points", $points);
}


public function getByPerGame($per_game){
	
    //Validate Inputs
    $per_game = $this->filterPerGame($per_game); if($per_game === false){return false;}

    return $this->getByColumn("per_game", $per_game);
}


public function getByIsMeta($is_meta){
	
    //Validate Inputs
    $is_meta = $this->filterIsMeta($is_meta); if($is_meta === false){return false;}

    return $this->getByColumn("is_meta", $is_meta);
}


public function getByGameCount($game_count){
	
    //Validate Inputs
    $game_count = $this->filterGameCount($game_count); if($game_count === false){return false;}

    return $this->getByColumn("game_count", $game_count);
}


public function getByGameSystemId($game_system_id){
	
    //Validate Inputs
    $game_system_id = $this->filterGameSystemId($game_system_id); if($game_system_id === false){return false;}

    return $this->getByColumn("game_system_id", $game_system_id);
}


public function getByGameSizeId($game_size_id){
	
    //Validate Inputs
    $game_size_id = $this->filterGameSizeId($game_size_id); if($game_size_id === false){return false;}

    return $this->getByColumn("game_size_id", $game_size_id);
}


public function getByFactionId($faction_id){
	
    //Validate Inputs
    $faction_id = $this->filterFactionId($faction_id); if($faction_id === false){return false;}

    return $this->getByColumn("faction_id", $faction_id);
}


public function getByUniqueOpponent($unique_opponent){
	
    //Validate Inputs
    $unique_opponent = $this->filterUniqueOpponent($unique_opponent); if($unique_opponent === false){return false;}

    return $this->getByColumn("unique_opponent", $unique_opponent);
}


public function getByUniqueOpponentLocations($unique_opponent_locations){
	
    //Validate Inputs
    $unique_opponent_locations = $this->filterUniqueOpponentLocations($unique_opponent_locations); if($unique_opponent_locations === false){return false;}

    return $this->getByColumn("unique_opponent_locations", $unique_opponent_locations);
}


public function getByPlayedThemeForce($played_theme_force){
	
    //Validate Inputs
    $played_theme_force = $this->filterPlayedThemeForce($played_theme_force); if($played_theme_force === false){return false;}

    return $this->getByColumn("played_theme_force", $played_theme_force);
}


public function getByFullyPainted($fully_painted){
	
    //Validate Inputs
    $fully_painted = $this->filterFullyPainted($fully_painted); if($fully_painted === false){return false;}

    return $this->getByColumn("fully_painted", $fully_painted);
}


public function getByFullyPaintedBattle($fully_painted_battle){
	
    //Validate Inputs
    $fully_painted_battle = $this->filterFullyPaintedBattle($fully_painted_battle); if($fully_painted_battle === false){return false;}

    return $this->getByColumn("fully_painted_battle", $fully_painted_battle);
}


public function getByEventId($event_id){
	
    //Validate Inputs
    $event_id = $this->filterEventId($event_id); if($event_id === false){return false;}

    return $this->getByColumn("event_id", $event_id);
}


/**************************************************
 
Column Validation Function(s)

**************************************************/
function filterId($id){
    //Not allowed to be null
    if(Check::isNull($id)){
        echo "id cannot be null!"; return false;
    }

    if(Check::notInt($id)){
        echo "id was invalid!"; return false;
    }

    return $id;
}



function filterName($name){
    //Not allowed to be null
    if(Check::isNull($name)){
        echo "name cannot be null!"; return false;
    }

    if(Check::notString($name)){
        echo "name was invalid!"; return false;
    }

    return $name;
}



function filterPoints($points){
    //Not allowed to be null
    if(Check::isNull($points)){
        echo "points cannot be null!"; return false;
    }

    if(Check::notInt($points)){
        echo "points was invalid!"; return false;
    }

    return $points;
}



function filterPerGame($per_game){
    //Not allowed to be null
    if(Check::isNull($per_game)){
        echo "per_game cannot be null!"; return false;
    }

    if(Check::notBool($per_game)){
        echo "per_game was invalid!"; return false;
    }

    return $per_game;
}



function filterIsMeta($is_meta){
    //Not allowed to be null
    if(Check::isNull($is_meta)){
        echo "is_meta cannot be null!"; return false;
    }

    if(Check::notBool($is_meta)){
        echo "is_meta was invalid!"; return false;
    }

    return $is_meta;
}



function filterGameCount($game_count){
    //Allowed to be null, catch that first
    if(Check::isNull($game_count)){ return null; }

    if(Check::notInt($game_count)){
        echo "game_count was invalid!"; return false;
    }

    return $game_count;
}



function filterGameSystemId($game_system_id){
    //Allowed to be null, catch that first
    if(Check::isNull($game_system_id)){ return null; }

    if(Check::notInt($game_system_id)){
        echo "game_system_id was invalid!"; return false;
    }

    return $game_system_id;
}



function filterGameSizeId($game_size_id){
    //Allowed to be null, catch that first
    if(Check::isNull($game_size_id)){ return null; }

    if(Check::notInt($game_size_id)){
        echo "game_size_id was invalid!"; return false;
    }

    return $game_size_id;
}



function filterFactionId($faction_id){
    //Allowed to be null, catch that first
    if(Check::isNull($faction_id)){ return null; }

    if(Check::notInt($faction_id)){
        echo "faction_id was invalid!"; return false;
    }

    return $faction_id;
}



function filterUniqueOpponent($unique_opponent){
    //Allowed to be null, catch that first
    if(Check::isNull($unique_opponent)){ return null; }

    if(Check::notBool($unique_opponent)){
        echo "unique_opponent was invalid!"; return false;
    }

    return $unique_opponent;
}



function filterUniqueOpponentLocations($unique_opponent_locations){
    //Allowed to be null, catch that first
    if(Check::isNull($unique_opponent_locations)){ return null; }

    if(Check::notBool($unique_opponent_locations)){
        echo "unique_opponent_locations was invalid!"; return false;
    }

    return $unique_opponent_locations;
}



function filterPlayedThemeForce($played_theme_force){
    //Allowed to be null, catch that first
    if(Check::isNull($played_theme_force)){ return null; }

    if(Check::notBool($played_theme_force)){
        echo "played_theme_force was invalid!"; return false;
    }

    return $played_theme_force;
}



function filterFullyPainted($fully_painted){
    //Allowed to be null, catch that first
    if(Check::isNull($fully_painted)){ return null; }

    if(Check::notBool($fully_painted)){
        echo "fully_painted was invalid!"; return false;
    }

    return $fully_painted;
}



function filterFullyPaintedBattle($fully_painted_battle){
    //Allowed to be null, catch that first
    if(Check::isNull($fully_painted_battle)){ return null; }

    if(Check::notBool($fully_painted_battle)){
        echo "fully_painted_battle was invalid!"; return false;
    }

    return $fully_painted_battle;
}



function filterEventId($event_id){
    //Allowed to be null, catch that first
    if(Check::isNull($event_id)){ return null; }

    if(Check::notInt($event_id)){
        echo "event_id was invalid!"; return false;
    }

    return $event_id;
}



}//close class

?>
