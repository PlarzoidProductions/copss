<?php

//$page started in index.php
//$s started in index.php
//player class imported?

require_once("include/classes/game.php");

$form_action="index.php?view=Report_Game";
$form_method="post";

//register global information
$page->register("numopponents", "select", array("use_post"=>1, "get_choices_array_func"=>"getNumOpponentsChoices",
                        "get_choices_array_func_args"=>"no", "default_val"=>'2', "reloading"=>1));

$page->register("was_teamgame", "checkbox", array("use_post"=>1, "on_text"=>"Team Game", "off_text"=>"1 v 1"));
$page->register("was_scenariotable", "checkbox", array("use_post"=>1, "on_text"=>"Scenario Table", "off_text"=>"Basic Table"));

$page->register("game_type", "select", array("use_post"=>1, "get_choices_array_func"=>"getGameTypeChoices", 
						"get_choices_array_func_args"=>null, "reloading"=>1));

$page->register("submit_report", "submit", array("use_post"=>1, "value"=>"Report Game"));

//see how many players we have
$numopponents = $page->getVar("numopponents");

//register variables for each
for($i=1; $i<=$numopponents; $i++){
	$page->register("player".$i, "select", array("get_choices_array_func"=>"getPlayerListChoices", "get_choices_array_func_args"=>"no"));
	$page->register("player".$i."size", "select", array("get_choices_array_func"=>"getGameSizeChoices", "get_choices_array_func_args"=>"no"));
	$page->register("player".$i."faction", "select", array("get_choices_array_func"=>"getFactionChoices", "get_choices_array_func_args"=>"no"));
	$page->register("player".$i."painted", "checkbox", array("use_post"=>1, "on_text"=>"Fully Painted", "off_text"=>"Unpainted"));

}

//populate select boxes
$page->getChoices();

//if game was submitted, do some bookkeeping
if($page->submitIsSet("submit_report")){

	$p = new Player();	

	$g = new Game();

	$numpainted=0;
	//extract info from input
	for($i=1; $i<=$numopponents; $i++){
		$player_list[$i] = $page->getVar("player".$i);
		$player_info[$i] = $p->findPlayerById($page->getVar("player".$i));
		$size_list[$i] = $page->getVar("player".$i."size");
		$faction_list[$i] = $page->getVar("player".$i."faction");
		if($page->isChecked("player".$i."painted")){
			$numpainted+=1;
			$paintedlist[$i]=1;
		} else {
			$paintedlist[$i]=0;
		}
	}

	$errors=array();

	

	//error checking for not selecting a player
	if(in_array(0, $player_list)){$errors[] = "Oops, one player was not selected!";}

	//error checking for selecting the same player twice
	for($i=0; $i<count($player_list); $i++){
		for($j=$i+1; $j<count($player_list);$j++){
			if($player_list[$i] == $player_list[$j]){
				$errors[]="Players cannot play themselves!";
				break;
			}
		}
		if(in_array("Players cannot play themselves!", $errors)){break;}
	}

	//check the scenario checkbox
	if($page->isChecked("was_scenariotable")){
		$scenario="YES";
	} else {
		$scenario="NO";
	}

	//determine if multiplayer game based off number of players
	if($numopponents - 2){
		$team_game = "YES";
	} else {
		$team_game = "NO";
	}

	//for now, fully painted is based off table, not by individual
	if($numpainted == $numopponents){
		$fully_painted="YES";
	} else {
		$fully_painted="NO";
	}

	/*###########################################
        # Account for all new opponents
        ###########################################*/
        $newplayer=array();
        foreach($player_list as $ghet){#ghet is completely random so I don't overwrite another variable
                $newplayer[$ghet]=0;
        }

	foreach($player_list as $player){
	foreach($player_list as $opponent){
                if($player!=$opponent){
                        if($g->checkNewPlayerAward(array($player,$opponent))=="YES"){
                                $newplayer[$player] = $newplayer[$player]+1;
                        }
                }
        }
	}


	$difflocation=0;
	//check for out of state bonus
	for($j=1; $j<=$numopponents; $j++){
		for($k=$j+1; $k<=$numopponents; $k++){
			if($player_info[$j][0][location] != $player_info[$k][0][location]){
				$difflocation=1;
				break;
			}
		}
		if($difflocation){
			break;
		}
	}

	if($difflocation){
		$outofstate = "YES";
	} else {
		$outofstate = "NO";
	}

	if(empty($errors)){
		foreach($player_list as $n=>$pl){
			$original_points[$n]=$p->getPointsByPlayerID($pl);
			$original_earned_points[$n]=$p->getPointsEarnedByPlayerID($pl);
		}

		$game_id = $g->createNewGame($player_list, $size_list, $faction_list, $paintedlist, $scenario, $team_game, $newplayer, $outofstate, $fully_painted);
			
		$p->updatePlayers($player_list, $size_list, $faction_list, $newplayer, $difflocation);

		foreach($player_list as $n=>$pl){
			$new_earned_points = $p->getPointsEarnedByPlayerID($pl);

			$num_tickets[$n] = floor($new_earned_points/10) - floor($original_earned_points[$n]/10);

			$points_gained[$n] = $p->getPointsByPlayerID($pl) - $original_points[$n];
			$new_points[$n] = $p->getPointsByPlayerID($pl);
		}
	}
}

if($page->submitIsSet("submit_report") && empty($errors)){
        $page->setDisplayMode("text");
} else {
        $page->setDisplayMode("form");
}

include("include/templates/gameReport_header.tpl");

for($i=1; $i<=$numopponents; $i++){
	include("include/templates/gameReport_segment.tpl");
}

if($page->submitIsSet("submit_report") && empty($errors)){
	include("include/templates/gameReport_recap.tpl");
} else {
	include("include/templates/gameReport_footer.tpl");
}

?>
