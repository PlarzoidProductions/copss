<?php

/**************************************************
*
*    Game_system_factions Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	parent_game_system - INT
*	name - VARCHAR
*	acronym - VARCHAR
*
**************************************************/
require_once("query.php");

class Game_system_factions {

var $db=NULL;
var $table="game_system_factions";


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
public function create($parent_game_system, $name, $acronym){

	//Validate the inputs
	$parent_game_system = $this->filterParentGameSystem($parent_game_system); if($parent_game_system === false){return false;}
	$name = $this->filterName($name); if($name === false){return false;}
	$acronym = $this->filterAcronym($acronym); if($acronym === false){return false;}

	//Create the values Array
	$values = array(
		":parent_game_system"=>$parent_game_system,
 		":name"=>$name,
 		":acronym"=>$acronym
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				parent_game_system,
				name,
				acronym
			) VALUES (
				:parent_game_system,
				:name,
				:acronym)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

**************************************************/
public function deleteGame_system_factions($id){

	//Create the values array
	$values = array(":id"=>$id);

	//Create Query
	$sql = "DELETE FROM $this->table WHERE id=:id";

	return $this->db->delete($sql, $values);
}


/**************************************************

Update Record By ID Function(s)

**************************************************/
public function updateGame_system_factionsById($id, $columns){

    //Values Array
    $values = array(":id"=>$id);
    foreach($columns as $column=>$value){
        $values[":".$column]=$value;
    }

    //Generate the query
    $sql = "UPDATE $this->table SET ";
    foreach(array_keys($columns) as $column){
        $sql.= "$column=:$column";
        if(strcmp($column, end(array_keys($columns)))){
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


public function getByParentGameSystem($parent_game_system){
	
    //Validate Inputs
    $parent_game_system = $this->filterParentGameSystem($parent_game_system); if($parent_game_system === false){return false;}

    return $this->getByColumn("parent_game_system", $parent_game_system);
}


public function getByName($name){
	
    //Validate Inputs
    $name = $this->filterName($name); if($name === false){return false;}

    return $this->getByColumn("name", $name);
}


public function getByAcronym($acronym){
	
    //Validate Inputs
    $acronym = $this->filterAcronym($acronym); if($acronym === false){return false;}

    return $this->getByColumn("acronym", $acronym);
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



function filterParentGameSystem($parent_game_system){
    //Not allowed to be null
    if(Check::isNull($parent_game_system)){
        echo "parent_game_system cannot be null!"; return false;
    }

    if(Check::notInt($parent_game_system)){
        echo "parent_game_system was invalid!"; return false;
    }

    return $parent_game_system;
}



function filterName($name){
    //Not allowed to be null
    if(Check::isNull($name)){
        echo "name cannot be null!"; return false;
    }

    if(Check::notString($name)){
        echo "name was invalid!"; return false;
    }

    return $name;
}



function filterAcronym($acronym){
    //Allowed to be null, catch that first
    if(Check::isNull($acronym)){ return null; }

    if(Check::notString($acronym)){
        echo "acronym was invalid!"; return false;
    }

    return $acronym;
}



}//close class

?>
