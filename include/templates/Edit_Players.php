<?php

//generate new views array for tabs container
$views=array("Add_Player", "List_Players");//, "Add_Player", "Modify_Player");

$view = $page->getVar("view");

include("include/templates/createTabs.php");

?>


