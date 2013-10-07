<?php

//php page that lists all available users from the database with options to edit or delete them.

$pl = new Player();

$player_list = $pl->getActivePlayers();

if(is_array($player_list)){
	foreach($player_list as $k=>$pl){
		if(count(explode("|", $pl[factionlist])) <= 2){
			$player_list[$k][factionlist]=preg_replace("~\|~", "", $pl[factionlist]);
		} else {
			$player_list[$k][factionlist]=explode("|", $pl[factionlist]);
			unset($player_list[$k][factionlist][0]);
			$player_list[$k][factionlist] = implode($player_list[$k][factionlist], ", ");
		}
	}
}

include("include/templates/player_list.tpl");

?>
