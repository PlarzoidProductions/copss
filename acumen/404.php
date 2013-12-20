<?php

    require_once("classes/page.php");

    $page = new Page();

    $form_method="post";
    $form_action=$_SERVER[PHP_SELF]."?view=$view";

    //display it
    $page->startTemplate();
    if(Session::isLoggedIn()){
        $page->doTabs();
    }
    include("templates/404.html");
    $page->displayFooter();
?>
