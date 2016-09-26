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
// parent_game_system - INT
// name - VARCHAR
// acronym - VARCHAR
//
///////////////////////////////////////////////

class GameSystemFactions {

    private $db;
    private $table = "game_system_factions";

    private $id = null;
    private $parent_game_system = null;
    private $name = null;
    private $acronym = null;

    private $varlist = array(
        "id",
        "parent_game_system",
        "name",
        "acronym");

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
			echo "game_system_factions->id cannot be null!";
		}
       //Check the value
       if(Check::notInt($id)){
           echo "game_system_factions->id is invalid!";
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
    // Functions for parent_game_system
    ///////////////////////////////////////////////
	public function checkParentGameSystem($parent_game_system){
	 	//Not allowed to be NULL
		if(Check::isNull($parent_game_system)){
			echo "game_system_factions->parent_game_system cannot be null!";
		}
       //Check the value
       if(Check::notInt($parent_game_system)){
           echo "game_system_factions->parent_game_system is invalid!";
           return false;
       }

       return intVal($parent_game_system);
   }

    public function setParentGameSystem($parent_game_system){
       if($this->checkParentGameSystem($parent_game_system){
           $this->parent_game_system = $parent_game_system;
       }
    }

    public function getParentGameSystem($parent_game_system){
        return $this->parent_game_system = $parent_game_system;
    }


    ///////////////////////////////////////////////
    // Functions for name
    ///////////////////////////////////////////////
	public function checkName($name){
	 	//Not allowed to be NULL
		if(Check::isNull($name)){
			echo "game_system_factions->name cannot be null!";
		}
       //Check the value
       if(Check::notString($name)){
           echo "game_system_factions->name is invalid!";
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
    // Functions for acronym
    ///////////////////////////////////////////////
	public function checkAcronym($acronym){
       //Allowed to be NULL
       if(Check::isNull($acronym)){ return null; }
       //Check the value
       if(Check::notString($acronym)){
           echo "game_system_factions->acronym is invalid!";
           return false;
       }

       return $acronym;
   }

    public function setAcronym($acronym){
       if($this->checkAcronym($acronym){
           $this->acronym = $acronym;
       }
    }

    public function getAcronym($acronym){
        return $this->acronym = $acronym;
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
