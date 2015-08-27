<?php

    //Includes
    require_once("classes/db_achievements.php");
    require_once("classes/db_meta_achievement_criteria.php");
    require_once("classes/db_achievements_earned.php");
    require_once("classes/db_players.php");
    require_once("classes/db_events.php");
	require_once("classes/db_tournament_game_details.php");
	require_once("classes/db_tournament_games.php");
	require_once("classes/db_tournament_registrations.php");
	require_once("classes/db_tournaments.php");
	require_once("classes/views.php");

    require_once("classes/check.php");
   
class Tournament_Engine {

    var $player_db = null;
    var $ach_db = null;
    var $meta_criteria_db = null;
    var $earned_db = null;
    var $events_db = null;
	var $tournaments_db = null;
	var $tournament_games_db = null;
	var $tournament_game_details_db = null;
	var $tournament_registrations_db = null;
	var $views = null;

    function Tournament_Engine(){
        $this->player_db = new Players();
        $this->ach_db = new Achievements();
        $this->meta_criteria_db = new Meta_achievement_criteria();
        $this->earned_db = new Achievements_earned();
        $this->events_db = new Events();

		$this->tournaments_db = new Tournaments();
		$this->tournament_games_db = new Tournament_Games();
		$this->tournament_game_details_db = new Tournament_Game_Details();
		$this->tournament_registrations_db = new Tournament_Registrations();
		
		$this->views = new Views();
    }

    function __destruct(){}


	/****************************************************

	Add Player

	Adds a player to a tournament via the tournament_registrations table
		Also handles adding a player mid-event

	****************************************************/

	public function addPlayer($t_id, $p_id, $f_id, $club){

		//Add the player
		$result = $this->registerPlayer($t_id, $p_id, $f_id, $club);

		//Check to see if the tournament is already running
		$games = $this->getTournamentGames($t_id);

		//If so, award the new player a buy for the current round
		if(count($games)){
			$round = end(array_keys($games));
			$result &= $this->addBuy($t_id, $p_id, $round);
		}

		return $result;
	}


	//Convenient Wrapper
	private function registerPlayer($t_id, $p_id, $f_id, $club){
		return $this->tournament_registrations_db->create($p_id, $t_id, $f_id, false, false, $club);	
	}



	public function startTournament($t_id, $use_clubs=false){

		//First, let's get all the info we'll need to do the pairings
		$tournament = $this->getTournament($t_id);
		$table=1;

		//All the different ways we can influence the initial pairings
		$keys = array(
			"country"=>array(),
			"state"=>array(),
			"faction_id"=>array(),
			"club"=>array());


		//Run through the registrations
		foreach($tournament["registrations"] as $reg){

			//Run through the keys
			foreach(array_keys($keys) as $key){


				//If the unique value the registrant has for this key is not yet in the list of values, add it.
				if(!array_key_exists($reg[$key], $keys[$key])){
					$keys[$key][$reg[$key]] = array();
				}

				//Track the registrant under this unique value for the key
				$keys[$key][$reg[$key]][] = $reg["registration_id"];

			}
		}

		//Decide the pairing criteria priority
		if(count($keys["country"]) > 1){
			$first = "country";
			$second = "state";
			$third = "faction";
		} else {
			$first = "state";
			$second = "faction";
			$third = "country";//gotta have a third, and this nicely bunches everyone up for detection later
		}

		//If we're using clubs, shuffle the order
		//However, we need to actually have clubs, on top of checking the "use_clubs" checkbox (which is checked by default)
		//Since we have <empty string> as no club, a club size=1 indicates no clubs, so we need clubs to have a size of at least 2
		if($use_clubs && (count($keys["club"])>1)){	
			$third = $second;
			$second = $first;
			$first = "club";
		}

		$paired = array();	//track who's been paired thus far

		//While we have people not yet paired
		while(count($paired) < count($tournament["registrations"])){

			//Use the priority and keys to make two groups
			$pools = $this->make_pools($keys, array($first, $second, $third), $paired);
			$largest = $pools["largest"];
			$pool = $pools["pool"];

			//Something (potentially) went wrong, or we're close to done, 
			//		and we need to pair the entire pool, and quit
			if($largest == null){
	
				//Check for an odd number, let's snag the bye now
				if(count($pool) % 2 == 1){
					shuffle($pool);
					$bye = array_pop($pool);
					$this->awardBye($t_id, 1, $bye);
				}
				
				while(count($pool)){
					//Snag two people
					shuffle($pool);
					$p1 = array_pop($pool);
					shuffle($pool);
					$p2 = array_pop($pool);

					//Toss 'em in the ring
                	$this->startGame($t_id, 1, $table, $p1, $p2);$table++;

                	//Add 'em to the done pile
                	$paired[] = $p1;
                	$paired[] = $p2;
				}
			} else {


				//We have the largest group that shouldn't be paired together, pair them to anyone in the pool
				//Until we run out of one group or the other
				while((count($largest)>0) && (count($pool)>0)){

					//mix 'em up
					shuffle($largest);
					shuffle($pool);

					//yank one out of each
					$p1 = array_pop($largest);
					$p2 = array_pop($pool);

					//Toss 'em in the ring
					$this->startGame($t_id, 1, $table, $p1, $p2);$table++;

					//Add 'em to the done pile
					$paired[] = $p1;
					$paired[] = $p2;
				}
			}
		}

		return "Successfully paired up ".count($paired)." players!";
	}

	private function make_pools($keys, $key_priority, $paired){

        $largest=array();   $pool=array();
        foreach($keys[$key_priority[0]] as $key){
            if(count($key) > count($largest)){
                $pool = $this->exclusive_merge($pool, $largest, $paired);
                $largest = $this->exclusive_merge(array(), $key, $paired);
            } else {
                $pool = $this->exclusive_merge($pool, $key, $paired);
            }
        }

		//We never found a sub-set larger than one, so everyone can be put into one pool
		if(count($largest) == 0){
			$pool=array();
			foreach($keys[$first] as $key){
				$pool = $this->exclusive_merge($pool, $key, $paired);
			}

			return array("largest"=>null, "pool"=>$pool);
		}


		//We found only one large grouping remaining
		if(count($pool) == 0){

			//If possible, we go to the next deciding factor
			if(count($key_priority) > 1){
				$key_priotity = array_reverse($key_priority);
				array_pop($key_priority);
				$key_priotity = array_reverse($key_priority);

				return $this->make_pools($keys, $key_priority, $paired);
			}

			//We're at the last deciding factor, so there's no choice but to pair within the group
			return array("largest"=>null, "pool"=>$largest);
		}


		//if we're here, the pools were made fine, so hand them back
		return array("largest"=>$largest, "pool"=>$pool);		
	
	}

	//Merge new data into existing array, minus any matching data from a third set
	//ie:  add a set of new players to an existing set, but don't add in the ones that have already been matched

	private function exclusive_merge($existing, $new, $exclusions){
		foreach($new as $n){
			if(!in_array($n, $exclusions)){
				$existing[] = $n;
			}
		}
		
		return $existing;
	}



	/***************************************************

	Start Game

	***************************************************/
	public function startGame($t_id, $round, $table, $p1_id, $p2_id){
	
		//Create a new game for the round
		$game_id = $this->tournament_games_db->create($t_id, $round, $table);

		if(empty($game_id)) return false;

		//Initialize the results columns in the DB for each player
		$result = $this->tournament_game_details_db->create($game_id, $p1_id);
		$result &= $this->tournament_game_details_db->create($game_id, $p2_id);

		return $result;
	}


	public function awardBye($t_id, $round, $player_id){

		//TODO

	}

	/****************************************************

    Get Tournament

    Retrieves data on the selected Tournament, placing it in a nested array

    ****************************************************/
	public function getTournament($t_id){
		if(Check::notInt($t_id)){ echo "Invalid Tournament ID: $t_id!";}

		//First, get the top level Tournament entity
		$tournament = $this->tournaments_db->getById($t_id);
		$tournament = $tournament[0];

		//Retrieve the registrations
		$tournament["registrations"] = $this->getTournamentRegistrations($t_id);

		//Retrieve the games, store them one level down
		$tournament["games"] = $this->getTournamentGames($t_id);

		return $tournament;
	}



	/***************************************************

	Get Tournament Games

	Gets all games for a tournament

	****************************************************/
	public function getTournamentGames($t_id){

		$games = array();

		$sql =  "SELECT 
					`tg`.`table_number` as `table_number`,
					`tg`.`round` as `round`,
					`tg`.`winner_id` as `winner_id`,
					`tgd`.*,
					`p`.*
				FROM
					`tournament_games` `tg`
				LEFT JOIN `tournament_game_details` `tgd`
					ON `tg`.`id`=`tgd`.`game_id`
				LEFT JOIN `players` `p`
					ON `tgd`.`player_id`=`p`.`id`
				WHERE `tg`.`tournament_id`=:t_id 
				ORDER BY `round`, `table_number`";

		return $this->views->customQuery($sql, array(":t_id"=>$t_id));
	}

	public function getTournamentRegistrations($t_id){

		$sql = "SELECT 
					`p`.*,
					`p`.`id` as player_id,
					`tr`.*,
					`tr`.`id` as registration_id,
					`f`.`name` as `faction`
				FROM
					`tournament_registrations` `tr`
				LEFT JOIN `players` `p`
					ON `tr`.`player_id`=`p`.`id`
				LEFT JOIN `game_system_factions` `f`
                    ON `tr`.`faction_id`=`f`.`id`
				WHERE
					`tr`.`tournament_id`=:t_id";

		$raw_regs = $this->views->customQuery($sql, array(":t_id"=>$t_id));

		$registrations = array();

		foreach($raw_regs as $reg){
			$registrations[$reg["id"]] = $reg;
		}

		return $registrations;
	}



}//class close 

?>
