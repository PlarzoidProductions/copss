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
*	game_id - INT
*	player_id - INT
*	faction_id - INT
*	game_size - INT
*	theme_force - TINYINT
*	fully_painted - TINYINT
*
**************************************************/
require_once("query.php");

class Game_players {

//DB Interaction variables
private var $db=NULL;
private var $table="game_players";

//Data storage variables
public var $id=NULL;
public var $game_id=NULL;
public var $player_id=NULL;
public var $faction_id=NULL;
public var $game_size=NULL;
public var $theme_force=NULL;
public var $fully_painted=NULL;

//List of variables for sanitization
private var $varlist = array(
	"game_id"=>"filterGameId",
	"player_id"=>"filterPlayerId",
	"faction_id"=>"filterFactionId",
	"game_size"=>"filterGameSize",
	"theme_force"=>"filterThemeForce",
	"fully_painted"=>"filterFullyPainted");

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

    return Game_players::fromArray($this->queryByColumns(array("id"=>$id)));
}

public function getByGameId($game_id){
	
    //Validate Inputs
    $game_id = $this->filterGameId($game_id); if($game_id === false){return false;}

    return Game_players::fromArray($this->queryByColumns(array("game_id"=>$game_id)));
}

public function getByPlayerId($player_id){
	
    //Validate Inputs
    $player_id = $this->filterPlayerId($player_id); if($player_id === false){return false;}

    return Game_players::fromArray($this->queryByColumns(array("player_id"=>$player_id)));
}

public function getByFactionId($faction_id){
	
    //Validate Inputs
    $faction_id = $this->filterFactionId($faction_id); if($faction_id === false){return false;}

    return Game_players::fromArray($this->queryByColumns(array("faction_id"=>$faction_id)));
}

public function getByGameSize($game_size){
	
    //Validate Inputs
    $game_size = $this->filterGameSize($game_size); if($game_size === false){return false;}

    return Game_players::fromArray($this->queryByColumns(array("game_size"=>$game_size)));
}

public function getByThemeForce($theme_force){
	
    //Validate Inputs
    $theme_force = $this->filterThemeForce($theme_force); if($theme_force === false){return false;}

    return Game_players::fromArray($this->queryByColumns(array("theme_force"=>$theme_force)));
}

public function getByFullyPainted($fully_painted){
	
    //Validate Inputs
    $fully_painted = $this->filterFullyPainted($fully_painted); if($fully_painted === false){return false;}

    return Game_players::fromArray($this->queryByColumns(array("fully_painted"=>$fully_painted)));
}

public static function fromArray($array){

    $output = new array();

    foreach($array as $a){

        $new = new Game_players();
    
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



function filterGameId($game_id){
    //Not allowed to be null
    if(Check::isNull($game_id)){
        echo "game_id cannot be null!"; return false;
    }

    if(Check::notInt($game_id)){
        echo "game_id was invalid!"; return false;
    }

    return intVal($game_id);
}



function filterPlayerId($player_id){
    //Not allowed to be null
    if(Check::isNull($player_id)){
        echo "player_id cannot be null!"; return false;
    }

    if(Check::notInt($player_id)){
        echo "player_id was invalid!"; return false;
    }

    return intVal($player_id);
}



function filterFactionId($faction_id){
    //Allowed to be null, catch that first
    if(Check::isNull($faction_id)){ return null; }

    if(Check::notInt($faction_id)){
        echo "faction_id was invalid!"; return false;
    }

    return intVal($faction_id);
}



function filterGameSize($game_size){
    //Allowed to be null, catch that first
    if(Check::isNull($game_size)){ return null; }

    if(Check::notInt($game_size)){
        echo "game_size was invalid!"; return false;
    }

    return intVal($game_size);
}



function filterThemeForce($theme_force){
    //Not allowed to be null
    if(Check::isNull($theme_force)){
        echo "theme_force cannot be null!"; return false;
    }

    if(Check::notBool($theme_force)){
        echo "theme_force was invalid!"; return false;
    }

    return intVal($theme_force);
}



function filterFullyPainted($fully_painted){
    //Not allowed to be null
    if(Check::isNull($fully_painted)){
        echo "fully_painted cannot be null!"; return false;
    }

    if(Check::notBool($fully_painted)){
        echo "fully_painted was invalid!"; return false;
    }

    return intVal($fully_painted);
}



}//close class

?>
