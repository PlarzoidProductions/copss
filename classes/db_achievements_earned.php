<?php

/**************************************************
*
*    Achievements_earned Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	player_id - INT
*	achievement_id - INT
*
**************************************************/
require_once("query.php");

class Achievements_earned {

var $db=NULL;
var $table="achievements_earned";


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
public function create($player_id, $achievement_id){

	//Validate the inputs
	if(!$this->checkPlayerId($player_id)){return false;}
	if(!$this->checkAchievementId($achievement_id)){return false;}

	//Create the values Array
	$values = array(
		":player_id"=>$player_id,
 		":achievement_id"=>$achievement_id
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				player_id,
				achievement_id
			) VALUES (
				:player_id,
				:achievement_id)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

**************************************************/
public function deleteAchievements_earned($id){

	//Validate the input
	if(!$this->checkPlayerId($player_id)){return false;}
	if(!$this->checkAchievementId($achievement_id)){return false;}
	//Create the values array
	$values = array(":id"=>$id);

	//Create Query
	$sql = "DELETE FROM $this->table WHERE id=:id";

	return $this->db->delete($sql, $values);
}


/**************************************************

Update Record By ID Function(s)

**************************************************/
private function updateAchievements_earnedById($id, $columns){

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
    if(!$this->checkId($id)){return false;}

    return $this->getByColumn("id", $id);
}


public function getByPlayerId($player_id){
	
    //Validate Inputs
    if(!$this->checkPlayerId($player_id)){return false;}

    return $this->getByColumn("player_id", $player_id);
}


public function getByAchievementId($achievement_id){
	
    //Validate Inputs
    if(!$this->checkAchievementId($achievement_id)){return false;}

    return $this->getByColumn("achievement_id", $achievement_id);
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



function checkPlayerId($player_id){
    //Not allowed to be null
    if(Check::isNull($player_id)){
        echo "player_id cannot be null!"; return false;
    }

    if(Check::notInt($player_id)){
        echo "player_id was invalid!"; return false;
    }

    return true;
}



function checkAchievementId($achievement_id){
    //Not allowed to be null
    if(Check::isNull($achievement_id)){
        echo "achievement_id cannot be null!"; return false;
    }

    if(Check::notInt($achievement_id)){
        echo "achievement_id was invalid!"; return false;
    }

    return true;
}



}//close class

?>
