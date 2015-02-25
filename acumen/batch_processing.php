<?php

require_once("classes/page.php");
require_once("classes/db_achievements.php");
require_once("classes/db_achievements_earned.php");
require_once("classes/db_players.php");

$page = new Page();
$a_db = new Achievements();
$ae_db = new Achievements_earned();
$p_db = new Players();


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
$page->register("ach_id", "select", array("label"=>"Tournament",
                                          "get_choices_array_func"=>"getEventAchievementChoices",
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

for($i=1; $i <= $num_players; $i++){
    $page->register("player_".$i."_id", "select", array("label"=>"Player $i",
                                                        "get_choices_array_func"=>"getPlayerChoices",
                                                        "get_choices_array_func_args"=>array()));
}
$page->getChoices();



var_dump("here");

/**************************************

Handle the Submit

**************************************/
if($page->submitIsSet("submit_batch")){

    //First, extract all our inputs
    $ach_id = $page->getVar("ach_id");


	if(empty($ach_id)){
		$errors[] = "Must pick an Achievement!";
	}

	//Next, get our podium
	if(empty($errors)){
		$first = $page->getVar("first_place");
		$second = $page->getVar("second_place");
		$third = $page->getVar("third_place");

var_dump("here");
		if(
			(Check::notNull($first) && Check::notNull($second) && ($first == $second)) || 
			(Check::notNull($second) && Check::notNull($third) && ($second == $third)) ||
			(Check::notNull($first) && Check::notNull($third) && ($first == $third))){
			$errors[] = "Same player cannot be awarded two podium spots!";
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

		//DB interation Status flag
    	$end_result = true;

		//Achievement details
		$achievement = $a_db->getById($ach_id);
        $achievement = $achievement[0];


		//Award Participation
    	foreach($players as $id=>$p){
        	$exists = $ae_db->queryByColumns(array("player_id"=>$id, "achievement_id"=>$ach_id));
        	if(!$exists || $achievement["per_game"]){
            	$result = $ae_db->create($id, $ach_id);
	            $end_result = $end_result && $result;
    	    }
    	}
var_dump("here");
		//Award Podium
		if(Check::notNull($first)){$result = $ae_db->create($first, $ach_id+1); $end_result = $end_result && $result;}
		if(Check::notNull($second)){$result = $ae_db->create($second, $ach_id+2); $end_result = $end_result && $result;}
		if(Check::notNull($third)){$result = $ae_db->create($third, $ach_id+3); $end_result = $end_result && $result;}
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
	
	$first_winner = $p_db->getById($first);
	$success_str = "Successfully awarded First Place to ".$first_winner[0][last_name].", ".$first_winner[0][first_name]."<br>";
	$second_winner = $p_db->getById($second);
    $success_str .= "Successfully awarded Second Place to ".$second_winner[0][last_name].", ".$second_winner[0][first_name]."<br>";
	$third_winner = $p_db->getById($third);
    $success_str .= "Successfully awarded Third Place to ".$third_winner[0][last_name].", ".$third_winner[0][first_name]."<br>";

	$success_str .= "<br>Successfully awarded ".$achievement[name]." to:<br>";

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
