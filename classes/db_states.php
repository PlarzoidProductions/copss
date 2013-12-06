<?php

/**************************************************
*
*    States Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	name - VARCHAR
*	country_id - INT
*
**************************************************/
require_once("query.php");

class States {

var $db=NULL;
var $table="states";


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
public function createStates($name, $country_id){

	//Validate the inputs
	if(Check::notString($name)){return false;}
	if(Check::notInt($country_id)){return false;}

	//Create the values Array
	$values = array(
		":name"=>$name,
 		":country_id"=>$country_id
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				name,
				country_id
			) VALUES (
				:name,
				:country_id)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

**************************************************/
public function deleteStates($id){

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
private function updateStatesById($id, $columns){

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
private function getStatesByColumn($column, $value){

    //inputs are pre-verified by the mapping functions below, so we can trust them

    //Values Array
    $values = array(":$column"=>$value);

    //Generate the query
    $sql = "SELECT * FROM $this->table WHERE $column=:$column";
    
    return $this->db->query($sql, $values);
}


public function getStatesById($id){
	
    //Validate Inputs
    if(Check::notInt($id)){return false;}

    return getStatesByColumn("id", $id.);
}


public function getStatesByName($name){
	
    //Validate Inputs
    if(Check::notString($name)){return false;}

    return getStatesByColumn("name", $name.);
}


public function getStatesByCountryId($country_id){
	
    //Validate Inputs
    if(Check::notInt($country_id)){return false;}

    return getStatesByColumn("country_id", $country_id.);
}

}//close class

?>
