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
public function create($description, $start_time, $stop_time, $tournament_id){

	//Validate the inputs
	$description = $this->filterDescription($description); if($description === false){return false;}
	$start_time = $this->filterStartTime($start_time); if($start_time === false){return false;}
	$stop_time = $this->filterStopTime($stop_time); if($stop_time === false){return false;}
	$tournament_id = $this->filterTournamentId($tournament_id); if($tournament_id === false){return false;}

	//Create the values Array
	$values = array(
		":description"=>$description,
 		":start_time"=>$start_time,
 		":stop_time"=>$stop_time,
 		":tournament_id"=>$tournament_id
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				description,
				start_time,
				stop_time,
				tournament_id
			) VALUES (
				:description,
				:start_time,
				:stop_time,
				:tournament_id)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

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
    $keys = array_keys($columns);
    foreach($keys as $column){
        $sql.= "$column=:$column";
        if(strcmp($column, end($keys))){
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


public function getByDescription($description){
	
    //Validate Inputs
    $description = $this->filterDescription($description); if($description === false){return false;}

    return $this->queryByColumns(array("description"=>$description));
}


public function getByStartTime($start_time){
	
    //Validate Inputs
    $start_time = $this->filterStartTime($start_time); if($start_time === false){return false;}

    return $this->queryByColumns(array("start_time"=>$start_time));
}


public function getByStopTime($stop_time){
	
    //Validate Inputs
    $stop_time = $this->filterStopTime($stop_time); if($stop_time === false){return false;}

    return $this->queryByColumns(array("stop_time"=>$stop_time));
}


public function getByTournamentId($tournament_id){
	
    //Validate Inputs
    $tournament_id = $this->filterTournamentId($tournament_id); if($tournament_id === false){return false;}

    return $this->queryByColumns(array("tournament_id"=>$tournament_id));
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
