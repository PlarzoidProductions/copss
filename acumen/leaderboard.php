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
                                        "reloading"=>1));
$page->register("direction", "select", array("get_choices_array_func"=>"sortDirectionChoices",
                                        "get_choices_array_func_args"=>array(),
                                        "reloading"=>1));
$page->getChoices();


/********************************************

Gather all the data on the players

********************************************/
$players = $player_db->getAll();

foreach($players as $k=>$p){
    $stats = $engine->getPlayerStats($p[id]);
    $players[$k][game_count] = $stats[games];
    $players[$k][opponents] = $stats[opponents];
    $players[$k][locations] = $stats[locations];
    $players[$k][factions] = count($stats[factions]);

    $earned = $views_db->queryByColumns("earned", array("player_id"=>$p[id]));
    $spent = $views_db->queryByColumns("spent", array("player_id"=>$p[id]));
    $players[$k][earned] = $earned[0][earned];
    $players[$k][spent] = $spent[0][spent];
    $players[$k][points] = $players[$k][earned] - $players[$k][spent];
}


/********************************************

Get the sort method

********************************************/
$sortby = $page->getVar("sort");
$sortdir = $page->getVar("direction");


//Algorithm taken from php documentation, user jimpoz @ jimpoz . com
    



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
