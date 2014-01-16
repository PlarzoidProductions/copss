<?php

require_once("classes/page.php");
require_once("classes/db_players.php");
require_once("classes/db_game_sizes.php");
require_once("classes/db_game_system_factions.php");
require_once("classes/db_games.php");
require_once("classes/db_game_players.php");
require_once("classes/db_prize_redemptions.php");
require_once("classes/views.php");
require_once("achievement_engine.php");


$page = new Page();
$player_db = new Players();
$size_db = new Game_sizes();
$faction_db = new Game_system_factions();
$game_db = new Games();
$game_players_db = new Game_players();
$pr_db = new Prize_redemptions();
$engine = new Ach_Engine();
$views_db = new Views();


$pl_id = $_REQUEST[pl_id];

/*********************************************

Register the inputs

*********************************************/
$page->register("player", "select", array("reloading"=>1, "default_val"=>$pl_id,
                                          "get_choices_array_func"=>"getPlayerChoices",
                                          "get_choices_array_func_args"=>array()));
$page->getChoices();


/********************************************

Gather all the data on the chosen player

********************************************/
if($pl_id){
    if(Check::notInt($pl_id)){
        $error = "Not a valid player id in URL!";
    } else {
        $selected_player = $pl_id;
    }
} else {
    $selected_player = $page->getVar("player");
}

if($selected_player){

    $player = $engine->getPlayerHistory($selected_player);

    foreach($player[games] as $i=>$g){
        foreach($g[players] as $j=>$gp){
            $tmp3 = $player_db->getById($gp[player_id]);
            $player[games][$i][players][$j][player_details] = $tmp3[0];

            $size = $size_db->getById($gp[game_size]);
            $player[games][$i][players][$j][size] = $size[0][size];

            //fetch faction
            $faction = $faction_db->getById($gp[faction_id]);
            $player[games][$i][players][$j][faction_name] = $faction[0][name];

        }
    }
                    
}


/********************************************

Prep the page

********************************************/
if($selected_player){


    //Game Data
    $odd=true;
    foreach($player[games] as $a=>$game){
        $players = $game[players];

        $player_list = "";
        foreach($players as $b=>$p){
            $player_list.= $p[player_details][last_name].", ".$p[player_details][first_name].": ";
            $player_list.= $p[size]."pts of ".$p[faction_name]."<br>";
        
            if($p[player_id] == $selected_player){
                $achievement_listing = "";
                $points = 0;
                foreach($p[achievements] as $ach){
                    $achievement_listing .= $ach[name]." (".$ach[points].")<br>";
                    $points += $ach[points];
                }

                $player[games][$a][achievement_listing] = $achievement_listing;
                $player[games][$a][points_earned] = $points;
            }
        }

        $player[games][$a][player_listing] = $player_list;
        
        if($odd)$player[games][$a][style]="odd";
        $odd = !$odd;
    }

    
    //Stats
    $stats = $engine->getPlayerStats($selected_player);
    
    $faction_list = "";
    foreach($stats[factions] as $f){
        $faction_details = $faction_db->getById($f);
        $faction_list .= $faction_details[0][acronym];
        if($f != end($stats[factions])){
            $faction_list .= ", ";
        }
    }
    $stats[faction_list] = $faction_list;

    $earned = $views_db->queryByColumns("earned", array("player_id"=>$selected_player));
    $spent = $views_db->queryByColumns("spent", array("player_id"=>$selected_player));

    $stats[points] = $earned[0][earned] - $spent[0][spent];


    //Prize Redemptions
    $redemptions = $pr_db->queryByColumns(array("player_id"=>$selected_player));
    $odd = true;
    foreach($redemptions as $k=>$pr){
        if($odd)$redemptions[$k][style]="odd";
        $odd = !$odd;
    }
}    

//Usual stuff
$form_method = "post";
$form_action = $_SERVER[PHP_SELF]."?view=$view";
$title = "View Player Details";

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
