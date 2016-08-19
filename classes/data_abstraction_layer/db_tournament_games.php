<?php

/**************************************************
*
*    Tournament_games Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	tournament_id - INT
*	round - INT
*	winner_id - INT
*
**************************************************/
require_once("query.php");

class Tournament_games {

//DB Interaction variables
private var $db=NULL;
private var $table="tournament_games";

//Data storage variables
public var $id=NULL;
public var $tournament_id=NULL;
public var $round=NULL;
public var $winner_id=NULL;

//List of variables for sanitization
private var $varlist = array(
	"tournament_id"=>"filterTournamentId",
	"round"=>"filterRound",
	"winner_id"=>"filterWinnerId");

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

    return Tournament_games::fromArray($this->queryByColumns(array("id"=>$id)));
}

public function getByTournamentId($tournament_id){
	
    //Validate Inputs
    $tournament_id = $this->filterTournamentId($tournament_id); if($tournament_id === false){return false;}

    return Tournament_games::fromArray($this->queryByColumns(array("tournament_id"=>$tournament_id)));
}

public function getByRound($round){
	
    //Validate Inputs
    $round = $this->filterRound($round); if($round === false){return false;}

    return Tournament_games::fromArray($this->queryByColumns(array("round"=>$round)));
}

public function getByWinnerId($winner_id){
	
    //Validate Inputs
    $winner_id = $this->filterWinnerId($winner_id); if($winner_id === false){return false;}

    return Tournament_games::fromArray($this->queryByColumns(array("winner_id"=>$winner_id)));
}

public static function fromArray($array){

    $output = new array();

    foreach($array as $a){

        $new = new Tournament_games();
    
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



function filterTournamentId($tournament_id){
    //Not allowed to be null
    if(Check::isNull($tournament_id)){
        echo "tournament_id cannot be null!"; return false;
    }

    if(Check::notInt($tournament_id)){
        echo "tournament_id was invalid!"; return false;
    }

    return intVal($tournament_id);
}



function filterRound($round){
    //Not allowed to be null
    if(Check::isNull($round)){
        echo "round cannot be null!"; return false;
    }

    if(Check::notInt($round)){
        echo "round was invalid!"; return false;
    }

    return intVal($round);
}



function filterWinnerId($winner_id){
    //Allowed to be null, catch that first
    if(Check::isNull($winner_id)){ return null; }

    if(Check::notInt($winner_id)){
        echo "winner_id was invalid!"; return false;
    }

    return intVal($winner_id);
}



}//close class

?>
