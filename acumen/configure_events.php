<?php

//  $page is already ready for us
require_once("classes/db_events.php");


/**************************************

Edit Selector

**************************************/
$page->register("edit_submit", "submit", array("value"=>"Select for Editing"));
$page->register("delete_selected", "submit", array("value"=>"Delete Selected Item"));
$page->register("edit_select", "select", array( "label"=>"Edit an Event",
                                                "get_choices_array_func"=>"getEvents",
                                                "get_choices_array_func_args"=>array()));
$page->getChoices();
$selected = $page->getVar("edit_select");

$inputs = array("edit_select", "edit_submit", "delete_selected");


/**************************************

Handle the delete

**************************************/
if($page->submitIsSet("delete_selected")){
    $db = new Events();

	try{
    	$result = $db->deleteById($selected);
	} catch(PDOException $e){
		if(($e->errorInfo[1]+0) == 1451){
			$error = "Unable to delete that event, an Achievement references it.";
		}
	}
}

/**************************************

Retrieve defaults accordingly

**************************************/
if($page->submitIsSet("edit_submit")){
    $db = new Events();

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
$subtitle = "Add/Edit Events";
if($defaults[id]){
    $subtitle2 = "Edit Event '".$defaults[name]."'";
} else {
    $subtitle2 = "Add New Event";
}


/***************************************

Process the addition / edit

***************************************/
if($page->submitIsSet("submit_config")){
   
    $db = new Events();

    $edit_id = $page->getVar("edit_id");
    $name = $page->getVar("name");

	if(strlen($name) < 5){
		$error = "Event name must be at least 5 characters!";
	}

	if(empty($error)){
    	if(Check::isNull($edit_id)){
        	$exists = $db->getByName($name);
        	if(empty($exists)){
            	$result = $db->create($name);
        	}
    	} else {
        	$columns = array("name"=>$name);
        	$result = $db->updateEventsById($edit_id, $columns);
    	}
	}

}


/***************************************

Display the special section

***************************************/

include("templates/configure_section.html");


?>
