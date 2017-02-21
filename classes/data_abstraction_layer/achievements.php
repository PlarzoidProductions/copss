<?php
///////////////////////////////////////////////
//
//  FILE WRITTEN BY SCRIPT database/scripts/create_classes.php
//
///////////////////////////////////////////////

require_once("query.php");

///////////////////////////////////////////////
//
//     Table Description
//
// id - INT - PRIMARY KEY - FK: tournaments, id
// name - VARCHAR
// points - INT
// per_game - TINYINT
// is_meta - TINYINT
// game_count - INT
// game_system_id - INT - FK: game_systems, id
// game_size_id - INT - FK: game_sizes, id
// faction_id - INT - FK: game_system_factions, id
// unique_opponent - TINYINT
// unique_opponent_locations - TINYINT
// played_theme_force - TINYINT
// fully_painted - TINYINT
// fully_painted_battle - TINYINT
// played_scenario - TINYINT
// multiplayer - TINYINT
// vs_vip - TINYINT
// tournament_id - INT
//
///////////////////////////////////////////////

class Achievements {

    private $db;
    private $table = "achievements";

    private $id = null;
    private $name = null;
    private $points = null;
    private $per_game = null;
    private $is_meta = null;
    private $game_count = null;
    private $game_system_id = null;
    private $game_size_id = null;
    private $faction_id = null;
    private $unique_opponent = null;
    private $unique_opponent_locations = null;
    private $played_theme_force = null;
    private $fully_painted = null;
    private $fully_painted_battle = null;
    private $played_scenario = null;
    private $multiplayer = null;
    private $vs_vip = null;
    private $tournament_id = null;

    private $varlist = array(
        "name",
        "points",
        "per_game",
        "is_meta",
        "game_count",
        "game_system_id",
        "game_size_id",
        "faction_id",
        "unique_opponent",
        "unique_opponent_locations",
        "played_theme_force",
        "fully_painted",
        "fully_painted_battle",
        "played_scenario",
        "multiplayer",
        "vs_vip",
        "tournament_id");

    public function __construct($id=null){
        $this->id = $id;
        $this->db = Query::getInstance();
    }

    public function getVarList(){
	   return $this->varlist;
    }

    ///////////////////////////////////////////////////////////
    //
    //     END OF AUTOMATED PORTION OF FILE
    //     Put any custom functions below.
    //     DO NOT DELETE THIS COMMENT
    //
    ///////////////////////////////////////////////////////////




    ///////////////////////////////////////////////////////////
    //
    //     END OF FILE.  ANYTHING AFTER THIS WILL BE LOST.
    //     DO NOT DELETE THIS COMMENT
    //
    ///////////////////////////////////////////////////////////


    ///////////////////////////////////////////////////
    //
    //  Data Access Functions (Setters & Getters)
    //
    ///////////////////////////////////////////////////

    ///////////////////////////////////////////////
    // Functions for id
    ///////////////////////////////////////////////
	public function checkId($id){
	 	//Not allowed to be NULL
		if(Check::isNull($id)){
			echo "achievements->id cannot be null!";
		}
       //Check the value
       if(Check::notInt($id)){
           echo "achievements->id is invalid!";
           return false;
       }

       return intVal($id);
    }

    public function setId($id){
       if($this->checkId($id){
           $this->id = $id;
       }
    }

    public function getId($id){
        return $this->id;
    }


    ///////////////////////////////////////////////
    // Functions for name
    ///////////////////////////////////////////////
	public function checkName($name){
	 	//Not allowed to be NULL
		if(Check::isNull($name)){
			echo "achievements->name cannot be null!";
		}
       //Check the value
       if(Check::notString($name)){
           echo "achievements->name is invalid!";
           return false;
       }

       return $name;
    }

    public function setName($name){
       if($this->checkName($name){
           $this->name = $name;
       }
    }

    public function getName($name){
        return $this->name;
    }


    ///////////////////////////////////////////////
    // Functions for points
    ///////////////////////////////////////////////
	public function checkPoints($points){
	 	//Not allowed to be NULL
		if(Check::isNull($points)){
			echo "achievements->points cannot be null!";
		}
       //Check the value
       if(Check::notInt($points)){
           echo "achievements->points is invalid!";
           return false;
       }

       return intVal($points);
    }

    public function setPoints($points){
       if($this->checkPoints($points){
           $this->points = $points;
       }
    }

    public function getPoints($points){
        return $this->points;
    }


    ///////////////////////////////////////////////
    // Functions for per_game
    ///////////////////////////////////////////////
	public function checkPerGame($per_game){
	 	//Not allowed to be NULL
		if(Check::isNull($per_game)){
			echo "achievements->per_game cannot be null!";
		}
       //Check the value
       if(Check::notBool($per_game)){
           echo "achievements->per_game is invalid!";
           return false;
       }

       return intVal($per_game);
    }

    public function setPerGame($per_game){
       if($this->checkPerGame($per_game){
           $this->per_game = $per_game;
       }
    }

    public function getPerGame($per_game){
        return $this->per_game;
    }


    ///////////////////////////////////////////////
    // Functions for is_meta
    ///////////////////////////////////////////////
	public function checkIsMeta($is_meta){
	 	//Not allowed to be NULL
		if(Check::isNull($is_meta)){
			echo "achievements->is_meta cannot be null!";
		}
       //Check the value
       if(Check::notBool($is_meta)){
           echo "achievements->is_meta is invalid!";
           return false;
       }

       return intVal($is_meta);
    }

    public function setIsMeta($is_meta){
       if($this->checkIsMeta($is_meta){
           $this->is_meta = $is_meta;
       }
    }

    public function getIsMeta($is_meta){
        return $this->is_meta;
    }


    ///////////////////////////////////////////////
    // Functions for game_count
    ///////////////////////////////////////////////
	public function checkGameCount($game_count){
       //Allowed to be NULL
       if(Check::isNull($game_count)){ return null; }
       //Check the value
       if(Check::notInt($game_count)){
           echo "achievements->game_count is invalid!";
           return false;
       }

       return intVal($game_count);
    }

    public function setGameCount($game_count){
       if($this->checkGameCount($game_count){
           $this->game_count = $game_count;
       }
    }

    public function getGameCount($game_count){
        return $this->game_count;
    }


    ///////////////////////////////////////////////
    // Functions for game_system_id
    ///////////////////////////////////////////////
	public function checkGameSystemId($game_system_id){
       //Allowed to be NULL
       if(Check::isNull($game_system_id)){ return null; }
       //Check the value
       if(Check::notInt($game_system_id)){
           echo "achievements->game_system_id is invalid!";
           return false;
       }

       return intVal($game_system_id);
    }

    public function setGameSystemId($game_system_id){
       if($this->checkGameSystemId($game_system_id){
           $this->game_system_id = $game_system_id;
       }
    }

    public function getGameSystemId($game_system_id){
        return $this->game_system_id;
    }


    ///////////////////////////////////////////////
    // Functions for game_size_id
    ///////////////////////////////////////////////
	public function checkGameSizeId($game_size_id){
       //Allowed to be NULL
       if(Check::isNull($game_size_id)){ return null; }
       //Check the value
       if(Check::notInt($game_size_id)){
           echo "achievements->game_size_id is invalid!";
           return false;
       }

       return intVal($game_size_id);
    }

    public function setGameSizeId($game_size_id){
       if($this->checkGameSizeId($game_size_id){
           $this->game_size_id = $game_size_id;
       }
    }

    public function getGameSizeId($game_size_id){
        return $this->game_size_id;
    }


    ///////////////////////////////////////////////
    // Functions for faction_id
    ///////////////////////////////////////////////
	public function checkFactionId($faction_id){
       //Allowed to be NULL
       if(Check::isNull($faction_id)){ return null; }
       //Check the value
       if(Check::notInt($faction_id)){
           echo "achievements->faction_id is invalid!";
           return false;
       }

       return intVal($faction_id);
    }

    public function setFactionId($faction_id){
       if($this->checkFactionId($faction_id){
           $this->faction_id = $faction_id;
       }
    }

    public function getFactionId($faction_id){
        return $this->faction_id;
    }


    ///////////////////////////////////////////////
    // Functions for unique_opponent
    ///////////////////////////////////////////////
	public function checkUniqueOpponent($unique_opponent){
       //Allowed to be NULL
       if(Check::isNull($unique_opponent)){ return null; }
       //Check the value
       if(Check::notBool($unique_opponent)){
           echo "achievements->unique_opponent is invalid!";
           return false;
       }

       return intVal($unique_opponent);
    }

    public function setUniqueOpponent($unique_opponent){
       if($this->checkUniqueOpponent($unique_opponent){
           $this->unique_opponent = $unique_opponent;
       }
    }

    public function getUniqueOpponent($unique_opponent){
        return $this->unique_opponent;
    }


    ///////////////////////////////////////////////
    // Functions for unique_opponent_locations
    ///////////////////////////////////////////////
	public function checkUniqueOpponentLocations($unique_opponent_locations){
       //Allowed to be NULL
       if(Check::isNull($unique_opponent_locations)){ return null; }
       //Check the value
       if(Check::notBool($unique_opponent_locations)){
           echo "achievements->unique_opponent_locations is invalid!";
           return false;
       }

       return intVal($unique_opponent_locations);
    }

    public function setUniqueOpponentLocations($unique_opponent_locations){
       if($this->checkUniqueOpponentLocations($unique_opponent_locations){
           $this->unique_opponent_locations = $unique_opponent_locations;
       }
    }

    public function getUniqueOpponentLocations($unique_opponent_locations){
        return $this->unique_opponent_locations;
    }


    ///////////////////////////////////////////////
    // Functions for played_theme_force
    ///////////////////////////////////////////////
	public function checkPlayedThemeForce($played_theme_force){
       //Allowed to be NULL
       if(Check::isNull($played_theme_force)){ return null; }
       //Check the value
       if(Check::notBool($played_theme_force)){
           echo "achievements->played_theme_force is invalid!";
           return false;
       }

       return intVal($played_theme_force);
    }

    public function setPlayedThemeForce($played_theme_force){
       if($this->checkPlayedThemeForce($played_theme_force){
           $this->played_theme_force = $played_theme_force;
       }
    }

    public function getPlayedThemeForce($played_theme_force){
        return $this->played_theme_force;
    }


    ///////////////////////////////////////////////
    // Functions for fully_painted
    ///////////////////////////////////////////////
	public function checkFullyPainted($fully_painted){
       //Allowed to be NULL
       if(Check::isNull($fully_painted)){ return null; }
       //Check the value
       if(Check::notBool($fully_painted)){
           echo "achievements->fully_painted is invalid!";
           return false;
       }

       return intVal($fully_painted);
    }

    public function setFullyPainted($fully_painted){
       if($this->checkFullyPainted($fully_painted){
           $this->fully_painted = $fully_painted;
       }
    }

    public function getFullyPainted($fully_painted){
        return $this->fully_painted;
    }


    ///////////////////////////////////////////////
    // Functions for fully_painted_battle
    ///////////////////////////////////////////////
	public function checkFullyPaintedBattle($fully_painted_battle){
       //Allowed to be NULL
       if(Check::isNull($fully_painted_battle)){ return null; }
       //Check the value
       if(Check::notBool($fully_painted_battle)){
           echo "achievements->fully_painted_battle is invalid!";
           return false;
       }

       return intVal($fully_painted_battle);
    }

    public function setFullyPaintedBattle($fully_painted_battle){
       if($this->checkFullyPaintedBattle($fully_painted_battle){
           $this->fully_painted_battle = $fully_painted_battle;
       }
    }

    public function getFullyPaintedBattle($fully_painted_battle){
        return $this->fully_painted_battle;
    }


    ///////////////////////////////////////////////
    // Functions for played_scenario
    ///////////////////////////////////////////////
	public function checkPlayedScenario($played_scenario){
       //Allowed to be NULL
       if(Check::isNull($played_scenario)){ return null; }
       //Check the value
       if(Check::notBool($played_scenario)){
           echo "achievements->played_scenario is invalid!";
           return false;
       }

       return intVal($played_scenario);
    }

    public function setPlayedScenario($played_scenario){
       if($this->checkPlayedScenario($played_scenario){
           $this->played_scenario = $played_scenario;
       }
    }

    public function getPlayedScenario($played_scenario){
        return $this->played_scenario;
    }


    ///////////////////////////////////////////////
    // Functions for multiplayer
    ///////////////////////////////////////////////
	public function checkMultiplayer($multiplayer){
       //Allowed to be NULL
       if(Check::isNull($multiplayer)){ return null; }
       //Check the value
       if(Check::notBool($multiplayer)){
           echo "achievements->multiplayer is invalid!";
           return false;
       }

       return intVal($multiplayer);
    }

    public function setMultiplayer($multiplayer){
       if($this->checkMultiplayer($multiplayer){
           $this->multiplayer = $multiplayer;
       }
    }

    public function getMultiplayer($multiplayer){
        return $this->multiplayer;
    }


    ///////////////////////////////////////////////
    // Functions for vs_vip
    ///////////////////////////////////////////////
	public function checkVsVip($vs_vip){
       //Allowed to be NULL
       if(Check::isNull($vs_vip)){ return null; }
       //Check the value
       if(Check::notBool($vs_vip)){
           echo "achievements->vs_vip is invalid!";
           return false;
       }

       return intVal($vs_vip);
    }

    public function setVsVip($vs_vip){
       if($this->checkVsVip($vs_vip){
           $this->vs_vip = $vs_vip;
       }
    }

    public function getVsVip($vs_vip){
        return $this->vs_vip;
    }


    ///////////////////////////////////////////////
    // Functions for tournament_id
    ///////////////////////////////////////////////
	public function checkTournamentId($tournament_id){
       //Allowed to be NULL
       if(Check::isNull($tournament_id)){ return null; }
       //Check the value
       if(Check::notInt($tournament_id)){
           echo "achievements->tournament_id is invalid!";
           return false;
       }

       return intVal($tournament_id);
    }

    public function setTournamentId($tournament_id){
       if($this->checkTournamentId($tournament_id){
           $this->tournament_id = $tournament_id;
       }
    }

    public function getTournamentId($tournament_id){
        return $this->tournament_id;
    }


	///////////////////////////////////////////////////
	//
	//	Commit (Insert/Update) to DB Function(s)
	//
	///////////////////////////////////////////////////
	
	public function commit(){

    	if($this->filterId($this->id)){
        	return $this->updateRow();
	    } else {
    	    return $this->insertRow();
	    }
	}

	private function insertRow(){

	    //Check for good data, first
    	foreach($varlist as $vname=>$valFn){
        	if(!$this->$valFn($this->$vname)) return false;
	    }

	    //Create the array of variables names and value calls
    	$c_names = "";
	    $v_calls = "";
    	$values = array();
	    foreach(array_keys($varlist) as $v){
    	    $c_names .= "$v";
        	$v_calls .= ":$v";
        	$values[":$v"] = $this->$v;

	        if($v != end(array_keys($varlist)){
    	        $c_names .= ", ";
        	    $v_calls .= ", ";
	        }
    	}

	    //Build the query
    	$sql = "INSERT INTO $this->table ($c_names) VALUES ($v_calls)";

	    return $this->db->insert($sql, $values);
	}

	private function updateRow(){

    	//Check for good data, first
    	foreach($varlist as $vname=>$valFn){
        	if(!$this->$valFn($this->$vname)) return false;
    	}

    	//Create the array of variables names and value calls
    	$c_str = "";
    	$values = array(":id"=>$this->id);
    	foreach(array_keys($varlist) as $v){
        	$c_str .= "$v=:$v";
        	$values[":$v"] = $this->$v;

        	if($v != end(array_keys($varlist)){
            	$c_str .= ", ";
        	}
    	}

    	//Build the query
    	$sql = "UPDATE $this->table SET $c_str WHERE id=:id";

    	return $this->db->update($sql, $values);
	}

    ///////////////////////////////////////////////////
    //
    //  Delete from DB Function(s)
    //
    ///////////////////////////////////////////////////

	public function deleteByColumns($columns){

	    //Create the values array
    	$values = array();
    	foreach($columns as $c=>$v){
        	$values[":".$c]=$v;
    	}

	    //Create Query\n";
    	$sql = "DELETE FROM $this->table WHERE ";
    	$keys = array_keys($columns);
	    foreach($keys as $column){
    	    $sql.= "$column=:$column";
        	if(strcmp($column, end($keys))){
            	$sql.= " AND ";
        	}
    	}

    	return $this->db->delete($sql, $values);
	}

	public function delete(){
    	if($this->id) return $this->deleteByColumns(array("id"=>$id));
    	return false;
	}

    ///////////////////////////////////////////////////
    //
    //  Query DB Function(s)
    //
    ///////////////////////////////////////////////////


	public static function getAll(){
    	//Generate the query
    	$sql = "SELECT * FROM $this->table";

    	$rows = $this->db->query($sql, array());

		$data = array();
		foreach($rows as $r){
			$data[] = Achievements::parseRow($r);
		}

		return $data;
	}

	public static function getbyId($id){
		return Achievements::queryByColumns(array("id"=>$id));
	}

    public static function queryByColumns($columns){

        //Create the values array
        $values = array();
        foreach($columns as $c=>$v){
            $values[":".$c]=$v;
        }

        //Create Query\n";
        $sql = "SELECT FROM $this->table WHERE ";
        $keys = array_keys($columns);
        foreach($keys as $column){
            $sql.= "$column=:$column";
            if(strcmp($column, end($keys))){
                $sql.= " AND ";
            }
        }

        $rows = $this->db->query($sql, $values);

		$data = array();
		foreach($rows as $r){
            $data[] = Achievements::parseRow($r);
        }

        return $data;
    }

	private static function parseRow($row){
		$achievements = new Achievements();

	    $achievements->setId($row["id"]);
	    $achievements->setName($row["name"]);
	    $achievements->setPoints($row["points"]);
	    $achievements->setPerGame($row["per_game"]);
	    $achievements->setIsMeta($row["is_meta"]);
	    $achievements->setGameCount($row["game_count"]);
	    $achievements->setGameSystemId($row["game_system_id"]);
	    $achievements->setGameSizeId($row["game_size_id"]);
	    $achievements->setFactionId($row["faction_id"]);
	    $achievements->setUniqueOpponent($row["unique_opponent"]);
	    $achievements->setUniqueOpponentLocations($row["unique_opponent_locations"]);
	    $achievements->setPlayedThemeForce($row["played_theme_force"]);
	    $achievements->setFullyPainted($row["fully_painted"]);
	    $achievements->setFullyPaintedBattle($row["fully_painted_battle"]);
	    $achievements->setPlayedScenario($row["played_scenario"]);
	    $achievements->setMultiplayer($row["multiplayer"]);
	    $achievements->setVsVip($row["vs_vip"]);
	    $achievements->setTournamentId($row["tournament_id"]);
	
		return $achievements;
	}
}