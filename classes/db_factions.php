<?php

/**************************************************
*
*    Factions Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	game_system_id - INT
*	name - VARCHAR
*	acronym - VARCHAR
*
**************************************************/
require_once("query.php");

class Factions {

var $db=NULL;
var $table="factions";


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
public function createFactions($game_system_id, $name, $acronym){

	//Validate the inputs
	if(Check::notInt($game_system_id)){return false;}
	if(Check::notString($name)){return false;}
	if(Check::notString($acronym)){return false;}

	//Create the values Array
	$values = array(
		":game_system_id"=>$game_system_id,
 		":name"=>$name,
 		":acronym"=>$acronym
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				game_system_id,
				name,
				acronym
			) VALUES (
				:game_system_id,
				:name,
				:acronym)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

**************************************************/
public function deleteFactions($id){

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
private function updateFactionsById($id, $columns){

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
private function getFactionsByColumn($column, $value){

    //inputs are pre-verified by the mapping functions below, so we can trust them

    //Values Array
    $values = array(":$column"=>$value);

    //Generate the query
    $sql = "SELECT * FROM $this->table WHERE $column=:$column";
    
    return $this->db->query($sql, $values);
}


public function getFactionsById($id){
	
    //Validate Inputs
    if(Check::notInt($id)){return false;}

    return getFactionsByColumn("id", $id.);
}


public function getFactionsByGameSystemId($game_system_id){
	
    //Validate Inputs
    if(Check::notInt($game_system_id)){return false;}

    return getFactionsByColumn("game_system_id", $game_system_id.);
}


public function getFactionsByName($name){
	
    //Validate Inputs
    if(Check::notString($name)){return false;}

    return getFactionsByColumn("name", $name.);
}


public function getFactionsByAcronym($acronym){
	
    //Validate Inputs
    if(Check::notString($acronym)){return false;}

    return getFactionsByColumn("acronym", $acronym.);
}

}//close class

?>
