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

Handle coming here from a link

**************************************/
$t_id = $_REQUEST[t_id];


/**************************************

Require user to pick a Tournament

**************************************/
$page->register("t_id", "select", array("reloading"=>1, "label"=>"Tournament", "default_val"=>$t_id,
										"get_choices_array_func"=>"getTournamentChoices",
                                        "get_choices_array_func_args"=>array()));

$t_id = $page->getVar("t_id");


/**************************************

Additional Inputs

**************************************/
if(!empty($t_id)){

	$tournament = $te->getTournament($t_id);

	echo print_r($tournament);

	//Register inputs!
	for($i=0; $i < count($tournament["games"]); $i++){
		$page->register("list_used_".$i, "select", array("get_choices_array_func"=>"getListChoices",
							"get_choices_array_func_args"=>array($tournament["num_lists_required"])));
		$page->register("won_".$i, "checkbox", array("on_text"=>"YES", "off_text"=>"NO", ($tournament["games"][$i]["winner_id"]==$tournament["games"][$i]["player_id"] ? true : false)));
		$page->register("cp_earned_".$i, "number", array("min"=>0, "max"=>100, "step"=>1, "default_val"=>$tournament["games"][$i]["control_points"]));
		$page->register("ap_earned_".$i, "number", array("min"=>0, "max"=>100, "step"=>1, "default_val"=>$tournament["games"][$i]["destruction_points"]));
		$page->register("mage_hunter_score_".$i, "number", array("min"=>0, "max"=>100, "step"=>1, "default_val"=>$tournament["games"][$i]["assassination_efficiency"]));
		$page->register("timed_out_".$i, "checkbox", array("on_text"=>"YES", "off_text"=>"NO", "default_val"=>$tournament["games"][$i]["timed_out"]));

		if($i % 2 == 0){
			$page->register("submit_game_".$tournament["games"][$i]["game_id"], "submit", array("value"=>"Submit Game"));
		}
	}
}

$page->getChoices();


/**************************************

Prep displaying the page

**************************************/
$title = "Manage Tournament";
$inputs = array("t_id");

$form_method = "post";
$form_action = $_SERVER[PHP_SELF]."?view=$view";


//Default template, may be overridden later
$page->setDisplayMode("form");

$link = array("href"=>"home.php?view=$view", "text"=>"Register more players?");


/***************************************

Display the page

***************************************/
$page->startTemplate($meta);
$page->doTabs();
include("templates/tournament_game_listing.html");
$page->displayFooter();

?>
