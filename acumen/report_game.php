<?php

require_once("classes/page.php");
require_once("classes/db_games.php");
require_once("classes/db_game_players.php");
require_once("classes/db_game_system_factions.php");
require_once("classes/db_game_sizes.php");
require_once("classes/db_achievements.php");
require_once("achievement_engine.php");

$page = new Page();
$game_db = new Games();
$game_player_db = new Game_players();
$faction_db = new Game_system_factions();
$size_db = new Game_sizes();
$ach_db = new Achievements();

$engine = new Ach_Engine();

/**************************************

Detect & Handle Edit or Delete Actions

**************************************/
$action = $_REQUEST[action];
$game_id = $_REQUEST["game_id"];
switch($action){
    case "edit_game":
        $defaults = $game_db->getById($game_id);
        $defaults = $defaults[0];
        $defaults[players] = $game_player_db->getByGameId($game_id);
        break;
    
    case "delete":  //Gotta delete children first...

        //get record of who played in the game
        $affected = $game_player_db->getByGameId($game_id); 

        //Erase all traces of the game
        $game_player_db->deleteByColumns(array("game_id"=>$game_id));
        $engine->deleteGameAchievements($game_id); 
        $game_db->deleteById($game_id);

        //Redress the affected player's achievements
        foreach($affected as $afp){
            $engine->redressAchievements($afp[player_id]);
        }

        //Report the status
        $result=true;
        $success_str = "Successfully Deleted Game!";
        break;
    
    default:
        break;
}


/**************************************

Basic Inputs

**************************************/
$page->register("game_system", "select", array( "required"=>true, "reloading"=>1,
                                                "default_val"=>$defaults[game_system],
                                                "get_choices_array_func"=>"getGameSystems",
                                                "get_choices_array_func_args"=>array()));
$page->register("game_id", "hidden", array("value"=>$game_id));
$page->register("num_players", "select", array( "reloading"=>true, "default_val"=>count($defaults[players]),
                                                "get_choices_array_func"=>"getIntegerChoices",
                                                "get_choices_array_func_args"=>array(2, 10, 1)));
$page->register("scenario_table", "checkbox", array("on_text"=>"Scenario", "off_text"=>"", 
                                                    "default_val"=>$defaults[scenario]));

$page->getChoices();

$page->register("submit_game", "submit", array("value"=>"Submit"));


/*************************************

Gather data on the Game System

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

//Determine if Game System uses factions, army sizes, fully painted, theme forces, etc
$num_factions = $faction_db->getByParentGameSystem($game_system);
$uses_factions = count($num_factions) > 0;

$num_sizes = $size_db->getByParentGameSystem($game_system);
$uses_sizes = count($num_sizes) > 0;

$num_painted_ach = $ach_db->queryByColumns(array("game_system_id"=>$game_system, "fully_painted"=>1));
$num_painted_all_ach = $ach_db->queryByColumns(array("game_system_id"=>$game_system, "fully_painted_battle"=>1));
$uses_painted = (count($num_painted_ach) > 0) || (count($num_painted_all_ach) > 0);

$num_theme_ach = $ach_db->queryByColumns(array("game_system_id"=>$game_system, "played_theme_force"=>1));
$uses_theme = count($num_theme_ach) > 0;

$num_scenario_ach = $ach_db->queryByColumns(array("game_system_id"=>$game_system, "played_scenario"=>1));
$uses_scenario = count($num_scenario_ach) > 0;


/*************************************

Inputs for each Player

*************************************/
for($i=1; $i <= $num_players; $i++){
    $page->register("player_".$i."_id", "select", 
        array("label"=>"Player $i",
            "default_val"=>$defaults[players][$i-1][player_id],
            "get_choices_array_func"=>"getPlayerChoices",
            "get_choices_array_func_args"=>array()));
    
    if($uses_factions)
        $page->register("player_".$i."_faction", "select", 
            array("default_val"=>$defaults[players][$i-1][faction_id],
                "get_choices_array_func"=>"getGameSystemFactions",
                "get_choices_array_func_args"=>array($game_system)));
    
    if($uses_sizes)
        $page->register("player_".$i."_size", "select", 
            array("default_val"=>$defaults[players][$i-1][game_size],                                                     "get_choices_array_func"=>"getGameSizes",
                "get_choices_array_func_args"=>array($game_system)));

    if($uses_theme)            
        $page->register("player_".$i."_theme_force", "checkbox", 
            array("label"=>"Played Theme Force",
                "default_val"=>$defaults[players][$i-1][theme_force],
                "on_text"=>"Yes", "off_text"=>"No"));

    if($uses_painted)
        $page->register("player_".$i."_fully_painted", "checkbox", 
            array("label"=>"Played Fully Painted",
                "default_val"=>$defaults[players][$i-1][fully_painted],
                "on_text"=>"Yes", "off_text"=>"No"));

}
$page->getChoices();


/**************************************

Handle the Submit

**************************************/
if($page->submitIsSet("submit_game")){

    //First, extract all our inputs
    $game_system = $page->getVar("game_system");
    if(empty($game_id))$game_id = $page->getVar("game_id");
    $num_players = intval($page->getVar("num_players"));
    $scenario = $page->getVar("scenario_table");
    if(Check::isNull($scenario)) $scenario=0;

    $players = array();
    for($i=1; $i <= $num_players; $i++){
        $id = $page->getVar("player_".$i."_id");
        if(Check::isNull($id)){ continue;}

        $players[$id] = array();

        if($uses_factions)
            $players[$id][faction] = $page->getVar("player_".$i."_faction");
        if($uses_sizes)
            $players[$id][size] = $page->getVar("player_".$i."_size");
        if($uses_theme)
            $players[$id][theme_force] = $page->getVar("player_".$i."_theme_force");
        if($uses_painted)
            $players[$id][fully_painted] = $page->getVar("player_".$i."_fully_painted");
    } 

    //Next, validate
    if(count($players) < 2){
        $errors[] = "Not enough players selected, need at least 2!";
    }

    if(Check::isNull($game_system)){ $errors[] = "Choose a Game System!";}
   
    $i=1;
    foreach(array_keys($players) as $id){

        //Retrieve their game entries
        $game_entries = $game_player_db->getByPlayerId($id);

        //Get the oldest game
        if(!empty($game_entries)){
            $last_entry = end($game_entries);
            $mins_elapsed = $game_db->getMinutesFromGameTime($last_entry["game_id"]);

            if($mins_elapsed[0]["minutes"] < 60){
                $errors[] = "Player $i must wait ".round(60-$mins_elapsed[0]["minutes"])." minutes to report a game!";
            }
        }

        if($uses_factions && Check::isNull($players[$id][faction]))
            $errors[] = "Choose a Faction for Player $i!";


        if($uses_sizes && Check::isNull($players[$id][size]))
                $errors[] = "Choose an Army Size for Player $i!";

        //Just set these to 0 if they're null, because they're mandatory in the db
        if(Check::isNull($players[$id][theme_force])){ $players[$id][theme_force]=0; }
        if(Check::isNull($players[$id][fully_painted])){ $players[$id][fully_painted]=0; }

        $i++;
    }


    //Do stuff
    if(empty($errors)){
        
        //Handle the parent game
        if($game_id){
            $game_db->updateGamesById($game_id, array("game_system"=>$game_system, "scenario"=>$scenario));
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
                                                $player[theme_force], $player[fully_painted]);
            $result = $result && $creation;
        }


        if($game_id){
            $engine->recalculateAchievements($parent_game_id);
        } else {
            $engine->awardAchievements($parent_game_id);
        }

    }
}


/**************************************

Prep displaying the page

**************************************/
$title = "Report a Game";
$inputs = array("game_system", "game_id", "num_players");
if($uses_scenario){
    $inputs[] = "scenario_table";
}

$form_method = "post";
$form_action = $_SERVER[PHP_SELF]."?view=$view";

if($page->submitIsSet("submit_game") && $result){
    $success_str = "Successfully ";
    if($game_id){
        $success_str.= "updated ";
    } else {
        $success_str.= "created ";
    }
    $success_str.= "the game!";

    $details = $engine->getGameDetails($parent_game_id);
}

$page->setDisplayMode("form");
$link = array("href"=>"home.php?view=$view", "text"=>"Report Another Game?");

/***************************************

Display the special section

***************************************/
$page->startTemplate();
$page->doTabs();
include("templates/report_game.html");
$page->displayFooter();

?>
