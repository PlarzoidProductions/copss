<?php

    require_once("classes/page.php");
    require_once("classes/db_achievements.php");
    require_once("classes/db_meta_achievement_criteria.php");

    $page = new Page();


    /***************************************

    Extract defaults if we're editing

    ***************************************/
    $action = $_REQUEST[action];
    if($action == "edit_ach"){
        $ach_id = $_REQUEST[ach_id];
        if(Check::notInt($$ach_id)){
            //TODO Handle error
            $error = "Invalid Achievement ID for editing!";
        }

        $ach_db = new Achievements();
        $defaults = $ach_db->getById($ach_id);

        if($defaults){
            $defaults = $defaults[0];
        }
    } else {
        $defaults = array("points"=>"0", "game_count"=>"0");
    }


    /***************************************

    Register some inputs

    ***************************************/

    $page->register("name", "textbox", array("required"=>true, "default_val"=>$defaults[name]));
    $page->register("points", "number", array("min"=>0, "max"=>100, "step"=>1, "required"=>true, "default_val"=>$defaults[points]));
    $page->register("per_game", "select", array("get_choices_array_func"=>"getYesNoChoices",
                                                "get_choices_array_func_args"=>array("Yes"),
                                                "default_val"=>$defaults[per_game])); 
    $page->register("game_system", "select", array( "get_choices_array_func"=>"getGameSystems",
                                                    "get_choices_array_func_args"=>array(),
                                                    "default_val"=>$defaults[game_system_id]));
    $page->getChoices();

    $parent_game_system = $page->getVar("game_system");
    if(empty($parent_game_system))$parent_game_system=1;
    
    $page->register("game_count", "number", array("min"=>0, "max"=>10000, "step"=>1, "default_val"=>$defaults[game_count]));
    $page->register("game_size", "select", array(   "get_choices_array_func"=>"getGameSizes",
                                                    "get_choices_array_func_args"=>array($parent_game_system),
                                                    "default_val"=>$defaults[game_size_id]));
    $page->register("faction", "select", array("get_choices_array_func"=>"getGameSystemFactions",
                                                "get_choices_array_func_args"=>array($parent_game_system),
                                                "default_val"=>$defaults[faction_id]));
    $page->register("unique_opponent", "checkbox", array(   "on_text"=>"Required", "off_text"=>"", 
                                                            "default_val"=>$defaults[unique_opponent]));
    $page->register("unique_opponent_location", "checkbox", array(  "on_text"=>"Required", "off_text"=>"",
                                                                    "default_val"=>$defaults[unique_opponent_location]));
    $page->register("played_theme_force", "checkbox", array("on_text"=>"Required", "off_text"=>"", 
                                                            "default_val"=>$defaults[played_theme_force]));
    $page->register("played_fully_painted", "checkbox", array(  "on_text"=>"Required", "off_text"=>"",
                                                                "default_val"=>$defaults[fully_painted]));
    $page->register("fully_painted_battle", "checkbox", array(  "on_text"=>"Required", "off_text"=>"",
                                                                "default_val"=>$defaults[fully_painted_battle]));
    $page->register("completed_event", "select", array( "get_choices_array_func"=>"getGameSystemEvents",
                                                        "get_choices_array_func_args"=>array($parent_game_system),
                                                        "default_val"=>$defaults[event_id]));
    $page->register("submit_ach", "submit", array("value"=>"Submit"));

    $page->getChoices();

    /***************************************

    Listen for the click

    ***************************************/

    if($page->submitIsSet("submit_ach")){

        //Retrieve the vars
        $first = $page->getVar("first_name");
        $last = $page->getVar("last_name");
        $email = $page->getVar("e_mail");
        $country = $page->getVar("country");
        $state = $page->getVar("state");
        $vip = $page->getVar("vip");

        $db = new Players();

        $result = $db->create($first, $last, $email, $country, $state, $vip);
    }


    /**************************************

    Create and Show the Page

    **************************************/
    if($page->submitIsSet("submit_ach") && ($result != false)){
        
    
    } else {
    
        $inputs = array("name", "points", "per_game", "game_system", "game_count", "game_size", "faction",
                        "unique_opponent", "unique_opponent_location", "played_theme_force", 
                        "played_fully_painted", "fully_painted_battle", "completed_event", "submit_ach");
        $page->setDisplayMode("form");
        $template = "templates/default_section.html";
    }
    
    $form_method="post";
    $form_action=$_SERVER[PHP_SELF]."?view=$view";
    $title = "Achievement Configuration";

    //display it
    $page->startTemplate();
    $page->doTabs();
    include $template;
    $page->displayFooter();
?>
