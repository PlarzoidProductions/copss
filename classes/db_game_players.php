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
	if(!$this->checkGameId($game_id)){return false;}
	if(!$this->checkPlayerId($player_id)){return false;}
	if(!$this->checkFactionId($faction_id)){return false;}
	if(!$this->checkGameSize($game_size)){return false;}
	if(!$this->checkThemeForce($theme_force)){return false;}
	if(!$this->checkFullyPainted($fully_painted)){return false;}
	if(!$this->checkWinner($winner)){return false;}

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
public function deleteGame_players($id){

	//Validate the input
	if(!$this->checkGameId($game_id)){return false;}
	if(!$this->checkPlayerId($player_id)){return false;}
	if(!$this->checkFactionId($faction_id)){return false;}
	if(!$this->checkGameSize($game_size)){return false;}
	if(!$this->checkThemeForce($theme_force)){return false;}
	if(!$this->checkFullyPainted($fully_painted)){return false;}
	if(!$this->checkWinner($winner)){return false;}
	//Create the values array
	$values = array(":id"=>$id);

	//Create Query
	$sql = "DELETE FROM $this->table WHERE id=:id";

	return $this->db->delete($sql, $values);
}


/**************************************************

Update Record By ID Function(s)

**************************************************/
private function updateGame_playersById($id, $columns){

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


public function getByGameId($game_id){
	
    //Validate Inputs
    if(!$this->checkGameId($game_id)){return false;}

    return $this->getByColumn("game_id", $game_id);
}


public function getByPlayerId($player_id){
	
    //Validate Inputs
    if(!$this->checkPlayerId($player_id)){return false;}

    return $this->getByColumn("player_id", $player_id);
}


public function getByFactionId($faction_id){
	
    //Validate Inputs
    if(!$this->checkFactionId($faction_id)){return false;}

    return $this->getByColumn("faction_id", $faction_id);
}


public function getByGameSize($game_size){
	
    //Validate Inputs
    if(!$this->checkGameSize($game_size)){return false;}

    return $this->getByColumn("game_size", $game_size);
}


public function getByThemeForce($theme_force){
	
    //Validate Inputs
    if(!$this->checkThemeForce($theme_force)){return false;}

    return $this->getByColumn("theme_force", $theme_force);
}


public function getByFullyPainted($fully_painted){
	
    //Validate Inputs
    if(!$this->checkFullyPainted($fully_painted)){return false;}

    return $this->getByColumn("fully_painted", $fully_painted);
}


public function getByWinner($winner){
	
    //Validate Inputs
    if(!$this->checkWinner($winner)){return false;}

    return $this->getByColumn("winner", $winner);
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



function checkGameId($game_id){
    //Not allowed to be null
    if(Check::isNull($game_id)){
        echo "game_id cannot be null!"; return false;
    }

    if(Check::notInt($game_id)){
        echo "game_id was invalid!"; return false;
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



function checkFactionId($faction_id){
    if(Check::notInt($faction_id)){
        echo "faction_id was invalid!"; return false;
    }

    return true;
}



function checkGameSize($game_size){
    if(Check::notInt($game_size)){
        echo "game_size was invalid!"; return false;
    }

    return true;
}



function checkThemeForce($theme_force){
    //Not allowed to be null
    if(Check::isNull($theme_force)){
        echo "theme_force cannot be null!"; return false;
    }

    if(Check::notBool($theme_force)){
        echo "theme_force was invalid!"; return false;
    }

    return true;
}



function checkFullyPainted($fully_painted){
    //Not allowed to be null
    if(Check::isNull($fully_painted)){
        echo "fully_painted cannot be null!"; return false;
    }

    if(Check::notBool($fully_painted)){
        echo "fully_painted was invalid!"; return false;
    }

    return true;
}



function checkWinner($winner){
    //Not allowed to be null
    if(Check::isNull($winner)){
        echo "winner cannot be null!"; return false;
    }

    if(Check::notBool($winner)){
        echo "winner was invalid!"; return false;
    }

    return true;
}



}//close class

?>
