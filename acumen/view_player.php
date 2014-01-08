<?php

require_once("classes/page.php");
require_once("classes/db_players.php");
require_once("classes/db_game_sizes.php");
require_once("classes/db_game_system_factions.php");
require_once("classes/db_games.php");
require_once("classes/db_game_players.php");

$page = new Page();
$player_db = new Players();
$size_db = new Game_sizes();
$faction_db = new Game_system_factions();
$game_db = new Games();
$game_players_db = new Game_players();


/*********************************************

Register the inputs

*********************************************/
$page->register("player", "select", array("reloading"=>1,
                                          "get_choices_array_func"=>"getPlayerChoices",
                                          "get_choices_array_func_args"=>array()));
$page->getChoices();


/********************************************

Gather all the data on the chosen player

********************************************/
$selected_player = $page->getVar("player");

if($selected_player){
    $player = $player_db->getById($selected_player);
    $player = $player[0];

    $tmp1 = $game_players_db->getByPlayerId($player[id]);

    $games_played = array();
    foreach($tmp1 as $g){
        $game = $game_db->getById($g[game_id]);
        $game = $game[0];
        
        $tmp2 = $game_players_db->getByGameId($game[id]);

        $game_players = array();
        foreach($tmp2 as $gp){
            $tmp3 = $player_db->getById($gp[player_id]);
            $gp[player_details] = $tmp3[0];

            $size = $size_db->getById($gp[game_size]);
            $gp[size] = $size[0][size];

            //fetch faction
            $faction = $faction_db->getById($gp[faction_id]);
            $gp[faction_name] = $faction[0][name];

            $game_players[] = $gp;
        }

        $game[players] = $game_players;
        $games_played[] = $game;
    }

    /*
        $games_played
            $game       <- games table entry
                [players]       <- game_players table entry
                    [player_details]    <- players table entry
    */
                    
}


/********************************************

Prep the page

********************************************/
if($games_played){
    $odd=true;
    foreach($games_played as $a=>$game){
        $players = $game[players];

        $player_list = "";
        foreach($players as $b=>$p){
            $player_list.= $p[player_details][last_name].", ".$p[player_details][first_name].": ";
            $player_list.= $p[size]."pts of ".$p[faction_name]."<br>";
        }

        $games_played[$a][player_listing] = $player_list;
        
        if($odd)$games_played[$a][style]="odd";
        $odd = !$odd;
    } 
}    

//Usual stuff
$form_method = "post";
$form_action = $_SERVER[PHP_SELF]."?view=$view";

$page->setDisplayMode("form");

$inputs = array("player");

/********************************************

Show the page

********************************************/

$page->startTemplate();
$page->doTabs();
include("templates/view_player.html");
$page->displayFooter();


?>
