<?php

require_once("settings.php");

/*---------------------------------------------------------------

game.php  - php class that includes functions
on the game mysql database


CREATE TABLE games (
 47 id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
 48 playerlist VARCHAR(255),
 49 sizelist VARCHAR(255),
 50 scenario BOOLEAN NOT NULL DEFAULT FALSE,
 51 teamgame BOOLEAN NOT NULL DEFAULT FALSE,
 52 newplayer BOOLEAN NOT NULL DEFAULT FALSE,
 53 newlocation BOOLEAN NOT NULL DEFAULT FALSE,
 54 fullypainted BOOLEAN NOT NULL DEFAULT FALSE
 55 );

---------------------------------------------------------------*/

//----------- Class Declaration  ------------------

class Game
{

	/*----------------------------------------
	*
	* properties (variables)
	*
	*---------------------------------------*/

	//mysql credentials
	private $mysql_user='ironarena';
	private $mysql_user_pass='iwantskullz';
	private $server = 'localhost';//'ia.plarzoid.com';
	//boolean on status of connection to mysql
	public $connected=0;  


        /*---------------------------------
        *construct() - called when class is 
        *instantiated into an object
        *---------------------------------*/
	public function Game(){
	}


        /*---------------------------------
        *destruct() - called when no references remain
	*to the object.
        *---------------------------------*/
	public function __destruct(){
	}


	public function findGamesByID($id) {
		$query = "SELECT * FROM games WHERE id=$id";
		$db_returned = $this->queryDB($query);
	
		if($db_returned){
		
			return $db_returned;
		}
		return false;
	}

	public function getOpponentsByPlayerID($id){
	
		$query = "SELECT playerlist FROM games WHERE playerlist LIKE '%".$id."%'";
		$playerlist = $this->queryDB($query);
		//$playerlist = $playerlist[0];//strip off array wrapper

		if(!$playerlist){return array();}

		$opponent_list = array();

		foreach($playerlist as $pl){
			$players = explode("|", $pl['playerlist']);

			foreach($players as $p){
				if($p != $id){$opponent_list[$p] = $p;}
			}
		}
		
		return $opponent_list;
	}

	public function getGamesByHour(){
		$sql = "select month(gametime) as month, day(gametime) as day, hour(gametime) as hour, count(gametime) as count ";
		$sql.= "from games group by day(gametime), hour(gametime) order by month, day, hour";

		$games = $this->queryDB($sql);
		return $games;
	}

	public function checkNewPlayerAward($player_list){
		$list_of_opponents = array();
		
		foreach($player_list as $player){
			$list_of_opponents[$player] = $this->getOpponentsByPlayerID($player);
		}

		foreach($player_list as $player){
			foreach($player_list as $player2){
				if($player != $player2){
					if(!in_array($player, array_keys($list_of_opponents[$player2]))){
						return "YES";
					}
				}
			}
		}
		return "NO";
	}
	
	public function getPointsByGameIDandPlayerID($gid, $pid){
		$sql = "SELECT * FROM games WHERE id=$gid";
		$result = $this->queryDB($sql);
		
		$g = $result[0];  //strip array wrapper
		
		$settings_db = new Settings();
		$s = $settings_db->getSettings();
		
		$points = 0;
		
		if($g['scenario']){$points += ($s['scenariotable']+0);}
		if($g['fullypainted']){$points += ($s['fullypaintedall']+0);}
		if($g['teamgame']){$points += ($s['teamgame']+0);}
		if($g['newlocation']){$points += ($s['outofstate']+0);}
	
		$player_ids = explode("|", $g['playerlist']);
	
		$players = array_flip($player_ids);
		$painted = explode("|", $g['fullypaintedlist']);
		
		if($painted[$players[$pid]]){$points += ($s['fullypainted']+0);}
	
		$newplayer = explode('|', $g['newplayer']);
		foreach($player_ids as $k=>$id){
			if($pid==$id){
                		if($newplayer[$k]){$points += ($s['newopponent']*$newplayer[$k]);}
			}
		}

		return $points;
		
	}
	
	public function getAllGames(){
		$query = "SELECT * FROM games";
		$db_returned = $this->queryDB($query);

	if($db_returned){
			return $db_returned;
		}
	
		return false;
	}
			

	public function createNewGame($playerlist, $sizelist, $factionlist, $paintedlist, $scenario, $teamgame, $newplayer, $newlocation, $fullypainted){
		//build insert query

		$playerlist = implode($playerlist, '|');
		$sizelist = implode($sizelist, '|');
		$factionlist = implode($factionlist, '|');
		$paintedlist = implode($paintedlist, '|');

		if($scenario=="YES"){
			$scenario=1;
		} else {
			$scenario=0;
		}

		if($teamgame=="YES"){
                        $teamgame=1;
                } else {
                        $teamgame=0;
                }

		$newplayer = implode($newplayer, '|');

		if($newlocation=="YES"){
                        $newlocation=1;
                } else {
                        $newlocation=0;
                }

		if($fullypainted=="YES"){
                        $fullypainted=1;
                } else {
                        $fullypainted=0;
                }

		//build majority of insert query
		$query = "INSERT INTO games (playerlist, sizelist, factionlist, scenario, teamgame, newplayer, newlocation, fullypainted, fullypaintedlist, gametime)
        		VALUES('$playerlist', '$sizelist', '$factionlist', $scenario, $teamgame, '$newplayer', $newlocation, $fullypainted, '$paintedlist', NOW())";

		//attempt to insert into DB
		$success = $this->updateDB($query);

		if($success){
			$confirm="SELECT id FROM games WHERE playerlist='$playerlist' AND sizelist='$sizelist' AND scenario='$scenario'AND
					teamgame='$teamgame'AND newplayer='$newplayer'AND newlocation='$newlocation'AND fullypainted='$fullypainted'";

			$gid = $this->queryDB($confirm);
		
			$playerlist = explode('|', $playerlist);
			$p_db = new Player();
			foreach($playerlist as $p){
				$p_db->updatePoints($p);
			}
			
		return $gid;
		}

		return false;
	}
	
	public function getGamesByPlayerID($id){

		$query = "SELECT * FROM games WHERE playerlist LIKE '%$id%'ORDER BY gametime";

		$games = $this->queryDB($query);

		if(!is_array($games)){return;}

		foreach(array_keys($games) as $k){
			if(!in_array("$id", explode('|', $games[$k][playerlist]))){
				unset($games[$k]);
			}
		}
	
		return $games;
	}



	function queryDB($query) {
		$players_db=mysql_connect($this->server, $this->mysql_user, $this->mysql_user_pass) or die(mysql_error());
//var_dump(mysql_error());                
		if($players_db){
                        mysql_select_db("ironarena") or die(mysql_error());
                } else {
                        Die("Unable to connect to MYSQL!");
                }
		$db_result = mysql_query($query) or die(mysql_error());
		mysql_close($players_db);
		
		//if a boolean was returned, kick it back now
		if(is_bool($db_result)){ return $db_result; }

		//else assume we have something to get
		$row = mysql_fetch_array($db_result);

		//repeat getting results as necessary, pumping them into a single master array
		while ($row){
			$ret[] = $row;
			$row = mysql_fetch_array($db_result);
		}	

		//only return it if we have a valid array
		if(is_array($ret)){
			return $ret;
		}

		//if all else fails, return false
		return false;
	}

	private function updateDB($query) {
		$players_db=mysql_connect($this->server, $this->mysql_user, $this->mysql_user_pass) or die(mysql_error());

                if($players_db){
                        mysql_select_db("ironarena") or die(mysql_error());
                } else {
                        Die("Unable to connect to MYSQL!");
                }

                $db_result = mysql_query($query) or die(mysql_error());;
		
		mysql_close($players_db);

		return $db_result;
	}


}//end of class declaration

?>
