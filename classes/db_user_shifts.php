<?php

/**************************************************
*
*    User_shifts Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	user_id - INT
*	shift_id - INT
*	checked_in - TINYINT
*	completed - TINYINT
*
**************************************************/
require_once("query.php");

class User_shifts {

var $db=NULL;
var $table="user_shifts";


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
public function create($user_id, $shift_id, $checked_in, $completed){

	//Validate the inputs
	$user_id = $this->filterUserId($user_id); if($user_id === false){return false;}
	$shift_id = $this->filterShiftId($shift_id); if($shift_id === false){return false;}
	$checked_in = $this->filterCheckedIn($checked_in); if($checked_in === false){return false;}
	$completed = $this->filterCompleted($completed); if($completed === false){return false;}

	//Create the values Array
	$values = array(
		":user_id"=>$user_id,
 		":shift_id"=>$shift_id,
 		":checked_in"=>$checked_in,
 		":completed"=>$completed
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				user_id,
				shift_id,
				checked_in,
				completed
			) VALUES (
				:user_id,
				:shift_id,
				:checked_in,
				:completed)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

**************************************************/
public function deleteUser_shifts($id){

	//Create the values array
	$values = array(":id"=>$id);

	//Create Query
	$sql = "DELETE FROM $this->table WHERE id=:id";

	return $this->db->delete($sql, $values);
}


/**************************************************

Update Record By ID Function(s)

**************************************************/
private function updateUser_shiftsById($id, $columns){

    //Values Array
    $values = array(":id"=>$id);
    foreach($columns as $column=>$value){
        $values[":".$column]=$value;
    }

    //Generate the query
    $sql = "UPDATE $this->table SET ";
    foreach(array_keys($columns) as $column){
        $sql.= "$column=:$column";
        if(strcmp($column, end($array_keys($columns)))){
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

Query By Column Function(s)

**************************************************/
private function getByColumn($column, $value){

    //inputs are pre-verified by the mapping functions below, so we can trust them

    //Values Array
    $values = array(":$column"=>$value);

    //Generate the query
    $sql = "SELECT * FROM $this->table WHERE $column=:$column";
    
    return $this->db->query($sql, $values);
}


public function getById($id){
	
    //Validate Inputs
    $id = $this->filterId($id); if($id === false){return false;}

    return $this->getByColumn("id", $id);
}


public function getByUserId($user_id){
	
    //Validate Inputs
    $user_id = $this->filterUserId($user_id); if($user_id === false){return false;}

    return $this->getByColumn("user_id", $user_id);
}


public function getByShiftId($shift_id){
	
    //Validate Inputs
    $shift_id = $this->filterShiftId($shift_id); if($shift_id === false){return false;}

    return $this->getByColumn("shift_id", $shift_id);
}


public function getByCheckedIn($checked_in){
	
    //Validate Inputs
    $checked_in = $this->filterCheckedIn($checked_in); if($checked_in === false){return false;}

    return $this->getByColumn("checked_in", $checked_in);
}


public function getByCompleted($completed){
	
    //Validate Inputs
    $completed = $this->filterCompleted($completed); if($completed === false){return false;}

    return $this->getByColumn("completed", $completed);
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

    return $id;
}



function filterUserId($user_id){
    //Not allowed to be null
    if(Check::isNull($user_id)){
        echo "user_id cannot be null!"; return false;
    }

    if(Check::notInt($user_id)){
        echo "user_id was invalid!"; return false;
    }

    return $user_id;
}



function filterShiftId($shift_id){
    //Not allowed to be null
    if(Check::isNull($shift_id)){
        echo "shift_id cannot be null!"; return false;
    }

    if(Check::notInt($shift_id)){
        echo "shift_id was invalid!"; return false;
    }

    return $shift_id;
}



function filterCheckedIn($checked_in){
    //Not allowed to be null
    if(Check::isNull($checked_in)){
        echo "checked_in cannot be null!"; return false;
    }

    if(Check::notBool($checked_in)){
        echo "checked_in was invalid!"; return false;
    }

    return $checked_in;
}



function filterCompleted($completed){
    //Not allowed to be null
    if(Check::isNull($completed)){
        echo "completed cannot be null!"; return false;
    }

    if(Check::notBool($completed)){
        echo "completed was invalid!"; return false;
    }

    return $completed;
}



}//close class

?>
