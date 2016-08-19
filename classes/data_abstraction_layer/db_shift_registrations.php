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

var $db=NULL;
var $table="shift_registrations";


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
public function create($user_id, $shift_id){

	//Validate the inputs
	$user_id = $this->filterUserId($user_id); if($user_id === false){return false;}
	$shift_id = $this->filterShiftId($shift_id); if($shift_id === false){return false;}

	//Create the values Array
	$values = array(
		":user_id"=>$user_id,
 		":shift_id"=>$shift_id
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				user_id,
				shift_id
			) VALUES (
				:user_id,
				:shift_id)";

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
public function updateShift_registrationsById($id, $columns){

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


public function getByUserId($user_id){
	
    //Validate Inputs
    $user_id = $this->filterUserId($user_id); if($user_id === false){return false;}

    return $this->queryByColumns(array("user_id"=>$user_id));
}


public function getByShiftId($shift_id){
	
    //Validate Inputs
    $shift_id = $this->filterShiftId($shift_id); if($shift_id === false){return false;}

    return $this->queryByColumns(array("shift_id"=>$shift_id));
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
