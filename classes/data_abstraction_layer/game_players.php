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
// id - INT
// game_id - INT
// player_id - INT
// faction_id - INT
// game_size - INT
// theme_force - TINYINT
// fully_painted - TINYINT
//
///////////////////////////////////////////////

class GamePlayers {

    private $db;
    private $table = "game_players";

    private $id = null;
    private $game_id = null;
    private $player_id = null;
    private $faction_id = null;
    private $game_size = null;
    private $theme_force = null;
    private $fully_painted = null;

    private $varlist = array(
        "id",
        "game_id",
        "player_id",
        "faction_id",
        "game_size",
        "theme_force",
        "fully_painted");

    public function __construct(){
        $this->db = Query::getInstance();
    }

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
			echo "game_players->id cannot be null!";
		}
       //Check the value
       if(Check::notInt($id)){
           echo "game_players->id is invalid!";
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
        return $this->id = $id;
    }


    ///////////////////////////////////////////////
    // Functions for game_id
    ///////////////////////////////////////////////
	public function checkGameId($game_id){
	 	//Not allowed to be NULL
		if(Check::isNull($game_id)){
			echo "game_players->game_id cannot be null!";
		}
       //Check the value
       if(Check::notInt($game_id)){
           echo "game_players->game_id is invalid!";
           return false;
       }

       return intVal($game_id);
   }

    public function setGameId($game_id){
       if($this->checkGameId($game_id){
           $this->game_id = $game_id;
       }
    }

    public function getGameId($game_id){
        return $this->game_id = $game_id;
    }


    ///////////////////////////////////////////////
    // Functions for player_id
    ///////////////////////////////////////////////
	public function checkPlayerId($player_id){
	 	//Not allowed to be NULL
		if(Check::isNull($player_id)){
			echo "game_players->player_id cannot be null!";
		}
       //Check the value
       if(Check::notInt($player_id)){
           echo "game_players->player_id is invalid!";
           return false;
       }

       return intVal($player_id);
   }

    public function setPlayerId($player_id){
       if($this->checkPlayerId($player_id){
           $this->player_id = $player_id;
       }
    }

    public function getPlayerId($player_id){
        return $this->player_id = $player_id;
    }


    ///////////////////////////////////////////////
    // Functions for faction_id
    ///////////////////////////////////////////////
	public function checkFactionId($faction_id){
       //Allowed to be NULL
       if(Check::isNull($faction_id)){ return null; }
       //Check the value
       if(Check::notInt($faction_id)){
           echo "game_players->faction_id is invalid!";
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
        return $this->faction_id = $faction_id;
    }


    ///////////////////////////////////////////////
    // Functions for game_size
    ///////////////////////////////////////////////
	public function checkGameSize($game_size){
       //Allowed to be NULL
       if(Check::isNull($game_size)){ return null; }
       //Check the value
       if(Check::notInt($game_size)){
           echo "game_players->game_size is invalid!";
           return false;
       }

       return intVal($game_size);
   }

    public function setGameSize($game_size){
       if($this->checkGameSize($game_size){
           $this->game_size = $game_size;
       }
    }

    public function getGameSize($game_size){
        return $this->game_size = $game_size;
    }


    ///////////////////////////////////////////////
    // Functions for theme_force
    ///////////////////////////////////////////////
	public function checkThemeForce($theme_force){
	 	//Not allowed to be NULL
		if(Check::isNull($theme_force)){
			echo "game_players->theme_force cannot be null!";
		}
       //Check the value
       if(Check::notBool($theme_force)){
           echo "game_players->theme_force is invalid!";
           return false;
       }

       return intVal($theme_force);
   }

    public function setThemeForce($theme_force){
       if($this->checkThemeForce($theme_force){
           $this->theme_force = $theme_force;
       }
    }

    public function getThemeForce($theme_force){
        return $this->theme_force = $theme_force;
    }


    ///////////////////////////////////////////////
    // Functions for fully_painted
    ///////////////////////////////////////////////
	public function checkFullyPainted($fully_painted){
	 	//Not allowed to be NULL
		if(Check::isNull($fully_painted)){
			echo "game_players->fully_painted cannot be null!";
		}
       //Check the value
       if(Check::notBool($fully_painted)){
           echo "game_players->fully_painted is invalid!";
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
        return $this->fully_painted = $fully_painted;
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
}
