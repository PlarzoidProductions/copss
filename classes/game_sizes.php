<?php

/**************************************************
*
*    Game_sizes Class
*
***************************************************/
require_once("query.php");

class Game_sizes {

var $db=NULL;
var $table="game_sizes";


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
public function createGame_sizes($size, $game_system_id){

	//Validate the inputs
	if(!Check::isInt($size)){return false;}
	if(!Check::isInt($game_system_id)){return false;}

	//Create the values Array
	$values = array(
		":size"=>$size,
 		":game_system_id"=>$game_system_id
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				size,
				game_system_id
			) VALUES (
				:size,
				:game_system_id)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

**************************************************/
public function deleteGame_sizes($id){

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
private function getGame_sizesByColumn($column, $value){

	//inputs are pre-verified by the mapping functions below, so we can trust them

	//Values Array
	$values = array(":$column"=>$value);

	//Generate the query
	$sql = "SELECT * FROM $this->table WHERE $column=:$column";
    
	return $this->db->query($sql, $values);
}


public function getGame_sizesById($id){
	
	//Validate Inputs
	if(!Check::isInt($id)){return false;}

	return getGame_sizesByColumn("id", $id.);
}


public function getGame_sizesBySize($size){
	
	//Validate Inputs
	if(!Check::isInt($size)){return false;}

	return getGame_sizesByColumn("size", $size.);
}


public function getGame_sizesByGame_system_id($game_system_id){
	
	//Validate Inputs
	if(!Check::isInt($game_system_id)){return false;}

	return getGame_sizesByColumn("game_system_id", $game_system_id.);
}

}//close class

?>
