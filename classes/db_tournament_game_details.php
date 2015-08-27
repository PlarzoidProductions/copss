<?php

/**************************************************
*
*    Tournament_game_details Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	game_id - INT
*	player_id - INT
*	list_played - INT
*	control_points - INT
*	destruction_points - INT
*	assassination_efficiency - INT
*	timed_out - TINYINT
*
**************************************************/
require_once("query.php");

class Tournament_game_details {

var $db=NULL;
var $table="tournament_game_details";


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
public function create($game_id, $player_id, $list_played, $control_points, $destruction_points, $assassination_efficiency, $timed_out){

	//Validate the inputs
	$game_id = $this->filterGameId($game_id); if($game_id === false){return false;}
	$player_id = $this->filterPlayerId($player_id); if($player_id === false){return false;}
	$list_played = $this->filterListPlayed($list_played); if($list_played === false){return false;}
	$control_points = $this->filterControlPoints($control_points); if($control_points === false){return false;}
	$destruction_points = $this->filterDestructionPoints($destruction_points); if($destruction_points === false){return false;}
	$assassination_efficiency = $this->filterAssassinationEfficiency($assassination_efficiency); if($assassination_efficiency === false){return false;}
	$timed_out = $this->filterTimedOut($timed_out); if($timed_out === false){return false;}

	//Create the values Array
	$values = array(
		":game_id"=>$game_id,
 		":player_id"=>$player_id,
 		":list_played"=>$list_played,
 		":control_points"=>$control_points,
 		":destruction_points"=>$destruction_points,
 		":assassination_efficiency"=>$assassination_efficiency,
 		":timed_out"=>$timed_out
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				game_id,
				player_id,
				list_played,
				control_points,
				destruction_points,
				assassination_efficiency,
				timed_out
			) VALUES (
				:game_id,
				:player_id,
				:list_played,
				:control_points,
				:destruction_points,
				:assassination_efficiency,
				:timed_out)";

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
public function updateTournament_game_detailsById($id, $columns){

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


public function getByGameId($game_id){
	
    //Validate Inputs
    $game_id = $this->filterGameId($game_id); if($game_id === false){return false;}

    return $this->queryByColumns(array("game_id"=>$game_id));
}


public function getByPlayerId($player_id){
	
    //Validate Inputs
    $player_id = $this->filterPlayerId($player_id); if($player_id === false){return false;}

    return $this->queryByColumns(array("player_id"=>$player_id));
}


public function getByListPlayed($list_played){
	
    //Validate Inputs
    $list_played = $this->filterListPlayed($list_played); if($list_played === false){return false;}

    return $this->queryByColumns(array("list_played"=>$list_played));
}


public function getByControlPoints($control_points){
	
    //Validate Inputs
    $control_points = $this->filterControlPoints($control_points); if($control_points === false){return false;}

    return $this->queryByColumns(array("control_points"=>$control_points));
}


public function getByDestructionPoints($destruction_points){
	
    //Validate Inputs
    $destruction_points = $this->filterDestructionPoints($destruction_points); if($destruction_points === false){return false;}

    return $this->queryByColumns(array("destruction_points"=>$destruction_points));
}


public function getByAssassinationEfficiency($assassination_efficiency){
	
    //Validate Inputs
    $assassination_efficiency = $this->filterAssassinationEfficiency($assassination_efficiency); if($assassination_efficiency === false){return false;}

    return $this->queryByColumns(array("assassination_efficiency"=>$assassination_efficiency));
}


public function getByTimedOut($timed_out){
	
    //Validate Inputs
    $timed_out = $this->filterTimedOut($timed_out); if($timed_out === false){return false;}

    return $this->queryByColumns(array("timed_out"=>$timed_out));
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



function filterGameId($game_id){
    //Not allowed to be null
    if(Check::isNull($game_id)){
        echo "game_id cannot be null!"; return false;
    }

    if(Check::notInt($game_id)){
        echo "game_id was invalid!"; return false;
    }

    return intVal($game_id);
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



function filterListPlayed($list_played){
    //Allowed to be null
    if(Check::isNull($list_played)){ return null; }

    if(Check::notInt($list_played)){
        echo "list_played was invalid!"; return false;
    }

    return intVal($list_played);
}



function filterControlPoints($control_points){
    //Allowed to be null
    if(Check::isNull($control_points)){ return null; }

    if(Check::notInt($control_points)){
        echo "control_points was invalid!"; return false;
    }

    return intVal($control_points);
}



function filterDestructionPoints($destruction_points){
    //Allowed to be null
    if(Check::isNull($destruction_points)){ return null; }

    if(Check::notInt($destruction_points)){
        echo "destruction_points was invalid!"; return false;
    }

    return intVal($destruction_points);
}



function filterAssassinationEfficiency($assassination_efficiency){
    //Allowed to be null, catch that first
    if(Check::isNull($assassination_efficiency)){ return null; }

    if(Check::notInt($assassination_efficiency)){
        echo "assassination_efficiency was invalid!"; return false;
    }

    return intVal($assassination_efficiency);
}



function filterTimedOut($timed_out){
    //Allowed to be null
    if(Check::isNull($timed_out)){ return null; }

    if(Check::notBool($timed_out)){
        echo "timed_out was invalid!"; return false;
    }

    return intVal($timed_out);
}



}//close class

?>
