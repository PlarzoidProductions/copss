<?php

//  $page is already ready for us
require_once("classes/db_game_systems.php");
require_once("classes/db_game_system_factions.php");

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

$page->register("edit_select", "select", array( "label"=>"Edit a Faction",
                                                "get_choices_array_func"=>"getGameSystemFactions",
                                                "get_choices_array_func_args"=>array($selected_parent)));
$page->getChoices();
$selected = $page->getVar("edit_select");

if(Check::isNull($selected_parent)){
    $selected_parent=1;
}

$page->register("edit_submit", "submit", array("value"=>"Select for Editing"));

$inputs = array("parent", "edit_select", "edit_submit");


/**************************************

Retrieve defaults accordingly

**************************************/
if($page->submitIsSet("edit_submit") && !Check::isNull($selected)){

    $db = new Game_system_factions();

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
$page->register("acronym", "textbox", array("default_val"=>$defaults[acronym]));

$page->register("edit_id", "hidden", array("value"=>$defaults[id]));
$page->register("submit_config", "submit", array("value"=>"Submit"));


/**************************************

Prep displaying the page

**************************************/
$inputs2 = array("edit_id", "name", "acronym", "submit_config");
$subtitle = "Add/Edit Factions";

$gs_db = new Game_systems();
$system = $gs_db->getById($selected_parent);

if($defaults[id]){
    $subtitle2 = "Edit Faction '".$defaults[name]."'";
} else {
    $subtitle2 = "Add New Faction to ".$system[0][name];
}


/***************************************

Process the addition / edit

***************************************/
if($page->submitIsSet("submit_config")){

    $db = new Game_system_factions();

    $edit_id = $page->getVar("edit_id");
    $name = $page->getVar("name");
    $acronym = $page->getVar("acronym");

    if(Check::isNull($edit_id)){
        $exists = $db->getByName($name);
        if(empty($exists)){
            $result = $db->create($selected_parent, $name, $acronym);
        }
    } else {
        $columns = array("name"=>$name, "acronym"=>$acronym);
        $result = $db->updateGame_system_factionsById($edit_id, $columns);
    }

}


/***************************************

Display the special section

***************************************/

include("templates/configure_section.html");


?>
