<?php

require_once('include/classes/page.php');

$player_db = new Player();
$choices_db = new Choices();
$game_db = new Game();

$players = $player_db->getAllPlayers();
$factions = $choices_db->getFactionChoices();
$player_factions = array();
$faction_count = array();

for($i=1; $i < 12; $i++){
	$faction_count[$i]=0;
}

foreach($players as $p){

	$player_factions[$p[id]] = array();

        foreach($factions as $faction){
                $player_factions[$p[id]][factions][$faction[value]]=0;
        }

	$games = $game_db->getGamesByPlayerID($p[id]);

	if(is_array($games)){
		foreach($games as $g){
		
			$player_list = explode("|", $g[playerlist]);
			$faction_list = explode("|", $g[factionlist]);

			foreach($player_list as $k=>$pl){
				if($pl==$p[id]){
					$player_factions[$p[id]][factions][$faction_list[$k]]+=1;
				}
			}
		}

		$count=0;
		foreach($player_factions[$p[id]][factions] as $f=>$c){
			if($c > 0){
				$count++;
			}
		}

		$player_factions[$p[id]]["count"]=$count;

		$faction_count[$count]+=1;
	}
}

echo "<b>Number of factions played by a given individual</b><br/>";
foreach($faction_count as $count=>$value){
	echo $count.": ".$value."<br/>";
}
echo "<br/>";

//Count the factions for each player
$single_faction_count = array();
$two_faction_count = array();

foreach($factions as $f){
	$single_faction_count[$f[value]]=0;
	$two_faction_count[$f[value]] = array();

	foreach($factions as $f2){
		$two_faction_count[$f[value]][$f2[value]]=0;
	}
}

foreach($player_factions as $pid=>$plf){
	if($plf["count"]==1){
		foreach($plf[factions] as $plff=>$plfc){
			if($plfc > 0){
				$single_faction_count[$plff]+=1;
			}
		}
	} elseif($plf["count"]==2){
		$f1=null;
		$f2=null;

		foreach($plf[factions] as $plff=>$plfc){
			if($plfc > 0){
				if($f1==null){$f1=$plff;} else {$f2=$plff;}
			}
		}

		$two_faction_count[$f1][$f2]+=1;
		$two_faction_count[$f2][$f1]+=1;
	}
}

$confirm=0;
echo "<b>Number of Players Playing Only One Faction</b><br/>";
foreach($factions as $faction){
	echo $faction[text].": ".$single_faction_count[$faction[value]]."<br/>";
	$confirm+=$single_faction_count[$faction[value]];
}
echo "<b>Total:</b> ".$confirm."<br/>";

echo "<br/><b>Number of Players Playing Only Two Factions</b>";
echo "<br/><table border=1>";
echo "<tr><td></td>";

foreach($factions as $first){
	echo "<th>".$first[text]."</th>";
}
echo "</tr>";
$confirm=0;
foreach($factions as $first){
	echo "<tr>";
	echo "<td>".$first[text]."</td>";
	foreach($factions as $second){
		echo "<td>".$two_faction_count[$first[value]][$second[value]]."</td>";
		$confirm+=$two_faction_count[$first[value]][$second[value]];
	}
	echo "</tr>";
}
echo "</table><br/>";

echo "Confirm 2-faction count: ".$confirm;





?>
