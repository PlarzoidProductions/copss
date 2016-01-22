<?php

    require_once("classes/page.php");

    $page = new Page();

    $form_method="post";
    $form_action=$_SERVER[PHP_SELF]."?view=$view";

    //display it
    $page->startTemplate();
    $page->doTabs();
    include("templates/event_calendar.html");
    $page->displayFooter();
?>
