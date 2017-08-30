<?php

require_once("classes/page.php");
require_once("classes/db_achievements.php");
require_once("classes/db_achievements_earned.php");
require_once("classes/db_players.php");
require_once("classes/db_events.php");

$page = new Page();
$a_db = new Achievements();
$ae_db = new Achievements_earned();
$p_db = new Players();
$e_db = new Events();


/**************************************

Handle Deletions

**************************************/
$action = $_REQUEST[action];
$ach_id = $_REQUEST[ach_id];

if(!strcmp($action, "delete")){

	$achievement_e = $ae_db->getById($ach_id);

	$player = $p_db->getById($achievement_e[0]["player_id"]);
	$achievement = $a_db->getById($achievement_e[0]["achievement_id"]);

	if($ae_db->deleteById($ach_id)){
		$success_str = "Successfully deleted '".$achievement[0]["name"]."' from:</br>";
		$success_str .= $player[0]["last_name"].", ".$player["first_name"]."'s record!";
	}
}


/**************************************

Basic Inputs

**************************************/
$page->register("ach_id", "select", array("label"=>"Achievement", "reloading"=>1,
                                          "get_choices_array_func"=>"getEventAchievementChoices",
                                          "get_choices_array_func_args"=>array()));


$page->register("num_players", "select", array( "reloading"=>true, "default_val"=>5,
                                                "get_choices_array_func"=>"getIntegerChoices",
                                                "get_choices_array_func_args"=>array(5, 15, 5)));

$page->getChoices();

$page->register("submit_batch", "submit", array("value"=>"Submit"));


/*************************************

Inputs for each Player

*************************************/

//Determine how many players
$num_players = $page->getVar("num_players");

if(Check::isNull($num_players)){
    $num_players = 5;
}


for($i=1; $i <= $num_players; $i++){
    $page->register("player_".$i."_id", "select", array("label"=>"Player $i",
                                                        "get_choices_array_func"=>"getPlayerChoices",
                                                        "get_choices_array_func_args"=>array()));
}

$page->register("first_player_id", "select", array("label"=>"Player $i", "label"=>"First Place",
                                                    "get_choices_array_func"=>"getPlayerChoices",
                                                    "get_choices_array_func_args"=>array()));
$page->register("second_player_id", "select", array("label"=>"Player $i", "label"=>"Second Place",
                                                    "get_choices_array_func"=>"getPlayerChoices",
                                                    "get_choices_array_func_args"=>array()));
$page->register("third_player_id", "select", array("label"=>"Player $i", "label"=>"Third Place",
                                                    "get_choices_array_func"=>"getPlayerChoices",
                                                    "get_choices_array_func_args"=>array()));

$page->getChoices();


$ach_id = $page->getVar("ach_id");

if(!Check::isNull($ach_id)){
	$achievement = $a_db->getById($ach_id);
	$achievement = $achievement[0];

	$event = $e_db->getById($achievement["event_id"]);
	$event = $event[0];
}


/**************************************

Handle the Submit

**************************************/
if($page->submitIsSet("submit_batch")){

    //First, extract all our inputs

	if(empty($ach_id)){
		$errors[] = "Must pick an Achievement!";
	}


	if(empty($errors)){
		$num_players = intval($page->getVar("num_players"));

   		$players = array();
    	for($i=1; $i <= $num_players; $i++){
        	$id = $page->getVar("player_".$i."_id");
	        if(Check::isNull($id)){ continue;}

    	    $player = $p_db->getById($id);
        	$players[$id] = $player[0];
	    }

    	$end_result = true;

		if($event["is_tournament"]){

			$first_player_id = $page->getVar("first_player_id");
			if(!Check::isNull($first_player_id)){
				$first_ach_id = $a_db->create($event["name"]." (1st)", $achievement["points"]+15, 0, 0, 0, 1, null, null, 0, 0, 0, 0, 0, 0, 0, 0, $event["id"]);
				$ae_db->create($first_player_id, $first_ach_id);
			}
			
			$second_player_id = $page->getVar("second_player_id");
			if(!Check::isNull($second_player_id)){
				$second_ach_id = $a_db->create($event["name"]." (2nd)", $achievement["points"]+10, 0, 0, 0, 1, null, null, 0, 0, 0, 0, 0, 0, 0, 0, $event["id"]);
				$ae_db->create($second_player_id, $second_ach_id);
			}

			$third_player_id = $page->getVar("third_player_id");
			if(!Check::isNull($third_player_id)){
				$third_ach_id = $a_db->create($event["name"]." (3rd)", $achievement["points"]+5, 0, 0, 0, 1, null, null, 0, 0, 0, 0, 0, 0, 0, 0, $event["id"]);
				$ae_db->create($third_player_id, $second_ach_id);
			}
		}

    	foreach($players as $id=>$p){

        	$exists = $ae_db->queryByColumns(array("player_id"=>$id, "achievement_id"=>$ach_id));
        	if(!$exists || $achievement["per_game"]){
            	$result = $ae_db->create($id, $ach_id);
	            $end_result = $end_result && $result;
    	    }
    	}
	}
}


/**************************************

Prep displaying the page

**************************************/
$title = "Event Achievement Batch Processing";
$inputs = array("ach_id", "num_players");

$form_method = "post";
$form_action = $_SERVER[PHP_SELF]."?view=$view";

if($page->submitIsSet("submit_batch") && $end_result){
    $success_str = "Successfully awarded ".$achievement[name]." to:<br>";

    foreach($players as $p){
        $success_str .= $p[last_name].", ".$p[first_name]."<br>";
    }
}

$page->setDisplayMode("form");
$link = array("href"=>"home.php?view=$view", "text"=>"Report Another Batch?");

/***************************************

Display the special section

***************************************/
$page->startTemplate();
$page->doTabs();
include("templates/report_batch.html");
$page->displayFooter();

?>
