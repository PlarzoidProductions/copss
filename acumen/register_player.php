<?php

    require_once("classes/page.php");
    require_once("classes/db_players.php");
    require_once("classes/db_states.php");
    require_once("classes/db_countries.php");

    $page = new Page();

    /***************************************

    Handle Edits

    ***************************************/
    $action = $_REQUEST["action"];
    $pl_id = $_REQUEST["pl_id"];
    $defaults = array("country_id"=>1);
   
    if(!strcmp($action, "edit")){
        $p_db = new Players();
        $defaults = $p_db->getById($pl_id);
        $defaults = $defaults[0];
    }

    /***************************************

    Register some inputs

    ***************************************/

    //store the fact we're editing
    $page->register("edit_id", "hidden", array("value"=>$pl_id));
    $page->register("first_name", "textbox", array("required"=>true, "default_val"=>$defaults[first_name]));
    $page->register("last_name", "textbox", array("required"=>true, "default_val"=>$defaults[last_name]));
    $page->register("e_mail", "email", array("label"=>"eMail", "default_val"=>$defaults[email]));

    $page->register("country", "select", array( "get_choices_array_func"=>"getCountries", 
                                                "reloading"=>1, "default_val"=>$defaults[country]));

    $country_id=$page->getVar("country");
    if(empty($country_id)) $country_id=$defaults[country];
    $page->register("state", "select", array(   "get_choices_array_func"=>"getStates",
                                                "get_choices_array_func_args"=>array($country_id),
                                                "default_val"=>$defaults[state]));

    $page->register("vip", "checkbox", array("on_text"=>"VIP", "off_text"=>"", 
                                             "default_val"=>$defaults[vip]));

    //retrieve the fact that we're editing
    if(empty($pl_id)) $pl_id = $page->getvar("edit_id");

    if($pl_id){
        $page->register("register", "submit", array("value"=>"Update!"));
    } else {
        $page->register("register", "submit", array("value"=>"Register!"));
    }
    $page->getChoices();


    /***************************************

    Listen for the click

    ***************************************/

    if($page->submitIsSet("register")){

        //Retrieve the vars
        $first = $page->getVar("first_name");
        $last = $page->getVar("last_name");
        $email = $page->getVar("e_mail");
        $country = $page->getVar("country");
        $state = $page->getVar("state");
        $vip = $page->getVar("vip");

        $db = new Players();
        if($pl_id){
            $columns = array("first_name"=>$first,
                             "last_name"=>$last,
                             "email"=>$email,
                             "country"=>$country,
                             "state"=>$state,
                             "vip"=>$vip);

            $result = $db->updatePlayersById($pl_id, $columns);
        } else {
            $result = $db->create($first, $last, $email, $country, $state, $vip);
        }
    }


    /**************************************

    Create and Show the Page

    **************************************/
    if($page->submitIsSet("register") && ($result != false)){
        
        //Build the Location
        $location = "";
        if($state){
            $states = new States();
            $s = $states->getById($state);
            $location.=$s[0][name].", ";
        }
        
        $countries = new Countries();
        $c = $countries->getById($country);
        $location.=$c[0][name];

        //Build the rest of string
        if($pl_id){
            $success_str = "Updated ";
        } else {
            $success_str = "Registered ";
        } 
        $success_str.= "$first $last from $location";
        if($vip) $success_str.= ", a VIP";
        $success_str.="!";

        $page->setDisplayMode("text");
        $link = array("href"=>"home.php?view=register_player", "text"=>"Register Another Player?");
        $template = "templates/success.html";
    
    } else {
    
        $inputs = array("edit_id", "first_name", "last_name", "e_mail", "country", "state", "vip", "register");
        $page->setDisplayMode("form");
        $template = "templates/default_section.html";
    }
    
    $form_method="post";
    $form_action=$_SERVER[PHP_SELF]."?view=$view";
    $title = "Player Registration";

    //display it
    $page->startTemplate();
    $page->doTabs();
    include $template;
    $page->displayFooter();
?>
