<?php

    require_once("classes/page.php");

    //Instantiate the page 

    $page = new Page();

    $form_method="post";
    $form_action=$_SERVER[PHP_SELF]."?view=$view";

    //Some info about the page
    $tab_title="API Key Management";
    $title="API Key Management";

    //Retrieve the currently stored api key
    $key_loc="database/api.key";
    $fptr=fopen($key_loc, 'r');
    
    if($fptr){
        $key=fgets($fptr);
        fclose($fptr);
    }

    //Register a form or two
    $page->register("api_key", "textbox", array("default_val"=>$key));
    $page->register("submit_key", "submit", array("value"=>"Submit"));

    //Add inputs to list of what's to be displayed
    $inputs = array("api_key", "submit_key");

    //Handle user button click
    if($page->submitIsSet("submit_key")){
        $fptr=fopen($key_loc, 'w');
        $success = fputs($fptr, $page->getVar("api_key"));
        fclose($fptr);
    }

    //display it
    $page->startTemplate();
    $page->doTabs();
    include("templates/default_section.html");
    $page->displayFooter();
?>
