<?php

require_once("classes/db_achievements.php");
require_once("classes/db_events.php");
require_once("classes/check.php");

$tourneys = '
Sat,01/10/15,35,Titan Games
Sat,01/24/15,50,Critical Hit Games
Sun,02/15/15,50,Core Worlds
Sat,02/22/15,35,Dropzone
Sun,03/15/15,50,Titan Games
Sat,03/21/15,35,Games and Stuff
Sun,04/12/25,35,Core Worlds
Sat,04/25/15,50,Bel Air Games
Sun,05/17/15,50,Games and Stuff
Sat,05/23/15,35,Critical Hit Games
Sun,06/14/15,35,Core Worlds
Sun,06/28/15,50,Bel Air Games
Sun,07/12/15,35,Titan Games
Sun,07/26/15,50,Critical Hit Games
Sat,08/08/15,50,Core Worlds
Sun,08/16/15,35,Dropzone
Sat,09/12/15,50,Titan Games
Sun,09/20/15,35,Games and Stuff
Sat,10/17/15,50,Dropzone
Sun,10/25/15,35,Bel Air Games
Sat,11/21/15,50,Games and Stuff
Sun,11/29/15,35,Critical Hit Games
Sat,12/05/15,35,Core Worlds
Sat,12/19/15,50,Bel Air Games
';

$ach_db = new Achievements();
$e_db = new Events();

$tourneys = preg_split("~\n|\r~", $tourneys);

echo "Found ".count($tourneys)."\n\n";

foreach ($tourneys as $t){

	if(empty($t)) continue;

	echo "Adding $t\n";
	$event_id = $e_db->create($t);
	//create($name, $points, $per_game, $is_meta, $game_count, $game_system_id, $game_size_id, $faction_id, $unique_opponent, $unique_opponent_locations, $played_theme_force, $fully_painted, $fully_painted_battle, $played_scenario, $multiplayer, $vs_vip, $event_id)
	$part_id = $ach_db->create($t, 2, 0, 0, 0, null, null, null, 0, 0, 0, 0, 0, 0, 0, 0, $event_id);
	$first_id = $ach_db->create($t." (First)", 5, 0, 0, 0, null, null, null, 0, 0, 0, 0, 0, 0, 0, 0, null);
	$second_id = $ach_db->create($t." (Second)", 4, 0, 0, 0, null, null, null, 0, 0, 0, 0, 0, 0, 0, 0, null);
	$third_id = $ach_db->create($t." (Third)", 3, 0, 0, 0, null, null, null, 0, 0, 0, 0, 0, 0, 0, 0, null);
}

echo "Done!\n\n";

?>
