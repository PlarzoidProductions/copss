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
// player_id - INT
// tournament_id - INT
// faction_id - INT
// has_dropped - TINYINT
// had_buy - TINYINT
//
///////////////////////////////////////////////

class TournamentRegistrations {

    private $db;
    private $table = "tournament_registrations";

    private $id = null;
    private $player_id = null;
    private $tournament_id = null;
    private $faction_id = null;
    private $has_dropped = null;
    private $had_buy = null;

    private $varlist = array(
        "id",
        "player_id",
        "tournament_id",
        "faction_id",
        "has_dropped",
        "had_buy");

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
			echo "tournament_registrations->id cannot be null!";
		}
       //Check the value
       if(Check::notInt($id)){
           echo "tournament_registrations->id is invalid!";
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
    // Functions for player_id
    ///////////////////////////////////////////////
	public function checkPlayerId($player_id){
	 	//Not allowed to be NULL
		if(Check::isNull($player_id)){
			echo "tournament_registrations->player_id cannot be null!";
		}
       //Check the value
       if(Check::notInt($player_id)){
           echo "tournament_registrations->player_id is invalid!";
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
    // Functions for tournament_id
    ///////////////////////////////////////////////
	public function checkTournamentId($tournament_id){
	 	//Not allowed to be NULL
		if(Check::isNull($tournament_id)){
			echo "tournament_registrations->tournament_id cannot be null!";
		}
       //Check the value
       if(Check::notInt($tournament_id)){
           echo "tournament_registrations->tournament_id is invalid!";
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
        return $this->tournament_id = $tournament_id;
    }


    ///////////////////////////////////////////////
    // Functions for faction_id
    ///////////////////////////////////////////////
	public function checkFactionId($faction_id){
	 	//Not allowed to be NULL
		if(Check::isNull($faction_id)){
			echo "tournament_registrations->faction_id cannot be null!";
		}
       //Check the value
       if(Check::notInt($faction_id)){
           echo "tournament_registrations->faction_id is invalid!";
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
    // Functions for has_dropped
    ///////////////////////////////////////////////
	public function checkHasDropped($has_dropped){
	 	//Not allowed to be NULL
		if(Check::isNull($has_dropped)){
			echo "tournament_registrations->has_dropped cannot be null!";
		}
       //Check the value
       if(Check::notBool($has_dropped)){
           echo "tournament_registrations->has_dropped is invalid!";
           return false;
       }

       return intVal($has_dropped);
   }

    public function setHasDropped($has_dropped){
       if($this->checkHasDropped($has_dropped){
           $this->has_dropped = $has_dropped;
       }
    }

    public function getHasDropped($has_dropped){
        return $this->has_dropped = $has_dropped;
    }


    ///////////////////////////////////////////////
    // Functions for had_buy
    ///////////////////////////////////////////////
	public function checkHadBuy($had_buy){
	 	//Not allowed to be NULL
		if(Check::isNull($had_buy)){
			echo "tournament_registrations->had_buy cannot be null!";
		}
       //Check the value
       if(Check::notBool($had_buy)){
           echo "tournament_registrations->had_buy is invalid!";
           return false;
       }

       return intVal($had_buy);
   }

    public function setHadBuy($had_buy){
       if($this->checkHadBuy($had_buy){
           $this->had_buy = $had_buy;
       }
    }

    public function getHadBuy($had_buy){
        return $this->had_buy = $had_buy;
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
