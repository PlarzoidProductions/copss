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
// first_name - VARCHAR
// last_name - VARCHAR
// country - INT - FK: countries, id
// state - INT - FK: states, id
// vip - TINYINT
// creation_date - DATETIME
//
///////////////////////////////////////////////

class Players {

    private $db;
    private $table = "players";

    private $id = null;
    private $first_name = null;
    private $last_name = null;
    private $country = null;
    private $state = null;
    private $vip = null;
    private $creation_date = null;

    private $varlist = array(
        "first_name",
        "last_name",
        "country",
        "state",
        "vip",
        "creation_date");

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
			echo "players->id cannot be null!";
		}
       //Check the value
       if(Check::notInt($id)){
           echo "players->id is invalid!";
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
    // Functions for first_name
    ///////////////////////////////////////////////
	public function checkFirstName($first_name){
	 	//Not allowed to be NULL
		if(Check::isNull($first_name)){
			echo "players->first_name cannot be null!";
		}
       //Check the value
       if(Check::notString($first_name)){
           echo "players->first_name is invalid!";
           return false;
       }

       return $first_name;
    }

    public function setFirstName($first_name){
       if($this->checkFirstName($first_name){
           $this->first_name = $first_name;
       }
    }

    public function getFirstName($first_name){
        return $this->first_name;
    }


    ///////////////////////////////////////////////
    // Functions for last_name
    ///////////////////////////////////////////////
	public function checkLastName($last_name){
	 	//Not allowed to be NULL
		if(Check::isNull($last_name)){
			echo "players->last_name cannot be null!";
		}
       //Check the value
       if(Check::notString($last_name)){
           echo "players->last_name is invalid!";
           return false;
       }

       return $last_name;
    }

    public function setLastName($last_name){
       if($this->checkLastName($last_name){
           $this->last_name = $last_name;
       }
    }

    public function getLastName($last_name){
        return $this->last_name;
    }


    ///////////////////////////////////////////////
    // Functions for country
    ///////////////////////////////////////////////
	public function checkCountry($country){
	 	//Not allowed to be NULL
		if(Check::isNull($country)){
			echo "players->country cannot be null!";
		}
       //Check the value
       if(Check::notInt($country)){
           echo "players->country is invalid!";
           return false;
       }

       return intVal($country);
    }

    public function setCountry($country){
       if($this->checkCountry($country){
           $this->country = $country;
       }
    }

    public function getCountry($country){
        return $this->country;
    }


    ///////////////////////////////////////////////
    // Functions for state
    ///////////////////////////////////////////////
	public function checkState($state){
       //Allowed to be NULL
       if(Check::isNull($state)){ return null; }
       //Check the value
       if(Check::notInt($state)){
           echo "players->state is invalid!";
           return false;
       }

       return intVal($state);
    }

    public function setState($state){
       if($this->checkState($state){
           $this->state = $state;
       }
    }

    public function getState($state){
        return $this->state;
    }


    ///////////////////////////////////////////////
    // Functions for vip
    ///////////////////////////////////////////////
	public function checkVip($vip){
       //Allowed to be NULL
       if(Check::isNull($vip)){ return null; }
       //Check the value
       if(Check::notBool($vip)){
           echo "players->vip is invalid!";
           return false;
       }

       return intVal($vip);
    }

    public function setVip($vip){
       if($this->checkVip($vip){
           $this->vip = $vip;
       }
    }

    public function getVip($vip){
        return $this->vip;
    }


    ///////////////////////////////////////////////
    // Functions for creation_date
    ///////////////////////////////////////////////
	public function checkCreationDate($creation_date){
	 	//Not allowed to be NULL
		if(Check::isNull($creation_date)){
			echo "players->creation_date cannot be null!";
		}
       return date("Y-m-d H:i:s", $creation_date);

    }

    public function setCreationDate($creation_date){
       if($this->checkCreationDate($creation_date){
           $this->creation_date = $creation_date;
       }
    }

    public function getCreationDate($creation_date){
        return $this->creation_date;
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
			$data[] = Players::parseRow($r);
		}

		return $data;
	}

	public static function getbyId($id){
		return Players::queryByColumns(array("id"=>$id));
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
            $data[] = Players::parseRow($r);
        }

        return $data;
    }

	private static function parseRow($row){
		$players = new Players();

	    $players->setId($row["id"]);
	    $players->setFirstName($row["first_name"]);
	    $players->setLastName($row["last_name"]);
	    $players->setCountry($row["country"]);
	    $players->setState($row["state"]);
	    $players->setVip($row["vip"]);
	    $players->setCreationDate($row["creation_date"]);
	
		return $players;
	}
}
