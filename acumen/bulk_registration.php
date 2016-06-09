<?php

    require_once("classes/page.php");
    require_once("classes/db_players.php");
    require_once("classes/db_states.php");
    require_once("classes/db_countries.php");


    $page = new Page();
    //$page = new Page("Admin");
	$p_db = new Players();
	$s_db = new States();
	$c_db = new Countries();

/********************************

Prep the Postal Codes

********************************/
$raw_postal_codes = "
AB  Alberta 
BC  British Columbia
MB  Manitoba 
NB  New Brunswick
NF  Newfoundland 
NS  Nova Scotia
NT  Northwest Territories
ON  Ontario 
PE  Prince Edward Island
QC  Quebec
SK  Saskatchewan  
YT  Yukon 
AK  ALASKA
AL  ALABAMA
AR  ARKANSAS
AS  AMERICAN SAMOA
AZ  ARIZONA
CA  CALIFORNIA
CO  COLORADO
CT  CONNECTICUT
DC  DISTRICT OF COLUMBIA
DE  DELAWARE
FL  FLORIDA
FM  FEDERATED STATES OF MICRONESIA
GA  GEORGIA
GU  GUAM
HI  HAWAII
IA  IOWA
ID  IDAHO
IL  ILLINOIS
IN  INDIANA
KS  KANSAS
KY  KENTUCKY
LA  LOUISIANA
MA  MASSACHUSETTS
MD  MARYLAND
ME  MAINE
MH  MARSHALL ISLANDS
MI  MICHIGAN
MN  MINNESOTA
MO  MISSOURI
MP  NORTHERN MARIANA ISLANDS
MS  MISSISSIPPI
MT  MONTANA
NC  NORTH CAROLINA
ND  NORTH DAKOTA
NE  NEBRASKA
NH  NEW HAMPSHIRE
NJ  NEW JERSEY
NM  NEW MEXICO
NV  NEVADA
NY  NEW YORK
OH  OHIO
OK  OKLAHOMA
OR  OREGON
PA  PENNSYLVANIA
PR  PUERTO RICO
PW  PALAU
RI  RHODE ISLAND
SC  SOUTH CAROLINA
SD  SOUTH DAKOTA
TN  TENNESSEE
TX  TEXAS
UT  UTAH
VA  VIRGINIA
VI  VIRGIN ISLANDS
VT  VERMONT
WA  WASHINGTON
WI  WISCONSIN
WV  WEST VIRGINIA
WY  WYOMING";

$p_codes = preg_split("~[\n\r]~", $raw_postal_codes);
$postal_codes = array();
foreach($p_codes as $pcode){
	$bits = preg_split("~  ~", $pcode);
	if(count($bits) == 2)
		$postal_codes[strtolower($bits[1])]=strtolower($bits[0]);
}

    /***************************************

    Register some inputs

    ***************************************/
	$page->register("inputfile", "file", array("filedir"=>"", "filedir_webpath"=>""));
	$page->register("file_format", "select", array("get_choices_array_func"=>"getFileModes",
												"get_choices_array_func_args"=>array()));
    $page->register("default_country", "select", array( "get_choices_array_func"=>"getCountries", 
                                                "reloading"=>1, "default_val"=>244));

    $country_id=$page->getVar("default_country");
    if(empty($country_id)) $country_id=244;

    $page->register("default_state", "select", array(   "get_choices_array_func"=>"getStates",
                                                "get_choices_array_func_args"=>array($country_id),
                                                "default_val"=>1));

    $page->register("upload", "submit", array("value"=>"Upload!"));
    
	$page->getChoices();

    /***************************************

    Listen for the click

    ***************************************/

    if($page->submitIsSet("upload")){
		//Check for upload errors
		try{
			if(!isset($_FILES["inputfile"]["error"]) || is_array($_FILES["inputfile"]["error"])){
				$error = "File upload error!";
			}

			if(!isset($error)){
				switch($_FILES["inputfile"]["error"]){
					case UPLOAD_ERR_OK: break;
					case UPLOAD_ERR_NO_FILE: $error = "No file was received!"; break;
					case UPLOAD_ERR_INI_SIZE:
					case UPLOAD_ERR_FORM_SIZE: $error = "Exceeded file size limit!"; break;
					default: $error = "Unknown Error!"; break;
				}

				if(!isset($error)){
					$finfo = new finfo(FILEINFO_MIME_TYPE);
					if(false === ($ext = array_search($finfo->file($_FILES["inputfile"]["tmp_name"]), array('csv'=>'text/csv', 'csv'=>'text/plain'), true))){
						$error = "Invalid file type!";
					}
				}
			}
			

			//Everything's OK,latch in the file's path
			if(!isset($error)){ $filepath = $_FILES["inputfile"]["tmp_name"]; }
			
		} catch (RuntimeException $e){
			echo $e->getMessage();
		}
		
		
		//Load the file
		if(isset($filepath)){
			$players = file($filepath);

			//Load the user_inputs
			$file_mode = $page->getVar("file_format");
    	    $default_country = $page->getVar("default_country");
        	$default_state = $page->getVar("default_state");

		    //Prep some lookup data
        	$raw_countries = $c_db->getAll();
        	$countries = array();
	        foreach($raw_countries as $rc){
    	        $countries[strtolower($rc["name"])] = $rc["id"];
        	}
			$countries["usa"]=$countries["united states"];	//handle acronyms
			$countries["u.s.a."]=$countries["united states"];
			$countries["uk"]=$countries["united kingdom"];
			$countries["u.k."]=$countries["united kingdom"];

	        $raw_states = $s_db->getByParent($default_country); //hopefully reduce SOME processing time
    	    if(count($raw_states)){
        	    $states = array();
            	foreach($raw_states as $rs){
                	$states[strtolower($rs["name"])] = $rs["id"];
					if(in_array(strtolower($rs["name"]), array_keys($postal_codes))){
						$states[$postal_codes[strtolower($rs["name"])]] = $rs["id"];
					}
           		}
        	}	

			//Set indices based on the file mode the user selected
			switch($file_mode){
				case "FLCS": $first=0; $last=1; $country=2; $state=3; break;
				case "LFCS": $last=0; $first=1; $country=2; $state=3; break;
				case "LFSC": $last=0; $first=1; $state=2; $country=3; break;
				case "FLSC": 
				default: $first=0; $last=1; $state=2; $country=3; break;
			}
        
			//Start up the error counter
			$reg_errors = array();

			//Go through the data
			$i=0; $successes=0;
			foreach($players as $player){
			
				$pl = preg_split("~,~", $player);

				//Validate First and Last Name
				if(count($pl) >= 2){  //we at least have a name
	            	$nameChars = "a-zA-Z0-9' -";

					//Check for empty First Name
    	        	if(empty($pl[$first]) || (strlen($pl[$first]) == 0)){
						$reg_errors[$i] = array("data"=>$pl, "error"=>"First Name doesn't exist!");
					}
					//Check for illegal characters in First Name
					/*	//Removed to better support the international community
					if(!isset($reg_errors[$i]) && !preg_match("~^[$nameChars]+$~", $pl[$first])){
        	        	$illegalChars = preg_replace("~[$nameChars]~", "", $pl[$first]);
            	    	$reg_errors[$i] = array("data"=>$pl, "error"=>"First Name contains invalid character(s): '$illegalChars'!");
	            	}
					*/

					//Check for empty Last Name
					if(empty($pl[$last]) || (strlen($pl[$last]) == 0)){
						$reg_errors[$i] = array("data"=>$pl, "error"=>"Last Name doesn't exist!");
					}
					/*	//Removed to better support the international community
					//Check for illegal characters in Last Name					
	            	if(!isset($reg_errors[$i]) && !preg_match("~^[$nameChars]+$~", $pl[$last])){
    	            	$illegalChars = preg_replace("~[$nameChars]~", "", $pl[$last]);
        	        	$reg_errors[$i] = array("data"=>$pl, "error"=>"Last Name contains invalid character(s): '$illegalChars'!");
            		}
					*/
				}

				//Validate Country
				if(!isset($reg_errors[$i]) && (count($pl) >= $country)){  //3 if country=2, 4 if country=3
					if(empty($pl[$country])){ $pl["country_id"] = $default_country; } //asign the default if empty

					//Clean input
					$pl[$country] = trim($pl[$country]);

					//Try a lookup
					$try_c = $countries[strtolower($pl[$country])];
					if(is_numeric($try_c)){ 
						$pl["country_id"] = $try_c;
					} else {
						$reg_errors[$i] = array("data"=>$pl, "error"=>"Unable to find country: '".$pl[$country]."'!");
					}
				}

				//Validate State (if applicable)
				if(!isset($reg_errors[$i]) && (count($pl) >= $state)){	//3 if state=2, 4 if state=3

            		if(!empty($pl[$state])){
						if($pl["country_id"]==$default_country){ 
							$pl["state_id"] = $default_state;   //assign the default if empty
						}
					}

					if(!empty($pl[$state]))	//we have a state name to work with
					if(count($states)){					//...and the states aray isn't empty, try a lookup

						//Clean the input
						$pl[$state] = trim($pl[$state]);

						//Try a lookup
						$try_s = $states[strtolower($pl[$state])];

						//look up was good
						if(is_numeric($try_s)){
							$pl["state_id"] = $try_s;
						} else {
					
							//lookup failed, try looking in the database
							$db_state = $s_db->queryByColumns(array("parent"=>$pl["country_id"], "name"=>$pl[$state]));
							if(!empty($db_state)){
								$states[strtolower($db_state[0]["name"])]=$db_state[0]["id"];	//Add to states array for later
								$pl["state_id"] = $db_state[0]["id"];
							} else {
								if(in_array(strtolower($pl[$state]), $postal_codes)){	//non-default country state
									$state_name = array_search(strtolower($pl[$state]), $postal_codes);

									$db_state = $s_db->queryByColumns(array("parent"=>$pl["country_id"], "name"=>$state_name));
									if(!empty($db_state)){
									    $states[strtolower($db_state[0]["name"])]=$db_state[0]["id"];   //Add to states array for later
									    $pl["state_id"] = $db_state[0]["id"];
									} else {
										//We didn't find it, throw an error
										$reg_errors[$i] = array("data"=>$pl, "error"=>"Unable to find state: '".$pl[$state]."'!");
									}
								}
							}
						}
					}
				}	

				//Check for duplicates
	            if(!isset($reg_errors[$i])){
    	            $columns = array("first_name"=>$pl[$first], "last_name"=>$pl[$last], "country"=>$pl["country_id"]);
        	        if(!empty($pl[$state])) $columns["state"] = $pl["state_id"];
            	    $exists = $p_db->existsByColumns($columns);

	                if($exists){
						continue;	//rather than report the error, just skip to next player
            	        //$reg_errors[$i] = array("data"=>$pl, "error"=>"Player with that name & location exists!");
    	            }
        	    }

				//Load the new player into the database
				if(!isset($reg_errors[$i])){
            		$result = $p_db->create($pl[$first], $pl[$last], $pl["country_id"], $pl["state_id"], 0);
					if($result) $successes++;
				}

				$i++;
        	}
		}

		$success_str = "Successfully registered ".$successes." players!";

		if($successes != $i){
			$error_str = "Failed to register the following players:<br/><br/>";
			foreach ($reg_errors as $rege){
				$error_str .= " - ".$rege["data"][$last].", ".$rege["data"][$first].": ".$rege["error"]."<br/>";
			}
		}

        $page->setDisplayMode("text");
        $template = "templates/success.html";
    
    } else {
    
        $inputs = array("inputfile", "file_format", "default_country", "default_state", "upload");
 		if(Session::isAdmin() && $pl_id){ $inputs[] = "delete"; }  //Add in the delete option if an admin is logged in
 		$page->setDisplayMode("form");
        $template = "templates/import_section.html";
    }
    
    $form_method="post";
    $form_action=$_SERVER[PHP_SELF]."?view=$view";
    $title = "Bulk Player Registration";

    //display it
    $page->startTemplate($meta);
    $page->doTabs();
    include $template;
    $page->displayFooter();
?>
