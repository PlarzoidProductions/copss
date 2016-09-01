<?php

/**************************************************
*
*    Games Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	creation_time - TIMESTAMP
*	game_system - INT
*	scenario - TINYINT
*
**************************************************/
require_once("query.php");

class Games {

//DB Interaction variables
private var $db=NULL;
private var $table="games";

//Data storage variables
public var $id=NULL;
public var $creation_time=NULL;
public var $game_system=NULL;
public var $scenario=NULL;

//List of variables for sanitization
private var $varlist = array(
	"creation_time"=>"filterCreationTime",
	"game_system"=>"filterGameSystem",
	"scenario"=>"filterScenario");

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

    return Games::fromArray($this->queryByColumns(array("id"=>$id)));
}

public function getByCreationTime($creation_time){
	
    //Validate Inputs
    $creation_time = $this->filterCreationTime($creation_time); if($creation_time === false){return false;}

    return Games::fromArray($this->queryByColumns(array("creation_time"=>$creation_time)));
}

public function getByGameSystem($game_system){
	
    //Validate Inputs
    $game_system = $this->filterGameSystem($game_system); if($game_system === false){return false;}

    return Games::fromArray($this->queryByColumns(array("game_system"=>$game_system)));
}

public function getByScenario($scenario){
	
    //Validate Inputs
    $scenario = $this->filterScenario($scenario); if($scenario === false){return false;}

    return Games::fromArray($this->queryByColumns(array("scenario"=>$scenario)));
}

public static function fromArray($array){

    $output = new array();

    foreach($array as $a){

        $new = new Games();
    
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



function filterCreationTime($creation_time){
    //Not allowed to be null
    if(Check::isNull($creation_time)){
        echo "creation_time cannot be null!"; return false;
    }

    if(Check::isNull($creation_time)){
        echo "creation_time was invalid!"; return false;
    }

    return date("Y-m-d H:i:s", $creation_time);
}



function filterGameSystem($game_system){
    //Not allowed to be null
    if(Check::isNull($game_system)){
        echo "game_system cannot be null!"; return false;
    }

    if(Check::notInt($game_system)){
        echo "game_system was invalid!"; return false;
    }

    return intVal($game_system);
}



function filterScenario($scenario){
    //Not allowed to be null
    if(Check::isNull($scenario)){
        echo "scenario cannot be null!"; return false;
    }

    if(Check::notBool($scenario)){
        echo "scenario was invalid!"; return false;
    }

    return intVal($scenario);
}



}//close class

?>
