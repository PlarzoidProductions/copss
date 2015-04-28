<?php

    require_once("classes/page.php");
    require_once("classes/db_tournaments.php");
	require_once("classes/db_game_systems.php");

    $page = new Page();
	$t_db = new Tournaments();
	$gs_db = new Game_Systems();

    /***************************************

    Handle Edits

    ***************************************/
    $action = $_REQUEST["action"];
    $t_id = $_REQUEST["t_id"];
   
    if(!strcmp($action, "edit")){
        $defaults = $t_db->getById($t_id);
        $defaults = $defaults[0];
    } else {
		$defaults = array(
			"game_system_id"=>1,
			"max_num_players"=>32,
			"max_num_rounds"=>5,
			"num_lists_required"=>1,
			"divide_and_conquer"=>0,
			"final_tables"=>true,
			"large_event_scoring"=>true
		);
	}

    /***************************************

    Register some inputs

    ***************************************/

    //store the fact we're editing
    $page->register("edit_id", "hidden", array("default_val"=>$t_id));

	$page->register("tournament_name", "textbox", array("required"=>true, "default_val"=>$defaults[name]));

    $page->register("game_system", "select", array( "get_choices_array_func"=>"getGameSystems", "default_val"=>$defaults[game_system_id]));

	$page->register("number_of_players", "number", array("min"=>8, "max"=>256, "step"=>2, "default_val"=>$defaults[max_num_players]));
	$page->register("number_of_rounds", "number", array("min"=>3, "max"=>8, "step"=>1, "default_val"=>$defaults[max_num_rounds]));
	$page->register("number_of_lists_required", "number", array("min"=>1, "max"=>3, "step"=>1, "default_val"=>$defaults[num_lists_required]));
    $page->register("dnc", "number", array("label"=>"Divide &amp; Conquer", "min"=>0, "max"=>3, "step"=>1, "default_val"=>$defaults[divide_and_conquer]));
	$page->register("final_standings_type", "select", array( "get_choices_array_func"=>"getTournamentStandingsTypes", "default_val"=>$defaults[standings_type]));
	
	$page->register("final_tables", "checkbox", array("on_text"=>"YES", "off_text"=>"NO",
	                                             "default_val"=>$defaults[final_tables]));
	$page->register("large_event_scoring", "checkbox", array("on_text"=>"YES", "off_text"=>"NO",
												 "default_val"=>$defaults[large_event_scoring]));



    //retrieve the fact that we're editing
    if(empty($t_id)) $t_id = $page->getvar("edit_id");

    if($t_id){
		//set the defaults
		$defaults = $t_db->getById($t_id);
		$defaults = $defaults[0];
        $page->register("submit", "submit", array("value"=>"Update!"));
    } else {
        $page->register("submit", "submit", array("value"=>"Submit!"));
    }

    $page->getChoices();

    /***************************************

    Listen for the click

    ***************************************/

    if($page->submitIsSet("submit")){

        //Retrieve the vars
        $name = $page->getVar("tournament_name");
        $game_system_id = $page->getVar("game_system");
        $num_players = $page->getVar("number_of_players");
        $num_rounds = $page->getVar("number_of_rounds");
        $num_lists = $page->getVar("number_of_lists_required");
		$dnc = $page->getVar("dnc");
		if(empty($dnc)) $dnc=0;
		$standings_type = $page->getVar("final_standings_type");
		$final_tables = $page->getVar("final_tables");
		if(empty($final_tables)) $final_tables=0;
		$large_event_scoring = $page->getVar("large_event_scoring");
		if(empty($large_event_scoring)) $large_event_scoring=0;
        

		//If we're editing, look for changes and duplicates
        if($t_id){

			$nameChars = "a-zA-Z0-9' -";
            if(strlen($name) == 0){
                $error = "Tournament must have a name!";
            } else 
            if(!preg_match("~^[$nameChars]+$~", $name)){
                $illegalChars = preg_replace("~[$nameChars]~", "", $name);
                $error = "Tournament Name contains invalid character(s): '$illegalChars'!";
            } else {

				if(strcmp($name, $defaults[name])){ 

                	$exists = $t_db->existsByColumns(array("name"=>$name));

                	if($exists){
                    	$error = "Tournament with that name already exists!";
                	}
				}
            }

            if(empty($error)){
				$columns = array("name"=>$name,
                                 "game_system_id"=>$game_system,
                                 "max_num_players"=>$num_players,
                                 "max_num_rounds"=>$num_rounds,
                                 "num_lists_required"=>$num_lists,
								 "divide_and_conquer"=>$dnc,
								 "standings_type"=>$standings_type,
								 "final_tables"=>$final_tables,
								 "large_event_scoring"=>$large_event_scoring
								);

                $result = $t_db->updateTournamentsById($t_id, $columns);
            }
        } else {
			$nameChars = "a-zA-Z0-9' -";
			if(strlen($name) == 0){
				$error = "Tournament must have a name!";
			} else 
            if(!preg_match("~^[$nameChars]+$~", $name)){
                $illegalChars = preg_replace("~[$nameChars]~", "", $name);
                $error = "Tournament Name contains invalid character(s): '$illegalChars'!";
            } else {

                $exists = $t_db->existsByColumns(array("name"=>$name));

                if($exists){
                    $error = "Tournament with that name already exists!";
                }
            }


            if(empty($error))
                $result = $t_db->create($name, $game_system, $num_players, $num_rounds, $num_lists, $dnc, $standings_type, $final_tables, $large_event_scoring);
        }
    }


    /**************************************

    Create and Show the Page

    **************************************/
    if($page->submitIsSet("submit") && ($result != false)){
        
        //Build the rest of string
        if($t_id){
            $success_str = "Updated ";
        } else {
            $success_str = "Created ";
        } 
        $success_str.= "tournament: $name!";

        $page->setDisplayMode("text");
        $link = array("href"=>"home.php?view=tournament_config", "text"=>"Configure Another Tournament?");
        $template = "templates/success.html";
    
    } else {
    
        $inputs = array("edit_id", "tournament_name", "game_system", "number_of_players", "number_of_rounds", "number_of_lists_required", 
						"dnc", "final_standings_type", "final_tables", "large_event_scoring","submit");
        $page->setDisplayMode("form");
        $template = "templates/default_section.html";
    }
    
    $form_method="post";
    $form_action=$_SERVER[PHP_SELF]."?view=$view";
    $title = "Tournament Configuration";

	//Get a listing of everything
	$tournaments = $t_db->getAll();

	$raw_game_systems = $gs_db->getAll();
	$game_systems = array();
	foreach($raw_game_systems as $gs){
		$game_systems[$gs['id']]=$gs['name'];
	}

	$odd=true;
	for($i=0; $i<count($tournaments); $i++){
		if($odd) $tournaments[$i][style]="odd";
		$odd = !$odd;

		$tournaments[$i]['game_system_name']=$game_systems[$tournaments[$i]["game_system_id"]];
	}
    //display it
    $page->startTemplate();
    $page->doTabs();
    include $template;
	include "templates/tournament_listing.html";
    $page->displayFooter();
?>
