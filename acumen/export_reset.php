<?php

    require_once("classes/page.php");
    require_once("classes/query.php");


    $page = new Page();
    $db = Query::getInstance();


    /***********************************

    Register the Reset Button

    ***********************************/

    $page->register("reset_db", "submit", array("value"=>"Reset Database"));

    /***********************************

    Get the list of Tables

    ***********************************/
    $raw_tables = $db->query("SHOW TABLES", array());

    $tables = array();
    foreach($raw_tables as $rt){
        $tables[] = $rt["Tables_in_copss"];
    }


    /***********************************

    Get the Data from the tables, construct a string

    ***********************************/
    $data = "";
    foreach ($tables as $t){

        $table_data = $db->query("SELECT * FROM $t", array());

        if(empty($table_data)) continue;

        $data .= "INSERT INTO $t VALUES \n";

        foreach($table_data as $point){

            $data .= "\t(";

            foreach($point as $c=>$v){

                if($v == null){
                    $data.= "NULL";
                } else if(is_numeric($v)){
                    $data .= $v;
                } else {
                    $data.= "\"".addslashes($v)."\"";
                }

                if($c != end(array_keys($point))){
                    $data.= ",";
                }
            }

            $data.= ")";
                
            if($point != end($table_data)){
                $data.= ",";
            }

            $data.= "\n";
        }

        $data .= "\n";

    }

    
    /*********************************

    Write the file

    *********************************/
    $filename = "iadb_".md5($data).".sql";
    file_put_contents($filename, $data);

    
    /*********************************

    Handle the Reset

    *********************************/
    if($page->submitIsSet("reset_db")){

        $table_order = array("achievements_earned",
                                "game_players",
                                "games",
                                "prize_redemptions",
                                "players");

        foreach($table_order as $t){
            $db->update("DELETE FROM $t");
        } 
    }
    

    /*********************************

    Prep the page

    *********************************/

    $form_method="post";
    $form_action=$_SERVER[PHP_SELF]."?view=$view";
    $title = "Database Export and Reset";

    $page->setDisplayMode("form");

    //display it
    $page->startTemplate();
    $page->doTabs();
    include("templates/export.html");
    $page->displayFooter();
?>
