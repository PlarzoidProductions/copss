<?php

require_once("classes/page.php");
require_once("classes/db_achievements.php");
require_once("classes/db_achievements_earned.php");
require_once("classes/db_players.php");
require_once("classes/db_events.php");
require_once("classes/check.php");

$page = new Page();
$a_db = new Achievements();
$ae_db = new Achievements_earned();
$p_db = new Players();
$e_db = new Events();


/**************************************

Basic Inputs

**************************************/
$page->register("event_id", "select", array("label"=>"Tournament",
                                          "get_choices_array_func"=>"getEvents",
                                          "get_choices_array_func_args"=>array()));

$page->register("num_players", "select", array( "reloading"=>true, "default_val"=>5,
                                                "get_choices_array_func"=>"getIntegerChoices",
                                                "get_choices_array_func_args"=>array(5, 30, 5)));

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


$page->register("first_place", "select", array("label"=>"First",
                                                        "get_choices_array_func"=>"getPlayerChoices",
                                                        "get_choices_array_func_args"=>array()));
$page->register("second_place", "select", array("label"=>"Second",
                                                        "get_choices_array_func"=>"getPlayerChoices",
                                                        "get_choices_array_func_args"=>array()));
$page->register("third_place", "select", array("label"=>"Third",
                                                        "get_choices_array_func"=>"getPlayerChoices",
                                                        "get_choices_array_func_args"=>array()));
$page->register("fourth_place", "select", array("label"=>"Fourth",
                                                        "get_choices_array_func"=>"getPlayerChoices",
                                                        "get_choices_array_func_args"=>array()));

for($i=1; $i <= $num_players; $i++){
    $page->register("player_".$i."_id", "select", array("label"=>"Player $i",
                                                        "get_choices_array_func"=>"getPlayerChoices",
                                                        "get_choices_array_func_args"=>array()));
}
$page->getChoices();

/**************************************

Handle the Submit

**************************************/
if($page->submitIsSet("submit_batch")){

    //First, extract the event ID
    $event_id = $page->getVar("event_id");

	//Check for null
	if(empty($event_id)){
		$errors[] = "Must pick an Achievement!";
	}

	//Next, get our podium
	if(empty($errors)){
		$first = $page->getVar("first_place");
		$second = $page->getVar("second_place");
		$third = $page->getVar("third_place");
		$fourth = $page->getVar("fourth_place");

		//Make sure nothing's null
		if(Check::isNull($first)){ $errors[] = "Missing First Place Player!"; }
		if(Check::isNull($second)){ $errors[] = "Missing Second Place Player!"; }
		if(Check::isNull($third)){ $errors[] = "Missing Third Place Player!"; }
		if(Check::isNull($fourth)){ $errors[] = "Missing Fourth Place Player!"; }


		//If we have four players, check for duplication
		if(empty($errors)){

			$podium = array($first, $second, $third, $fourth);
			if(count(array_unique($podium)) != 4){
				$errors[] = "Same player cannot be awarded two podium spots!";
			}
		}
	}

	//Then, get all our participants
	if(empty($errors)){
		$num_players = intval($page->getVar("num_players"));

   		$players = array();
    	for($i=1; $i <= $num_players; $i++){
        	$id = $page->getVar("player_".$i."_id");
	        if(Check::isNull($id)){ continue;}
			if(($id == $first) || ($id == $second) || ($id == $third)) continue;

    	    $player = $p_db->getById($id);
        	$players[$id] = $player[0];
	    }

		//DB interaction Status flag
    	$end_result = true;

		//Snag Event details
		$event = $e_db->getById($event_id);
        $event = $event[0];

		//Generate the Achievements
		//public function create($name, $points, $per_game, $is_meta, $game_count, $game_system_id, $game_size_id, 
		//						$faction_id, $unique_opponent, $unique_opponent_locations, $played_theme_force, 
		//						$fully_painted, $fully_painted_battle, $played_scenario, $multiplayer, $vs_vip, $event_id)
		$first_id =  $a_db->create("1st @ ".$event["name"], 6, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $event_id);
		$second_id = $a_db->create("2nd @ ".$event["name"], 5, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $event_id);
		$third_id =  $a_db->create("3rd @ ".$event["name"], 3, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $event_id);
		$fourth_id = $a_db->create("4th @ ".$event["name"], 3, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $event_id);
		$part_id =   $a_db->create($event["name"]." Participation", 2, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $event_id);


		//Award Participation
    	foreach($players as $id=>$p){
        	$exists = $ae_db->queryByColumns(array("player_id"=>$id, "achievement_id"=>$part_id));
        	if(!$exists){
            	$result = $ae_db->create($id, $part_id);
	            $end_result = $end_result && $result;
    	    }
    	}
		
		//Award Podium
		if(Check::notNull($first)){$result = $ae_db->create($first, $first_id); $end_result = $end_result && $result;}
		if(Check::notNull($second)){$result = $ae_db->create($second, $second_id); $end_result = $end_result && $result;}
		if(Check::notNull($third)){$result = $ae_db->create($third, $third_id); $end_result = $end_result && $result;}
		if(Check::notNull($fourth)){$result = $ae_db->create($fourth, $fourth_id); $end_result = $end_result && $result;}
	}
}


/**************************************

Prep displaying the page

**************************************/
$title = "Event Achievement Batch Processing";
$inputs = array("event_id", "num_players");

$form_method = "post";
$form_action = $_SERVER[PHP_SELF]."?view=$view";

if($page->submitIsSet("submit_batch") && $end_result){
	
	$sucess_str = "";
	if(Check::notNull($first)){
		$first_winner = $p_db->getById($first);
		$success_str .= "Successfully awarded First Place to ".$first_winner[0][last_name].", ".$first_winner[0][first_name]."<br>";
	}
	if(Check::notNull($second)){
		$second_winner = $p_db->getById($second);
    		$success_str .= "Successfully awarded Second Place to ".$second_winner[0][last_name].", ".$second_winner[0][first_name]."<br>";
	}
	if(Check::notNull($third)){
		$third_winner = $p_db->getById($third);
		$success_str .= "Successfully awarded Third Place to ".$third_winner[0][last_name].", ".$third_winner[0][first_name]."<br>";
	}
	if(Check::notNull($fourth)){
        $fourth_winner = $p_db->getById($fourth);
        $success_str .= "Successfully awarded Fourth Place to ".$fourth_winner[0][last_name].", ".$fourth_winner[0][first_name]."<br>";
    }

	$success_str .= "<br>Successfully awarded Participation points for ".$event["name"]." to:<br>";

    foreach($players as $p){
		if(($p[id] == $first) || ($p[id] == $second) || ($p[id] == $third)) continue;
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
