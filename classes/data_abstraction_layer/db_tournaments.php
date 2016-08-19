<?php

/**************************************************
*
*    Tournaments Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	name - VARCHAR
*	game_system_id - INT
*	max_num_players - INT
*	max_num_rounds - INT
*	num_lists_required - INT
*	divide_and_conquer - INT
*	standings_type - VARCHAR
*	final_tables - TINYINT
*	large_event_scoring - TINYINT
*
**************************************************/
require_once("query.php");

class Tournaments {

//DB Interaction variables
private var $db=NULL;
private var $table="tournaments";

//Data storage variables
public var $id=NULL;
public var $name=NULL;
public var $game_system_id=NULL;
public var $max_num_players=NULL;
public var $max_num_rounds=NULL;
public var $num_lists_required=NULL;
public var $divide_and_conquer=NULL;
public var $standings_type=NULL;
public var $final_tables=NULL;
public var $large_event_scoring=NULL;

//List of variables for sanitization
private var $varlist = array(
	"name"=>"filterName",
	"game_system_id"=>"filterGameSystemId",
	"max_num_players"=>"filterMaxNumPlayers",
	"max_num_rounds"=>"filterMaxNumRounds",
	"num_lists_required"=>"filterNumListsRequired",
	"divide_and_conquer"=>"filterDivideAndConquer",
	"standings_type"=>"filterStandingsType",
	"final_tables"=>"filterFinalTables",
	"large_event_scoring"=>"filterLargeEventScoring");

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

    return Tournaments::fromArray($this->queryByColumns(array("id"=>$id)));
}

public function getByName($name){
	
    //Validate Inputs
    $name = $this->filterName($name); if($name === false){return false;}

    return Tournaments::fromArray($this->queryByColumns(array("name"=>$name)));
}

public function getByGameSystemId($game_system_id){
	
    //Validate Inputs
    $game_system_id = $this->filterGameSystemId($game_system_id); if($game_system_id === false){return false;}

    return Tournaments::fromArray($this->queryByColumns(array("game_system_id"=>$game_system_id)));
}

public function getByMaxNumPlayers($max_num_players){
	
    //Validate Inputs
    $max_num_players = $this->filterMaxNumPlayers($max_num_players); if($max_num_players === false){return false;}

    return Tournaments::fromArray($this->queryByColumns(array("max_num_players"=>$max_num_players)));
}

public function getByMaxNumRounds($max_num_rounds){
	
    //Validate Inputs
    $max_num_rounds = $this->filterMaxNumRounds($max_num_rounds); if($max_num_rounds === false){return false;}

    return Tournaments::fromArray($this->queryByColumns(array("max_num_rounds"=>$max_num_rounds)));
}

public function getByNumListsRequired($num_lists_required){
	
    //Validate Inputs
    $num_lists_required = $this->filterNumListsRequired($num_lists_required); if($num_lists_required === false){return false;}

    return Tournaments::fromArray($this->queryByColumns(array("num_lists_required"=>$num_lists_required)));
}

public function getByDivideAndConquer($divide_and_conquer){
	
    //Validate Inputs
    $divide_and_conquer = $this->filterDivideAndConquer($divide_and_conquer); if($divide_and_conquer === false){return false;}

    return Tournaments::fromArray($this->queryByColumns(array("divide_and_conquer"=>$divide_and_conquer)));
}

public function getByStandingsType($standings_type){
	
    //Validate Inputs
    $standings_type = $this->filterStandingsType($standings_type); if($standings_type === false){return false;}

    return Tournaments::fromArray($this->queryByColumns(array("standings_type"=>$standings_type)));
}

public function getByFinalTables($final_tables){
	
    //Validate Inputs
    $final_tables = $this->filterFinalTables($final_tables); if($final_tables === false){return false;}

    return Tournaments::fromArray($this->queryByColumns(array("final_tables"=>$final_tables)));
}

public function getByLargeEventScoring($large_event_scoring){
	
    //Validate Inputs
    $large_event_scoring = $this->filterLargeEventScoring($large_event_scoring); if($large_event_scoring === false){return false;}

    return Tournaments::fromArray($this->queryByColumns(array("large_event_scoring"=>$large_event_scoring)));
}

public static function fromArray($array){

    $output = new array();

    foreach($array as $a){

        $new = new Tournaments();
    
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



function filterGameSystemId($game_system_id){
    //Not allowed to be null
    if(Check::isNull($game_system_id)){
        echo "game_system_id cannot be null!"; return false;
    }

    if(Check::notInt($game_system_id)){
        echo "game_system_id was invalid!"; return false;
    }

    return intVal($game_system_id);
}



function filterMaxNumPlayers($max_num_players){
    //Not allowed to be null
    if(Check::isNull($max_num_players)){
        echo "max_num_players cannot be null!"; return false;
    }

    if(Check::notInt($max_num_players)){
        echo "max_num_players was invalid!"; return false;
    }

    return intVal($max_num_players);
}



function filterMaxNumRounds($max_num_rounds){
    //Allowed to be null, catch that first
    if(Check::isNull($max_num_rounds)){ return null; }

    if(Check::notInt($max_num_rounds)){
        echo "max_num_rounds was invalid!"; return false;
    }

    return intVal($max_num_rounds);
}



function filterNumListsRequired($num_lists_required){
    //Not allowed to be null
    if(Check::isNull($num_lists_required)){
        echo "num_lists_required cannot be null!"; return false;
    }

    if(Check::notInt($num_lists_required)){
        echo "num_lists_required was invalid!"; return false;
    }

    return intVal($num_lists_required);
}



function filterDivideAndConquer($divide_and_conquer){
    //Not allowed to be null
    if(Check::isNull($divide_and_conquer)){
        echo "divide_and_conquer cannot be null!"; return false;
    }

    if(Check::notInt($divide_and_conquer)){
        echo "divide_and_conquer was invalid!"; return false;
    }

    return intVal($divide_and_conquer);
}



function filterStandingsType($standings_type){
    //Not allowed to be null
    if(Check::isNull($standings_type)){
        echo "standings_type cannot be null!"; return false;
    }

    if(Check::notString($standings_type)){
        echo "standings_type was invalid!"; return false;
    }

    return $standings_type;
}



function filterFinalTables($final_tables){
    //Not allowed to be null
    if(Check::isNull($final_tables)){
        echo "final_tables cannot be null!"; return false;
    }

    if(Check::notBool($final_tables)){
        echo "final_tables was invalid!"; return false;
    }

    return intVal($final_tables);
}



function filterLargeEventScoring($large_event_scoring){
    //Not allowed to be null
    if(Check::isNull($large_event_scoring)){
        echo "large_event_scoring cannot be null!"; return false;
    }

    if(Check::notBool($large_event_scoring)){
        echo "large_event_scoring was invalid!"; return false;
    }

    return intVal($large_event_scoring);
}



}//close class

?>
