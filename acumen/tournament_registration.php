<?php

require_once("classes/page.php");
require_once("classes/db_players.php");
require_once("classes/db_game_system_factions.php");
require_once("classes/db_tournaments.php");
require_once("classes/db_tournament_registrations.php");

require_once("tournament_engine.php");


$page = new Page();
$t_db = new Tournaments();
$tr_db = new Tournament_Registrations();
$p_db = new Players();
$f_db = new Game_system_factions();

$te = new Tournament_Engine();

/**************************************

Handle Deletions

**************************************/
$action = $_REQUEST[action];
$tr_id = $_REQUEST[tr_id];

if(!empty($action)){

	$registration = $tr_db->getById($tr_id);

	$player = $p_db->getById($registration[0]["player_id"]);
	$tournament = $t_db->getById($registration[0]["tournament_id"]);

	if(!strcmp($action, "delete")){
		if($tr_db->deleteById($tr_id)){
			$success_str = "Successfully removed ".$player[0]["last_name"].", ".$player[0]["first_name"]."' from:".$tournament[0]["name"]." </br>";
		}
	}
}


/**************************************

Require user to pick a Tournament

**************************************/
$page->register("t_id", "select", array("reloading"=>1, "label"=>"Tournament",
										"get_choices_array_func"=>"getTournamentChoices",
                                        "get_choices_array_func_args"=>array()));

$t_id = $page->getVar("t_id");

/**************************************

Player Inputs

**************************************/
if(!empty($t_id)){

	$tournament = $t_db->getById($t_id);
	
	$page->register("submit_batch", "submit", array("value"=>"Submit"));

	$clubs_raw = $tr_db->getClubOptionsByTournamentId($t_id);
	$clubs = array();
	foreach($clubs_raw as $club){
		$clubs[] = $club["name"];
	}
	$club_list_string = implode(',', $clubs);


	for($i=1; $i <= 5; $i++){
    	$page->register("player_".$i."_id", "select", array("label"=>"Player $i",
                                                        "get_choices_array_func"=>"getPlayerChoices",
                                                        "get_choices_array_func_args"=>array()));
		$page->register("faction_".$i."_id", "select", array("label"=>"Faction",
														"get_choices_array_func"=>"getGameSystemFactions",
														"get_choices_array_func_args"=>array($tournament[0]["game_system_id"])));
		$page->register("club_".$i."_id", "textbox", array("label"=>"Club", "class"=>"awesomplete",
														"data-list"=>$club_list_string));
	}
}

$page->getChoices();

/**************************************

Handle the Submit

**************************************/
if($page->submitIsSet("submit_batch")){

	$end_result = true;

   	$players = array();
    for($i=1; $i <= 5; $i++){
        $p_id = $page->getVar("player_".$i."_id");
		$faction_id = $page->getVar("faction_".$i."_id");
		$club = $page->getVar("club_".$i."_id");

	    if(Check::isNull($p_id)){ continue;}
		if(Check::isNull($faction_id)){
			$error = "Must choose a faction for Player $i!";
			break;
		}

    	$player = $p_db->getById($p_id);
		$faction = $f_db->getById($faction_id);

       	$players[$p_id] = $player[0];
		$players[$p_id]["faction_id"] = $faction_id;
		$players[$p_id]["faction_name"] = $faction[0]["name"];
		$players[$p_id]["club"] = $club;

	}

	foreach($players as $p_id=>$p){
		//Check that the chosen player isn't already registered
        $exists = $tr_db->queryByColumns(array("player_id"=>$p_id, "tournament_id"=>$t_id));
       	if(!$exists){

			//If not, do so
           	$result = $te->addPlayer($t_id, $p_id, $p["faction_id"], $p["club"]);
			$end_result = $end_result && $result;
    	}
    }
}


/**************************************

Prep displaying the page

**************************************/
$title = "Tournament Registration";
$inputs = array("t_id");

$form_method = "post";
$form_action = $_SERVER[PHP_SELF]."?view=$view";

if($page->submitIsSet("submit_batch") && $end_result){
    $success_str = "Successfully registered the following players for ".$tournament[0][name].":<br>";

    foreach($players as $p){
        $success_str .= $p[last_name].", ".$p[first_name]."<br>";
    }
}

$page->setDisplayMode("form");
$link = array("href"=>"home.php?view=$view", "text"=>"Register more players?");

$registrations = $tr_db->getRegistrationsByTournamentId($t_id);

/***************************************

Display the page

***************************************/

$meta = '<link rel="stylesheet"  href="'.$page->getWebRoot().'styles/awesomplete.css">
<script src="'.$page->getWebRoot().'js/awesomplete.js" async></script>';

$page->startTemplate($meta);
$page->doTabs();
include("templates/tournament_registration.html");
if(!empty($t_id)){
	include("templates/tournament_registration_listing.html");
}
$page->displayFooter();

?>
