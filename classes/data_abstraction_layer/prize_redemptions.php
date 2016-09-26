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
// prize_id - INT
// creation_time - DATETIME
//
///////////////////////////////////////////////

class PrizeRedemptions {

    private $db;
    private $table = "prize_redemptions";

    private $id = null;
    private $player_id = null;
    private $prize_id = null;
    private $creation_time = null;

    private $varlist = array(
        "id",
        "player_id",
        "prize_id",
        "creation_time");

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
			echo "prize_redemptions->id cannot be null!";
		}
       //Check the value
       if(Check::notInt($id)){
           echo "prize_redemptions->id is invalid!";
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
			echo "prize_redemptions->player_id cannot be null!";
		}
       //Check the value
       if(Check::notInt($player_id)){
           echo "prize_redemptions->player_id is invalid!";
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
    // Functions for prize_id
    ///////////////////////////////////////////////
	public function checkPrizeId($prize_id){
	 	//Not allowed to be NULL
		if(Check::isNull($prize_id)){
			echo "prize_redemptions->prize_id cannot be null!";
		}
       //Check the value
       if(Check::notInt($prize_id)){
           echo "prize_redemptions->prize_id is invalid!";
           return false;
       }

       return intVal($prize_id);
   }

    public function setPrizeId($prize_id){
       if($this->checkPrizeId($prize_id){
           $this->prize_id = $prize_id;
       }
    }

    public function getPrizeId($prize_id){
        return $this->prize_id = $prize_id;
    }


    ///////////////////////////////////////////////
    // Functions for creation_time
    ///////////////////////////////////////////////
	public function checkCreationTime($creation_time){
	 	//Not allowed to be NULL
		if(Check::isNull($creation_time)){
			echo "prize_redemptions->creation_time cannot be null!";
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
