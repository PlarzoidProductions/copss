<?php

    require_once("classes/page.php");
    require_once("classes/db_achievements.php");
    require_once("classes/db_meta_achievement_criteria.php");
    require_once("classes/db_game_systems.php");
    require_once("classes/db_game_system_factions.php");
    require_once("classes/db_game_sizes.php");
    require_once("classes/db_events.php");

    $page = new Page("ADMIN");

    $ach_db = new Achievements();
    $meta_db = new Meta_achievement_criteria();

    /***************************************

    Extract defaults if we're editing

    ***************************************/
    $action = $_REQUEST[action];
    if($action){
        $ach_id = $_REQUEST[ach_id];
        if(Check::notInt($ach_id)){
            $error = "Invalid Achievement ID for editing!";
        }
    
        if(empty($error)){
            if($action == "edit_ach"){
                $defaults = $ach_db->getById($ach_id);

                if($defaults){
                    $defaults = $defaults[0];
                }
            }

            if($action == "delete"){
                $ach_db->deleteByColumns(array("id"=>$ach_id));
            }
        }
    } else {
        $defaults = array("points"=>"0", "game_count"=>"0");
    }

    
    /***************************************

    Register some inputs

    ***************************************/

    $page->register("ach_id", "hidden", array("value"=>$ach_id));
    $page->register("name", "textbox", array("required"=>true, "default_val"=>$defaults[name]));
    $page->register("points", "number", array(  "min"=>0, "max"=>100, "step"=>1, "required"=>true, 
                                                "default_val"=>intVal($defaults[points])));
    $page->register("per_game", "select", array("get_choices_array_func"=>"getYesNoChoices",
                                                "get_choices_array_func_args"=>array("Yes"),
                                                "default_val"=>$defaults[per_game])); 
    $page->register("game_system", "select", array( "get_choices_array_func"=>"getGameSystems",
                                                    "get_choices_array_func_args"=>array(),
                                                    "default_val"=>$defaults[game_system_id]));
    $page->register("ach_type", "select", array(    "get_choices_array_func"=>"getAchievementTypes",
                                                    "get_choices_array_func_args"=>array(),
                                                    "default_val"=>$defaults[is_meta],
                                                    "reloading"=>1, "label"=>"Achievement Type"));
    
    $page->getChoices();

    $parent_game_system = $page->getVar("game_system");
    if(empty($parent_game_system))$parent_game_system=1;

    $page->register("game_count", "number", array("min"=>0, "max"=>10000, "step"=>1, "default_val"=>$defaults[game_count]));
    $page->register("game_size", "select", array(   "get_choices_array_func"=>"getGameSizes",
                                                    "get_choices_array_func_args"=>array($parent_game_system),
                                                    "default_val"=>$defaults[game_size_id]));
    $page->register("faction", "select", array( "label"=>"Played Against Faction",
                                                "get_choices_array_func"=>"getGameSystemFactions",
                                                "get_choices_array_func_args"=>array($parent_game_system),
                                                "default_val"=>$defaults[faction_id]));
    $page->register("unique_opponent", "checkbox", array(   "on_text"=>"Required", "off_text"=>"", 
                                                            "default_val"=>$defaults[unique_opponent]));
    $page->register("unique_opponent_location", "checkbox", array(  "on_text"=>"Required", "off_text"=>"",
                                                                    "default_val"=>$defaults[unique_opponent_locations]));
    $page->register("played_theme_force", "checkbox", array("on_text"=>"Required", "off_text"=>"", 
                                                            "default_val"=>$defaults[played_theme_force]));
    $page->register("played_fully_painted", "checkbox", array(  "on_text"=>"Required", "off_text"=>"",
                                                                "default_val"=>$defaults[fully_painted]));
    $page->register("fully_painted_battle", "checkbox", array(  "on_text"=>"Required", "off_text"=>"",
                                                                "default_val"=>$defaults[fully_painted_battle]));
    $page->register("played_scenario", "checkbox", array("label"=>"Played on a Scenario Table",  
                                                        "on_text"=>"Required", "off_text"=>"",
                                                        "default_val"=>$defaults[played_scenario]));
    $page->register("multiplayer", "checkbox", array("label"=>"Multiplayer Game",
                                                        "on_text"=>"Required", "off_text"=>"",
                                                        "default_val"=>$defaults[multiplayer]));
    $page->register("completed_event", "select", array( "get_choices_array_func"=>"getEvents",
                                                        "get_choices_array_func_args"=>array(),
                                                        "default_val"=>$defaults[event_id]));
    $page->register("submit_ach", "submit", array("value"=>"Submit"));

    $page->getChoices();


    //Register stuffs for children
    $children = $ach_db->getByGameSystemId($parent_game_system);

    foreach($children as $child){

        if($child[is_meta]) continue;

        $exists=false;
        if($defaults[id]){
            $exists = $meta_db->queryByColumns( array(  "parent_achievement"=>$defaults[id], 
                                                        "child_achievement"=>$child[id]));
        }

        $cnt=0;
        if($exists){
            $cnt = $exists[0]["count"];
        }
        $page->register("child_".$child[id]."_count", "number", 
                        array(  "label"=>"Qty ".$child[name]." (".$child[points].") Required",
                                "min"=>0, "max"=>1000, "step"=>1, "default_val"=>$cnt));
    }
    

    /***************************************

    Listen for the click

    ***************************************/

    if($page->submitIsSet("submit_ach")){

        //Retrieve the vars
        $ach_id = $page->getVar("ach_id");
        $name = $page->getVar("name");
        $points = $page->getVar("points");
        $per_game = $page->getVar("per_game");
        $game_system = $page->getVar("game_system");
        $is_meta = $page->getVar("ach_type");
        $game_count = $page->getVar("game_count");
        $game_size = $page->getVar("game_size");
        $faction = $page->getVar("faction");
        $unique_opponent = $page->getVar("unique_opponent");
        $unique_opponent_location = $page->getVar("unique_opponent_location");
        $played_theme_force = $page->getVar("played_theme_force");
        $played_fully_painted = $page->getVar("played_fully_painted");
        $fully_painted_battle = $page->getVar("fully_painted_battle");
        $played_scenario = $page->getvar("played_scenario");
        $multiplayer = $page->getvar("multiplayer");
        $completed_event = $page->getVar("completed_event");
       
        //Update vs Create
        if($ach_id){
            $columns = array("name"=>$name,
                            "points"=>$points,
                            "per_game"=>$per_game,
                            "is_meta"=>$is_meta,
                            "game_count"=>$game_count,
                            "unique_opponent"=>$unique_opponent,
                            "unique_opponent_locations"=>$unique_opponent_location,
                            "played_theme_force"=>$played_theme_force,
                            "fully_painted"=>$played_fully_painted,
                            "fully_painted_battle"=>$fully_painted_battle,
                            "played_scenario"=>$played_scenario,
                            "multiplayer"=>$multiplayer
                        );
            
            //handle references
            if($game_system){
                $columns["game_system_id"]=$game_system;
            } else {
                $columns["game_system_id"]=null;
            }

            if($game_size){
                $columns["game_size_id"] = $game_size;
            } else {
                $columns["game_size_id"] = null;
            }

            if($faction){
                $columns["faction_id"] = $faction;
            } else {
                $columns["faction_id"] = null;
            }

            if($completed_event){
                $columns["event_id"] = $completed_event;
            } else {
                $columns["event_id"] = null;
            }

            $result = $ach_db->updateAchievementsById($ach_id, $columns);
        
        } else {
            $result = $ach_db->create($name, $points, $per_game, $is_meta, $game_count, $game_system, $game_size,
                                $faction, $unique_opponent, $unique_opponent_location, $played_theme_force,
                                $played_fully_painted, $fully_painted_battle, $played_scenario, $multiplayer, $completed_event);
        }

    
        //Handle children stuffs
        if($is_meta){

            //Determine parent ID
            if($ach_id){
                $parent = $ach_id;
            } else {
                $parent = $result;
            }
        
            //Loop through the children
            foreach($children as $child){

                if($child[is_meta]) continue;

                $count = $page->getVar("child_".$child[id]."_count");
                $exists = $meta_db->queryByColumns(array(   "parent_achievement"=>$parent,
                                                            "child_achievement"=>$child[id]));

                if($exists){
                    $meta_db->updateById($child[id], array("count"=>$count));
                } else {
                    if($count > 0){
                        $meta_db->create($parent, $child[id], $count);
                    }
                }
            }
        }

    }//if submitted


    /*************************************

    Prep the list of Achievements

    *************************************/
    $achievements = $ach_db->getAll();
    
    if(!empty($achievements)){
    
        $gsys = new Game_systems();
        $gsz = new Game_sizes();
        $factions = new Game_system_factions();
        $events = new Events();

        //references
        foreach ($achievements as $key=>$ach) {
            if($ach[game_system_id]){
                $system = $gsys->getById($ach[game_system_id]);
                $achievements[$key][game_system_name] = $system[0][name];
            }

            if($ach[game_size_id]){
                $size = $gsz->getById($ach[game_size_id]);
                $achievements[$key][game_size] = $size[0][size];
            }

            if($ach[faction_id]){
                $faction = $factions->getById($ach[faction_id]);
                $achievements[$key][faction] = $faction[0][name];
            }

            if($ach[event_id]){
                $event = $events->getById($ach[event_id]);
                $achievements[$key][event_name] = $event[0][name];
            }


            //Styling
            if($i){
                $achievements[$key][style] = "odd";
            } else {
                $achievements[$key][style] = "even";
            }
            $i = !$i;
        }
    }


    /**************************************

    Create and Show the Page

    **************************************/

    //If the user submitted something
    if($page->submitIsSet("submit_ach") && ($result != false)){ 

        if($ach_id){
            $success_str = "Successfully modified $name";
        } else {
            $success_str = "Successfully added $name";
        }
        $link = array("href"=>"home.php?view=achievement_config", "text"=>"Make Another Achievement?");
        $template = "templates/success.html"; 

    //... otherwise
    } else {

        if($defaults[id]){
            $is_meta = $defaults[is_meta];
        } else {
            $is_meta = $page->getVar("ach_type");
        }

        //Build the Inputs Array
        $inputs = array("ach_id", "name", "points", "per_game", "game_system", "ach_type");
        if($is_meta){
            foreach($children as $child){
                if($child[is_meta]) continue;
                $inputs[] = "child_".$child[id]."_count";
            }
        } else {
            $inputs = array_merge($inputs, array("game_count", "game_size", "faction",
                            "unique_opponent", "unique_opponent_location", "played_theme_force", 
                            "played_fully_painted", "fully_painted_battle", "played_scenario",
                            "multiplayer", "completed_event")
                        );
        
        }
        $inputs[] = "submit_ach";

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
    include("templates/achievement_listing.html");
    $page->displayFooter();
?>
