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
// cost - INT
//
///////////////////////////////////////////////

class Prizes {

    private $db;
    private $table = "prizes";

    private $id = null;
    private $description = null;
    private $cost = null;

    private $varlist = array(
        "id",
        "description",
        "cost");

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
			echo "prizes->id cannot be null!";
		}
       //Check the value
       if(Check::notInt($id)){
           echo "prizes->id is invalid!";
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
			echo "prizes->description cannot be null!";
		}
       //Check the value
       if(Check::notString($description)){
           echo "prizes->description is invalid!";
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
    // Functions for cost
    ///////////////////////////////////////////////
	public function checkCost($cost){
	 	//Not allowed to be NULL
		if(Check::isNull($cost)){
			echo "prizes->cost cannot be null!";
		}
       //Check the value
       if(Check::notInt($cost)){
           echo "prizes->cost is invalid!";
           return false;
       }

       return intVal($cost);
   }

    public function setCost($cost){
       if($this->checkCost($cost){
           $this->cost = $cost;
       }
    }

    public function getCost($cost){
        return $this->cost = $cost;
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
