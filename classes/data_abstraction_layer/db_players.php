<?php

/**************************************************
*
*    Players Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	first_name - VARCHAR
*	last_name - VARCHAR
*	country - INT
*	state - INT
*	vip - TINYINT
*	creation_date - DATETIME
*
**************************************************/
require_once("query.php");

class Players {

//DB Interaction variables
private var $db=NULL;
private var $table="players";

//Data storage variables
public var $id=NULL;
public var $first_name=NULL;
public var $last_name=NULL;
public var $country=NULL;
public var $state=NULL;
public var $vip=NULL;
public var $creation_date=NULL;

//List of variables for sanitization
private var $varlist = array(
	"first_name"=>"filterFirstName",
	"last_name"=>"filterLastName",
	"country"=>"filterCountry",
	"state"=>"filterState",
	"vip"=>"filterVip",
	"creation_date"=>"filterCreationDate");

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

    return Players::fromArray($this->queryByColumns(array("id"=>$id)));
}

public function getByFirstName($first_name){
	
    //Validate Inputs
    $first_name = $this->filterFirstName($first_name); if($first_name === false){return false;}

    return Players::fromArray($this->queryByColumns(array("first_name"=>$first_name)));
}

public function getByLastName($last_name){
	
    //Validate Inputs
    $last_name = $this->filterLastName($last_name); if($last_name === false){return false;}

    return Players::fromArray($this->queryByColumns(array("last_name"=>$last_name)));
}

public function getByCountry($country){
	
    //Validate Inputs
    $country = $this->filterCountry($country); if($country === false){return false;}

    return Players::fromArray($this->queryByColumns(array("country"=>$country)));
}

public function getByState($state){
	
    //Validate Inputs
    $state = $this->filterState($state); if($state === false){return false;}

    return Players::fromArray($this->queryByColumns(array("state"=>$state)));
}

public function getByVip($vip){
	
    //Validate Inputs
    $vip = $this->filterVip($vip); if($vip === false){return false;}

    return Players::fromArray($this->queryByColumns(array("vip"=>$vip)));
}

public function getByCreationDate($creation_date){
	
    //Validate Inputs
    $creation_date = $this->filterCreationDate($creation_date); if($creation_date === false){return false;}

    return Players::fromArray($this->queryByColumns(array("creation_date"=>$creation_date)));
}

public static function fromArray($array){

    $output = new array();

    foreach($array as $a){

        $new = new Players();
    
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



function filterFirstName($first_name){
    //Not allowed to be null
    if(Check::isNull($first_name)){
        echo "first_name cannot be null!"; return false;
    }

    if(Check::notString($first_name)){
        echo "first_name was invalid!"; return false;
    }

    return $first_name;
}



function filterLastName($last_name){
    //Not allowed to be null
    if(Check::isNull($last_name)){
        echo "last_name cannot be null!"; return false;
    }

    if(Check::notString($last_name)){
        echo "last_name was invalid!"; return false;
    }

    return $last_name;
}



function filterCountry($country){
    //Not allowed to be null
    if(Check::isNull($country)){
        echo "country cannot be null!"; return false;
    }

    if(Check::notInt($country)){
        echo "country was invalid!"; return false;
    }

    return intVal($country);
}



function filterState($state){
    //Allowed to be null, catch that first
    if(Check::isNull($state)){ return null; }

    if(Check::notInt($state)){
        echo "state was invalid!"; return false;
    }

    return intVal($state);
}



function filterVip($vip){
    //Allowed to be null, catch that first
    if(Check::isNull($vip)){ return null; }

    if(Check::notBool($vip)){
        echo "vip was invalid!"; return false;
    }

    return intVal($vip);
}



function filterCreationDate($creation_date){
    //Not allowed to be null
    if(Check::isNull($creation_date)){
        echo "creation_date cannot be null!"; return false;
    }

    if(Check::isNull($creation_date)){
        echo "creation_date was invalid!"; return false;
    }

    return date("Y-m-d H:i:s", $creation_date);
}



}//close class

?>
