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
	if(!$this->checkParentAchievement($parent_achievement)){return false;}
	if(!$this->checkChildAchievement($child_achievement)){return false;}
	if(!$this->checkCount($count)){return false;}

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

	//Validate the input
	if(!$this->checkParentAchievement($parent_achievement)){return false;}
	if(!$this->checkChildAchievement($child_achievement)){return false;}
	if(!$this->checkCount($count)){return false;}
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
        if(strcmp($column, end($array_keys($columns)))){
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
    if(!$this->checkId($id)){return false;}

    return $this->getByColumn("id", $id);
}


public function getByParentAchievement($parent_achievement){
	
    //Validate Inputs
    if(!$this->checkParentAchievement($parent_achievement)){return false;}

    return $this->getByColumn("parent_achievement", $parent_achievement);
}


public function getByChildAchievement($child_achievement){
	
    //Validate Inputs
    if(!$this->checkChildAchievement($child_achievement)){return false;}

    return $this->getByColumn("child_achievement", $child_achievement);
}


public function getByCount($count){
	
    //Validate Inputs
    if(!$this->checkCount($count)){return false;}

    return $this->getByColumn("count", $count);
}


/**************************************************
 
Column Validation Function(s)

**************************************************/
function checkId($id){
    //Not allowed to be null
    if(Check::isNull($id)){
        echo "id cannot be null!"; return false;
    }

    if(Check::notInt($id)){
        echo "id was invalid!"; return false;
    }

    return true;
}



function checkParentAchievement($parent_achievement){
    //Not allowed to be null
    if(Check::isNull($parent_achievement)){
        echo "parent_achievement cannot be null!"; return false;
    }

    if(Check::notInt($parent_achievement)){
        echo "parent_achievement was invalid!"; return false;
    }

    return true;
}



function checkChildAchievement($child_achievement){
    //Not allowed to be null
    if(Check::isNull($child_achievement)){
        echo "child_achievement cannot be null!"; return false;
    }

    if(Check::notInt($child_achievement)){
        echo "child_achievement was invalid!"; return false;
    }

    return true;
}



function checkCount($count){
    //Not allowed to be null
    if(Check::isNull($count)){
        echo "count cannot be null!"; return false;
    }

    if(Check::notInt($count)){
        echo "count was invalid!"; return false;
    }

    return true;
}



}//close class

?>
