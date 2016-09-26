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
// description - VARCHAR
// start_time - DATETIME
// stop_time - DATETIME
// tournament_id - INT
//
///////////////////////////////////////////////

class Shifts {

    private $db;
    private $table = "shifts";

    private $id = null;
    private $description = null;
    private $start_time = null;
    private $stop_time = null;
    private $tournament_id = null;

    private $varlist = array(
        "id",
        "description",
        "start_time",
        "stop_time",
        "tournament_id");

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
			echo "shifts->id cannot be null!";
		}
       //Check the value
       if(Check::notInt($id)){
           echo "shifts->id is invalid!";
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
    // Functions for description
    ///////////////////////////////////////////////
	public function checkDescription($description){
	 	//Not allowed to be NULL
		if(Check::isNull($description)){
			echo "shifts->description cannot be null!";
		}
       //Check the value
       if(Check::notString($description)){
           echo "shifts->description is invalid!";
           return false;
       }

       return $description;
   }

    public function setDescription($description){
       if($this->checkDescription($description){
           $this->description = $description;
       }
    }

    public function getDescription($description){
        return $this->description = $description;
    }


    ///////////////////////////////////////////////
    // Functions for start_time
    ///////////////////////////////////////////////
	public function checkStartTime($start_time){
	 	//Not allowed to be NULL
		if(Check::isNull($start_time)){
			echo "shifts->start_time cannot be null!";
		}
       return date("Y-m-d H:i:s", $start_time);

   }

    public function setStartTime($start_time){
       if($this->checkStartTime($start_time){
           $this->start_time = $start_time;
       }
    }

    public function getStartTime($start_time){
        return $this->start_time = $start_time;
    }


    ///////////////////////////////////////////////
    // Functions for stop_time
    ///////////////////////////////////////////////
	public function checkStopTime($stop_time){
	 	//Not allowed to be NULL
		if(Check::isNull($stop_time)){
			echo "shifts->stop_time cannot be null!";
		}
       return date("Y-m-d H:i:s", $stop_time);

   }

    public function setStopTime($stop_time){
       if($this->checkStopTime($stop_time){
           $this->stop_time = $stop_time;
       }
    }

    public function getStopTime($stop_time){
        return $this->stop_time = $stop_time;
    }


    ///////////////////////////////////////////////
    // Functions for tournament_id
    ///////////////////////////////////////////////
	public function checkTournamentId($tournament_id){
	 	//Not allowed to be NULL
		if(Check::isNull($tournament_id)){
			echo "shifts->tournament_id cannot be null!";
		}
       //Check the value
       if(Check::notInt($tournament_id)){
           echo "shifts->tournament_id is invalid!";
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
