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
// name - VARCHAR
// game_system_id - INT
// max_num_players - INT
// max_num_rounds - INT
// num_lists_required - INT
// divide_and_conquer - INT
// standings_type - VARCHAR
// final_tables - TINYINT
// large_event_scoring - TINYINT
//
///////////////////////////////////////////////

class Tournaments {

    private $db;
    private $table = "tournaments";

    private $id = null;
    private $name = null;
    private $game_system_id = null;
    private $max_num_players = null;
    private $max_num_rounds = null;
    private $num_lists_required = null;
    private $divide_and_conquer = null;
    private $standings_type = null;
    private $final_tables = null;
    private $large_event_scoring = null;

    private $varlist = array(
        "id",
        "name",
        "game_system_id",
        "max_num_players",
        "max_num_rounds",
        "num_lists_required",
        "divide_and_conquer",
        "standings_type",
        "final_tables",
        "large_event_scoring");

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
			echo "tournaments->id cannot be null!";
		}
       //Check the value
       if(Check::notInt($id)){
           echo "tournaments->id is invalid!";
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
    // Functions for name
    ///////////////////////////////////////////////
	public function checkName($name){
	 	//Not allowed to be NULL
		if(Check::isNull($name)){
			echo "tournaments->name cannot be null!";
		}
       //Check the value
       if(Check::notString($name)){
           echo "tournaments->name is invalid!";
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
        return $this->name = $name;
    }


    ///////////////////////////////////////////////
    // Functions for game_system_id
    ///////////////////////////////////////////////
	public function checkGameSystemId($game_system_id){
	 	//Not allowed to be NULL
		if(Check::isNull($game_system_id)){
			echo "tournaments->game_system_id cannot be null!";
		}
       //Check the value
       if(Check::notInt($game_system_id)){
           echo "tournaments->game_system_id is invalid!";
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
        return $this->game_system_id = $game_system_id;
    }


    ///////////////////////////////////////////////
    // Functions for max_num_players
    ///////////////////////////////////////////////
	public function checkMaxNumPlayers($max_num_players){
	 	//Not allowed to be NULL
		if(Check::isNull($max_num_players)){
			echo "tournaments->max_num_players cannot be null!";
		}
       //Check the value
       if(Check::notInt($max_num_players)){
           echo "tournaments->max_num_players is invalid!";
           return false;
       }

       return intVal($max_num_players);
   }

    public function setMaxNumPlayers($max_num_players){
       if($this->checkMaxNumPlayers($max_num_players){
           $this->max_num_players = $max_num_players;
       }
    }

    public function getMaxNumPlayers($max_num_players){
        return $this->max_num_players = $max_num_players;
    }


    ///////////////////////////////////////////////
    // Functions for max_num_rounds
    ///////////////////////////////////////////////
	public function checkMaxNumRounds($max_num_rounds){
       //Allowed to be NULL
       if(Check::isNull($max_num_rounds)){ return null; }
       //Check the value
       if(Check::notInt($max_num_rounds)){
           echo "tournaments->max_num_rounds is invalid!";
           return false;
       }

       return intVal($max_num_rounds);
   }

    public function setMaxNumRounds($max_num_rounds){
       if($this->checkMaxNumRounds($max_num_rounds){
           $this->max_num_rounds = $max_num_rounds;
       }
    }

    public function getMaxNumRounds($max_num_rounds){
        return $this->max_num_rounds = $max_num_rounds;
    }


    ///////////////////////////////////////////////
    // Functions for num_lists_required
    ///////////////////////////////////////////////
	public function checkNumListsRequired($num_lists_required){
	 	//Not allowed to be NULL
		if(Check::isNull($num_lists_required)){
			echo "tournaments->num_lists_required cannot be null!";
		}
       //Check the value
       if(Check::notInt($num_lists_required)){
           echo "tournaments->num_lists_required is invalid!";
           return false;
       }

       return intVal($num_lists_required);
   }

    public function setNumListsRequired($num_lists_required){
       if($this->checkNumListsRequired($num_lists_required){
           $this->num_lists_required = $num_lists_required;
       }
    }

    public function getNumListsRequired($num_lists_required){
        return $this->num_lists_required = $num_lists_required;
    }


    ///////////////////////////////////////////////
    // Functions for divide_and_conquer
    ///////////////////////////////////////////////
	public function checkDivideAndConquer($divide_and_conquer){
	 	//Not allowed to be NULL
		if(Check::isNull($divide_and_conquer)){
			echo "tournaments->divide_and_conquer cannot be null!";
		}
       //Check the value
       if(Check::notInt($divide_and_conquer)){
           echo "tournaments->divide_and_conquer is invalid!";
           return false;
       }

       return intVal($divide_and_conquer);
   }

    public function setDivideAndConquer($divide_and_conquer){
       if($this->checkDivideAndConquer($divide_and_conquer){
           $this->divide_and_conquer = $divide_and_conquer;
       }
    }

    public function getDivideAndConquer($divide_and_conquer){
        return $this->divide_and_conquer = $divide_and_conquer;
    }


    ///////////////////////////////////////////////
    // Functions for standings_type
    ///////////////////////////////////////////////
	public function checkStandingsType($standings_type){
	 	//Not allowed to be NULL
		if(Check::isNull($standings_type)){
			echo "tournaments->standings_type cannot be null!";
		}
       //Check the value
       if(Check::notString($standings_type)){
           echo "tournaments->standings_type is invalid!";
           return false;
       }

       return $standings_type;
   }

    public function setStandingsType($standings_type){
       if($this->checkStandingsType($standings_type){
           $this->standings_type = $standings_type;
       }
    }

    public function getStandingsType($standings_type){
        return $this->standings_type = $standings_type;
    }


    ///////////////////////////////////////////////
    // Functions for final_tables
    ///////////////////////////////////////////////
	public function checkFinalTables($final_tables){
	 	//Not allowed to be NULL
		if(Check::isNull($final_tables)){
			echo "tournaments->final_tables cannot be null!";
		}
       //Check the value
       if(Check::notBool($final_tables)){
           echo "tournaments->final_tables is invalid!";
           return false;
       }

       return intVal($final_tables);
   }

    public function setFinalTables($final_tables){
       if($this->checkFinalTables($final_tables){
           $this->final_tables = $final_tables;
       }
    }

    public function getFinalTables($final_tables){
        return $this->final_tables = $final_tables;
    }


    ///////////////////////////////////////////////
    // Functions for large_event_scoring
    ///////////////////////////////////////////////
	public function checkLargeEventScoring($large_event_scoring){
	 	//Not allowed to be NULL
		if(Check::isNull($large_event_scoring)){
			echo "tournaments->large_event_scoring cannot be null!";
		}
       //Check the value
       if(Check::notBool($large_event_scoring)){
           echo "tournaments->large_event_scoring is invalid!";
           return false;
       }

       return intVal($large_event_scoring);
   }

    public function setLargeEventScoring($large_event_scoring){
       if($this->checkLargeEventScoring($large_event_scoring){
           $this->large_event_scoring = $large_event_scoring;
       }
    }

    public function getLargeEventScoring($large_event_scoring){
        return $this->large_event_scoring = $large_event_scoring;
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
