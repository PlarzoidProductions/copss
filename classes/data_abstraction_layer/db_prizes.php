<?php

/**************************************************
*
*    Prizes Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	description - VARCHAR
*	cost - INT
*
**************************************************/
require_once("query.php");

class Prizes {

//DB Interaction variables
private var $db=NULL;
private var $table="prizes";

//Data storage variables
public var $id=NULL;
public var $description=NULL;
public var $cost=NULL;

//List of variables for sanitization
private var $varlist = array(
	"description"=>"filterDescription",
	"cost"=>"filterCost");

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

    return Prizes::fromArray($this->queryByColumns(array("id"=>$id)));
}

public function getByDescription($description){
	
    //Validate Inputs
    $description = $this->filterDescription($description); if($description === false){return false;}

    return Prizes::fromArray($this->queryByColumns(array("description"=>$description)));
}

public function getByCost($cost){
	
    //Validate Inputs
    $cost = $this->filterCost($cost); if($cost === false){return false;}

    return Prizes::fromArray($this->queryByColumns(array("cost"=>$cost)));
}

public static function fromArray($array){

    $output = new array();

    foreach($array as $a){

        $new = new Prizes();
    
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



function filterCost($cost){
    //Not allowed to be null
    if(Check::isNull($cost)){
        echo "cost cannot be null!"; return false;
    }

    if(Check::notInt($cost)){
        echo "cost was invalid!"; return false;
    }

    return intVal($cost);
}



}//close class

?>
