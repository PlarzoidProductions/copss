<?php

require_once("classes/page.php");
require_once("classes/db_players.php");
require_once("acumen/achievement_engine.php");
require_once("classes/views.php");

$page = new Page();
$player_db = new Players();
$views_db = new Views();
$engine = new Ach_Engine;

/********************************************

Register two inputs

********************************************/
$page->register("sort", "select", array("get_choices_array_func"=>"leaderboardSortChoices",
                                        "get_choices_array_func_args"=>array(),
                                        "reloading"=>1, "default_val"=>"points"));
$page->register("direction", "select", array("get_choices_array_func"=>"sortDirectionChoices",
                                        "get_choices_array_func_args"=>array(),
                                        "reloading"=>1));
$page->getChoices();


/********************************************

Get the sort method

********************************************/
$sortby = $page->getVar("sort");
if(!$sortby) $sortby = "points";
$sortdir = $page->getVar("direction");
if(empty($sortdir) && !is_numeric($sortdir)) $sortdir=1;


/********************************************

Gather all the data on the players

********************************************/

$players = $views_db->getAll("leaderboard");

$sorter = array();
$names = array();
$points = array();

foreach($players as $k=>$p){
    $players[$k][name] = $p[last_name].", ".$p[first_name];
    $players[$k][points] = $players[$k][earned] + $players[$k][spent];

    //Arrays for sorting
    $sorter[$k] = $players[$k][$sortby];
    $names[$k] = strtolower($players[$k][name]);
    $points[$k] = $players[$k][points];
}

//Sort stuff
if(!strcmp($sortby, "name")){
    if($sortdir){//descending
        $sort_success = array_multisort($names, SORT_DESC, $points, SORT_NUMERIC, SORT_DESC, $players);
    } else {
        $sort_success = array_multisort($names, SORT_ASC, $points, SORT_NUMERIC, SORT_DESC, $players);
    }
} else {
    if($sortdir){//descending
        $sort_success = array_multisort($sorter, SORT_NUMERIC, SORT_DESC, $names, SORT_ASC, $players);
    } else {
        $sort_success = array_multisort($sorter, SORT_NUMERIC, SORT_ASC, $names, SORT_ASC, $players);
    }
}


/********************************************

do the style thing

********************************************/
$odd=true;
foreach($players as $k=>$p){
    if($odd) $players[$k][style] = "odd";
    $odd = !$odd;
}

/********************************************

Prep the page

********************************************/

//Usual stuff
$form_method = "post";
$form_action = $_SERVER[PHP_SELF]."?view=$view";
$title = "Leaderboard!";

$page->setDisplayMode("form");
$inputs = array("sort", "direction");

/********************************************

Show the page

********************************************/

$page->startTemplate();
$page->doTabs();
include("templates/leaderboard.html");
$page->displayFooter();


?>
