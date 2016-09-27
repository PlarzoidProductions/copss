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
// tournament_id - INT - FK: tournaments, id
// round - INT
// winner_id - INT - FK: tournament_registrations, id
//
///////////////////////////////////////////////

class TournamentGames {

    private $db;
    private $table = "tournament_games";

    private $id = null;
    private $tournament_id = null;
    private $round = null;
    private $winner_id = null;

    private $varlist = array(
        "tournament_id",
        "round",
        "winner_id");

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
			echo "tournament_games->id cannot be null!";
		}
       //Check the value
       if(Check::notInt($id)){
           echo "tournament_games->id is invalid!";
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
    // Functions for tournament_id
    ///////////////////////////////////////////////
	public function checkTournamentId($tournament_id){
	 	//Not allowed to be NULL
		if(Check::isNull($tournament_id)){
			echo "tournament_games->tournament_id cannot be null!";
		}
       //Check the value
       if(Check::notInt($tournament_id)){
           echo "tournament_games->tournament_id is invalid!";
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


    ///////////////////////////////////////////////
    // Functions for round
    ///////////////////////////////////////////////
	public function checkRound($round){
	 	//Not allowed to be NULL
		if(Check::isNull($round)){
			echo "tournament_games->round cannot be null!";
		}
       //Check the value
       if(Check::notInt($round)){
           echo "tournament_games->round is invalid!";
           return false;
       }

       return intVal($round);
   }

    public function setRound($round){
       if($this->checkRound($round){
           $this->round = $round;
       }
    }

    public function getRound($round){
        return $this->round;
    }


    ///////////////////////////////////////////////
    // Functions for winner_id
    ///////////////////////////////////////////////
	public function checkWinnerId($winner_id){
       //Allowed to be NULL
       if(Check::isNull($winner_id)){ return null; }
       //Check the value
       if(Check::notInt($winner_id)){
           echo "tournament_games->winner_id is invalid!";
           return false;
       }

       return intVal($winner_id);
   }

    public function setWinnerId($winner_id){
       if($this->checkWinnerId($winner_id){
           $this->winner_id = $winner_id;
       }
    }

    public function getWinnerId($winner_id){
        return $this->winner_id;
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
			$data[] = TournamentGames::parseRow($r);
		}

		return $data;
	}

	public static function getbyId($id){
		return TournamentGames::queryByColumns(array("id"=>$id));
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
            $data[] = TournamentGames::parseRow($r);
        }

        return $data;
    }

	private static function parseRow($row){
		$tournament_games = new TournamentGames();

	    $tournament_games->setId($row["id"]);
	    $tournament_games->setTournamentId($row["tournament_id"]);
	    $tournament_games->setRound($row["round"]);
	    $tournament_games->setWinnerId($row["winner_id"]);
	
		return $tournament_games;
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
