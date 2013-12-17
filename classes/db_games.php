<?php

/**************************************************
*
*    Games Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	time - TIMESTAMP
*	game_system - INT
*	scenario - TINYINT
*
**************************************************/
require_once("query.php");

class Games {

var $db=NULL;
var $table="games";


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
public function create($time, $game_system, $scenario){

	//Validate the inputs
	if(!$this->checkTime($time)){return false;}
	if(!$this->checkGameSystem($game_system)){return false;}
	if(!$this->checkScenario($scenario)){return false;}

	//Create the values Array
	$values = array(
		":time"=>$time,
 		":game_system"=>$game_system,
 		":scenario"=>$scenario
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				time,
				game_system,
				scenario
			) VALUES (
				:time,
				:game_system,
				:scenario)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

**************************************************/
public function deleteGames($id){

	//Validate the input
	if(!$this->checkTime($time)){return false;}
	if(!$this->checkGameSystem($game_system)){return false;}
	if(!$this->checkScenario($scenario)){return false;}
	//Create the values array
	$values = array(":id"=>$id);

	//Create Query
	$sql = "DELETE FROM $this->table WHERE id=:id";

	return $this->db->delete($sql, $values);
}


/**************************************************

Update Record By ID Function(s)

**************************************************/
private function updateGamesById($id, $columns){

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


public function getByTime($time){
	
    //Validate Inputs
    if(!$this->checkTime($time)){return false;}

    return $this->getByColumn("time", $time);
}


public function getByGameSystem($game_system){
	
    //Validate Inputs
    if(!$this->checkGameSystem($game_system)){return false;}

    return $this->getByColumn("game_system", $game_system);
}


public function getByScenario($scenario){
	
    //Validate Inputs
    if(!$this->checkScenario($scenario)){return false;}

    return $this->getByColumn("scenario", $scenario);
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



function checkTime($time){
    //Not allowed to be null
    if(Check::isNull($time)){
        echo "time cannot be null!"; return false;
    }

    if(Check::isNull($time)){
        echo "time was invalid!"; return false;
    }

    return true;
}



function checkGameSystem($game_system){
    //Not allowed to be null
    if(Check::isNull($game_system)){
        echo "game_system cannot be null!"; return false;
    }

    if(Check::notInt($game_system)){
        echo "game_system was invalid!"; return false;
    }

    return true;
}



function checkScenario($scenario){
    //Not allowed to be null
    if(Check::isNull($scenario)){
        echo "scenario cannot be null!"; return false;
    }

    if(Check::notBool($scenario)){
        echo "scenario was invalid!"; return false;
    }

    return true;
}



}//close class

?>
