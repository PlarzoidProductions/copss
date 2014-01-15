<?php

require_once("classes/page.php");
require_once("classes/views.php");
require_once("classes/db_prize_redemptions.php");

$page = new Page();
$pr_db = new Prize_redemptions();
$views_db = new Views();


/*********************************************

Handle Edits and Deletes

*********************************************/
$action = $_REQUEST[action];
$selected = $_REQUEST[pr_id];
$defaults = array("cost"=>5);
switch($action){
    case "edit":
        $defaults = $pr_db->getById($selected);
        $defaults = $defaults[0];
        if($defaults[cost] < 0) $defaults[mode] = "ADD";
        break;
    case "delete":
        $result = $pr_db->deleteById($selected);
        if($result){
            $success_str = "Successfully deleted the prize redemption!";
        }
        break;
    default:
        break;
}


/*********************************************

Register the inputs

*********************************************/
$page->register("player", "select", array("reloading"=>1, "default_val"=>$defaults[player_id],
                                          "get_choices_array_func"=>"getPlayerChoices",
                                          "get_choices_array_func_args"=>array()));
$page->register("mode", "select", array("get_choices_array_func"=>"getRedeemFunctionChoices",
                                        "get_choices_array_func_args"=>array(),
                                        "default_val"=>$defaults[mode]));
$page->register("amount", "number", array("min"=>1, "max"=>100, "step"=>1, "default_val"=>$defaults[cost]));
$page->register("description", "textbox", array("placeholder"=>"dice, poster, etc...", 
                                                "default_val"=>$defaults[description]));
$page->register("redeem", "submit", array("value"=>"Submit"));
$page->getChoices();


/********************************************

Gather all the data on the chosen player

********************************************/
if($selected){
    $selected_player = $defaults[player_id];
} else {
    $selected_player = $page->getVar("player");
}

if($selected_player){

    $earned = $views_db->queryByColumns("earned", array("player_id"=>$selected_player));
    $spent = $views_db->queryByColumns("spent", array("player_id"=>$selected_player));

    $available = $earned[0][earned] - $spent[0][spent];
}


/********************************************

Prep the page

********************************************/
if($page->submitIsSet("redeem")){

    $mode = $page->getVar("mode");
    $amount = $page->getVar("amount");
    $desc = $page->getvar("description");

    if(!strcmp($mode, "SPEND")){
        if(intval($amount) > intval($available)){
            $error = "You don't have enough points to buy that!";
        }
    } else {
        $amount = $amount * -1;
    }
     
    if(empty($error)){
        $result = $pr_db->create($selected_player, $amount, $desc);
    }

    if($result){
        if(!strcmp($mode, "SPEND")){
            $success_str = "Successfully redeemed $amount skulls for $desc!";
        } else {
            $success_str = "Successfully added ".($amount*-1)." for $desc!";
        }
    }
}    

//Usual stuff
$form_method = "post";
$form_action = $_SERVER[PHP_SELF]."?view=$view";
$title = "Redeem Skullz!";

//player input handled differently
$inputs = array("mode", "amount", "description", "redeem");

$page->setDisplayMode("form");


/********************************************

Show the page

********************************************/

$page->startTemplate();
$page->doTabs();
include("templates/redeem.html");
$page->displayFooter();


?>
