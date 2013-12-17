<?php

    require_once("classes/page.php");
    require_once("classes/db_players.php");

    $page = new Page();

    /***************************************

    Register some inputs

    ***************************************/

    $page->register("first_name", "textbox", array("required"=>true));
    $page->register("last_name", "textbox", array("required"=>true));
    $page->register("e_mail", "email", array("label"=>"eMail"));

    $page->register("country", "select", array("get_choices_array_func"=>"getCountries"));
    $page->register("state", "select", array("get_choices_array_func"=>"getStatesByCountryId",
                                            "get_choices_array_func_args"=>$page->getVar("country")));

    $page->register("vip", "checkbox", array("on_text"=>"VIP", "off_text"=>""));

    $page->register("register", "submit", array("value"=>"Register!"));


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

        //TODO Do stuff
    }


    /**************************************

    Create and Show the Page

    **************************************/

    $title = "Player Registration";
    $inputs = array("first_name", "last_name", "e_mail", "country", "state", "vip", "register");

    if($page->submitIsSet("register")){
        $page->setDisplayMode("text");
    } else {
        $page->setDisplayMode("form");
    }

    $form_method="post";
    $form_action=$_SERVER[PHP_SELF]."?view=$view";

    //display it
    $page->startTemplate();
    $page->doTabs();
    include("templates/default_section.html");
    $page->displayFooter();
?>
