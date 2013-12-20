<?php

$fptr = fopen("locations.txt", 'r');

echo "USE iron_arena;\n\n";

$line=" ";

while($line != null){
    $line = fgets($fptr);

    //COUNTRIES

    $countries = array();

    if(!preg_match("~[0-9]+~",$line) && !empty($line)){
        echo "INSERT INTO countries (name) VALUES ('".mysql_escape_string(trim($line))."');\n";
    }
}


//US STATES

echo "\n";

$us_states = array( "Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware", "Florida", "Georgia", "Hawaii", 
                    "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", 
                    "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", 
                    "North Carolina", "North Dakota", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Rhode Island", "South Carolina", "South Dakota", 
                    "Tennessee", "Texas", "Utah", "Vermont", "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming", "District of Columbia", 
                    "American Samoa", "Guam", "Northern Mariana Islands", "Puerto Rico", "U.S. Virgin Islands", "Baker Island", "Howland Island", 
                    "Jarvis Island", "Johnston Atoll", "Kingman Reef", "Midway Atoll", "Navassa Island", "Palmyra Atoll", "Wake Island");

foreach($us_states as $s){
    echo "INSERT INTO states (country_id, name) VALUES (244, '".mysql_escape_string(trim($s))."');\n";
}

//CANADIAN PROVINCES

echo "\n";
$canadian_prov = array( "Alberta", "British Columbia", "Manitoba", "New Brunswick", "Newfoundland and Labrador", 
                        "Nova Scotia". "Ontario", "Prince Edward Island", "Quebec", "Saskatchewan", "Northwest Territories", "Nunavut", "Yukon");

foreach($canadian_prov as $p){
    echo "INSERT INTO states (country_id, name) VALUES (43, '".mysql_escape_string(trim($p))."');\n";
}

?>
