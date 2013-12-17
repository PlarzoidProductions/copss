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
	if(!$this->checkParentGameSystem($parent_game_system)){return false;}
	if(!$this->checkName($name)){return false;}
	if(!$this->checkAcronym($acronym)){return false;}

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

	//Validate the input
	if(!$this->checkParentGameSystem($parent_game_system)){return false;}
	if(!$this->checkName($name)){return false;}
	if(!$this->checkAcronym($acronym)){return false;}
	//Create the values array
	$values = array(":id"=>$id);

	//Create Query
	$sql = "DELETE FROM $this->table WHERE id=:id";

	return $this->db->delete($sql, $values);
}


/**************************************************

Update Record By ID Function(s)

**************************************************/
private function updateGame_system_factionsById($id, $columns){

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


public function getByParentGameSystem($parent_game_system){
	
    //Validate Inputs
    if(!$this->checkParentGameSystem($parent_game_system)){return false;}

    return $this->getByColumn("parent_game_system", $parent_game_system);
}


public function getByName($name){
	
    //Validate Inputs
    if(!$this->checkName($name)){return false;}

    return $this->getByColumn("name", $name);
}


public function getByAcronym($acronym){
	
    //Validate Inputs
    if(!$this->checkAcronym($acronym)){return false;}

    return $this->getByColumn("acronym", $acronym);
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



function checkParentGameSystem($parent_game_system){
    //Not allowed to be null
    if(Check::isNull($parent_game_system)){
        echo "parent_game_system cannot be null!"; return false;
    }

    if(Check::notInt($parent_game_system)){
        echo "parent_game_system was invalid!"; return false;
    }

    return true;
}



function checkName($name){
    //Not allowed to be null
    if(Check::isNull($name)){
        echo "name cannot be null!"; return false;
    }

    if(Check::notString($name)){
        echo "name was invalid!"; return false;
    }

    return true;
}



function checkAcronym($acronym){
    if(Check::notString($acronym)){
        echo "acronym was invalid!"; return false;
    }

    return true;
}



}//close class

?>
