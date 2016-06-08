<?php

    require_once("classes/page.php");

    $page = new Page();

    $form_method="post";
    $form_action=$_SERVER[PHP_SELF]."?view=$view";

    //Code in some data to display
    $tab_title="About";
    $title="About";

    $text='This framework was built to function as a tool to quickly build php based websites.  

It is provided as-is and without warranty.';


    //display it
    $page->startTemplate();
    $page->doTabs();
    include("templates/simple_text.html");
    $page->displayFooter();
?>
