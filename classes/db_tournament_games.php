<?php

/**************************************************
*
*    Tournament_games Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	tournament_id - INT
*	round - INT
*	table_number - INT
*	winner_id - INT
*
**************************************************/
require_once("query.php");

class Tournament_games {

var $db=NULL;
var $table="tournament_games";


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
public function create($tournament_id, $round, $table_number, $winner_id){

	//Validate the inputs
	$tournament_id = $this->filterTournamentId($tournament_id); if($tournament_id === false){return false;}
	$round = $this->filterRound($round); if($round === false){return false;}
	$table_round = $this->filterTableNumber($table_number); if($table_number === false){return false;}
	$winner_id = $this->filterWinnerId($winner_id); if($winner_id === false){return false;}

	//Create the values Array
	$values = array(
		":tournament_id"=>$tournament_id,
 		":round"=>$round,
		":table_number"=>$table_number,
 		":winner_id"=>$winner_id
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				tournament_id,
				round,
				table_number,
				winner_id
			) VALUES (
				:tournament_id,
				:round,
				:table_number,
				:winner_id)";

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
public function updateTournament_gamesById($id, $columns){

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


public function getByTournamentId($tournament_id){
	
    //Validate Inputs
    $tournament_id = $this->filterTournamentId($tournament_id); if($tournament_id === false){return false;}

    return $this->queryByColumns(array("tournament_id"=>$tournament_id));
}


public function getByRound($round){
	
    //Validate Inputs
    $round = $this->filterRound($round); if($round === false){return false;}

    return $this->queryByColumns(array("round"=>$round));
}


public function getByTableNumber($table_number){
	
	//validate Inputs
	$table_number = $this->filterTableNumber($table_number); if($table_number === false){return false;}

	return $this->queryByColumns(array("table_number"=>$table_number));
}


public function getByWinnerId($winner_id){
	
    //Validate Inputs
    $winner_id = $this->filterWinnerId($winner_id); if($winner_id === false){return false;}

    return $this->queryByColumns(array("winner_id"=>$winner_id));
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



function filterTournamentId($tournament_id){
    //Not allowed to be null
    if(Check::isNull($tournament_id)){
        echo "tournament_id cannot be null!"; return false;
    }

    if(Check::notInt($tournament_id)){
        echo "tournament_id was invalid!"; return false;
    }

    return intVal($tournament_id);
}



function filterRound($round){
    //Not allowed to be null
    if(Check::isNull($round)){
        echo "round cannot be null!"; return false;
    }

    if(Check::notInt($round)){
        echo "round was invalid!"; return false;
    }

    return intVal($round);
}


function filterTableNumber($table_number){
    //Not allowed to be null
    if(Check::isNull($table_number)){
        echo "table_number cannot be null!"; return false;
    }

    if(Check::notInt($table_number)){
        echo "table_number was invalid!"; return false;
    }

    return intVal($table_number);
}


function filterWinnerId($winner_id){
    //Allowed to be null, catch that first
    if(Check::isNull($winner_id)){ return null; }

    if(Check::notInt($winner_id)){
        echo "winner_id was invalid!"; return false;
    }

    return intVal($winner_id);
}



}//close class

?>
