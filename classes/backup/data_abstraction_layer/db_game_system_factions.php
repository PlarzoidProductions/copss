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
public function deleteByColumns($columns){

    //Create the values array
    $values = array();
    foreach($columns as $c=>$v){
        $values[":".$c]=$v;
    }

    //Create Query\n";
    $sql = "DELETE FROM $this->table WHERE ";
    $keys = array_keys($columns);
    foreach($keys as $column){
        $sql.= "$column=:$column";
        if(strcmp($column, end($keys))){
            $sql.= " AND ";
        }
    }

    return $this->db->delete($sql, $values);
}

public function deleteById($id){
    return $this->deleteByColumns(array("id"=>$id));
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
    $keys = array_keys($columns);
    foreach($keys as $column){
        $sql.= "$column=:$column";
        if(strcmp($column, end($keys))){
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

Query by Column(s) Function

**************************************************/
public function queryByColumns($columns){

    //Values Array
    $values = array();
    foreach($columns as $column=>$value){
        $values[":".$column]=$value;
    }

    //Generate the query
    $sql = "SELECT * FROM $this->table WHERE ";
    $keys = array_keys($columns);
    foreach($keys as $column){
        $sql.= "$column=:$column";
        if(strcmp($column, end($keys))){
            $sql.= " AND ";
        }
    }

    return $this->db->query($sql, $values);
}


public function getById($id){
	
    //Validate Inputs
    $id = $this->filterId($id); if($id === false){return false;}

    return $this->queryByColumns(array("id"=>$id));
}


public function getByParentGameSystem($parent_game_system){
	
    //Validate Inputs
    $parent_game_system = $this->filterParentGameSystem($parent_game_system); if($parent_game_system === false){return false;}

    return $this->queryByColumns(array("parent_game_system"=>$parent_game_system));
}


public function getByName($name){
	
    //Validate Inputs
    $name = $this->filterName($name); if($name === false){return false;}

    return $this->queryByColumns(array("name"=>$name));
}


public function getByAcronym($acronym){
	
    //Validate Inputs
    $acronym = $this->filterAcronym($acronym); if($acronym === false){return false;}

    return $this->queryByColumns(array("acronym"=>$acronym));
}


/**************************************************

Exists by Column(s) Function

**************************************************/
public function existsByColumns($columns){
    $results = $this->queryByColumns($columns);

    return count($results);
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

    return intVal($id);
}



function filterParentGameSystem($parent_game_system){
    //Not allowed to be null
    if(Check::isNull($parent_game_system)){
        echo "parent_game_system cannot be null!"; return false;
    }

    if(Check::notInt($parent_game_system)){
        echo "parent_game_system was invalid!"; return false;
    }

    return intVal($parent_game_system);
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
