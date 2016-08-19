<?php

/**************************************************
*
*    Feedback Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	type - VARCHAR
*	comment - LONGTEXT
*
**************************************************/
require_once("query.php");

class Feedback {

var $db=NULL;
var $table="feedback";


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
public function create($type, $comment){

	//Validate the inputs
	$type = $this->filterType($type); if($type === false){return false;}
	$comment = $this->filterComment($comment); if($comment === false){return false;}

	//Create the values Array
	$values = array(
		":type"=>$type,
 		":comment"=>$comment
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				type,
				comment
			) VALUES (
				:type,
				:comment)";

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
public function updateFeedbackById($id, $columns){

    //Values Array
    $values = array(":id"=>$id);
    foreach($columns as $column=>$value){
        $values[":".$column]=$value;
    }

    //Generate the query
    $sql = "UPDATE $this->table SET ";
    $keys = array_keys($columns);
    foreach($keys as $column){
        $sql.= "$column=:$column";
        if(strcmp($column, end($keys))){
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


public function getByType($type){
	
    //Validate Inputs
    $type = $this->filterType($type); if($type === false){return false;}

    return $this->queryByColumns(array("type"=>$type));
}


public function getByComment($comment){
	
    //Validate Inputs
    $comment = $this->filterComment($comment); if($comment === false){return false;}

    return $this->queryByColumns(array("comment"=>$comment));
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



function filterType($type){
    //Not allowed to be null
    if(Check::isNull($type)){
        echo "type cannot be null!"; return false;
    }

    if(Check::notString($type)){
        echo "type was invalid!"; return false;
    }

    return $type;
}



function filterComment($comment){
    //Not allowed to be null
    if(Check::isNull($comment)){
        echo "comment cannot be null!"; return false;
    }

    if(Check::isNull($comment)){
        echo "comment was invalid!"; return false;
    }

    return $comment;
}



}//close class

?>
