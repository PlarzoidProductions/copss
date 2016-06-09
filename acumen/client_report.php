<?php

    require_once("classes/page.php");
    require_once("togglSDK/Toggl.php");


    //Instantiate the page 

    $page = new Page();

    $form_method="post";
    $form_action=$_SERVER[PHP_SELF]."?view=$view";

    //Some info about the page
    $tab_title="Client Billing";
    $title=$tab_title;

    //Register drop down for picking the Client
    $page->register("client", "select", array("get_choices_array_func"=>"getTogglClientChoices",
                                              "get_choices_array_func_args"=>array(),
                                              "reloading"=>1,
                                              "default_val"=>$page->getVar("client")));
    $page->getChoices();

    //Display something useful
    $client_id = $page->getVar("client");
    if($client_id){
        $client = TogglClient::getClientDetails($client_id);
        $client["projects"] = TogglClient::getClientProjects($client_id);
       
        foreach($client["projects"] as &$p){
            $page->register("cb_".$p[id], "checkbox", array("on_text"=>"Selected", "off_text"=>"Unselected"));
        }

        $page->register("create", "submit", array("value"=>"Create Report"));
    }

    var_dump($client);

    //display it
    $page->startTemplate();
    $page->doTabs();
    include("templates/showProjects.html");
    $page->displayFooter();
?>
