<?php

/**************************************************
*
*    Tournament_game_details Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	game_id - INT
*	player_id - INT
*	list_played - INT
*	control_points - INT
*	destruction_points - INT
*	assassination_efficiency - INT
*	timed_out - TINYINT
*
**************************************************/
require_once("query.php");

class Tournament_game_details {

//DB Interaction variables
private var $db=NULL;
private var $table="tournament_game_details";

//Data storage variables
public var $id=NULL;
public var $game_id=NULL;
public var $player_id=NULL;
public var $list_played=NULL;
public var $control_points=NULL;
public var $destruction_points=NULL;
public var $assassination_efficiency=NULL;
public var $timed_out=NULL;

//List of variables for sanitization
private var $varlist = array(
	"game_id"=>"filterGameId",
	"player_id"=>"filterPlayerId",
	"list_played"=>"filterListPlayed",
	"control_points"=>"filterControlPoints",
	"destruction_points"=>"filterDestructionPoints",
	"assassination_efficiency"=>"filterAssassinationEfficiency",
	"timed_out"=>"filterTimedOut");

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

    return Tournament_game_details::fromArray($this->queryByColumns(array("id"=>$id)));
}

public function getByGameId($game_id){
	
    //Validate Inputs
    $game_id = $this->filterGameId($game_id); if($game_id === false){return false;}

    return Tournament_game_details::fromArray($this->queryByColumns(array("game_id"=>$game_id)));
}

public function getByPlayerId($player_id){
	
    //Validate Inputs
    $player_id = $this->filterPlayerId($player_id); if($player_id === false){return false;}

    return Tournament_game_details::fromArray($this->queryByColumns(array("player_id"=>$player_id)));
}

public function getByListPlayed($list_played){
	
    //Validate Inputs
    $list_played = $this->filterListPlayed($list_played); if($list_played === false){return false;}

    return Tournament_game_details::fromArray($this->queryByColumns(array("list_played"=>$list_played)));
}

public function getByControlPoints($control_points){
	
    //Validate Inputs
    $control_points = $this->filterControlPoints($control_points); if($control_points === false){return false;}

    return Tournament_game_details::fromArray($this->queryByColumns(array("control_points"=>$control_points)));
}

public function getByDestructionPoints($destruction_points){
	
    //Validate Inputs
    $destruction_points = $this->filterDestructionPoints($destruction_points); if($destruction_points === false){return false;}

    return Tournament_game_details::fromArray($this->queryByColumns(array("destruction_points"=>$destruction_points)));
}

public function getByAssassinationEfficiency($assassination_efficiency){
	
    //Validate Inputs
    $assassination_efficiency = $this->filterAssassinationEfficiency($assassination_efficiency); if($assassination_efficiency === false){return false;}

    return Tournament_game_details::fromArray($this->queryByColumns(array("assassination_efficiency"=>$assassination_efficiency)));
}

public function getByTimedOut($timed_out){
	
    //Validate Inputs
    $timed_out = $this->filterTimedOut($timed_out); if($timed_out === false){return false;}

    return Tournament_game_details::fromArray($this->queryByColumns(array("timed_out"=>$timed_out)));
}

public static function fromArray($array){

    $output = new array();

    foreach($array as $a){

        $new = new Tournament_game_details();
    
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



function filterListPlayed($list_played){
    //Not allowed to be null
    if(Check::isNull($list_played)){
        echo "list_played cannot be null!"; return false;
    }

    if(Check::notInt($list_played)){
        echo "list_played was invalid!"; return false;
    }

    return intVal($list_played);
}



function filterControlPoints($control_points){
    //Not allowed to be null
    if(Check::isNull($control_points)){
        echo "control_points cannot be null!"; return false;
    }

    if(Check::notInt($control_points)){
        echo "control_points was invalid!"; return false;
    }

    return intVal($control_points);
}



function filterDestructionPoints($destruction_points){
    //Not allowed to be null
    if(Check::isNull($destruction_points)){
        echo "destruction_points cannot be null!"; return false;
    }

    if(Check::notInt($destruction_points)){
        echo "destruction_points was invalid!"; return false;
    }

    return intVal($destruction_points);
}



function filterAssassinationEfficiency($assassination_efficiency){
    //Allowed to be null, catch that first
    if(Check::isNull($assassination_efficiency)){ return null; }

    if(Check::notInt($assassination_efficiency)){
        echo "assassination_efficiency was invalid!"; return false;
    }

    return intVal($assassination_efficiency);
}



function filterTimedOut($timed_out){
    //Not allowed to be null
    if(Check::isNull($timed_out)){
        echo "timed_out cannot be null!"; return false;
    }

    if(Check::notBool($timed_out)){
        echo "timed_out was invalid!"; return false;
    }

    return intVal($timed_out);
}



}//close class

?>
