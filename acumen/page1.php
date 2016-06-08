<?php

    require_once("classes/page.php");

    //Instantiate the page 

    $page = new Page();

    $form_method="post";
    $form_action=$_SERVER[PHP_SELF]."?view=$view";

    //Some info about the page
    $tab_title="Page 1";
    $title="Input Example";

    //Register a form or two
    $page->register("author", "textbox", array("label"=>"Author's name"));
    $page->register("text", "textarea");
    $page->register("submit_story", "submit", array("value"=>"Submit"));

    //Add inputs to list of what's to be displayed
    $inputs = array("text", "author", "submit_story");

    //display it
    $page->startTemplate();
    $page->doTabs();
    include("templates/default_section.html");
    $page->displayFooter();
?>
