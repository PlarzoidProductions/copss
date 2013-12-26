<?php

//  $page is already ready for us
require_once("classes/db_countries.php");

/**************************************

Edit Selector

**************************************/
$page->register("edit_submit", "submit", array("value"=>"Select for Editing"));
$page->register("edit_select", "select", array( "label"=>"Edit a Country",
                                                "get_choices_array_func"=>"getCountries",
                                                "get_choices_array_func_args"=>array()));
$page->getChoices();
$selected = $page->getVar("edit_select");


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
$inputs = array("edit_id", "name", "submit_config");
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

    if(Check::isNull($edit_id)){
        $exists = $db->getByName($name);
        if($exists != false){
            $result = $db->create($name);
        }
    } else {
        $columns = array("name"=>$name);
        $result = $db->updateCountriesById($edit_id, $columns);
    }

}


/***************************************

Display the special section

***************************************/

include("templates/configure_section.html");


?>
