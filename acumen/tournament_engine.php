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
   
class Ach_Engine {

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

    function Ach_Engine(){
        $this->player_db = new Players();
        $this->ach_db = new Achievements();
        $this->meta_criteria_db = new Meta_achievement_criteria();
        $this->earned_db = new Achievements_earned();
        $this->events_db = new Events();

		$this->tournaments_db = new Tournaments();
		$this->tournament_games_db = new Tournament_Games();
		$this->tournament_game_details_db = new Tournaments_Game_Details();
		$this->tournament_registrations_db = new Tournament_Registrations();
		
		$this->views = new Views();
    }

    function __destruct(){}



	/****************************************************

    Get Tournament

    Retrieves data on the selected Tournament, placing it in a nested array of the format:
        
        $tournament - Trouenament data from the tournaments table
        $tournament[round] - Array of games for the selected round in the tournament
        $tournament[round][n] - Data on the nth game from the tournament_games table
        $tournament[round][n][player1] - Player1's game details data from the tournament_game_details table
        $tournament[round][n][player2] - Player2's game details data from the tournament_game_details table

    ****************************************************/

	public function getTournament($t_id){
		if(Check::notInt($t_id)){ echo "Invalid Tournament ID: $t_id!";}

		//First, get the top level Tournament entity
		$tournament = $this->tournaments_db->getById($t_id);
		$tournament = $tournament[0];

		//Next, get the games & details by rounds
		$rounds = array();
		$round = 1;
		do {
			$sql = "SELECT FROM tournament_game WHERE tournament_id=:tournament_id AND round=:round";
			$games = $this->views->customQuery($sql, array(":tournament_id"=>$t_id, ":round"=>$round));

		} while (count($games) > 0);

	}

    /****************************************************

    Get Player History

    Retrieves data on the selected player, placing it in a nested array of the format:
        
        $player - Player data from the players table
        $player[games] - Array of games the player played in
        $player[games][n] - Data on the game from the games table
        $player[games][n][players] - Array of players from the game
        $player[games][n][players][m] - Player info for player m of the nth game from the game_players table
        $player[games][n][players][m][player_details] - Data on player m from the nth game from the players table

    ****************************************************/

    public function getPlayerHistory($selected_player){
        if(Check::notInt($selected_player)){echo "Invalid player ID: '$selected_player'!";}

        //Get Player info
        $player = $this->player_db->getById($selected_player);
        $player = $player[0];


        //Get Player's Game Data
        $tmp1 = $this->game_players_db->getByPlayerId($player[id]);

        $games_played = array();
        foreach($tmp1 as $g){
            $games_played[] = $this->getGameDetails($g[game_id]);
        }

        $player[games] = $games_played;

        return $player;
    }


    /****************************************************

    Get Game Details

    Retrieves data on the selected game, placing it in a nested array of the format:
        
        $game - Game data from the games table
        $game[players] - Array of players that were inthe game
        $game[players][n] - Player data from the game_players table
        $game[players][n][player_details] - Data on player n from the players table
        $game[players][n][achievements] - List of achievements earned from the game
        $game[players][n][points] - Points earned from the game

    ****************************************************/ 

    function getGameDetails($game_id){
        if(Check::notInt($game_id)){
            echo "Invalid game ID: '$game_id'!";
            return;
        }

        //Get the Game's data
        $game = $this->game_db->getById($game_id);
        $game = $game[0];  //strip wrapper


        //Get list of players
        $player_list = $this->game_players_db->getByGameId($game_id);


        //Get Opponent's Data
        $players = array();
        $odd=true;
        foreach($player_list as $p){
            $player = $this->player_db->getById($p[player_id]);
            $p[player_details] = $player[0];
            $achs = $this->earned_db->queryByColumns(array("player_id"=>$p[player_id], 
                                                        "game_id"=>$game_id));

            $achievements = array();
            $points = 0;
            foreach($achs as $a){
                $ach_details = $this->ach_db->getById($a[achievement_id]);
                $achievements[] = $ach_details[0];
                $points += $ach_details[0][points];
            }
            $p[achievements] = $achievements;
            $p[points] = $points;

            $p[point_total] = $this->getPlayerPoints($p[player_id]);

            
            $p[style] = ($odd ? "odd" : " even");
            $odd = !$odd;

            $players[] = $p;
        }

        $game[players] = $players;

        return $game;
    }


    function getPlayerPoints($player_id){
        if(Check::notInt($player_id)){
            echo "Invalid player ID: '$player_id'!";
            return;
        }

        $achievements = $this->earned_db->getByPlayerId($player_id);

        $points = 0;
        foreach($achievements as $a){
            $details = $this->ach_db->getById($a[achievement_id]);
            $points += $details[0][points];
        }

        $redemptions = $this->redems_db->getByPlayerId($player_id);
        foreach($redemptions as $r){
            $points += $r[cost];
        }

        return $points;

    } 

    /****************************************************

    Get Achievements List

    Retrieves Achievements, including children, if necessary:
        
        $achievements - Array of achievements
        $achievements[n] - Achievement details, from the achievements table

        $achievements[n][criteria] - Array of meta criteria, if applicable
        $achievements[n][criteria][m] - Criteria details, from the meta_achievements_criteria table
        $achievements[n][criteria][m][child_details] - Achievement details of the child in the criteria (m), from the achievements table


        Nested Meta achievements will produce nested results in the array structure as well.

        $achievements[n][criteria][m][child_details][criteria][j][child_details][criteria][k][child_details] ... etc
    ****************************************************/

    function getAchievements(){
        $achs = $this->ach_db->getAll();
        //$achs = $achs[0];  //strip wrapper
        
        $achievements = array();
        foreach($achs as $a){
            $achievements[] = $this->getAchievementDetails($a[id]);
        }

        return $achievements;

    }

    function getAchievementDetails($ach_id){
        if(Check::notInt($ach_id)){
            echo "Not a valid Achievement ID: '$ach_id'";
            return;
        }

        $achievement = $this->ach_db->getById($ach_id);
        $achievement = $achievement[0];
    
        if($achievement[is_meta]){
        
            $criteria = $this->meta_criteria_db->queryByColumns(array("parent_achievement"=>$ach_id));
            
            if(!is_array($criteria)){
                echo "Found Meta Achievement (".$a[id].") without any criteria!";
                return;
            }

            $children = array();
            foreach($criteria as $c){
                $c[child_details] = $this->getAchievementDetails($c[child_achievement]);
                $children[] = $c;
            }

            $achievement[criteria] = $children;

        }

        return $achievement;
    }

    function getEventAchievementsByPlayerId($player_id){
        $achs = $this->earned_db->queryByColumns(array("player_id"=>$player_id));
        
        $achievements = array();
        foreach($achs as $a){
            $achievement = $this->getAchievementDetails($a[achievement_id]);

            if($achievement[event_id]){

                $event = $this->events_db->getById($achievement[event_id]);
                $achievement[event_name] = $event[0][name];

                $achievement[earned_id] = $a[id];

                $achievements[] = $achievement;
            }
        }
        
        return $achievements;

    }



}//class close 

?>
