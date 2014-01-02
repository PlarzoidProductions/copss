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
*	name - VARCHAR
*	start - DATETIME
*	stop - DATETIME
*
**************************************************/
require_once("query.php");

class Shifts {

var $db=NULL;
var $table="shifts";


/***************************************************

Constructor & Destructor

***************************************************/
public function __construct(){
    $this->db = Query::getInstance();
}

public function __destruct(){}


/**************************************************

Create Function

**************************************************/
public function create($name, $start, $stop){

	//Validate the inputs
	$name = $this->filterName($name); if($name === false){return false;}
	$start = $this->filterStart($start); if($start === false){return false;}
	$stop = $this->filterStop($stop); if($stop === false){return false;}

	//Create the values Array
	$values = array(
		":name"=>$name,
 		":start"=>$start,
 		":stop"=>$stop
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				name,
				start,
				stop
			) VALUES (
				:name,
				:start,
				:stop)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

**************************************************/
public function deleteByColumns($columns){

    //Create the values array
    $values = array();
    foreach($columns as $column){
        $values[":".$column]=$value;
    }

    //Create Query\n";
    $sql = "DELETE FROM $this->table WHERE ";
    $keys = array_keys($columns);
    foreach($keys as $column){
        $sql.= "$column=:$column";
        if(strcmp($column, end($keys))){
            $sql.= ", ";
        }
    }

    return $this->db->delete($sql, $values);
}

public function deleteById($id){
    return $this->deleteByColumns(array("id"=>$id));
}


/**************************************************

Update Record By ID Function(s)

**************************************************/
public function updateShiftsById($id, $columns){

    //Values Array
    $values = array(":id"=>$id);
    foreach($columns as $column=>$value){
        $values[":".$column]=$value;
    }

    //Generate the query
    $sql = "UPDATE $this->table SET ";
    foreach(array_keys($columns) as $column){
        $sql.= "$column=:$column";
        if(strcmp($column, end(array_keys($columns)))){
            $sql.= ", ";
        }
    }
    $sql.= " WHERE id=:id";

    return $this->db->update($sql, $values);
}


/**************************************************

Query Everything

**************************************************/
public function getAll(){

    //Generate the query
    $sql = "SELECT * FROM $this->table";

    return $this->db->query($sql, array());
}


/**************************************************

Query by Column(s) Function

**************************************************/
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

    return $this->queryByColumns(array("id"=>$id));
}


public function getByName($name){
	
    //Validate Inputs
    $name = $this->filterName($name); if($name === false){return false;}

    return $this->queryByColumns(array("name"=>$name));
}


public function getByStart($start){
	
    //Validate Inputs
    $start = $this->filterStart($start); if($start === false){return false;}

    return $this->queryByColumns(array("start"=>$start));
}


public function getByStop($stop){
	
    //Validate Inputs
    $stop = $this->filterStop($stop); if($stop === false){return false;}

    return $this->queryByColumns(array("stop"=>$stop));
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



function filterStart($start){
    //Not allowed to be null
    if(Check::isNull($start)){
        echo "start cannot be null!"; return false;
    }

    if(Check::isNull($start)){
        echo "start was invalid!"; return false;
    }

    return $start;
}



function filterStop($stop){
    //Not allowed to be null
    if(Check::isNull($stop)){
        echo "stop cannot be null!"; return false;
    }

    if(Check::isNull($stop)){
        echo "stop was invalid!"; return false;
    }

    return $stop;
}



}//close class

?>
