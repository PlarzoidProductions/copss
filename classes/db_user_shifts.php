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
	if(!$this->checkUserId($user_id)){return false;}
	if(!$this->checkShiftId($shift_id)){return false;}
	if(!$this->checkCheckedIn($checked_in)){return false;}
	if(!$this->checkCompleted($completed)){return false;}

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

	//Validate the input
	if(!$this->checkUserId($user_id)){return false;}
	if(!$this->checkShiftId($shift_id)){return false;}
	if(!$this->checkCheckedIn($checked_in)){return false;}
	if(!$this->checkCompleted($completed)){return false;}
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
    if(!$this->checkId($id)){return false;}

    return $this->getByColumn("id", $id);
}


public function getByUserId($user_id){
	
    //Validate Inputs
    if(!$this->checkUserId($user_id)){return false;}

    return $this->getByColumn("user_id", $user_id);
}


public function getByShiftId($shift_id){
	
    //Validate Inputs
    if(!$this->checkShiftId($shift_id)){return false;}

    return $this->getByColumn("shift_id", $shift_id);
}


public function getByCheckedIn($checked_in){
	
    //Validate Inputs
    if(!$this->checkCheckedIn($checked_in)){return false;}

    return $this->getByColumn("checked_in", $checked_in);
}


public function getByCompleted($completed){
	
    //Validate Inputs
    if(!$this->checkCompleted($completed)){return false;}

    return $this->getByColumn("completed", $completed);
}


/**************************************************
 
Column Validation Function(s)

**************************************************/
function checkId($id){
    //Not allowed to be null
    if(Check::isNull($id)){
        echo "id cannot be null!"; return false;
    }

    if(Check::notInt($id)){
        echo "id was invalid!"; return false;
    }

    return true;
}



function checkUserId($user_id){
    //Not allowed to be null
    if(Check::isNull($user_id)){
        echo "user_id cannot be null!"; return false;
    }

    if(Check::notInt($user_id)){
        echo "user_id was invalid!"; return false;
    }

    return true;
}



function checkShiftId($shift_id){
    //Not allowed to be null
    if(Check::isNull($shift_id)){
        echo "shift_id cannot be null!"; return false;
    }

    if(Check::notInt($shift_id)){
        echo "shift_id was invalid!"; return false;
    }

    return true;
}



function checkCheckedIn($checked_in){
    //Not allowed to be null
    if(Check::isNull($checked_in)){
        echo "checked_in cannot be null!"; return false;
    }

    if(Check::notBool($checked_in)){
        echo "checked_in was invalid!"; return false;
    }

    return true;
}



function checkCompleted($completed){
    //Not allowed to be null
    if(Check::isNull($completed)){
        echo "completed cannot be null!"; return false;
    }

    if(Check::notBool($completed)){
        echo "completed was invalid!"; return false;
    }

    return true;
}



}//close class

?>
