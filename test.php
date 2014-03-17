<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

require_once("classes/db_games.php");
require_once("acumen/achievement_engine.php");
require_once("classes/page.php");//will give us all the db_classes

$game_db = new Games();
$engine = new Ach_Engine();
$faction_db = new Game_system_factions();
$player_db = new Players();
$game_players_db = new Game_players();

$player_stats = array();

$players = $player_db->getAll();

$num_winninger_players = 0;
$num_2f_players = 0;

$factions = array();

$games_played = $game_players_db->getAll();

foreach($games_played as $game_entry){

    if(empty($factions[$game_entry[faction_id]][$game_entry[player_id]])){
        $factions[$game_entry[faction_id]][$game_entry[player_id]] = 1;
    } else {
        $factions[$game_entry[faction_id]][$game_entry[player_id]]++;
    }

}

print_r($factions);

foreach($factions as $f_id=>$counts){
    $faction_details = $faction_db->getById($f_id);


    if($faction_details[0][parent_game_system] != 1) continue;

    echo $faction_details[0][name].":\n";

    echo "Num Players = ".count($counts).", ";

    $sum=0;
    foreach($counts as $count){
        $sum+=$count;
    }

    echo "Num Games = ".$sum.", ";
    echo "Avg Num Games = ".$sum/count($counts)."\n\n";

}

?>

