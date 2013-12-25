<?php

    require_once("classes/page.php");

    $page = new Page("ADMIN");
    $db = new Users();

    /***************************************

    Modes of Operation

    ***************************************/
    $modes = array("countries", "states", "game_systems", "game_system_factions", "game_sizes");
    $page->register("mode", "select", array("label"=>"Configure", "reloading"=>1,
                                            "get_choices_array_func"=>"getConfigureModes",
                                            "get_choices_array_func_args"=>array()));
    $page->getChoices();
    
    $mode = $page->getVar("mode");

    //If not chosen yet, then let's start withthe top option
    if($mode == null){$mode = $modes[0];}

    /***************************************

    Start displaying the page

    ***************************************/
    $form_method="post";
    $form_action=$_SERVER[PHP_SELF]."?view=$view";
    
    $title = "General Configuration";

    $page->startTemplate();
    $page->doTabs();

    $page->setDisplayMode("form");
    $inputs = array("mode");
    include("templates/configure_header.html");

    /***************************************

    Include the requisite piece

    ***************************************/
    if((@include("acumen/configure_$mode.php")) == false){;
        include ("acumen/404.php");
    }

    /***************************************

    Finish displaying the page

    ***************************************/
    include("templates/configure_footer.html");
    $page->displayFooter();
?>
