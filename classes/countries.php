<?php

/**************************************************
*
*    Countries Class
*
***************************************************/
require_once("query.php");

class Countries {

var $db=NULL;
var $table="countries";


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
public function createCountries($name){

	//Validate the inputs
	if(!Check::isString($name)){return false;}

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
public function deleteCountries($id){

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
private function getCountriesByColumn($column, $value){

	//inputs are pre-verified by the mapping functions below, so we can trust them

	//Values Array
	$values = array(":$column"=>$value);

	//Generate the query
	$sql = "SELECT * FROM $this->table WHERE $column=:$column";
    
	return $this->db->query($sql, $values);
}


public function getCountriesById($id){
	
	//Validate Inputs
	if(!Check::isInt($id)){return false;}

	return getCountriesByColumn("id", $id.);
}


public function getCountriesByName($name){
	
	//Validate Inputs
	if(!Check::isString($name)){return false;}

	return getCountriesByColumn("name", $name.);
}

}//close class

?>
