<?php

    //Includes
    require_once("classes/db_achievements.php");
    require_once("classes/db_meta_achievement_criteria.php");
    require_once("classes/db_players.php");
    require_once("classes/db_games.php");
    require_once("classes/db_game_players.php");

    require_once("classes/check.php");
   
class Ach_Engine {

    var $player_db = null;
    var $game_db = null;
    var $game_players_db = null;
    var $ach_db = null;
    var $meta_criteria_db = null;


    function Ach_Engine(){
        $this->player_db = new Players();
        $this->game_db = new Games();
        $this->game_players_db = new Game_players();
        $this->ach_db = new Achievements();
        $this->meta_criteria_db = new Meta_achievements_criteria();
    }

    function __destruct(){}


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
        foreach($player_list as $p){
            $player = $this->player_db->getById($p[player_id]);
            $p[player_details] = $player[0];

            $players[] = $p;
        }

        $game[players] = $players;

        return $game;
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
        $achs = $achs[0];  //strip wrapper

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

        if($achievement[is_meta]){
        
            $criteria = $this->meta_criteria_db->getByColumns(array("parent_achievement"=>$ach_id));

            if(Check::isNull($criteria)){
                echo "Found Meta Achievement (".$a[id].") without any criteria!";
                return;
            }

            $children = array();
            foreach($criteria as $c){
                $c[child_details] = $this->getAchievementDetails($c[id]);
                $children[] = $c;
            }

            $achievement[criteria] = $children;

        }

        return $achievement;
    }

}//class close 

?>
