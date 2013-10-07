<?php

require_once("include/classes/settings.php");
require_once("include/classes/page.php");
require_once("include/classes/game.php");
require_once("include/classes/payout.php");
require_once("include/classes/choices.php");

$page = new Page();

/*#########################################################
#
# get the settings for the point values for all the awards
#
#########################################################*/

$settings_db = new Settings();
$s = $settings_db->getRawSettings();
$s = $s[0];  //strip array wrapper

$title=$settings_db->getName();

/*########################################################
# get player id# and player info
########################################################*/

$page->register("id", "hidden"); 
$id=$page->getVar("id");

$player_db = new Player();
$p = $player_db->findPlayerById($id);
$p = $p[0];  //strip array wrapper

$p['factionlist'] = implode(", ", explode("|", $p['factionlist']));


/*#########################################################
#
#  Pull the player's location out and turn it into a string
#
#########################################################*/

$choices = new Choices();

$p['location'] = $choices->getLocationName($p['location']);


/*#######################################################
#
# get all the user's games
#
#######################################################*/

$game_db = new Game();
$game_list = $game_db->getGamesByPlayerID($id);

//make the database output more human readable
if(!empty($game_list)){
	foreach(array_keys($game_list) as $key){
		
		$size_list=explode("|", $game_list[$key][sizelist]);
		$game_list[$key][sizelist] = $size_list[array_search($id, explode("|", $game_list[$key][playerlist]))];
		$size_pts = $s['played'.$game_list[$key][sizelist]];
		$game_list[$key][sizelist].= ', ('.$size_pts.'pts)';
		
		$player_ids = explode("|", $game_list[$key]['playerlist']);
		$players = array_flip($player_ids);

		$game_list[$key][playerlist] = implode($player_db->getOpponents($game_list[$key][playerlist], $id), "; ");
		$game_list[$key][points] = $game_db->getPointsByGameIDandPlayerID($game_list[$key]['id'], $id) + $size_pts;
		
		$painted_list = explode("|", $game_list[$key]['fullypaintedlist']);
		
		$newplayer_list = explode('|', $game_list[$key]['newplayer']);
		foreach($player_ids as $k=>$pid){
			if($pid==$id){$game_list[$key][newplayer]=$newplayer_list[$k];}
		}		

		$game_list[$key]['fullypainted_army'] = $painted_list[$players[$id]];
	}
}

/*######################################################
#
# decode the esclation rewards
#
######################################################*/

$ngames=$s[numgames];
$nlocations=$s[numlocations];
$nplayers=$s[numplayers];
$nfactions=$s[numfactions];

$ngamepts=0;
$ngames=explode(":", $ngames);
	foreach($ngames as $g){
		$a = explode(",", $g);
		if($p[numgames] > $a[0]){
			$ngamepts+=$a[1];
		}
	}

$nlocationpts=0;
$nlocations=explode(":", $nlocations);
        foreach($nlocations as $l){
                $a = explode(",", $l);
                if(count(explode("|", $p[locationlist])) >= $a[0]){
                        $nlocationpts+=$a[1];
                }
        }

$nplayerpts=0;
$nplayers=explode(":", $nplayers);
        foreach($nplayers as $pl){
                $a = explode(",", $pl);
                if($p[numplayers] >= $a[0]){
                        $nplayerpts+=$a[1];
                }
        }
$nfactionpts=0;
$nfactions=explode(":", $nfactions);
        foreach($nfactions as $f){
                $a = explode(",", $g);
                if(count(explode(",", $p[factionlist])) >= $a[0]){
                        $nfactionpts+=$a[1];
                }
        }

/*######################################################
#
# get all the player's point redemptions
#
######################################################*/

$payout_db = new Payout();

$redemptions = $payout_db->getPayoutsByPlayerID($id);


/*######################################################
#
# set up page display format and import templates
#
######################################################*/

$page->setDisplayMode("text");

include("include/templates/default_header.tpl");
include("include/templates/player_details.tpl");

if(!empty($game_list)){
	include("include/templates/player_details_games.tpl");
} else {
	include("include/templates/goplayagame.tpl");
}

if(!empty($redemptions)){include("include/templates/payout_listing.tpl");}

include("include/templates/player_details_footer.tpl");
include("include/templates/default_footer.tpl");

?>
