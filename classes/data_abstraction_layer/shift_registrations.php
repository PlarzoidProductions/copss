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
// user_id - INT - FK: users, id
// shift_id - INT - FK: shifts, id
//
///////////////////////////////////////////////

class ShiftRegistrations {

    private $db;
    private $table = "shift_registrations";

    private $id = null;
    private $user_id = null;
    private $shift_id = null;

    private $varlist = array(
        "user_id",
        "shift_id");

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
			echo "shift_registrations->id cannot be null!";
		}
       //Check the value
       if(Check::notInt($id)){
           echo "shift_registrations->id is invalid!";
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
    // Functions for user_id
    ///////////////////////////////////////////////
	public function checkUserId($user_id){
	 	//Not allowed to be NULL
		if(Check::isNull($user_id)){
			echo "shift_registrations->user_id cannot be null!";
		}
       //Check the value
       if(Check::notInt($user_id)){
           echo "shift_registrations->user_id is invalid!";
           return false;
       }

       return intVal($user_id);
    }

    public function setUserId($user_id){
       if($this->checkUserId($user_id){
           $this->user_id = $user_id;
       }
    }

    public function getUserId($user_id){
        return $this->user_id;
    }


    ///////////////////////////////////////////////
    // Functions for shift_id
    ///////////////////////////////////////////////
	public function checkShiftId($shift_id){
	 	//Not allowed to be NULL
		if(Check::isNull($shift_id)){
			echo "shift_registrations->shift_id cannot be null!";
		}
       //Check the value
       if(Check::notInt($shift_id)){
           echo "shift_registrations->shift_id is invalid!";
           return false;
       }

       return intVal($shift_id);
    }

    public function setShiftId($shift_id){
       if($this->checkShiftId($shift_id){
           $this->shift_id = $shift_id;
       }
    }

    public function getShiftId($shift_id){
        return $this->shift_id;
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
			$data[] = ShiftRegistrations::parseRow($r);
		}

		return $data;
	}

	public static function getbyId($id){
		return ShiftRegistrations::queryByColumns(array("id"=>$id));
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
            $data[] = ShiftRegistrations::parseRow($r);
        }

        return $data;
    }

	private static function parseRow($row){
		$shift_registrations = new ShiftRegistrations();

	    $shift_registrations->setId($row["id"]);
	    $shift_registrations->setUserId($row["user_id"]);
	    $shift_registrations->setShiftId($row["shift_id"]);
	
		return $shift_registrations;
	}
}