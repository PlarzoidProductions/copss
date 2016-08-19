<?php

/**************************************************
*
*    Prizes Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
*	id - INT - PRIMARY KEY
*	description - VARCHAR
*	cost - INT
*
**************************************************/
require_once("query.php");

class Prizes {

var $db=NULL;
var $table="prizes";


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
public function create($description, $cost){

	//Validate the inputs
	$description = $this->filterDescription($description); if($description === false){return false;}
	$cost = $this->filterCost($cost); if($cost === false){return false;}

	//Create the values Array
	$values = array(
		":description"=>$description,
 		":cost"=>$cost
	);

	//Build the query
	$sql = "INSERT INTO $this->table (
				description,
				cost
			) VALUES (
				:description,
				:cost)";

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
public function updatePrizesById($id, $columns){

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


public function getByDescription($description){
	
    //Validate Inputs
    $description = $this->filterDescription($description); if($description === false){return false;}

    return $this->queryByColumns(array("description"=>$description));
}


public function getByCost($cost){
	
    //Validate Inputs
    $cost = $this->filterCost($cost); if($cost === false){return false;}

    return $this->queryByColumns(array("cost"=>$cost));
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



function filterDescription($description){
    //Not allowed to be null
    if(Check::isNull($description)){
        echo "description cannot be null!"; return false;
    }

    if(Check::notString($description)){
        echo "description was invalid!"; return false;
    }

    return $description;
}



function filterCost($cost){
    //Not allowed to be null
    if(Check::isNull($cost)){
        echo "cost cannot be null!"; return false;
    }

    if(Check::notInt($cost)){
        echo "cost was invalid!"; return false;
    }

    return intVal($cost);
}



}//close class

?>
