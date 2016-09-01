<?php

/**************************************************
*
*    Shifts Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	description - VARCHAR
*	start_time - DATETIME
*	stop_time - DATETIME
*	tournament_id - INT
*
**************************************************/
require_once("query.php");

class Shifts {

//DB Interaction variables
private var $db=NULL;
private var $table="shifts";

//Data storage variables
public var $id=NULL;
public var $description=NULL;
public var $start_time=NULL;
public var $stop_time=NULL;
public var $tournament_id=NULL;

//List of variables for sanitization
private var $varlist = array(
	"description"=>"filterDescription",
	"start_time"=>"filterStartTime",
	"stop_time"=>"filterStopTime",
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

    return Shifts::fromArray($this->queryByColumns(array("id"=>$id)));
}

public function getByDescription($description){
	
    //Validate Inputs
    $description = $this->filterDescription($description); if($description === false){return false;}

    return Shifts::fromArray($this->queryByColumns(array("description"=>$description)));
}

public function getByStartTime($start_time){
	
    //Validate Inputs
    $start_time = $this->filterStartTime($start_time); if($start_time === false){return false;}

    return Shifts::fromArray($this->queryByColumns(array("start_time"=>$start_time)));
}

public function getByStopTime($stop_time){
	
    //Validate Inputs
    $stop_time = $this->filterStopTime($stop_time); if($stop_time === false){return false;}

    return Shifts::fromArray($this->queryByColumns(array("stop_time"=>$stop_time)));
}

public function getByTournamentId($tournament_id){
	
    //Validate Inputs
    $tournament_id = $this->filterTournamentId($tournament_id); if($tournament_id === false){return false;}

    return Shifts::fromArray($this->queryByColumns(array("tournament_id"=>$tournament_id)));
}

public static function fromArray($array){

    $output = new array();

    foreach($array as $a){

        $new = new Shifts();
    
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



function filterDescription($description){
    //Not allowed to be null
    if(Check::isNull($description)){
        echo "description cannot be null!"; return false;
    }

    if(Check::notString($description)){
        echo "description was invalid!"; return false;
    }

    return $description;
}



function filterStartTime($start_time){
    //Not allowed to be null
    if(Check::isNull($start_time)){
        echo "start_time cannot be null!"; return false;
    }

    if(Check::isNull($start_time)){
        echo "start_time was invalid!"; return false;
    }

    return date("Y-m-d H:i:s", $start_time);
}



function filterStopTime($stop_time){
    //Not allowed to be null
    if(Check::isNull($stop_time)){
        echo "stop_time cannot be null!"; return false;
    }

    if(Check::isNull($stop_time)){
        echo "stop_time was invalid!"; return false;
    }

    return date("Y-m-d H:i:s", $stop_time);
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



}//close class

?>
