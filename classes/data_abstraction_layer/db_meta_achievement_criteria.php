<?php

/**************************************************
*
*    Meta_achievement_criteria Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	parent_achievement - INT
*	child_achievement - INT
*	count - INT
*
**************************************************/
require_once("query.php");

class Meta_achievement_criteria {

//DB Interaction variables
private var $db=NULL;
private var $table="meta_achievement_criteria";

//Data storage variables
public var $id=NULL;
public var $parent_achievement=NULL;
public var $child_achievement=NULL;
public var $count=NULL;

//List of variables for sanitization
private var $varlist = array(
	"parent_achievement"=>"filterParentAchievement",
	"child_achievement"=>"filterChildAchievement",
	"count"=>"filterCount");

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

    return Meta_achievement_criteria::fromArray($this->queryByColumns(array("id"=>$id)));
}

public function getByParentAchievement($parent_achievement){
	
    //Validate Inputs
    $parent_achievement = $this->filterParentAchievement($parent_achievement); if($parent_achievement === false){return false;}

    return Meta_achievement_criteria::fromArray($this->queryByColumns(array("parent_achievement"=>$parent_achievement)));
}

public function getByChildAchievement($child_achievement){
	
    //Validate Inputs
    $child_achievement = $this->filterChildAchievement($child_achievement); if($child_achievement === false){return false;}

    return Meta_achievement_criteria::fromArray($this->queryByColumns(array("child_achievement"=>$child_achievement)));
}

public function getByCount($count){
	
    //Validate Inputs
    $count = $this->filterCount($count); if($count === false){return false;}

    return Meta_achievement_criteria::fromArray($this->queryByColumns(array("count"=>$count)));
}

public static function fromArray($array){

    $output = new array();

    foreach($array as $a){

        $new = new Meta_achievement_criteria();
    
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



function filterParentAchievement($parent_achievement){
    //Not allowed to be null
    if(Check::isNull($parent_achievement)){
        echo "parent_achievement cannot be null!"; return false;
    }

    if(Check::notInt($parent_achievement)){
        echo "parent_achievement was invalid!"; return false;
    }

    return intVal($parent_achievement);
}



function filterChildAchievement($child_achievement){
    //Not allowed to be null
    if(Check::isNull($child_achievement)){
        echo "child_achievement cannot be null!"; return false;
    }

    if(Check::notInt($child_achievement)){
        echo "child_achievement was invalid!"; return false;
    }

    return intVal($child_achievement);
}



function filterCount($count){
    //Not allowed to be null
    if(Check::isNull($count)){
        echo "count cannot be null!"; return false;
    }

    if(Check::notInt($count)){
        echo "count was invalid!"; return false;
    }

    return intVal($count);
}



}//close class

?>
