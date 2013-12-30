<?php

    require_once("classes/page.php");
    require_once("classes/db_players.php");
    require_once("classes/db_states.php");
    require_once("classes/db_countries.php");

    $page = new Page();

    /***************************************

    Register some inputs

    ***************************************/

    $page->register("first_name", "textbox", array("required"=>true));
    $page->register("last_name", "textbox", array("required"=>true));
    $page->register("e_mail", "email", array("label"=>"eMail"));

    $page->register("country", "select", array( "get_choices_array_func"=>"getCountries", 
                                                "reloading"=>1));

    $country_id=$page->getVar("country");
    if(empty($country_id)) $country_id=1;
    $page->register("state", "select", array(   "get_choices_array_func"=>"getStates",
                                                "get_choices_array_func_args"=>array($country_id)));

    $page->register("vip", "checkbox", array("on_text"=>"VIP", "off_text"=>""));

    $page->register("register", "submit", array("value"=>"Register!"));

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

        $result = $db->create($first, $last, $email, $country, $state, $vip);
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
        $reg_string = "Registered $first $last from $location";
        if($vip) $reg_string.= ", a VIP";
        $reg_string.="!";

        $page->setDisplayMode("text");
        $link = array("href"=>"home.php?view=register_player", "text"=>"Register Another Player?");
        $template = "templates/success.html";
    
    } else {
    
        $inputs = array("first_name", "last_name", "e_mail", "country", "state", "vip", "register");
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
