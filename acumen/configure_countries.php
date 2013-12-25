<?php

//  $page is already ready for us
require_once("classes/db_countries.php");

/**************************************

Edit Selector

**************************************/
$page->register("edit_submit", "submit", array("value"=>"Edit");
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

Editable fields

**************************************/

$page->register("name", "text", array("default_val"=>$defaults[0][name]));
$page->register("submit", "submit", array());

$inputs = array("name", "submit");

include("templates/configure_section.html");

?>
