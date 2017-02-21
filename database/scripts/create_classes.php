#!/usr/bin/php
<?php

require_once("class_engine.php");


//turn off notices
//error_reporting(E_ERROR & ~E_NOTICE);


/*************************************************
**************************************************

Command Line Input Checking

*************************************************
*************************************************/

/************************************************
1. Check for the right number of inputs
*/

    $inputs = $argv;
    if(count($inputs) != 3){
        echo "Usage: create_classes.php <sql script> <output directory>\n";
        return;
    }

    
/***********************************************
2. Start Class Engine, Check inputs
*/

    $engine = new Class_Engine();

    $sql_file = $inputs[1];
    $class_dir = $inputs[2];

    //Check the output directory
    try { $engine->isDirWriteable($class_dir); }
    catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }

    //Check that we can read the file 
    try { $engine->isFileReadable($sql_file); }
    catch (Exception $e) { 
        echo $e->getMessage(); 
        exit;    
    }
   

/***********************************************
3. Open SQL and parse the file
*/

    //Open it
    try { $engine->openSQL($sql_file); }
	catch (Exception $e) {
		echo $e->getMessage();
		exit;
	}

    $engine->parseFile();
    $engine->closeSQL();

/***********************************************
4. Write class files for tables
*/

    //Write files
    $engine->writeClasses($class_dir);

    exit;


?>
