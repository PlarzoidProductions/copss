<?php

require_once("classes/db_events.php");
require_once("classes/db_achievements.php");
require_once("classes/check.php");

$e_db = new Events();
$a_db = new Achievements();

$events = $e_db->getAll();
foreach ($events as $event){
	if($event["is_tournament"]){
		$a_db->create($event["name"]." (Participation)", 15, 0, 0, 0, 1, null, null, 0, 0, 0, 0, 0, 0, 0, 0, $event["id"]);
	}
}

?>
