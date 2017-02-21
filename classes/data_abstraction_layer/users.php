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
// username - VARCHAR
// password - CHAR
// creation_date - DATETIME
// last_login - TIMESTAMP
// admin - TINYINT
//
///////////////////////////////////////////////

class Users {

    private $db;
    private $table = "users";

    private $id = null;
    private $name = null;
    private $username = null;
    private $password = null;
    private $creation_date = null;
    private $last_login = null;
    private $admin = null;

    private $varlist = array(
        "name",
        "username",
        "password",
        "creation_date",
        "last_login",
        "admin");

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
			echo "users->id cannot be null!";
		}
       //Check the value
       if(Check::notInt($id)){
           echo "users->id is invalid!";
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
       //Allowed to be NULL
       if(Check::isNull($name)){ return null; }
       //Check the value
       if(Check::notString($name)){
           echo "users->name is invalid!";
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
    // Functions for username
    ///////////////////////////////////////////////
	public function checkUsername($username){
	 	//Not allowed to be NULL
		if(Check::isNull($username)){
			echo "users->username cannot be null!";
		}
       //Check the value
       if(Check::notString($username)){
           echo "users->username is invalid!";
           return false;
       }

       return $username;
   }

    public function setUsername($username){
       if($this->checkUsername($username){
           $this->username = $username;
       }
    }

    public function getUsername($username){
        return $this->username;
    }


    ///////////////////////////////////////////////
    // Functions for password
    ///////////////////////////////////////////////
	public function checkPassword($password){
	 	//Not allowed to be NULL
		if(Check::isNull($password)){
			echo "users->password cannot be null!";
		}
       //Check the value
       if(Check::isNull($password)){
           echo "users->password is invalid!";
           return false;
       }

       return $password;
   }

    public function setPassword($password){
       if($this->checkPassword($password){
           $this->password = $password;
       }
    }

    public function getPassword($password){
        return $this->password;
    }


    ///////////////////////////////////////////////
    // Functions for creation_date
    ///////////////////////////////////////////////
	public function checkCreationDate($creation_date){
	 	//Not allowed to be NULL
		if(Check::isNull($creation_date)){
			echo "users->creation_date cannot be null!";
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


    ///////////////////////////////////////////////
    // Functions for last_login
    ///////////////////////////////////////////////
	public function checkLastLogin($last_login){
       //Allowed to be NULL
       if(Check::isNull($last_login)){ return null; }
       return date("Y-m-d H:i:s", $last_login);

   }

    public function setLastLogin($last_login){
       if($this->checkLastLogin($last_login){
           $this->last_login = $last_login;
       }
    }

    public function getLastLogin($last_login){
        return $this->last_login;
    }


    ///////////////////////////////////////////////
    // Functions for admin
    ///////////////////////////////////////////////
	public function checkAdmin($admin){
	 	//Not allowed to be NULL
		if(Check::isNull($admin)){
			echo "users->admin cannot be null!";
		}
       //Check the value
       if(Check::notBool($admin)){
           echo "users->admin is invalid!";
           return false;
       }

       return intVal($admin);
   }

    public function setAdmin($admin){
       if($this->checkAdmin($admin){
           $this->admin = $admin;
       }
    }

    public function getAdmin($admin){
        return $this->admin;
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
			$data[] = Users::parseRow($r);
		}

		return $data;
	}

	public static function getbyId($id){
		return Users::queryByColumns(array("id"=>$id));
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
            $data[] = Users::parseRow($r);
        }

        return $data;
    }

	private static function parseRow($row){
		$users = new Users();

	    $users->setId($row["id"]);
	    $users->setName($row["name"]);
	    $users->setUsername($row["username"]);
	    $users->setPassword($row["password"]);
	    $users->setCreationDate($row["creation_date"]);
	    $users->setLastLogin($row["last_login"]);
	    $users->setAdmin($row["admin"]);
	
		return $users;
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
