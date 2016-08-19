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
*	tournament_id - INT
*
**************************************************/
require_once("query.php");

class Achievements {

//DB Interaction variables
private var $db=NULL;
private var $table="achievements";

//Data storage variables
public var $id=NULL;
public var $name=NULL;
public var $points=NULL;
public var $per_game=NULL;
public var $is_meta=NULL;
public var $game_count=NULL;
public var $game_system_id=NULL;
public var $game_size_id=NULL;
public var $faction_id=NULL;
public var $unique_opponent=NULL;
public var $unique_opponent_locations=NULL;
public var $played_theme_force=NULL;
public var $fully_painted=NULL;
public var $fully_painted_battle=NULL;
public var $played_scenario=NULL;
public var $multiplayer=NULL;
public var $vs_vip=NULL;
public var $tournament_id=NULL;

//List of variables for sanitization
private var $varlist = array(
	"name"=>"filterName",
	"points"=>"filterPoints",
	"per_game"=>"filterPerGame",
	"is_meta"=>"filterIsMeta",
	"game_count"=>"filterGameCount",
	"game_system_id"=>"filterGameSystemId",
	"game_size_id"=>"filterGameSizeId",
	"faction_id"=>"filterFactionId",
	"unique_opponent"=>"filterUniqueOpponent",
	"unique_opponent_locations"=>"filterUniqueOpponentLocations",
	"played_theme_force"=>"filterPlayedThemeForce",
	"fully_painted"=>"filterFullyPainted",
	"fully_painted_battle"=>"filterFullyPaintedBattle",
	"played_scenario"=>"filterPlayedScenario",
	"multiplayer"=>"filterMultiplayer",
	"vs_vip"=>"filterVsVip",
	"tournament_id"=>"filterTournamentId");

/***************************************************

Constructor & Destructor

***************************************************/
public function __construct(){
    $this->db = Query::getInstance();
}

public function __destruct(){}


/**************************************************

Commit (Insert/Update) to DB Function(s)

**************************************************/
public function commit(){

    if($this->filterId($this->id)){
        return $this->updateRow();
    } else {
        return $this->insertRow();
    }
}

private function insertRow(){

    //Check for good data, first
    foreach($varlist as $vname=>$valFn){
        if(!$this->$valFn($this->$vname)) return false;
    }

    //Create the array of variables names and value calls
    $c_names = "";
    $v_calls = "";
    $values = array();
    foreach(array_keys($varlist) as $v){
        $c_names .= "$v";
        $v_calls .= ":$v";
        $values[":$v"] = $this->$v;

        if($v != end(array_keys($varlist)){
            $c_names .= ", ";
            $v_calls .= ", ";
        }
    }

    //Build the query
    $sql = "INSERT INTO $this->table ($c_names) VALUES ($v_calls)";

    return $this->db->insert($sql, $values);
}

private function updateRow(){

    //Check for good data, first
    foreach($varlist as $vname=>$valFn){
        if(!$this->$valFn($this->$vname)) return false;
    }

    //Create the array of variables names and value calls
    $c_str = "";
    $values = array(":id"=>$this->id);
    foreach(array_keys($varlist) as $v){
        $c_str .= "$v=:$v";
        $values[":$v"] = $this->$v;

        if($v != end(array_keys($varlist)){
            $c_str .= ", ";
        }
    }

    //Build the query
    $sql = "UPDATE $this->table SET $c_str WHERE id=:id";

    return $this->db->update($sql, $values);
}

/**************************************************

Delete Functions

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
            $sql.= " AND ";
        }
    }

    return $this->db->delete($sql, $values);
}

public function deleteById($id){
    return $this->deleteByColumns(array("id"=>$id));
}

public function delete(){
    if($this->id) return $this->deleteById($this->id);

    return false;
}


/**************************************************

Query Functions

**************************************************/
public function getAll(){

    //Generate the query
    $sql = "SELECT * FROM $this->table";

    return $this->db->query($sql, array());
}

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

    return Achievements::fromArray($this->queryByColumns(array("id"=>$id)));
}

public function getByName($name){
	
    //Validate Inputs
    $name = $this->filterName($name); if($name === false){return false;}

    return Achievements::fromArray($this->queryByColumns(array("name"=>$name)));
}

public function getByPoints($points){
	
    //Validate Inputs
    $points = $this->filterPoints($points); if($points === false){return false;}

    return Achievements::fromArray($this->queryByColumns(array("points"=>$points)));
}

public function getByPerGame($per_game){
	
    //Validate Inputs
    $per_game = $this->filterPerGame($per_game); if($per_game === false){return false;}

    return Achievements::fromArray($this->queryByColumns(array("per_game"=>$per_game)));
}

public function getByIsMeta($is_meta){
	
    //Validate Inputs
    $is_meta = $this->filterIsMeta($is_meta); if($is_meta === false){return false;}

    return Achievements::fromArray($this->queryByColumns(array("is_meta"=>$is_meta)));
}

public function getByGameCount($game_count){
	
    //Validate Inputs
    $game_count = $this->filterGameCount($game_count); if($game_count === false){return false;}

    return Achievements::fromArray($this->queryByColumns(array("game_count"=>$game_count)));
}

public function getByGameSystemId($game_system_id){
	
    //Validate Inputs
    $game_system_id = $this->filterGameSystemId($game_system_id); if($game_system_id === false){return false;}

    return Achievements::fromArray($this->queryByColumns(array("game_system_id"=>$game_system_id)));
}

public function getByGameSizeId($game_size_id){
	
    //Validate Inputs
    $game_size_id = $this->filterGameSizeId($game_size_id); if($game_size_id === false){return false;}

    return Achievements::fromArray($this->queryByColumns(array("game_size_id"=>$game_size_id)));
}

public function getByFactionId($faction_id){
	
    //Validate Inputs
    $faction_id = $this->filterFactionId($faction_id); if($faction_id === false){return false;}

    return Achievements::fromArray($this->queryByColumns(array("faction_id"=>$faction_id)));
}

public function getByUniqueOpponent($unique_opponent){
	
    //Validate Inputs
    $unique_opponent = $this->filterUniqueOpponent($unique_opponent); if($unique_opponent === false){return false;}

    return Achievements::fromArray($this->queryByColumns(array("unique_opponent"=>$unique_opponent)));
}

public function getByUniqueOpponentLocations($unique_opponent_locations){
	
    //Validate Inputs
    $unique_opponent_locations = $this->filterUniqueOpponentLocations($unique_opponent_locations); if($unique_opponent_locations === false){return false;}

    return Achievements::fromArray($this->queryByColumns(array("unique_opponent_locations"=>$unique_opponent_locations)));
}

public function getByPlayedThemeForce($played_theme_force){
	
    //Validate Inputs
    $played_theme_force = $this->filterPlayedThemeForce($played_theme_force); if($played_theme_force === false){return false;}

    return Achievements::fromArray($this->queryByColumns(array("played_theme_force"=>$played_theme_force)));
}

public function getByFullyPainted($fully_painted){
	
    //Validate Inputs
    $fully_painted = $this->filterFullyPainted($fully_painted); if($fully_painted === false){return false;}

    return Achievements::fromArray($this->queryByColumns(array("fully_painted"=>$fully_painted)));
}

public function getByFullyPaintedBattle($fully_painted_battle){
	
    //Validate Inputs
    $fully_painted_battle = $this->filterFullyPaintedBattle($fully_painted_battle); if($fully_painted_battle === false){return false;}

    return Achievements::fromArray($this->queryByColumns(array("fully_painted_battle"=>$fully_painted_battle)));
}

public function getByPlayedScenario($played_scenario){
	
    //Validate Inputs
    $played_scenario = $this->filterPlayedScenario($played_scenario); if($played_scenario === false){return false;}

    return Achievements::fromArray($this->queryByColumns(array("played_scenario"=>$played_scenario)));
}

public function getByMultiplayer($multiplayer){
	
    //Validate Inputs
    $multiplayer = $this->filterMultiplayer($multiplayer); if($multiplayer === false){return false;}

    return Achievements::fromArray($this->queryByColumns(array("multiplayer"=>$multiplayer)));
}

public function getByVsVip($vs_vip){
	
    //Validate Inputs
    $vs_vip = $this->filterVsVip($vs_vip); if($vs_vip === false){return false;}

    return Achievements::fromArray($this->queryByColumns(array("vs_vip"=>$vs_vip)));
}

public function getByTournamentId($tournament_id){
	
    //Validate Inputs
    $tournament_id = $this->filterTournamentId($tournament_id); if($tournament_id === false){return false;}

    return Achievements::fromArray($this->queryByColumns(array("tournament_id"=>$tournament_id)));
}

public static function fromArray($array){

    $output = new array();

    foreach($array as $a){

        $new = new Achievements();
    
        if($array[id]) $new->id=$a[id];

        foreach($this->varlist as $v){
            $new->$v = $a[$v];
        }

        $output[] = $new;
    }

    return $output;
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



function filterTournamentId($tournament_id){
    //Allowed to be null, catch that first
    if(Check::isNull($tournament_id)){ return null; }

    if(Check::notInt($tournament_id)){
        echo "tournament_id was invalid!"; return false;
    }

    return intVal($tournament_id);
}



}//close class

?>
