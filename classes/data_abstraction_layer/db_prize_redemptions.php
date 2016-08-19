<?php

/**************************************************
*
*    Prize_redemptions Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	player_id - INT
*	prize_id - INT
*	creation_time - DATETIME
*
**************************************************/
require_once("query.php");

class Prize_redemptions {

//DB Interaction variables
private var $db=NULL;
private var $table="prize_redemptions";

//Data storage variables
public var $id=NULL;
public var $player_id=NULL;
public var $prize_id=NULL;
public var $creation_time=NULL;

//List of variables for sanitization
private var $varlist = array(
	"player_id"=>"filterPlayerId",
	"prize_id"=>"filterPrizeId",
	"creation_time"=>"filterCreationTime");

/***************************************************

Constructor & Destructor

***************************************************/
public function __construct(){
    $this->db = Query::getInstance();
}

public function __destruct(){}


/**************************************************

Commit (Insert/Update) to DB Function(s)

**************************************************/
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

/**************************************************

Delete Functions

**************************************************/
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

public function deleteById($id){
    return $this->deleteByColumns(array("id"=>$id));
}

public function delete(){
    if($this->id) return $this->deleteById($this->id);

    return false;
}


/**************************************************

Query Functions

**************************************************/
public function getAll(){

    //Generate the query
    $sql = "SELECT * FROM $this->table";

    return $this->db->query($sql, array());
}

public function queryByColumns($columns){

    //Values Array
    $values = array();
    foreach($columns as $column=>$value){
        $values[":".$column]=$value;
    }

    //Generate the query
    $sql = "SELECT * FROM $this->table WHERE ";
    $keys = array_keys($columns);
    foreach($keys as $column){
        $sql.= "$column=:$column";
        if(strcmp($column, end($keys))){
            $sql.= " AND ";
        }
    }

    return $this->db->query($sql, $values);
}


public function getById($id){
	
    //Validate Inputs
    $id = $this->filterId($id); if($id === false){return false;}

    return Prize_redemptions::fromArray($this->queryByColumns(array("id"=>$id)));
}

public function getByPlayerId($player_id){
	
    //Validate Inputs
    $player_id = $this->filterPlayerId($player_id); if($player_id === false){return false;}

    return Prize_redemptions::fromArray($this->queryByColumns(array("player_id"=>$player_id)));
}

public function getByPrizeId($prize_id){
	
    //Validate Inputs
    $prize_id = $this->filterPrizeId($prize_id); if($prize_id === false){return false;}

    return Prize_redemptions::fromArray($this->queryByColumns(array("prize_id"=>$prize_id)));
}

public function getByCreationTime($creation_time){
	
    //Validate Inputs
    $creation_time = $this->filterCreationTime($creation_time); if($creation_time === false){return false;}

    return Prize_redemptions::fromArray($this->queryByColumns(array("creation_time"=>$creation_time)));
}

public static function fromArray($array){

    $output = new array();

    foreach($array as $a){

        $new = new Prize_redemptions();
    
        if($array[id]) $new->id=$a[id];

        foreach($this->varlist as $v){
            $new->$v = $a[$v];
        }

        $output[] = $new;
    }

    return $output;
}


/**************************************************

Exists by Column(s) Function

**************************************************/
public function existsByColumns($columns){
    $results = $this->queryByColumns($columns);

    return count($results);
}


/**************************************************
 
Column Validation Function(s)

**************************************************/
function filterId($id){
    //Not allowed to be null
    if(Check::isNull($id)){
        echo "id cannot be null!"; return false;
    }

    if(Check::notInt($id)){
        echo "id was invalid!"; return false;
    }

    return intVal($id);
}



function filterPlayerId($player_id){
    //Not allowed to be null
    if(Check::isNull($player_id)){
        echo "player_id cannot be null!"; return false;
    }

    if(Check::notInt($player_id)){
        echo "player_id was invalid!"; return false;
    }

    return intVal($player_id);
}



function filterPrizeId($prize_id){
    //Not allowed to be null
    if(Check::isNull($prize_id)){
        echo "prize_id cannot be null!"; return false;
    }

    if(Check::notInt($prize_id)){
        echo "prize_id was invalid!"; return false;
    }

    return intVal($prize_id);
}



function filterCreationTime($creation_time){
    //Not allowed to be null
    if(Check::isNull($creation_time)){
        echo "creation_time cannot be null!"; return false;
    }

    if(Check::isNull($creation_time)){
        echo "creation_time was invalid!"; return false;
    }

    return date("Y-m-d H:i:s", $creation_time);
}



}//close class

?>
