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
    $ae_db->deleteById($ach_id);
}


/**************************************

Basic Inputs

**************************************/
$page->register("ach_id", "select", array("label"=>"Achievement",
                                          "get_choices_array_func"=>"getEventAchievementChoices",
                                          "get_choices_array_func_args"=>array()));

$page->register("num_players", "select", array( "reloading"=>true, "default_val"=>5,
                                                "get_choices_array_func"=>"getIntegerChoices",
                                                "get_choices_array_func_args"=>array(2, 10, 1)));

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
$page->getChoices();


/**************************************

Handle the Submit

**************************************/
if($page->submitIsSet("submit_batch")){

    //First, extract all our inputs
    $ach_id = $page->getVar("ach_id");
    $num_players = intval($page->getVar("num_players"));

    $players = array();
    for($i=1; $i <= $num_players; $i++){
        $id = $page->getVar("player_".$i."_id");
        if(Check::isNull($id)){ continue;}

        $player = $p_db->getById($id);
        $players[$id] = $player[0];
    }

    $end_result = true;
    foreach($players as $id=>$p){
        $result = $ae_db->create($id, $ach_id);
        $end_result = $end_result && $result;
    }

    $achievement = $a_db->getById($ach_id);
    $achievement = $achievement[0];
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
