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
// type - VARCHAR
// comment - LONGTEXT
//
///////////////////////////////////////////////

class Feedback {

    private $db;
    private $table = "feedback";

    private $id = null;
    private $type = null;
    private $comment = null;

    private $varlist = array(
        "id",
        "type",
        "comment");

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
			echo "feedback->id cannot be null!";
		}
       //Check the value
       if(Check::notInt($id)){
           echo "feedback->id is invalid!";
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
    // Functions for type
    ///////////////////////////////////////////////
	public function checkType($type){
	 	//Not allowed to be NULL
		if(Check::isNull($type)){
			echo "feedback->type cannot be null!";
		}
       //Check the value
       if(Check::notString($type)){
           echo "feedback->type is invalid!";
           return false;
       }

       return $type;
   }

    public function setType($type){
       if($this->checkType($type){
           $this->type = $type;
       }
    }

    public function getType($type){
        return $this->type = $type;
    }


    ///////////////////////////////////////////////
    // Functions for comment
    ///////////////////////////////////////////////
	public function checkComment($comment){
	 	//Not allowed to be NULL
		if(Check::isNull($comment)){
			echo "feedback->comment cannot be null!";
		}
       //Check the value
       if(Check::isNull($comment)){
           echo "feedback->comment is invalid!";
           return false;
       }

       return $comment;
   }

    public function setComment($comment){
       if($this->checkComment($comment){
           $this->comment = $comment;
       }
    }

    public function getComment($comment){
        return $this->comment = $comment;
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
