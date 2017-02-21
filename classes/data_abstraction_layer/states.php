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
// name - VARCHAR
// parent - INT - FK: countries, id
//
///////////////////////////////////////////////

class States {

    private $db;
    private $table = "states";

    private $id = null;
    private $name = null;
    private $parent = null;

    private $varlist = array(
        "name",
        "parent");

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
			echo "states->id cannot be null!";
		}
       //Check the value
       if(Check::notInt($id)){
           echo "states->id is invalid!";
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
    // Functions for name
    ///////////////////////////////////////////////
	public function checkName($name){
	 	//Not allowed to be NULL
		if(Check::isNull($name)){
			echo "states->name cannot be null!";
		}
       //Check the value
       if(Check::notString($name)){
           echo "states->name is invalid!";
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
        return $this->name;
    }


    ///////////////////////////////////////////////
    // Functions for parent
    ///////////////////////////////////////////////
	public function checkParent($parent){
	 	//Not allowed to be NULL
		if(Check::isNull($parent)){
			echo "states->parent cannot be null!";
		}
       //Check the value
       if(Check::notInt($parent)){
           echo "states->parent is invalid!";
           return false;
       }

       return intVal($parent);
   }

    public function setParent($parent){
       if($this->checkParent($parent){
           $this->parent = $parent;
       }
    }

    public function getParent($parent){
        return $this->parent;
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
			$data[] = States::parseRow($r);
		}

		return $data;
	}

	public static function getbyId($id){
		return States::queryByColumns(array("id"=>$id));
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
            $data[] = States::parseRow($r);
        }

        return $data;
    }

	private static function parseRow($row){
		$states = new States();

	    $states->setId($row["id"]);
	    $states->setName($row["name"]);
	    $states->setParent($row["parent"]);
	
		return $states;
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
