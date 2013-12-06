<?php

/**************************************************
*
*    Events Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	name - VARCHAR
*
**************************************************/
require_once("query.php");

class Events {

var $db=NULL;
var $table="events";


/***************************************************

Constructor & Destructor

***************************************************/
public function __construct(){
    $this->db = new Query();
}

public function __destruct(){}


/**************************************************

Create Function

**************************************************/
public function createEvents($name){

	//Validate the inputs
	if(Check::notString($name)){return false;}

	//Create the values Array
	$values = array(
		":name"=>$name
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				name
			) VALUES (
				:name)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

**************************************************/
public function deleteEvents($id){

	//Validate the input
	if(Check::isInt($id)){return false;}

	//Create the values array
	$values = array(":id"=>$id);

	//Create Query
	$sql = "DELETE FROM $this->table WHERE id=:id";

	return $this->db->delete($sql, $values);
}


/**************************************************

Update Record By ID Function(s)

**************************************************/
private function updateEventsById($id, $columns){

    //Values Array
    $values = array(":id"=>$id);
    foreach($columns as $column=>$value){
        $values[":".$column]=$value;
    }

    //Generate the query
    $sql = "UPDATE $this->table SET ";
    foreach(array_keys($columns) as $column){
        $sql.= "$column=:$column";
        if(strcmp($column, end($array_keys($columns))){
            $sql.= ", ";
        }
    }
    $sql.= " WHERE id=:id";

    return $this->db->update($sql, $values);
}


/**************************************************

Query By Column Function(s)

**************************************************/
private function getEventsByColumn($column, $value){

    //inputs are pre-verified by the mapping functions below, so we can trust them

    //Values Array
    $values = array(":$column"=>$value);

    //Generate the query
    $sql = "SELECT * FROM $this->table WHERE $column=:$column";
    
    return $this->db->query($sql, $values);
}


public function getEventsById($id){
	
    //Validate Inputs
    if(Check::notInt($id)){return false;}

    return getEventsByColumn("id", $id.);
}


public function getEventsByName($name){
	
    //Validate Inputs
    if(Check::notString($name)){return false;}

    return getEventsByColumn("name", $name.);
}

}//close class

?>
