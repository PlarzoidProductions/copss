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
// id - INT - PRIMARY KEY
// game_id - INT - FK: tournament_games, id
// player_id - INT - FK: tournament_registrations, id
// list_played - INT
// control_points - INT
// destruction_points - INT
// assassination_efficiency - INT
// timed_out - TINYINT
//
///////////////////////////////////////////////

class TournamentGameDetails {

    private $db;
    private $table = "tournament_game_details";

    private $id = null;
    private $game_id = null;
    private $player_id = null;
    private $list_played = null;
    private $control_points = null;
    private $destruction_points = null;
    private $assassination_efficiency = null;
    private $timed_out = null;

    private $varlist = array(
        "game_id",
        "player_id",
        "list_played",
        "control_points",
        "destruction_points",
        "assassination_efficiency",
        "timed_out");

    public function __construct($id=null){
        $this->id = $id;
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
			echo "tournament_game_details->id cannot be null!";
		}
       //Check the value
       if(Check::notInt($id)){
           echo "tournament_game_details->id is invalid!";
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
    // Functions for game_id
    ///////////////////////////////////////////////
	public function checkGameId($game_id){
	 	//Not allowed to be NULL
		if(Check::isNull($game_id)){
			echo "tournament_game_details->game_id cannot be null!";
		}
       //Check the value
       if(Check::notInt($game_id)){
           echo "tournament_game_details->game_id is invalid!";
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
        return $this->game_id;
    }


    ///////////////////////////////////////////////
    // Functions for player_id
    ///////////////////////////////////////////////
	public function checkPlayerId($player_id){
	 	//Not allowed to be NULL
		if(Check::isNull($player_id)){
			echo "tournament_game_details->player_id cannot be null!";
		}
       //Check the value
       if(Check::notInt($player_id)){
           echo "tournament_game_details->player_id is invalid!";
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
        return $this->player_id;
    }


    ///////////////////////////////////////////////
    // Functions for list_played
    ///////////////////////////////////////////////
	public function checkListPlayed($list_played){
	 	//Not allowed to be NULL
		if(Check::isNull($list_played)){
			echo "tournament_game_details->list_played cannot be null!";
		}
       //Check the value
       if(Check::notInt($list_played)){
           echo "tournament_game_details->list_played is invalid!";
           return false;
       }

       return intVal($list_played);
   }

    public function setListPlayed($list_played){
       if($this->checkListPlayed($list_played){
           $this->list_played = $list_played;
       }
    }

    public function getListPlayed($list_played){
        return $this->list_played;
    }


    ///////////////////////////////////////////////
    // Functions for control_points
    ///////////////////////////////////////////////
	public function checkControlPoints($control_points){
	 	//Not allowed to be NULL
		if(Check::isNull($control_points)){
			echo "tournament_game_details->control_points cannot be null!";
		}
       //Check the value
       if(Check::notInt($control_points)){
           echo "tournament_game_details->control_points is invalid!";
           return false;
       }

       return intVal($control_points);
   }

    public function setControlPoints($control_points){
       if($this->checkControlPoints($control_points){
           $this->control_points = $control_points;
       }
    }

    public function getControlPoints($control_points){
        return $this->control_points;
    }


    ///////////////////////////////////////////////
    // Functions for destruction_points
    ///////////////////////////////////////////////
	public function checkDestructionPoints($destruction_points){
	 	//Not allowed to be NULL
		if(Check::isNull($destruction_points)){
			echo "tournament_game_details->destruction_points cannot be null!";
		}
       //Check the value
       if(Check::notInt($destruction_points)){
           echo "tournament_game_details->destruction_points is invalid!";
           return false;
       }

       return intVal($destruction_points);
   }

    public function setDestructionPoints($destruction_points){
       if($this->checkDestructionPoints($destruction_points){
           $this->destruction_points = $destruction_points;
       }
    }

    public function getDestructionPoints($destruction_points){
        return $this->destruction_points;
    }


    ///////////////////////////////////////////////
    // Functions for assassination_efficiency
    ///////////////////////////////////////////////
	public function checkAssassinationEfficiency($assassination_efficiency){
       //Allowed to be NULL
       if(Check::isNull($assassination_efficiency)){ return null; }
       //Check the value
       if(Check::notInt($assassination_efficiency)){
           echo "tournament_game_details->assassination_efficiency is invalid!";
           return false;
       }

       return intVal($assassination_efficiency);
   }

    public function setAssassinationEfficiency($assassination_efficiency){
       if($this->checkAssassinationEfficiency($assassination_efficiency){
           $this->assassination_efficiency = $assassination_efficiency;
       }
    }

    public function getAssassinationEfficiency($assassination_efficiency){
        return $this->assassination_efficiency;
    }


    ///////////////////////////////////////////////
    // Functions for timed_out
    ///////////////////////////////////////////////
	public function checkTimedOut($timed_out){
	 	//Not allowed to be NULL
		if(Check::isNull($timed_out)){
			echo "tournament_game_details->timed_out cannot be null!";
		}
       //Check the value
       if(Check::notBool($timed_out)){
           echo "tournament_game_details->timed_out is invalid!";
           return false;
       }

       return intVal($timed_out);
   }

    public function setTimedOut($timed_out){
       if($this->checkTimedOut($timed_out){
           $this->timed_out = $timed_out;
       }
    }

    public function getTimedOut($timed_out){
        return $this->timed_out;
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
			$data[] = TournamentGameDetails::parseRow($r);
		}

		return $data;
	}

	public static function getbyId($id){
		return TournamentGameDetails::queryByColumns(array("id"=>$id));
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
            $data[] = TournamentGameDetails::parseRow($r);
        }

        return $data;
    }

	private static function parseRow($row){
		$tournament_game_details = new TournamentGameDetails();

	    $tournament_game_details->setId($row["id"]);
	    $tournament_game_details->setGameId($row["game_id"]);
	    $tournament_game_details->setPlayerId($row["player_id"]);
	    $tournament_game_details->setListPlayed($row["list_played"]);
	    $tournament_game_details->setControlPoints($row["control_points"]);
	    $tournament_game_details->setDestructionPoints($row["destruction_points"]);
	    $tournament_game_details->setAssassinationEfficiency($row["assassination_efficiency"]);
	    $tournament_game_details->setTimedOut($row["timed_out"]);
	
		return $tournament_game_details;
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
