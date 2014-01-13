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

var $db=NULL;
var $table="meta_achievement_criteria";


/***************************************************

Constructor & Destructor

***************************************************/
public function __construct(){
    $this->db = Query::getInstance();
}

public function __destruct(){}


/**************************************************

Create Function

**************************************************/
public function create($parent_achievement, $child_achievement, $count){

	//Validate the inputs
	$parent_achievement = $this->filterParentAchievement($parent_achievement); if($parent_achievement === false){return false;}
	$child_achievement = $this->filterChildAchievement($child_achievement); if($child_achievement === false){return false;}
	$count = $this->filterCount($count); if($count === false){return false;}

	//Create the values Array
	$values = array(
		":parent_achievement"=>$parent_achievement,
 		":child_achievement"=>$child_achievement,
 		":count"=>$count
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				parent_achievement,
				child_achievement,
				count
			) VALUES (
				:parent_achievement,
				:child_achievement,
				:count)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

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


/**************************************************

Update Record By ID Function(s)

**************************************************/
public function updateMeta_achievement_criteriaById($id, $columns){

    //Values Array
    $values = array(":id"=>$id);
    foreach($columns as $column=>$value){
        $values[":".$column]=$value;
    }

    //Generate the query
    $sql = "UPDATE $this->table SET ";
    foreach(array_keys($columns) as $column){
        $sql.= "$column=:$column";
        if(strcmp($column, end(array_keys($columns)))){
            $sql.= ", ";
        }
    }
    $sql.= " WHERE id=:id";

    return $this->db->update($sql, $values);
}


/**************************************************

Query Everything

**************************************************/
public function getAll(){

    //Generate the query
    $sql = "SELECT * FROM $this->table";

    return $this->db->query($sql, array());
}


/**************************************************

Query by Column(s) Function

**************************************************/
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

    return $this->queryByColumns(array("id"=>$id));
}


public function getByParentAchievement($parent_achievement){
	
    //Validate Inputs
    $parent_achievement = $this->filterParentAchievement($parent_achievement); if($parent_achievement === false){return false;}

    return $this->queryByColumns(array("parent_achievement"=>$parent_achievement));
}


public function getByChildAchievement($child_achievement){
	
    //Validate Inputs
    $child_achievement = $this->filterChildAchievement($child_achievement); if($child_achievement === false){return false;}

    return $this->queryByColumns(array("child_achievement"=>$child_achievement));
}


public function getByCount($count){
	
    //Validate Inputs
    $count = $this->filterCount($count); if($count === false){return false;}

    return $this->queryByColumns(array("count"=>$count));
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
