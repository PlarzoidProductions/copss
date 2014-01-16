<?php

    require_once("classes/page.php");
    require_once("classes/db_feedback.php");

    $page = new Page();
    $f_db = new Feedback();

    /***************************************

    Register some inputs

    ***************************************/
    $page->register("feedback_type", "select", array("get_choices_array_func"=>"getFeedbackTypes",
                                            "get_choices_array_func_args"=>array()));

    $page->register("feedback", "textarea", array("rows"=>10, "cols"=>35,
                                                  "placeholder"=>"I think..."));

    $page->register("post_feedback", "submit", array("value"=>"Submit"));
    $page->getChoices();


    /***************************************

    Listen for the click

    ***************************************/
    if($page->submitIsSet("post_feedback")){

        //Retrieve the vars
        $type = $page->getVar("feedback_type");
        $comments = $page->getVar("feedback");

        $result = $f_db->create($type, $comments);
    }


    /**************************************

    Create and Show the Page

    **************************************/
    if($page->submitIsSet("post_feedback")){
        
        if($result){
            $success_str = "Successfully left feedback!  Thanks!";
        } else {
            $error = "So, uh... there was an issue leaving feedback.  How ironic!";
        }

        $page->setDisplayMode("text");
        $link = array("href"=>"home.php?view=feedback", "text"=>"Leave More Feedback?");
   
    } else {

        $inputs = array("feedback_type", "feedback", "post_feedback");
        $page->setDisplayMode("form");
    }

    $feedback = $f_db->getAll();

    $odd=true;
    foreach($feedback as $k=>$f){
        if($odd) $feedback[$k][style] = "odd";
        $odd = !$odd;
    }

    $form_method="post";
    $form_action=$_SERVER[PHP_SELF]."?view=$view";
    $title = "Software Feedback";

    //display it
    $page->startTemplate();
    $page->doTabs();
    include("templates/feedback.html");
    $page->displayFooter();
?>
