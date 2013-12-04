<?php

/**************************************************
*
*    States Class
*
***************************************************/
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
	if(!Check::isString($name)){return false;}
	if(!Check::isInt($country_id)){return false;}

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
	if(!Check::isInt($id)){return false;}

	return getStatesByColumn("id", $id.);
}


public function getStatesByName($name){
	
	//Validate Inputs
	if(!Check::isString($name)){return false;}

	return getStatesByColumn("name", $name.);
}


public function getStatesByCountry_id($country_id){
	
	//Validate Inputs
	if(!Check::isInt($country_id)){return false;}

	return getStatesByColumn("country_id", $country_id.);
}

}//close class

?>
