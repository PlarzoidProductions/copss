<?php

//  $page is already ready for us
require_once("classes/db_game_systems.php");

/**************************************

Edit Selector

**************************************/
$page->register("edit_submit", "submit", array("value"=>"Select for Editing"));
$page->register("edit_select", "select", array( "label"=>"Edit a Game System",
                                                "get_choices_array_func"=>"getGameSystems",
                                                "get_choices_array_func_args"=>array()));
$page->getChoices();
$selected = $page->getVar("edit_select");

$inputs = array("edit_select", "edit_submit");


/**************************************

Retrieve defaults accordingly

**************************************/
if($page->submitIsSet("edit_submit")){
    $db = new Game_systems();

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
$subtitle = "Add/Edit Game Systems";
if($defaults[id]){
    $subtitle2 = "Edit Game System '".$defaults[name]."'";
} else {
    $subtitle2 = "Add New Game System";
}


/***************************************

Process the addition / edit

***************************************/
if($page->submitIsSet("submit_config")){
   
    $db = new Game_systems();

    $edit_id = $page->getVar("edit_id");
    $name = $page->getVar("name");

    if(Check::isNull($edit_id)){
        $exists = $db->getByName($name);
        if(empty($exists)){
            $result = $db->create($name);
        }
    } else {
        $columns = array("name"=>$name);
        $result = $db->updateGame_systemsById($edit_id, $columns);
    }

}


/***************************************

Display the special section

***************************************/

include("templates/configure_section.html");

?>
