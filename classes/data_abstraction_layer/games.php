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
// creation_time - TIMESTAMP
// game_system - INT
// scenario - TINYINT
//
///////////////////////////////////////////////

class Games {

    private $db;
    private $table = "games";

    private $id = null;
    private $creation_time = null;
    private $game_system = null;
    private $scenario = null;

    private $varlist = array(
        "id",
        "creation_time",
        "game_system",
        "scenario");

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
			echo "games->id cannot be null!";
		}
       //Check the value
       if(Check::notInt($id)){
           echo "games->id is invalid!";
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
    // Functions for creation_time
    ///////////////////////////////////////////////
	public function checkCreationTime($creation_time){
	 	//Not allowed to be NULL
		if(Check::isNull($creation_time)){
			echo "games->creation_time cannot be null!";
		}
       return date("Y-m-d H:i:s", $creation_time);

   }

    public function setCreationTime($creation_time){
       if($this->checkCreationTime($creation_time){
           $this->creation_time = $creation_time;
       }
    }

    public function getCreationTime($creation_time){
        return $this->creation_time = $creation_time;
    }


    ///////////////////////////////////////////////
    // Functions for game_system
    ///////////////////////////////////////////////
	public function checkGameSystem($game_system){
	 	//Not allowed to be NULL
		if(Check::isNull($game_system)){
			echo "games->game_system cannot be null!";
		}
       //Check the value
       if(Check::notInt($game_system)){
           echo "games->game_system is invalid!";
           return false;
       }

       return intVal($game_system);
   }

    public function setGameSystem($game_system){
       if($this->checkGameSystem($game_system){
           $this->game_system = $game_system;
       }
    }

    public function getGameSystem($game_system){
        return $this->game_system = $game_system;
    }


    ///////////////////////////////////////////////
    // Functions for scenario
    ///////////////////////////////////////////////
	public function checkScenario($scenario){
	 	//Not allowed to be NULL
		if(Check::isNull($scenario)){
			echo "games->scenario cannot be null!";
		}
       //Check the value
       if(Check::notBool($scenario)){
           echo "games->scenario is invalid!";
           return false;
       }

       return intVal($scenario);
   }

    public function setScenario($scenario){
       if($this->checkScenario($scenario){
           $this->scenario = $scenario;
       }
    }

    public function getScenario($scenario){
        return $this->scenario = $scenario;
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
