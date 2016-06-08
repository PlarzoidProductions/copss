<?php

    require_once("classes/page.php");

    $page = new Page();

    $form_method="post";
    $form_action=$_SERVER[PHP_SELF]."?view=$view";

    //Code in some data to display
    $tab_title="Home";
    $title="Home";

    $text='This small site is an example of what can be built easily with this framework.  

It does not use a database, and that needs to be updated for later versions of the example.';


    //display it
    $page->startTemplate();
    $page->doTabs();
    include("templates/simple_text.html");
    $page->displayFooter();
?>
