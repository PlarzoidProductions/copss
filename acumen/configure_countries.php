<?php

//  $page is already ready for us
require_once("classes/db_countries.php");

/**************************************

Edit Selector

**************************************/
$page->register("edit_submit", "submit", array("value"=>"Select for Editing"));
$page->register("delete_selected", "submit", array("value"=>"Delete Selected Item"));
$page->register("edit_select", "select", array( "label"=>"Edit a Country",
                                                "get_choices_array_func"=>"getCountries",
                                                "get_choices_array_func_args"=>array()));
$page->getChoices();
$selected = $page->getVar("edit_select");

$inputs = array("edit_select", "edit_submit", "delete_selected");


/**************************************

Handle the delete

**************************************/
if($page->submitIsSet("delete_selected")){
    $db = new Countries();

	$country = $db->getById($selected);	

	try{
    	$result = $db->deleteByColumns(array("id"=>$selected));
	} catch(PDOException $e){
        if(($e->errorInfo[1]+0) == 1451){
            $error = "Unable to delete '".$country[0]["name"]."', a State or Player references it.";
        }
    }

}

/**************************************

Retrieve defaults accordingly

**************************************/
if($page->submitIsSet("edit_submit")){
    $db = new Countries();

    $defaults = $db->getById($selected);

    if($defaults){
        $defaults = $defaults[0];
    }
} else {
    $defaults = array();
}


/**************************************

Editable field(s)

**************************************/
$page->register("name", "textbox", array("default_val"=>$defaults[name]));

$page->register("edit_id", "hidden", array("value"=>$defaults[id]));
$page->register("submit_config", "submit", array("value"=>"Submit"));


/**************************************

Prep displaying the page

**************************************/
$inputs2 = array("edit_id", "name", "submit_config");
$subtitle = "Add/Edit Countries";
if($defaults[id]){
    $subtitle2 = "Edit Country '".$defaults[name]."'";
} else {
    $subtitle2 = "Add New Country";
}


/***************************************

Process the addition / edit

***************************************/
if($page->submitIsSet("submit_config")){
   
    $db = new Countries();

    $edit_id = $page->getVar("edit_id");
    $name = $page->getVar("name");

	if(strlen($name) < 3){
		$error = "Country Name must be at least 3 characters!";
	}

	if(empty($error)){
	    if(Check::isNull($edit_id)){
    	    $exists = $db->getByName($name);
        	if(empty($exists)){
            	$result = $db->create($name);
	        }
    	} else {
        	$columns = array("name"=>$name);
	        $result = $db->updateCountriesById($edit_id, $columns);
    	}
	}
}


/***************************************

Display the special section

***************************************/

include("templates/configure_section.html");


?>
