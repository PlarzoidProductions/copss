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
// player_id - INT - FK: players, id
// achievement_id - INT - FK: achievements, id
// game_id - INT - FK: games, id
//
///////////////////////////////////////////////

class AchievementsEarned {

    private $db;
    private $table = "achievements_earned";

    private $id = null;
    private $player_id = null;
    private $achievement_id = null;
    private $game_id = null;

    private $varlist = array(
        "player_id",
        "achievement_id",
        "game_id");

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
			echo "achievements_earned->id cannot be null!";
		}
       //Check the value
       if(Check::notInt($id)){
           echo "achievements_earned->id is invalid!";
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
    // Functions for player_id
    ///////////////////////////////////////////////
	public function checkPlayerId($player_id){
	 	//Not allowed to be NULL
		if(Check::isNull($player_id)){
			echo "achievements_earned->player_id cannot be null!";
		}
       //Check the value
       if(Check::notInt($player_id)){
           echo "achievements_earned->player_id is invalid!";
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
    // Functions for achievement_id
    ///////////////////////////////////////////////
	public function checkAchievementId($achievement_id){
	 	//Not allowed to be NULL
		if(Check::isNull($achievement_id)){
			echo "achievements_earned->achievement_id cannot be null!";
		}
       //Check the value
       if(Check::notInt($achievement_id)){
           echo "achievements_earned->achievement_id is invalid!";
           return false;
       }

       return intVal($achievement_id);
   }

    public function setAchievementId($achievement_id){
       if($this->checkAchievementId($achievement_id){
           $this->achievement_id = $achievement_id;
       }
    }

    public function getAchievementId($achievement_id){
        return $this->achievement_id;
    }


    ///////////////////////////////////////////////
    // Functions for game_id
    ///////////////////////////////////////////////
	public function checkGameId($game_id){
       //Allowed to be NULL
       if(Check::isNull($game_id)){ return null; }
       //Check the value
       if(Check::notInt($game_id)){
           echo "achievements_earned->game_id is invalid!";
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
			$data[] = AchievementsEarned::parseRow($r);
		}

		return $data;
	}

	public static function getbyId($id){
		return AchievementsEarned::queryByColumns(array("id"=>$id));
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
            $data[] = AchievementsEarned::parseRow($r);
        }

        return $data;
    }

	private static function parseRow($row){
		$achievements_earned = new AchievementsEarned();

	    $achievements_earned->setId($row["id"]);
	    $achievements_earned->setPlayerId($row["player_id"]);
	    $achievements_earned->setAchievementId($row["achievement_id"]);
	    $achievements_earned->setGameId($row["game_id"]);
	
		return $achievements_earned;
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
