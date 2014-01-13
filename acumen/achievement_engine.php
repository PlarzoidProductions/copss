<?php

    //Includes
    require_once("classes/db_achievements.php");
    require_once("classes/db_meta_achievement_criteria.php");
    require_once("classes/db_achievements_earned.php");
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
    var $earned_db = null;


    function Ach_Engine(){
        $this->player_db = new Players();
        $this->game_db = new Games();
        $this->game_players_db = new Game_players();
        $this->ach_db = new Achievements();
        $this->meta_criteria_db = new Meta_achievement_criteria();
        $this->earned_db = new Achievements_earned();
    }

    function __destruct(){}


    function awardAchievements($game_id){
        if(Check::notInt($game_id)){
            echo "Invalid game ID: '$game_id'!";
            return;
        } 
        
        //Get the info
        $game = $this->getGameDetails($game_id);
        $players = $game[players];

        $achievements = $this->getAchievements();

        //Prepare the list of achievements earned
        $earned = array();
        foreach($players as $pl){
            $earned[$pl[player_id]] = array();
        }
           
        //Detect & award achievements
        foreach($achievements as $a){
            foreach($players as $p){
                $this->detectAndAward($p, $game, $a);
            }
        }

    }


    private function detectAndAward($player, $game, $achievement){

        //Assume they've earned it, unless proven otherwise
        $earned = 1;

        //If not per-game, check for existance
        if(!$achievement[per_game]){
            $tmp = $this->earned_db->queryByColumns(array(  "player_id"=>$p[player_id], 
                                                            "achievement_id"=>$a[id]));
            if(!empty($tmp)){
                return;  //Already has it, so let's quit now
            }
        }

        //If meta, check for all children
        if($achievement[is_meta]){
            foreach($achievement[criteria] as $criteria){
                $hasEarned = $this->earned_db->queryByColumns(array("player_id"=>$player[player_id],
                                                                    "achievement_id"=>$criteria[child_achievement]));

                if(is_array($hasEarned)){
                    if(count($has_earned) < $criteria["count"]){
                        return;  //Nope, not gonna do it
                    }
                }
            }
        }

        //Some Boolean checks, they're either go or no-go
        if($achievement[game_count]){
            $history = $this->getPlayerHistory($player[player_id]);

            if(count($history[games]) < $achievement[game_count]){
                return;
            }
        }
        if($achievement[game_system_id]){
            if($game[game_system] != $achievement[game_system_id]){
                return;
            }
        }
        if($achievement[game_size_id]){
            if($player[game_size] != $achievement[game_size_id]){
                return;
            }
        }
        if($achievement[faction_id]){
            $f_check = false;
            foreach($game[players] as $gp){
                if($gp[player_id] != $player[player_id]){
                    if($gp[faction_id] == $achievement[faction_id]){
                        $f_check = true;
                    }
                }
            }

            if(!$f_check){
                return;
            }
        }
        if($achievement[played_theme_force]){
            if(!$player[theme_force]) return;
        }
        if($achievement[fully_painted]){
            if(!$player[fully_painted]) return;
        }
        if($achievement[fully_painted_battle]){
            $battle = true;
            foreach($game[players] as $p){
                $battle = $battle && $p[fully_painted];
            }

            if(!$battle) return;
        }
        if($achievement[played_scenario]){
            if(!$game[scenario]) return;
        }
        if($achievement[multiplayer]){
            if(count($game[players]) <= 2) return;
        }

        //Things that can be earned multiple times in one game
        if($achievement[unique_opponent]){
            $history = $this->getPlayerHistory($player[player_id]);

            $opponents = array();

            //establish history 
            foreach($history[games] as $g){
                if($g[id] == $game[id]) continue;
                foreach($g[players] as $p){
                    if($p[player_id] == $player[player_id]) continue;
                    
                    $opponents[] = $p[player_id];
                }
            }
            
            //detect new
            $new_opponents = 0;
            foreach($game[players] as $gp){
                if($gp[player_id] != $player[player_id]){
                    if(!in_array($player[player_id], $opponents)){
                          $new_opponents++;
                          $locations[] = $player[player_id];
                    }
                }
            }
            
            //We've made it this far, store the number of "wins"
            $earned = $new_opponents;

        }
        if($achievement[unique_opponent_locations]){
            $history = $this->getPlayerHistory($player[player_id]);

            $locations = array();

            //establish history 
            foreach($history[games] as $g){
                if($g[id] == $game[id]) continue;
                foreach($g[players] as $p){
                    if($p[player_id] == $player[player_id]) continue;
                    
                    $locations[] = $p[player_details][country]."-".$p[player_details][state];
                }
            }

            //detect new
            $new_locs = 0;
            foreach($game[players] as $gp){
                if($gp[player_id] != $player[player_id]){
                    $gp_loc = $gp[player_details][country]."-".$gp[player_details][state];

                    if(!in_array($gp_loc, $locations)){
                          $new_locs++;
                          $locations[] = $gp_loc;
                    }
                }
            }

            //in the odd case someone make an achievement with (new opponent && new location)
            //Let's take the minimum of the two to get the number of times to award the new achievement
            if(is_numeric($earned)){
                $earned = min($earned, $new_locs);
            } else {
                $earned = $new_locs;
            }
        }

        //If we're here, it's time to award things
        if($earned > 0){
            for($i=0; $i < $earned; $i++){
                $this->earned_db->create($player[player_id], $achievement[id]);

                echo "Awarded ".$achievement[name]." to Player ".$player[player_id]."<br>";
            }   
        } 
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


}//class close 

?>
