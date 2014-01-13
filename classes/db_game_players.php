<?php

/**************************************************
*
*    Game_players Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	game_id - INT
*	player_id - INT
*	faction_id - INT
*	game_size - INT
*	theme_force - TINYINT
*	fully_painted - TINYINT
*	winner - TINYINT
*
**************************************************/
require_once("query.php");

class Game_players {

var $db=NULL;
var $table="game_players";


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
public function create($game_id, $player_id, $faction_id, $game_size, $theme_force, $fully_painted, $winner){

	//Validate the inputs
	$game_id = $this->filterGameId($game_id); if($game_id === false){return false;}
	$player_id = $this->filterPlayerId($player_id); if($player_id === false){return false;}
	$faction_id = $this->filterFactionId($faction_id); if($faction_id === false){return false;}
	$game_size = $this->filterGameSize($game_size); if($game_size === false){return false;}
	$theme_force = $this->filterThemeForce($theme_force); if($theme_force === false){return false;}
	$fully_painted = $this->filterFullyPainted($fully_painted); if($fully_painted === false){return false;}
	$winner = $this->filterWinner($winner); if($winner === false){return false;}

	//Create the values Array
	$values = array(
		":game_id"=>$game_id,
 		":player_id"=>$player_id,
 		":faction_id"=>$faction_id,
 		":game_size"=>$game_size,
 		":theme_force"=>$theme_force,
 		":fully_painted"=>$fully_painted,
 		":winner"=>$winner
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				game_id,
				player_id,
				faction_id,
				game_size,
				theme_force,
				fully_painted,
				winner
			) VALUES (
				:game_id,
				:player_id,
				:faction_id,
				:game_size,
				:theme_force,
				:fully_painted,
				:winner)";

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
public function updateGame_playersById($id, $columns){

    //Values Array
    $values = array(":id"=>$id);
    foreach($columns as $column=>$value){
        $values[":".$column]=$value;
    }

    //Generate the query
    $sql = "UPDATE $this->table SET ";
    foreach(array_keys($columns) as $column){
        $sql.= "$column=:$column";
        if(strcmp($column, end(array_keys($columns)))){
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


public function getByFactionId($faction_id){
	
    //Validate Inputs
    $faction_id = $this->filterFactionId($faction_id); if($faction_id === false){return false;}

    return $this->queryByColumns(array("faction_id"=>$faction_id));
}


public function getByGameSize($game_size){
	
    //Validate Inputs
    $game_size = $this->filterGameSize($game_size); if($game_size === false){return false;}

    return $this->queryByColumns(array("game_size"=>$game_size));
}


public function getByThemeForce($theme_force){
	
    //Validate Inputs
    $theme_force = $this->filterThemeForce($theme_force); if($theme_force === false){return false;}

    return $this->queryByColumns(array("theme_force"=>$theme_force));
}


public function getByFullyPainted($fully_painted){
	
    //Validate Inputs
    $fully_painted = $this->filterFullyPainted($fully_painted); if($fully_painted === false){return false;}

    return $this->queryByColumns(array("fully_painted"=>$fully_painted));
}


public function getByWinner($winner){
	
    //Validate Inputs
    $winner = $this->filterWinner($winner); if($winner === false){return false;}

    return $this->queryByColumns(array("winner"=>$winner));
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



function filterFactionId($faction_id){
    //Allowed to be null, catch that first
    if(Check::isNull($faction_id)){ return null; }

    if(Check::notInt($faction_id)){
        echo "faction_id was invalid!"; return false;
    }

    return intVal($faction_id);
}



function filterGameSize($game_size){
    //Allowed to be null, catch that first
    if(Check::isNull($game_size)){ return null; }

    if(Check::notInt($game_size)){
        echo "game_size was invalid!"; return false;
    }

    return intVal($game_size);
}



function filterThemeForce($theme_force){
    //Not allowed to be null
    if(Check::isNull($theme_force)){
        echo "theme_force cannot be null!"; return false;
    }

    if(Check::notBool($theme_force)){
        echo "theme_force was invalid!"; return false;
    }

    return intVal($theme_force);
}



function filterFullyPainted($fully_painted){
    //Not allowed to be null
    if(Check::isNull($fully_painted)){
        echo "fully_painted cannot be null!"; return false;
    }

    if(Check::notBool($fully_painted)){
        echo "fully_painted was invalid!"; return false;
    }

    return intVal($fully_painted);
}



function filterWinner($winner){
    //Not allowed to be null
    if(Check::isNull($winner)){
        echo "winner cannot be null!"; return false;
    }

    if(Check::notBool($winner)){
        echo "winner was invalid!"; return false;
    }

    return intVal($winner);
}



}//close class

?>
