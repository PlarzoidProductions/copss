<?php

include("acumen/achievement_engine.php");

$a_db = new Achievements();
$ae_db = new Achievements_earned();

$ae_db->deleteByColumns(array("game_id"=>1));

$ae = new Ach_Engine();
$ae->awardAchievements(1);
/*
$result = $ae_db->queryByColumns(array("player_id"=>1));
$points = 0;
foreach($result as $k=>$ae){
    $ach = $a_db->getById($ae[achievement_id]);
    $result[$k][ach_name] = $ach[0][name];
    $points += $ach[0][points];
}
    

echo "<pre>";
print_r($result);
echo "</pre>";
echo "Points: ".$points;
*/
?>

