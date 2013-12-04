<?php

/**************************************************
*
*    Meta_achievements Class
*
***************************************************/
require_once("query.php");

class Meta_achievements {

var $db=NULL;
var $table="meta_achievements";


/***************************************************

Constructor & Destructor

***************************************************/
public function __construct(){
    $this->db = new Query();
}

public function __destruct(){}


/**************************************************

Create Function

**************************************************/
public function createMeta_achievements($child_id, $parent_id, $count){

	//Validate the inputs
	if(!Check::isInt($child_id)){return false;}
	if(!Check::isInt($parent_id)){return false;}
	if(!Check::isInt($count)){return false;}

	//Create the values Array
	$values = array(
		":child_id"=>$child_id,
 		":parent_id"=>$parent_id,
 		":count"=>$count
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				child_id,
				parent_id,
				count
			) VALUES (
				:child_id,
				:parent_id,
				:count)";

	return $this->db->insert($sql, $values);
}


/**************************************************

Delete Function

**************************************************/
public function deleteMeta_achievements($id){

	//Validate the input
	if(Check::isInt($id)){return false;}

	//Create the values array
	$values = array(":id"=>$id);

	//Create Query
	$sql = "DELETE FROM $this->table WHERE id=:id";

	return $this->db->delete($sql, $values);
}


/**************************************************

Query By Column Function(s)

**************************************************/
private function getMeta_achievementsByColumn($column, $value){

	//inputs are pre-verified by the mapping functions below, so we can trust them

	//Values Array
	$values = array(":$column"=>$value);

	//Generate the query
	$sql = "SELECT * FROM $this->table WHERE $column=:$column";
    
	return $this->db->query($sql, $values);
}


public function getMeta_achievementsById($id){
	
	//Validate Inputs
	if(!Check::isInt($id)){return false;}

	return getMeta_achievementsByColumn("id", $id.);
}


public function getMeta_achievementsByChild_id($child_id){
	
	//Validate Inputs
	if(!Check::isInt($child_id)){return false;}

	return getMeta_achievementsByColumn("child_id", $child_id.);
}


public function getMeta_achievementsByParent_id($parent_id){
	
	//Validate Inputs
	if(!Check::isInt($parent_id)){return false;}

	return getMeta_achievementsByColumn("parent_id", $parent_id.);
}


public function getMeta_achievementsByCount($count){
	
	//Validate Inputs
	if(!Check::isInt($count)){return false;}

	return getMeta_achievementsByColumn("count", $count.);
}

}//close class

?>
