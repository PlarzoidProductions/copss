<?php

/**************************************************
*
*    Tournament Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	game_system_id - INT
*	game_size_id - INT
*	max_num_players - INT
*	num_lists - INT
*	divide_and_conquer - INT
*	scoring_mode - ENUM
*	has_time_extensions - TINYINT
*	finals_tables - TINYINT
*	large_event - TINYINT
*
**************************************************/
require_once("query.php");

class Tournament {

var $db=NULL;
var $table="tournament";


/***************************************************

Constructor & Destructor

***************************************************/
public function __construct(){
    $this->db = new Query();
}

public function __destruct(){}


/**************************************************

Create Function

**************************************************/
public function createTournament($game_system_id, $game_size_id, $max_num_players, $num_lists, $divide_and_conquer, $scoring_mode, $has_time_extensions, $finals_tables, $large_event){

	//Validate the inputs
	if(Check::notInt($game_system_id)){return false;}
	if(Check::notInt($game_size_id)){return false;}
	if(Check::notInt($max_num_players)){return false;}
	if(Check::notInt($num_lists)){return false;}
	if(Check::notInt($divide_and_conquer)){return false;}
	if(Check::isNull($scoring_mode)){return false;}
	if(Check::notBool($has_time_extensions)){return false;}
	if(Check::notBool($finals_tables)){return false;}
	if(Check::notBool($large_event)){return false;}

	//Create the values Array
	$values = array(
		":game_system_id"=>$game_system_id,
 		":game_size_id"=>$game_size_id,
 		":max_num_players"=>$max_num_players,
 		":num_lists"=>$num_lists,
 		":divide_and_conquer"=>$divide_and_conquer,
 		":scoring_mode"=>$scoring_mode,
 		":has_time_extensions"=>$has_time_extensions,
 		":finals_tables"=>$finals_tables,
 		":large_event"=>$large_event
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				game_system_id,
				game_size_id,
				max_num_players,
				num_lists,
				divide_and_conquer,
				scoring_mode,
				has_time_extensions,
				finals_tables,
				large_event
			) VALUES (
				:game_system_id,
				:game_size_id,
				:max_num_players,
				:num_lists,
				:divide_and_conquer,
				:scoring_mode,
				:has_time_extensions,
				:finals_tables,
				:large_event)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

**************************************************/
public function deleteTournament($id){

	//Validate the input
	if(Check::isInt($id)){return false;}

	//Create the values array
	$values = array(":id"=>$id);

	//Create Query
	$sql = "DELETE FROM $this->table WHERE id=:id";

	return $this->db->delete($sql, $values);
}


/**************************************************

Update Record By ID Function(s)

**************************************************/
private function updateTournamentById($id, $columns){

    //Values Array
    $values = array(":id"=>$id);
    foreach($columns as $column=>$value){
        $values[":".$column]=$value;
    }

    //Generate the query
    $sql = "UPDATE $this->table SET ";
    foreach(array_keys($columns) as $column){
        $sql.= "$column=:$column";
        if(strcmp($column, end($array_keys($columns))){
            $sql.= ", ";
        }
    }
    $sql.= " WHERE id=:id";

    return $this->db->update($sql, $values);
}


/**************************************************

Query By Column Function(s)

**************************************************/
private function getTournamentByColumn($column, $value){

    //inputs are pre-verified by the mapping functions below, so we can trust them

    //Values Array
    $values = array(":$column"=>$value);

    //Generate the query
    $sql = "SELECT * FROM $this->table WHERE $column=:$column";
    
    return $this->db->query($sql, $values);
}


public function getTournamentById($id){
	
    //Validate Inputs
    if(Check::notInt($id)){return false;}

    return getTournamentByColumn("id", $id.);
}


public function getTournamentByGameSystemId($game_system_id){
	
    //Validate Inputs
    if(Check::notInt($game_system_id)){return false;}

    return getTournamentByColumn("game_system_id", $game_system_id.);
}


public function getTournamentByGameSizeId($game_size_id){
	
    //Validate Inputs
    if(Check::notInt($game_size_id)){return false;}

    return getTournamentByColumn("game_size_id", $game_size_id.);
}


public function getTournamentByMaxNumPlayers($max_num_players){
	
    //Validate Inputs
    if(Check::notInt($max_num_players)){return false;}

    return getTournamentByColumn("max_num_players", $max_num_players.);
}


public function getTournamentByNumLists($num_lists){
	
    //Validate Inputs
    if(Check::notInt($num_lists)){return false;}

    return getTournamentByColumn("num_lists", $num_lists.);
}


public function getTournamentByDivideAndConquer($divide_and_conquer){
	
    //Validate Inputs
    if(Check::notInt($divide_and_conquer)){return false;}

    return getTournamentByColumn("divide_and_conquer", $divide_and_conquer.);
}


public function getTournamentByScoringMode($scoring_mode){
	
    //Validate Inputs
    if(Check::isNull($scoring_mode)){return false;}

    return getTournamentByColumn("scoring_mode", $scoring_mode.);
}


public function getTournamentByHasTimeExtensions($has_time_extensions){
	
    //Validate Inputs
    if(Check::notBool($has_time_extensions)){return false;}

    return getTournamentByColumn("has_time_extensions", $has_time_extensions.);
}


public function getTournamentByFinalsTables($finals_tables){
	
    //Validate Inputs
    if(Check::notBool($finals_tables)){return false;}

    return getTournamentByColumn("finals_tables", $finals_tables.);
}


public function getTournamentByLargeEvent($large_event){
	
    //Validate Inputs
    if(Check::notBool($large_event)){return false;}

    return getTournamentByColumn("large_event", $large_event.);
}

}//close class

?>
