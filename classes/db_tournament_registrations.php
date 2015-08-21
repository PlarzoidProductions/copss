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
*	club - VARCHAR
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
public function create($player_id, $tournament_id, $faction_id, $has_dropped, $had_buy, $club){

	//Validate the inputs
	$player_id = $this->filterPlayerId($player_id); if($player_id === false){return false;}
	$tournament_id = $this->filterTournamentId($tournament_id); if($tournament_id === false){return false;}
	$faction_id = $this->filterFactionId($faction_id); if($faction_id === false){return false;}
	$has_dropped = $this->filterHasDropped($has_dropped); if($has_dropped === false){return false;}
	$had_buy = $this->filterHadBuy($had_buy); if($had_buy === false){return false;}
	$club = $this->filterClub($club); if($club === false){return false;}

	//Create the values Array
	$values = array(
		":player_id"=>$player_id,
 		":tournament_id"=>$tournament_id,
 		":faction_id"=>$faction_id,
 		":has_dropped"=>$has_dropped,
 		":had_buy"=>$had_buy,
		":club"=>$club
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				player_id,
				tournament_id,
				faction_id,
				has_dropped,
				had_buy,
				club
			) VALUES (
				:player_id,
				:tournament_id,
				:faction_id,
				:has_dropped,
				:had_buy,
				:club)";

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

public function getRegistrationsByTournamentId($t_id){

	$sql = "select  
				`tr`.`id` as `id`, 
				`tr`.`tournament_id` as tournament_id, 
				`tr`.`has_dropped` as has_dropped, 
				`tr`.`had_buy` as had_buy, 
				`tr`.`club` as club,
				`p`.`first_name` as first_name, 
				`p`.`last_name` as last_name, 
				`f`.`name` as faction_name 
			from 
				`tournament_registrations` `tr` 
			left join `players` `p` 
				on `tr`.`player_id`=`p`.`id` 
			left join `game_system_factions` `f` 
				on `tr`.`faction_id`=`f`.`id` 
			where tournament_id=:id
			order by last_name, first_name;";

	return $this->db->query($sql, array(":id"=>$t_id));
}


public function getClubOptionsByTournamentId($t_id){

	$sql = "SELECT
				DISTINCT(club) as name
			FROM
				$this->table
			WHERE
				tournament_id=:t_id AND club IS NOT NULL
			ORDER BY name ASC";

	return $this->db->query($sql, array(":t_id"=>$t_id));
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

public function getByClub($club){
	
	//Validate Inputs
	$club = $this->filterClub($club); if($club === false){return false;}

	return $this->queryByColumns(array("club"=>$club));
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

function filterClub($club){
	//Allowed to be null
	if(Check::isNull($club)){
		return null;
	}

	return strval($club);
}


}//close class

?>