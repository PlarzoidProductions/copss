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
			$third = "faction";
		}
		if($use_clubs){
			$third = $second;
			$second = $first;
			$first = $keys["club"];
		}

		//Do the thing
		$paired = array();	//track who's been paired thus far
		if($use_clubs){
			


		}






	}

	/***************************************************

	Start Game

	***************************************************/
	public function startGame($t_id, $round, $p1_id, $p2_id){
	
		//Create a new game for the round
		$game_id = $this->tournament_games_db->create($t_id, $round);

		if(empty($game_id)) return false;


		//Initialize the results columns in the DB for each player
		$result = $this->tournament_game_details_db->create($game_id, $p1_id);
		$result &= $this->tournament_game_details_db->create($game_id, $p2_id);

		return $result;
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

		$sql =  "select 
					`tg`.`round` as `round`,
					`tg`.`winner_id` as `winner_id`,
					`tgd`.*,
					`p`.*
				from 
					`tournament_games` `tg`
				left join `tournament_game_details` `tgd`
					on `tg`.`id`=`tgd`.`game_id`
				left join `players` `p`
					on `tgd`.`player_id`=`p`.`id`
				where `tg`.`tournament_id`=:t_id and `round`=:round";

		$i=1;
		do{
			$set = $this->views->customQuery($sql, array(":t_id"=>$t_id, ":round"=>$i));
			if(!empty($set)){ $games[$i]=$set; }
		} while(!empty($set));

		return $games;
	}

	public function getTournamentRegistrations($t_id){

		$sql = "SELECT 
					`p`.*,
					`p`.`id` as player_id,
					`tr`.*,
					`tr`.`id` as registration_id
				FROM
					`tournament_registrations` `tr`
				LEFT JOIN `players` `p`
					ON `tr`.`player_id`=`p`.`id`
				WHERE
					`tr`.`tournament_id`=:t_id";

		return $this->views->customQuery($sql, array(":t_id"=>$t_id));
	}



}//class close 

?>
