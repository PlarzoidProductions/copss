<?php

/**************************************************
*
*    Shift_registrations Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	user_id - INT
*	shift_id - INT
*
**************************************************/
require_once("query.php");

class Shift_registrations {

//DB Interaction variables
private var $db=NULL;
private var $table="shift_registrations";

//Data storage variables
public var $id=NULL;
public var $user_id=NULL;
public var $shift_id=NULL;

//List of variables for sanitization
private var $varlist = array(
	"user_id"=>"filterUserId",
	"shift_id"=>"filterShiftId");

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

    return Shift_registrations::fromArray($this->queryByColumns(array("id"=>$id)));
}

public function getByUserId($user_id){
	
    //Validate Inputs
    $user_id = $this->filterUserId($user_id); if($user_id === false){return false;}

    return Shift_registrations::fromArray($this->queryByColumns(array("user_id"=>$user_id)));
}

public function getByShiftId($shift_id){
	
    //Validate Inputs
    $shift_id = $this->filterShiftId($shift_id); if($shift_id === false){return false;}

    return Shift_registrations::fromArray($this->queryByColumns(array("shift_id"=>$shift_id)));
}

public static function fromArray($array){

    $output = new array();

    foreach($array as $a){

        $new = new Shift_registrations();
    
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



function filterUserId($user_id){
    //Not allowed to be null
    if(Check::isNull($user_id)){
        echo "user_id cannot be null!"; return false;
    }

    if(Check::notInt($user_id)){
        echo "user_id was invalid!"; return false;
    }

    return intVal($user_id);
}



function filterShiftId($shift_id){
    //Not allowed to be null
    if(Check::isNull($shift_id)){
        echo "shift_id cannot be null!"; return false;
    }

    if(Check::notInt($shift_id)){
        echo "shift_id was invalid!"; return false;
    }

    return intVal($shift_id);
}



}//close class

?>
