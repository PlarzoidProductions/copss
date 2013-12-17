<?php

/**************************************************
*
*    Game_sizes Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	parent_game_system - INT
*	size - INT
*	name - VARCHAR
*
**************************************************/
require_once("query.php");

class Game_sizes {

var $db=NULL;
var $table="game_sizes";


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
public function create($parent_game_system, $size, $name){

	//Validate the inputs
	if(!$this->checkParentGameSystem($parent_game_system)){return false;}
	if(!$this->checkSize($size)){return false;}
	if(!$this->checkName($name)){return false;}

	//Create the values Array
	$values = array(
		":parent_game_system"=>$parent_game_system,
 		":size"=>$size,
 		":name"=>$name
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				parent_game_system,
				size,
				name
			) VALUES (
				:parent_game_system,
				:size,
				:name)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

**************************************************/
public function deleteGame_sizes($id){

	//Validate the input
	if(!$this->checkParentGameSystem($parent_game_system)){return false;}
	if(!$this->checkSize($size)){return false;}
	if(!$this->checkName($name)){return false;}
	//Create the values array
	$values = array(":id"=>$id);

	//Create Query
	$sql = "DELETE FROM $this->table WHERE id=:id";

	return $this->db->delete($sql, $values);
}


/**************************************************

Update Record By ID Function(s)

**************************************************/
private function updateGame_sizesById($id, $columns){

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


public function getBySize($size){
	
    //Validate Inputs
    if(!$this->checkSize($size)){return false;}

    return $this->getByColumn("size", $size);
}


public function getByName($name){
	
    //Validate Inputs
    if(!$this->checkName($name)){return false;}

    return $this->getByColumn("name", $name);
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



function checkSize($size){
    //Not allowed to be null
    if(Check::isNull($size)){
        echo "size cannot be null!"; return false;
    }

    if(Check::notInt($size)){
        echo "size was invalid!"; return false;
    }

    return true;
}



function checkName($name){
    if(Check::notString($name)){
        echo "name was invalid!"; return false;
    }

    return true;
}



}//close class

?>
