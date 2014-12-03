<?php

/**************************************************
*
*    Tournament_registrations Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	player_id - INT
*	tournament_id - INT
*	faction_id - INT
*	has_dropped - TINYINT
*	had_buy - TINYINT
*
**************************************************/
require_once("query.php");

class Tournament_registrations {

var $db=NULL;
var $table="tournament_registrations";


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
public function create($player_id, $tournament_id, $faction_id, $has_dropped, $had_buy){

	//Validate the inputs
	$player_id = $this->filterPlayerId($player_id); if($player_id === false){return false;}
	$tournament_id = $this->filterTournamentId($tournament_id); if($tournament_id === false){return false;}
	$faction_id = $this->filterFactionId($faction_id); if($faction_id === false){return false;}
	$has_dropped = $this->filterHasDropped($has_dropped); if($has_dropped === false){return false;}
	$had_buy = $this->filterHadBuy($had_buy); if($had_buy === false){return false;}

	//Create the values Array
	$values = array(
		":player_id"=>$player_id,
 		":tournament_id"=>$tournament_id,
 		":faction_id"=>$faction_id,
 		":has_dropped"=>$has_dropped,
 		":had_buy"=>$had_buy
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				player_id,
				tournament_id,
				faction_id,
				has_dropped,
				had_buy
			) VALUES (
				:player_id,
				:tournament_id,
				:faction_id,
				:has_dropped,
				:had_buy)";

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
public function updateTournament_registrationsById($id, $columns){

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


public function getByTournamentId($tournament_id){
	
    //Validate Inputs
    $tournament_id = $this->filterTournamentId($tournament_id); if($tournament_id === false){return false;}

    return $this->queryByColumns(array("tournament_id"=>$tournament_id));
}


public function getByFactionId($faction_id){
	
    //Validate Inputs
    $faction_id = $this->filterFactionId($faction_id); if($faction_id === false){return false;}

    return $this->queryByColumns(array("faction_id"=>$faction_id));
}


public function getByHasDropped($has_dropped){
	
    //Validate Inputs
    $has_dropped = $this->filterHasDropped($has_dropped); if($has_dropped === false){return false;}

    return $this->queryByColumns(array("has_dropped"=>$has_dropped));
}


public function getByHadBuy($had_buy){
	
    //Validate Inputs
    $had_buy = $this->filterHadBuy($had_buy); if($had_buy === false){return false;}

    return $this->queryByColumns(array("had_buy"=>$had_buy));
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



function filterFactionId($faction_id){
    //Not allowed to be null
    if(Check::isNull($faction_id)){
        echo "faction_id cannot be null!"; return false;
    }

    if(Check::notInt($faction_id)){
        echo "faction_id was invalid!"; return false;
    }

    return intVal($faction_id);
}



function filterHasDropped($has_dropped){
    //Not allowed to be null
    if(Check::isNull($has_dropped)){
        echo "has_dropped cannot be null!"; return false;
    }

    if(Check::notBool($has_dropped)){
        echo "has_dropped was invalid!"; return false;
    }

    return intVal($has_dropped);
}



function filterHadBuy($had_buy){
    //Not allowed to be null
    if(Check::isNull($had_buy)){
        echo "had_buy cannot be null!"; return false;
    }

    if(Check::notBool($had_buy)){
        echo "had_buy was invalid!"; return false;
    }

    return intVal($had_buy);
}



}//close class

?>
