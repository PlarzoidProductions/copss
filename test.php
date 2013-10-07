<?php
require_once('include/classes/page.php');

$p = new Player();
$g = new Game();
$c = new Choices();
$pays = new Payout();

$players = $p->getAllPlayers();
$faction_list = $c->getFactionChoices();

$skulls=0;
$redemptions = 0;
$locations = array();
$factions = array();

foreach($faction_list as $f){
	$factions[$f[value]] = 0;
	$themeforces[$f[value]] = 0;
}

foreach($players as $pl){
	$skulls+=$pl[points];

	$payout = $pays->findPayoutByID($pl[id]);

	if(is_array($payout)){
	foreach($payout as $pay){
		if($pay[points] < 0){
			$redemptions -= $pay[points];
			$skulls -= $pay[points];
		}
	}
	}

	if(!in_array($pl[location], array_keys($locations))){
		$locations[$pl[location]]=1;
	} else {
		$locations[$pl[location]]+=1;
	}
	
}

echo "<br/><b>Skulls Earned: ".$skulls."</b><br/>";
echo "<b>Skulls Spent: ".$redemptions."</b><br/><br/>";

$locs = $c->getStates();

foreach($locs as $l){
	if(in_array($l[value], array_keys($locations))){
		echo $l[text].": ".$locations[$l[value]]."<br/>";
	}
}

$locs = $c->getCountries();

foreach($locs as $l){
        if(in_array($l[value], array_keys($locations))){
                echo $l[text].": ".$locations[$l[value]]."<br/>";
        }
}

echo "<br/>";

$game_list = array();
$game_size_list = array("25"=>0, "35"=>0, "50"=>0, "75"=>0);

$player_armies=array();

$games = $g->getAllGames();
foreach($games as $game){

	$sizes = explode('|', $game[sizelist]);
	$count = count($sizes);
	
	if(!in_array($count, array_keys($game_list))){
		$game_list[$count] = 1;
	} else {
		$game_list[$count] += 1;
	}

	foreach($sizes as $s){
		$game_size_list[$s]+=1;
	}

	$factionlist = explode("|", $game[factionlist]);
	$themes = explode("|", $game[themeforce_list]);
	foreach($factionlist as $k=>$f){
		$factions[$f]+=1;
		$themeforces[$f]+=$themes[$k];
	}

        $player_ids = explode("|", $game[playerlist]);

        foreach($player_ids as $k=>$pl){

         	$player_armies[$pl][$factionlist[$k]]=0;        
        }

}

echo "<br><b># times a faction was played</b></br>";
foreach($faction_list as $f){
	echo $f[text].": ".$factions[$f[value]]."<br>";
}

echo "<br><b># times a faction theme force  was played</b></br>";
foreach($faction_list as $f){
	
        echo $f[text].": ".$themeforces[$f[value]].", ".round(($themeforces[$f[value]]/$factions[$f[value]]*100), 1)."%<br>";
}

echo "<br/><b># of games by # of Players:</b><br/>";
foreach($game_list as $n=>$gl){
	echo $n.": ".$gl."<br/>";
}
echo "Total: ".count($games);

echo "<br/><b># of armies by Size:</b><br/>";
foreach($game_size_list as $n=>$gsl){
	echo $n.": ".$gsl."<br/>";
}

echo "<br/><b>Registration Time Breakdown</b><br/>";
$groups = $p->getRegistrationsByHour();
foreach($groups as $group){
        echo $group[month]."-".$group[day].",".$group[hour].",".$group[count]."<br/>";
}

echo "<br/><b>Game Time Breakdown</b><br/>";
$groups = $g->getGamesByHour();
foreach($groups as $group){
	echo $group[month]."-".$group[day].",".$group[hour].",".$group[count]."<br/>";
}

?>
