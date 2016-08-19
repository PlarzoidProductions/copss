<?php

/**************************************************
*
*    Prize_redemptions Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	player_id - INT
*	prize_id - INT
*	creation_time - DATETIME
*
**************************************************/
require_once("query.php");

class Prize_redemptions {

var $db=NULL;
var $table="prize_redemptions";


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
public function create($player_id, $prize_id){

	//Validate the inputs
	$player_id = $this->filterPlayerId($player_id); if($player_id === false){return false;}
	$prize_id = $this->filterPrizeId($prize_id); if($prize_id === false){return false;}

	//Create the values Array
	$values = array(
		":player_id"=>$player_id,
 		":prize_id"=>$prize_id
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				player_id,
				prize_id,
				creation_time
			) VALUES (
				:player_id,
				:prize_id,
				NOW())";

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
public function updatePrize_redemptionsById($id, $columns){

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


public function getByPlayerId($player_id){
	
    //Validate Inputs
    $player_id = $this->filterPlayerId($player_id); if($player_id === false){return false;}

    return $this->queryByColumns(array("player_id"=>$player_id));
}


public function getByPrizeId($prize_id){
	
    //Validate Inputs
    $prize_id = $this->filterPrizeId($prize_id); if($prize_id === false){return false;}

    return $this->queryByColumns(array("prize_id"=>$prize_id));
}


public function getByCreationTime($creation_time){
	
    //Validate Inputs
    $creation_time = $this->filterCreationTime($creation_time); if($creation_time === false){return false;}

    return $this->queryByColumns(array("creation_time"=>$creation_time));
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



function filterPlayerId($player_id){
    //Not allowed to be null
    if(Check::isNull($player_id)){
        echo "player_id cannot be null!"; return false;
    }

    if(Check::notInt($player_id)){
        echo "player_id was invalid!"; return false;
    }

    return intVal($player_id);
}



function filterPrizeId($prize_id){
    //Not allowed to be null
    if(Check::isNull($prize_id)){
        echo "prize_id cannot be null!"; return false;
    }

    if(Check::notInt($prize_id)){
        echo "prize_id was invalid!"; return false;
    }

    return intVal($prize_id);
}



function filterCreationTime($creation_time){
    //Not allowed to be null
    if(Check::isNull($creation_time)){
        echo "creation_time cannot be null!"; return false;
    }

    if(Check::isNull($creation_time)){
        echo "creation_time was invalid!"; return false;
    }

    return date("Y-m-d H:i:s", $creation_time);
}



}//close class

?>
