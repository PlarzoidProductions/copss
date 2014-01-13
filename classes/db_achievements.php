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
*	played_scenario - TINYINT
*	multiplayer - TINYINT
*	vs_vip - TINYINT
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
public function create($name, $points, $per_game, $is_meta, $game_count, $game_system_id, $game_size_id, $faction_id, $unique_opponent, $unique_opponent_locations, $played_theme_force, $fully_painted, $fully_painted_battle, $played_scenario, $multiplayer, $vs_vip, $event_id){

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
	$played_scenario = $this->filterPlayedScenario($played_scenario); if($played_scenario === false){return false;}
	$multiplayer = $this->filterMultiplayer($multiplayer); if($multiplayer === false){return false;}
	$vs_vip = $this->filterVsVip($vs_vip); if($vs_vip === false){return false;}
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
 		":played_scenario"=>$played_scenario,
 		":multiplayer"=>$multiplayer,
 		":vs_vip"=>$vs_vip,
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
				played_scenario,
				multiplayer,
				vs_vip,
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
				:played_scenario,
				:multiplayer,
				:vs_vip,
				:event_id)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

**************************************************/
public function deleteByColumns($columns){

    //Create the values array
    $values = array();
    foreach($columns as $c=>$v){
        $values[":".$c]=$v;
    }

    //Create Query\n";
    $sql = "DELETE FROM $this->table WHERE ";
    $keys = array_keys($columns);
    foreach($keys as $column){
        $sql.= "$column=:$column";
        if(strcmp($column, end($keys))){
            $sql.= ", ";
        }
    }

    return $this->db->delete($sql, $values);
}

public function deleteById($id){
    return $this->deleteByColumns(array("id"=>$id));
}


/**************************************************

Update Record By ID Function(s)

**************************************************/
public function updateAchievementsById($id, $columns){

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

Query by Column(s) Function

**************************************************/
public function queryByColumns($columns){

    //Values Array
    $values = array();
    foreach($columns as $column=>$value){
        $values[":".$column]=$value;
    }

    //Generate the query
    $sql = "SELECT * FROM $this->table WHERE ";
    $keys = array_keys($columns);
    foreach($keys as $column){
        $sql.= "$column=:$column";
        if(strcmp($column, end($keys))){
            $sql.= " AND ";
        }
    }

    return $this->db->query($sql, $values);
}


public function getById($id){
	
    //Validate Inputs
    $id = $this->filterId($id); if($id === false){return false;}

    return $this->queryByColumns(array("id"=>$id));
}


public function getByName($name){
	
    //Validate Inputs
    $name = $this->filterName($name); if($name === false){return false;}

    return $this->queryByColumns(array("name"=>$name));
}


public function getByPoints($points){
	
    //Validate Inputs
    $points = $this->filterPoints($points); if($points === false){return false;}

    return $this->queryByColumns(array("points"=>$points));
}


public function getByPerGame($per_game){
	
    //Validate Inputs
    $per_game = $this->filterPerGame($per_game); if($per_game === false){return false;}

    return $this->queryByColumns(array("per_game"=>$per_game));
}


public function getByIsMeta($is_meta){
	
    //Validate Inputs
    $is_meta = $this->filterIsMeta($is_meta); if($is_meta === false){return false;}

    return $this->queryByColumns(array("is_meta"=>$is_meta));
}


public function getByGameCount($game_count){
	
    //Validate Inputs
    $game_count = $this->filterGameCount($game_count); if($game_count === false){return false;}

    return $this->queryByColumns(array("game_count"=>$game_count));
}


public function getByGameSystemId($game_system_id){
	
    //Validate Inputs
    $game_system_id = $this->filterGameSystemId($game_system_id); if($game_system_id === false){return false;}

    return $this->queryByColumns(array("game_system_id"=>$game_system_id));
}


public function getByGameSizeId($game_size_id){
	
    //Validate Inputs
    $game_size_id = $this->filterGameSizeId($game_size_id); if($game_size_id === false){return false;}

    return $this->queryByColumns(array("game_size_id"=>$game_size_id));
}


public function getByFactionId($faction_id){
	
    //Validate Inputs
    $faction_id = $this->filterFactionId($faction_id); if($faction_id === false){return false;}

    return $this->queryByColumns(array("faction_id"=>$faction_id));
}


public function getByUniqueOpponent($unique_opponent){
	
    //Validate Inputs
    $unique_opponent = $this->filterUniqueOpponent($unique_opponent); if($unique_opponent === false){return false;}

    return $this->queryByColumns(array("unique_opponent"=>$unique_opponent));
}


public function getByUniqueOpponentLocations($unique_opponent_locations){
	
    //Validate Inputs
    $unique_opponent_locations = $this->filterUniqueOpponentLocations($unique_opponent_locations); if($unique_opponent_locations === false){return false;}

    return $this->queryByColumns(array("unique_opponent_locations"=>$unique_opponent_locations));
}


public function getByPlayedThemeForce($played_theme_force){
	
    //Validate Inputs
    $played_theme_force = $this->filterPlayedThemeForce($played_theme_force); if($played_theme_force === false){return false;}

    return $this->queryByColumns(array("played_theme_force"=>$played_theme_force));
}


public function getByFullyPainted($fully_painted){
	
    //Validate Inputs
    $fully_painted = $this->filterFullyPainted($fully_painted); if($fully_painted === false){return false;}

    return $this->queryByColumns(array("fully_painted"=>$fully_painted));
}


public function getByFullyPaintedBattle($fully_painted_battle){
	
    //Validate Inputs
    $fully_painted_battle = $this->filterFullyPaintedBattle($fully_painted_battle); if($fully_painted_battle === false){return false;}

    return $this->queryByColumns(array("fully_painted_battle"=>$fully_painted_battle));
}


public function getByPlayedScenario($played_scenario){
	
    //Validate Inputs
    $played_scenario = $this->filterPlayedScenario($played_scenario); if($played_scenario === false){return false;}

    return $this->queryByColumns(array("played_scenario"=>$played_scenario));
}


public function getByMultiplayer($multiplayer){
	
    //Validate Inputs
    $multiplayer = $this->filterMultiplayer($multiplayer); if($multiplayer === false){return false;}

    return $this->queryByColumns(array("multiplayer"=>$multiplayer));
}


public function getByVsVip($vs_vip){
	
    //Validate Inputs
    $vs_vip = $this->filterVsVip($vs_vip); if($vs_vip === false){return false;}

    return $this->queryByColumns(array("vs_vip"=>$vs_vip));
}


public function getByEventId($event_id){
	
    //Validate Inputs
    $event_id = $this->filterEventId($event_id); if($event_id === false){return false;}

    return $this->queryByColumns(array("event_id"=>$event_id));
}


/**************************************************

Exists by Column(s) Function

**************************************************/
public function existsByColumns($columns){
    $results = $this->queryByColumns($columns);

    return count($results);
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

    return intVal($id);
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

    return intVal($points);
}



function filterPerGame($per_game){
    //Not allowed to be null
    if(Check::isNull($per_game)){
        echo "per_game cannot be null!"; return false;
    }

    if(Check::notBool($per_game)){
        echo "per_game was invalid!"; return false;
    }

    return intVal($per_game);
}



function filterIsMeta($is_meta){
    //Not allowed to be null
    if(Check::isNull($is_meta)){
        echo "is_meta cannot be null!"; return false;
    }

    if(Check::notBool($is_meta)){
        echo "is_meta was invalid!"; return false;
    }

    return intVal($is_meta);
}



function filterGameCount($game_count){
    //Allowed to be null, catch that first
    if(Check::isNull($game_count)){ return null; }

    if(Check::notInt($game_count)){
        echo "game_count was invalid!"; return false;
    }

    return intVal($game_count);
}



function filterGameSystemId($game_system_id){
    //Allowed to be null, catch that first
    if(Check::isNull($game_system_id)){ return null; }

    if(Check::notInt($game_system_id)){
        echo "game_system_id was invalid!"; return false;
    }

    return intVal($game_system_id);
}



function filterGameSizeId($game_size_id){
    //Allowed to be null, catch that first
    if(Check::isNull($game_size_id)){ return null; }

    if(Check::notInt($game_size_id)){
        echo "game_size_id was invalid!"; return false;
    }

    return intVal($game_size_id);
}



function filterFactionId($faction_id){
    //Allowed to be null, catch that first
    if(Check::isNull($faction_id)){ return null; }

    if(Check::notInt($faction_id)){
        echo "faction_id was invalid!"; return false;
    }

    return intVal($faction_id);
}



function filterUniqueOpponent($unique_opponent){
    //Allowed to be null, catch that first
    if(Check::isNull($unique_opponent)){ return null; }

    if(Check::notBool($unique_opponent)){
        echo "unique_opponent was invalid!"; return false;
    }

    return intVal($unique_opponent);
}



function filterUniqueOpponentLocations($unique_opponent_locations){
    //Allowed to be null, catch that first
    if(Check::isNull($unique_opponent_locations)){ return null; }

    if(Check::notBool($unique_opponent_locations)){
        echo "unique_opponent_locations was invalid!"; return false;
    }

    return intVal($unique_opponent_locations);
}



function filterPlayedThemeForce($played_theme_force){
    //Allowed to be null, catch that first
    if(Check::isNull($played_theme_force)){ return null; }

    if(Check::notBool($played_theme_force)){
        echo "played_theme_force was invalid!"; return false;
    }

    return intVal($played_theme_force);
}



function filterFullyPainted($fully_painted){
    //Allowed to be null, catch that first
    if(Check::isNull($fully_painted)){ return null; }

    if(Check::notBool($fully_painted)){
        echo "fully_painted was invalid!"; return false;
    }

    return intVal($fully_painted);
}



function filterFullyPaintedBattle($fully_painted_battle){
    //Allowed to be null, catch that first
    if(Check::isNull($fully_painted_battle)){ return null; }

    if(Check::notBool($fully_painted_battle)){
        echo "fully_painted_battle was invalid!"; return false;
    }

    return intVal($fully_painted_battle);
}



function filterPlayedScenario($played_scenario){
    //Allowed to be null, catch that first
    if(Check::isNull($played_scenario)){ return null; }

    if(Check::notBool($played_scenario)){
        echo "played_scenario was invalid!"; return false;
    }

    return intVal($played_scenario);
}



function filterMultiplayer($multiplayer){
    //Allowed to be null, catch that first
    if(Check::isNull($multiplayer)){ return null; }

    if(Check::notBool($multiplayer)){
        echo "multiplayer was invalid!"; return false;
    }

    return intVal($multiplayer);
}



function filterVsVip($vs_vip){
    //Allowed to be null, catch that first
    if(Check::isNull($vs_vip)){ return null; }

    if(Check::notBool($vs_vip)){
        echo "vs_vip was invalid!"; return false;
    }

    return intVal($vs_vip);
}



function filterEventId($event_id){
    //Allowed to be null, catch that first
    if(Check::isNull($event_id)){ return null; }

    if(Check::notInt($event_id)){
        echo "event_id was invalid!"; return false;
    }

    return intVal($event_id);
}



}//close class

?>
