<?php

require_once('include/classes/player.php');
require_once('include/classes/game.php');

$p = new Player();
$player_list_by_points = $p->getPlayersByPoints();
$player_list_by_num_games = $p->getPlayersByNumgames();

$g = new Game();
$games = $g->getAllGames();

$num_players = count($player_list_by_points);

$pld = $player_list_by_points[0];
$point_leader = $pld[lastname].', '.$pld[firstname].' @ '.$pld[points].' pts';

$nld = $player_list_by_num_games[0];
$numgames_leader = $nld[lastname].', '.$nld[firstname].' @ '.$nld[numgames].' games';

$num_games = count($games);

include('include/templates/default_header.tpl');
include('include/templates/stats.tpl');
include('include/templates/default_footer.tpl');

?>
