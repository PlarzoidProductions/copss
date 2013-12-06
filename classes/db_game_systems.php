<?php

/**************************************************
*
*    Game_systems Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	name - VARCHAR
*	max_num_players - INT
*
**************************************************/
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
	if(Check::notString($name)){return false;}
	if(Check::notInt($max_num_players)){return false;}

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

Update Record By ID Function(s)

**************************************************/
private function updateGame_systemsById($id, $columns){

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
    if(Check::notInt($id)){return false;}

    return getGame_systemsByColumn("id", $id.);
}


public function getGame_systemsByName($name){
	
    //Validate Inputs
    if(Check::notString($name)){return false;}

    return getGame_systemsByColumn("name", $name.);
}


public function getGame_systemsByMaxNumPlayers($max_num_players){
	
    //Validate Inputs
    if(Check::notInt($max_num_players)){return false;}

    return getGame_systemsByColumn("max_num_players", $max_num_players.);
}

}//close class

?>
