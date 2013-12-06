<?php

/**************************************************
*
*    Tournament_results Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	registrant - INT
*	tournament_match_id - INT
*	winner - TINYINT
*	control_points - TINYINT
*	army_points - TINYINT
*	models_destroyed - TINYINT
*	used_extension - TINYINT
*	caster_kill - TINYINT
*	scenario_victory - TINYINT
*	timed_out - TINYINT
*	list_used - INT
*	faction_id - INT
*
**************************************************/
require_once("query.php");

class Tournament_results {

var $db=NULL;
var $table="tournament_results";


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
public function createTournament_results($registrant, $tournament_match_id, $winner, $control_points, $army_points, $models_destroyed, $used_extension, $caster_kill, $scenario_victory, $timed_out, $list_used, $faction_id){

	//Validate the inputs
	if(Check::notInt($registrant)){return false;}
	if(Check::notInt($tournament_match_id)){return false;}
	if(Check::notBool($winner)){return false;}
	if(Check::notBool($control_points)){return false;}
	if(Check::notBool($army_points)){return false;}
	if(Check::notBool($models_destroyed)){return false;}
	if(Check::notBool($used_extension)){return false;}
	if(Check::notBool($caster_kill)){return false;}
	if(Check::notBool($scenario_victory)){return false;}
	if(Check::notBool($timed_out)){return false;}
	if(Check::notInt($list_used)){return false;}
	if(Check::notInt($faction_id)){return false;}

	//Create the values Array
	$values = array(
		":registrant"=>$registrant,
 		":tournament_match_id"=>$tournament_match_id,
 		":winner"=>$winner,
 		":control_points"=>$control_points,
 		":army_points"=>$army_points,
 		":models_destroyed"=>$models_destroyed,
 		":used_extension"=>$used_extension,
 		":caster_kill"=>$caster_kill,
 		":scenario_victory"=>$scenario_victory,
 		":timed_out"=>$timed_out,
 		":list_used"=>$list_used,
 		":faction_id"=>$faction_id
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				registrant,
				tournament_match_id,
				winner,
				control_points,
				army_points,
				models_destroyed,
				used_extension,
				caster_kill,
				scenario_victory,
				timed_out,
				list_used,
				faction_id
			) VALUES (
				:registrant,
				:tournament_match_id,
				:winner,
				:control_points,
				:army_points,
				:models_destroyed,
				:used_extension,
				:caster_kill,
				:scenario_victory,
				:timed_out,
				:list_used,
				:faction_id)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

**************************************************/
public function deleteTournament_results($id){

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
private function updateTournament_resultsById($id, $columns){

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
private function getTournament_resultsByColumn($column, $value){

    //inputs are pre-verified by the mapping functions below, so we can trust them

    //Values Array
    $values = array(":$column"=>$value);

    //Generate the query
    $sql = "SELECT * FROM $this->table WHERE $column=:$column";
    
    return $this->db->query($sql, $values);
}


public function getTournament_resultsById($id){
	
    //Validate Inputs
    if(Check::notInt($id)){return false;}

    return getTournament_resultsByColumn("id", $id.);
}


public function getTournament_resultsByRegistrant($registrant){
	
    //Validate Inputs
    if(Check::notInt($registrant)){return false;}

    return getTournament_resultsByColumn("registrant", $registrant.);
}


public function getTournament_resultsByTournamentMatchId($tournament_match_id){
	
    //Validate Inputs
    if(Check::notInt($tournament_match_id)){return false;}

    return getTournament_resultsByColumn("tournament_match_id", $tournament_match_id.);
}


public function getTournament_resultsByWinner($winner){
	
    //Validate Inputs
    if(Check::notBool($winner)){return false;}

    return getTournament_resultsByColumn("winner", $winner.);
}


public function getTournament_resultsByControlPoints($control_points){
	
    //Validate Inputs
    if(Check::notBool($control_points)){return false;}

    return getTournament_resultsByColumn("control_points", $control_points.);
}


public function getTournament_resultsByArmyPoints($army_points){
	
    //Validate Inputs
    if(Check::notBool($army_points)){return false;}

    return getTournament_resultsByColumn("army_points", $army_points.);
}


public function getTournament_resultsByModelsDestroyed($models_destroyed){
	
    //Validate Inputs
    if(Check::notBool($models_destroyed)){return false;}

    return getTournament_resultsByColumn("models_destroyed", $models_destroyed.);
}


public function getTournament_resultsByUsedExtension($used_extension){
	
    //Validate Inputs
    if(Check::notBool($used_extension)){return false;}

    return getTournament_resultsByColumn("used_extension", $used_extension.);
}


public function getTournament_resultsByCasterKill($caster_kill){
	
    //Validate Inputs
    if(Check::notBool($caster_kill)){return false;}

    return getTournament_resultsByColumn("caster_kill", $caster_kill.);
}


public function getTournament_resultsByScenarioVictory($scenario_victory){
	
    //Validate Inputs
    if(Check::notBool($scenario_victory)){return false;}

    return getTournament_resultsByColumn("scenario_victory", $scenario_victory.);
}


public function getTournament_resultsByTimedOut($timed_out){
	
    //Validate Inputs
    if(Check::notBool($timed_out)){return false;}

    return getTournament_resultsByColumn("timed_out", $timed_out.);
}


public function getTournament_resultsByListUsed($list_used){
	
    //Validate Inputs
    if(Check::notInt($list_used)){return false;}

    return getTournament_resultsByColumn("list_used", $list_used.);
}


public function getTournament_resultsByFactionId($faction_id){
	
    //Validate Inputs
    if(Check::notInt($faction_id)){return false;}

    return getTournament_resultsByColumn("faction_id", $faction_id.);
}

}//close class

?>
