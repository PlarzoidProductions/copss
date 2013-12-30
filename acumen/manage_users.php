<?php

    require_once("classes/page.php");
    require_once("classes/db_users.php");

    $page = new Page("ADMIN");
    $db = new Users();

    /***************************************

    Detect if we're editing...

    ***************************************/
    $uid = $_REQUEST[user_id];
    if($uid!=null){
        $defaults = $db->getById($uid);
    }

    $page->register("username", "textbox", array("required"=>true, "default_val"=>$defaults[0][username]));
    $page->register("password1", "password", array("label"=>"Password"));
    $page->register("password2", "password", array("label"=>"Confirm Password"));

    $page->register("admin", "checkbox", array("on_text"=>"Admin", "off_text"=>"", "default_val"=>$defaults[0][admin]));

    $page->register("add", "submit", array("value"=>"Add"));
    $page->register("update", "submit", array("value"=>"Update"));
    $page->register("delete", "submit", array("value"=>"Delete", "onclick"=>"confirm('Really delete the user?')"));

    $page->getChoices();

    /***************************************

    Listen for the click

    ***************************************/
    $submitted = $page->submitIsSet("add") || $page->submitIsSet("update") || $page->submitIsSet("delete");
    if($submitted){

        //Retrieve the vars
        $name = $page->getVar("username");
        $pass1 = $page->getVar("password1");
        $pass2 = $page->getVar("password2");
        $admin = $page->getVar("admin");
        if(empty($admin)) $admin=0;


        //Add the new user

        if($page->submitIsSet("add")){
            if(strlen($pass1) < 8){
                $result=false;
                $error = "Password must be at least 8 characters long!";
            }

            if($pass1 != $pass2){
                $reult=false;
                $error = "Passwords do not match.";
            }

            $exists = $db->getByUsername($username);
            if($exists){
                $error = "Username already exists!";
            }

            if(empty($error)){
                $result = $db->create($name, md5($pass1), null, $admin);
            }
        }

        //Update the chosen user

        if($page->submitIsSet("update")){
            if($uid == null){
                $error = "Can't update what doesn't exist!";
            } else {
                
                $exists = $db->getByUsername($username);
                if($exists){
                    if($exists[0][id] != $uid)  $error = "Username already exists!";
                }

                $columns = array("username"=>$username, "admin"=>$admin);
            
                if(strlen($pass1) > 0){
                    if(strlen($pass1) < 8){
                        $result=false;
                        $error = "Password must be at least 8 characters long!";
                    }

                    if($pass1 != $pass2){
                        $reult=false;
                        $error = "Passwords do not match.";
                    }

                    if(empty($error)){
                        $columns[password]=md5($pass1);
                    }
                }

                if(empty($error)){
                    $result = $db->updateUsersById($uid, $columns);
                }

            }
        }

        //Delete the user

        if($page->submitIsSet("delete")){
            $result = $db->deleteById($uid);
        }
    }


    /**************************************

    Create and Show the Page

    **************************************/
    if($submitted && ($result != false)){
       
        $success_str = "Successfully ";
        if($page->submitIsSet("add")){
            $success_str.= "added";
        } else if($page->submitIsSet("update")){
            $uccess_str.= "updated";
        } else if($page->submitIsSet("delete")){
            $success_str.= "deleted";
        }
        $success_str.= " user $username!";

        $page->setDisplayMode("text");
        $link = array("href"=>"home.php?view=manage_users", "text"=>"Add another User?");
        $template = "templates/success.html";
    
    } else {

        $inputs = array("username", "password1", "password2", "admin");
        if($uid==null){
            $inputs[]="add";
        } else {
            $inputs[]="update";
            $inputs[]="delete";
        }
        $page->setDisplayMode("form");
        $template = "templates/default_section.html";
    
    }
    
    $form_method="post";
    $form_action=$_SERVER[PHP_SELF]."?view=$view";
    if($uid != null) $form_action.="&user_id=$uid";

    $title = "Add / Update / Delete Users";

    //display it
    $page->startTemplate();
    $page->doTabs();
    include $template;
    $page->displayFooter();
?>
