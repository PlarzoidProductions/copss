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
public function deleteMeta_achievement_criteria($id){

	//Create the values array
	$values = array(":id"=>$id);

	//Create Query
	$sql = "DELETE FROM $this->table WHERE id=:id";

	return $this->db->delete($sql, $values);
}


/**************************************************

Update Record By ID Function(s)

**************************************************/
private function updateMeta_achievement_criteriaById($id, $columns){

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

Query By Column Function(s)

**************************************************/
private function getByColumn($column, $value){

    //inputs are pre-verified by the mapping functions below, so we can trust them

    //Values Array
    $values = array(":$column"=>$value);

    //Generate the query
    $sql = "SELECT * FROM $this->table WHERE $column=:$column";
    
    return $this->db->query($sql, $values);
}


public function getById($id){
	
    //Validate Inputs
    $id = $this->filterId($id); if($id === false){return false;}

    return $this->getByColumn("id", $id);
}


public function getByParentAchievement($parent_achievement){
	
    //Validate Inputs
    $parent_achievement = $this->filterParentAchievement($parent_achievement); if($parent_achievement === false){return false;}

    return $this->getByColumn("parent_achievement", $parent_achievement);
}


public function getByChildAchievement($child_achievement){
	
    //Validate Inputs
    $child_achievement = $this->filterChildAchievement($child_achievement); if($child_achievement === false){return false;}

    return $this->getByColumn("child_achievement", $child_achievement);
}


public function getByCount($count){
	
    //Validate Inputs
    $count = $this->filterCount($count); if($count === false){return false;}

    return $this->getByColumn("count", $count);
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

    return $id;
}



function filterParentAchievement($parent_achievement){
    //Not allowed to be null
    if(Check::isNull($parent_achievement)){
        echo "parent_achievement cannot be null!"; return false;
    }

    if(Check::notInt($parent_achievement)){
        echo "parent_achievement was invalid!"; return false;
    }

    return $parent_achievement;
}



function filterChildAchievement($child_achievement){
    //Not allowed to be null
    if(Check::isNull($child_achievement)){
        echo "child_achievement cannot be null!"; return false;
    }

    if(Check::notInt($child_achievement)){
        echo "child_achievement was invalid!"; return false;
    }

    return $child_achievement;
}



function filterCount($count){
    //Not allowed to be null
    if(Check::isNull($count)){
        echo "count cannot be null!"; return false;
    }

    if(Check::notInt($count)){
        echo "count was invalid!"; return false;
    }

    return $count;
}



}//close class

?>
