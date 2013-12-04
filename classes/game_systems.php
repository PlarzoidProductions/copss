<?php

/**************************************************
*
*    Game_systems Class
*
***************************************************/
require_once("query.php");

class Game_systems {

var $db=NULL;
var $table="game_systems";


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
public function createGame_systems($name, $max_num_players){

	//Validate the inputs
	if(!Check::isString($name)){return false;}
	if(!Check::isInt($max_num_players)){return false;}

	//Create the values Array
	$values = array(
		":name"=>$name,
 		":max_num_players"=>$max_num_players
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				name,
				max_num_players
			) VALUES (
				:name,
				:max_num_players)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

**************************************************/
public function deleteGame_systems($id){

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
private function getGame_systemsByColumn($column, $value){

	//inputs are pre-verified by the mapping functions below, so we can trust them

	//Values Array
	$values = array(":$column"=>$value);

	//Generate the query
	$sql = "SELECT * FROM $this->table WHERE $column=:$column";
    
	return $this->db->query($sql, $values);
}


public function getGame_systemsById($id){
	
	//Validate Inputs
	if(!Check::isInt($id)){return false;}

	return getGame_systemsByColumn("id", $id.);
}


public function getGame_systemsByName($name){
	
	//Validate Inputs
	if(!Check::isString($name)){return false;}

	return getGame_systemsByColumn("name", $name.);
}


public function getGame_systemsByMax_num_players($max_num_players){
	
	//Validate Inputs
	if(!Check::isInt($max_num_players)){return false;}

	return getGame_systemsByColumn("max_num_players", $max_num_players.);
}

}//close class

?>
