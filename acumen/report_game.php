<?php

require_once("classes/page.php");
require_once("classes/db_games.php");
require_once("classes/db_game_players.php");

$page = new Page();
$game_db = new Games();
$game_player_db = new Game_players();

/**************************************

Detect & Handle Edit or Delete Actions

**************************************/
$action = $_REQUEST[action];
$game_id = $_REQUEST["game_id"];
switch($action){
    case "edit":
        $defaults = $game_db->getById($game_id);
        $defaults[players] = $game_players_db->getByGameId($game_id);
        //TODO Calculate Achievements
        break;
    case "delete":
        $game_db->deleteById($game_id);
        $game_players->deleteByColumns(array("game_id"=>$game_id));
        //TODO Calculate Achievements
        break;
    default:
        break;
}


/**************************************

Basic Inputs

**************************************/
$page->register("game_system", "select", array( "required"=>true,
                                                "default_val"=>$defaults[game_system],
                                                "get_choices_array_func"=>"getGameSystems",
                                                "get_choices_array_func_args"=>array()));
$page->register("num_players", "select", array( "reloading"=>true, "default_val"=>count($defaults[players]),
                                                "get_choices_array_func"=>"getIntegerChoices",
                                                "get_choices_array_func_args"=>array(2, 100, 1)));
$page->register("scenario_table", "checkbox", array("on_text"=>"Scenario", "off_text"=>"", 
                                                    "default_val"=>$defaults[scenario]));

$page->getChoices();

$page->register("submit_game", "submit", array("value"=>"Submit"));


/*************************************

Inputs for each Player

*************************************/

//Determine how many players
$num_players = $page->getVar("num_players");

if(Check::isNull($num_players)){
    if($defaults[id]){
        $num_players = count($defaults[players]);
    } else {
        $num_players = 2;
    }
}

//Determine Game System
$game_system = $page->getVar("game_system");
if(Check::isNull($game_system)){
    if($defaults[id]){
        $game_system = $defaults[game_system];
    } else {
        $game_system = 1;
    }
}


for($i=1; $i <= $num_players; $i++){
    $page->register("player_".$i."_id", "select", array("label"=>"Player $i",
                                                        "default_val"=>$defaults[players][$i][player_id],
                                                        "get_choices_array_func"=>"getPlayerChoices",
                                                        "get_choices_array_func_args"=>array()));
    $page->register("player_".$i."_faction", "select", array("default_val"=>$defaults[players][$i][faction_id],
                                                             "get_choices_array_func"=>"getGameSystemFactions",
                                                             "get_choices_array_func_args"=>array($game_system))
                                                             );
    $page->register("player_".$i."_size", "select", array("default_val"=>$defaults[players][$i][game_size],
                                                             "get_choices_array_func"=>"getGameSizes",
                                                             "get_choices_array_func_args"=>array($game_system))
                                                             );
    $page->register("player_".$i."_theme_force", "checkbox", array("label"=>"Played Theme Force",
                                                                   "default_val"=>$defaults[players][$i][theme_force],
                                                                   "on_text"=>"Yes", "off_text"=>"No"));
    $page->register("player_".$i."_fully_painted", "checkbox", array("label"=>"Played Fully Painted",
                                                                   "default_val"=>$defaults[players][$i][fully_painted],
                                                                   "on_text"=>"Yes", "off_text"=>"No"));
    $page->register("player_".$i."_won", "checkbox", array("label"=>"Won",
                                                            "default_val"=>$defaults[players][$i][winner],
                                                            "on_text"=>"Yes", "off_text"=>"No"));
}
$page->getChoices();


/**************************************

Handle the Submit

**************************************/
if($page->submitIsSet("submit_game")){

    //First, extract all our inputs
    $game_system = $page->getVar("game_system");
    $num_players = intval($page->getVar("num_players"));
    $scenario = $page->isChecked("scenario_table");

    $players = array();
    for($i=1; $i <= $num_players; $i++){
        $id = $page->getVar("player_".$i."_id");
        if(Check::isNull($id)){ continue;}

        $players[$id] = array();

        $players[$id][faction] = $page->getVar("player_".$i."_faction");
        $players[$id][size] = $page->getVar("player_".$i."_size");
        $players[$id][theme_force] = $page->getVar("player_".$i."_theme_force");
        $players[$id][won] = $page->getVar("player_".$i."_won");
        $players[$id][fully_painted] = $page->getVar("player_".$i."_fully_painted");
        
    } 

    //Next, validate
    if(count($players) < 2){
        $errors[] = "Not enough players selected, need at least 2!";
    }

    if(Check::isNull($game_system)){ $errors[] = "Choose a Game System!";}
   
    for($i=1; $i <= $num_players; $i++){
        if(Check::isNull($players[$id][faction])){ $errors[] = "Choose a Faction for Player $i!";}
        if(Check::isNull($players[$id][size])){ $errors[] = "Choose an Army Size for Player $i!";}

        //Just set these to 0 if they're null
        if(Check::isNull($players[$id][theme_force])){ $players[$id][theme_force]=0; }
        if(Check::isNull($players[$id][won])){ $players[$id][won]=0; }
        if(Check::isNull($players[$id][fully_painted])){ $players[$id][fully_painted]=0; }
    }


    //Do stuff
    if(empty($errors)){
        
        //Handle the parent game
        if($game_id){
            $game_db->updateByColumns(array("game_system"=>$game_system, "scenario"=>$scenario));
            $parent_game_id = $game_id;
        } else {
            $parent_game_id = $game_db->create($game_system, $scenario);
        }
        
        //Handle the children players
        if($game_id){   //If we're updating, clear the slate
            $game_player_db->deleteByColumns(array("game_id"=>$parent_game_id));
        }

        $result = true;
        foreach($players as $id=>$player){
            $creation = $game_player_db->create($parent_game_id, $id, $player[faction], $player[size],
                                                $player[theme_force], $player[fully_painted], $player[won]);
            $result = $result && $creation;
        }

        foreach(array_keys($players) as $pid){
            //TODO Recalculate Achievements
        }

    }
}


/**************************************

Prep displaying the page

**************************************/
$title = "Report a Game";
$inputs = array("game_system", "num_players", "scenario_table");
$player_inputs = array("_id", "_faction", "_size", "_theme_force", "_fully_painted", "_won");

$form_method = "post";
$form_action = $_SERVER[PHP_SELF]."?view=$view";

$page->setDisplayMode("form");


/***************************************

Display the special section

***************************************/
$page->startTemplate();
$page->doTabs();
include("templates/report_game.html");
$page->displayFooter();

?>
