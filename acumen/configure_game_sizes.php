<?php

//  $page is already ready for us
require_once("classes/db_game_systems.php");
require_once("classes/db_game_sizes.php");

/**************************************

Edit Selector

**************************************/
$page->register("parent", "select", array(  "label"=>"Parent Game System", "reloading"=>1,
                                                    "get_choices_array_func"=>"getGameSystems",
                                                    "get_choices_array_func_args"=>array()));
$page->getChoices();
$selected_parent = $page->getVar("parent");

if(Check::isNull($selected_parent)){
    $selected_parent=1;
}

$page->register("edit_select", "select", array( "label"=>"Edit a Game Size",
                                                "get_choices_array_func"=>"getGameSizes",
                                                "get_choices_array_func_args"=>array($selected_parent)));
$page->getChoices();
$selected = $page->getVar("edit_select");

if(Check::isNull($selected_parent)){
    $selected_parent=1;
}

$page->register("edit_submit", "submit", array("value"=>"Select for Editing"));
$page->register("delete_selected", "submit", array("value"=>"Delete Selected Game Size"));

$inputs = array("parent", "edit_select", "edit_submit", "delete_selected");


/**************************************

Handle the delete

**************************************/
if($page->submitIsSet("delete_selected") && !Check::isNull($selected)){
    $db = new Game_sizes();

	$size = $db->getById($selected);

	try{
    	$result = $db->deleteByColumns(array("id"=>$selected));
	} catch(PDOException $e){
        if(($e->errorInfo[1]+0) == 1451){
            $error = "Unable to delete '".$size[0]["size"]."', an Achievement or Reported Game references it.";
        }
    }

}


/**************************************

Retrieve defaults accordingly

**************************************/
if($page->submitIsSet("edit_submit") && !Check::isNull($selected)){

    $db = new Game_sizes();

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
$page->register("size", "number", array("default_val"=>$defaults[size],
                                        "min"=>1, "max"=>10000, "step"=>1));
$page->register("name", "textbox", array("default_val"=>$defaults[name]));

$page->register("edit_id", "hidden", array("value"=>$defaults[id]));
$page->register("submit_config", "submit", array("value"=>"Submit"));


/**************************************

Prep displaying the page

**************************************/
$inputs2 = array("edit_id", "size", "name", "submit_config");
$subtitle = "Add/Edit Games Sizes";

$gs_db = new Game_systems();
$system = $gs_db->getById($selected_parent);

if($defaults[id]){
    $subtitle2 = "Edit Game Size '".$defaults[size]." (".$defaults[name].")'";
} else {
    $subtitle2 = "Add New Game Size to ".$system[0][name];
}


/***************************************

Process the addition / edit

***************************************/
if($page->submitIsSet("submit_config")){

    $db = new Game_sizes();

    $edit_id = $page->getVar("edit_id");
    $size = $page->getVar("size");
    $name = $page->getVar("name");

	if(strlen($size) == 0)){
		$error = "Size cannot be blank!";
	}

	if(empty($error)){
	    if(Check::isNull($edit_id)){
    	    $exists = $db->getBySize($size);
        	if(empty($exists)){
            	$result = $db->create($selected_parent, $size, $name);
	        }
    	} else {
        	$columns = array("name"=>$name, "acronym"=>$acronym);
	        $result = $db->updateGame_sizesById($edit_id, $columns);
    	}
	}
}


/***************************************

Display the special section

***************************************/

include("templates/configure_section.html");


?>
