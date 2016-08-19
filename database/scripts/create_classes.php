#!/usr/bin/php
<?php

//turn off notices
error_reporting(E_ERROR & ~E_NOTICE);

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
2. Check accessability of SQL file
*/
    $sql_file = $inputs[1];

    if(!file_exists($sql_file)){
        echo "Unable to locate file: $sql_file.\n";
        return;
    }

    if(!is_readable($sql_file)){
        echo "Unable to read file: $sql_file.\n";
        return;
    }

    $sql_fptr = fopen($sql_file, "r");


/************************************************
3. Check accessability of Class directory
*/
    $class_dir = $inputs[2];

    if(!is_dir($class_dir)){
        echo "Directory does not exist: $class_dir.\n";
        return;
    } 

    if(!is_writable($class_dir)){
        echo "Unable to write to output dir: $class_dir.\n";
        return;
    }

    echo "Command: $inputs[0]\n";
    echo "Time: ".date("Y-m-d H:i:s")."\n\n";

    echo "Opening files...\n";


/****************************************************
*****************************************************

Parsing the SQL file

*****************************************************
****************************************************/

//disable column detection, until we've encountered a create table line
$table_opened=false;

$line = " ";

while($line){

//Read in a line
$line = fgets($sql_fptr);

/************************************************
1. Detect and capture the name of the table
*/
$create_table_pattern = "~`[a-z_]+`\.`([a-z_]+)`~";

if(preg_match("~CREATE~", $line) && preg_match("~TABLE~", $line) && preg_match($create_table_pattern, $line, $matches)){

    $table_name = end($matches);
    
    $table_Fn_name = ucfirst(strToLower($table_name));

    echo "\n======================================================================\n";
    echo "\n";
    echo "Found Table: $table_name\n";
    echo "Table Function name: $table_Fn_name\n\n";

    //create the empty columns array
    $columns = array();

    //Create the empty keys array
    $keys = array("primary"=>"", "foreign"=>array());

    //enable column detection
    $table_opened=true;

    //Skip the rest of the loop
    continue;
}

/************************************************
2. Detect and extract columns of the table
*/
$column_pattern = "~`([a-zA-Z_]+)`\s+([A-Z]+)~";

if($table_opened && !preg_match("~INDEX~", $line) && preg_match($column_pattern, $line, $matches)){

    $name = $matches[1];
    
    $varname = "\$".strToLower($name);
    
    $type = $matches[2];

    $notNull = preg_match("~NOT\sNULL~", $line);

    switch($type){
        case "INT":
        case "BIGINT":
            $fn = "notInt";
            break;
        case "VARCHAR":
            $fn = "notString";
            break;
        case "TINYINT":
        case "BOOLEAN":
            $fn = "notBool";
            break;
        case "FLOAT":
        case "DOUBLE":
            $fn = "notFloat";
            break;
        default :
            $fn = "isNull";
    }

    $fnName = "";
    $parts = preg_split("~_~", $name);
    foreach($parts as $p){
        $fnName.= ucfirst(strtolower($p));
    }

    $validateFn = "function filter".$fnName."($varname){\n";
    if($notNull){ 
        $validateFn.= "    //Not allowed to be null\n";
        $validateFn.= "    if(Check::isNull($varname)){\n";
        $validateFn.= "        echo \"$name cannot be null!\"; return false;\n";
        $validateFn.= "    }\n\n";
    } else {
        $validateFn.= "    //Allowed to be null, catch that first\n";
        $validateFn.= "    if(Check::isNull($varname)){ return null; }\n\n";
    }
    $validateFn.= "    if(Check::$fn($varname)){\n";
    $validateFn.= "        echo \"$name was invalid!\"; return false;\n";
    $validateFn.= "    }\n\n";
    
    switch($type){
        case "INT":
        case "BIGINT":
        case "TINYINT":
            $validateFn.= "    return intVal($varname);\n";
            break;
        case "FLOAT":
            $validateFn.= "    return floatVal($varname);\n";
            break;
        case "DOUBLE":
            $validateFn.= "    return doubleVal($varname);\n";
            break;
        case "BOOLEAN":
            $validateFn.= "    if($varname){ return 1; } else { return 0; }\n";
            break;
        case "DATETIME":
        case "TIMESTAMP":
            $validateFn.= "    return date(\"Y-m-d H:i:s\", $varname);\n";
            break;
        default:
            $validateFn.= "    return $varname;\n";
            break;
    }

    $validateFn.= "}\n\n";

    $validateMe = "$varname = \$this->filter$fnName($varname); if($varname === false){return false;}";

    $columns[]=array(
        "name"=>$name,
        "varname"=>$varname,
        "fnName"=>$fnName,
        "type"=>$type,
        "validateFn"=>$validateMe,
        "selfValidation"=>$validateFn);

    echo "Found Column: $name\n";

    //skip the rest of the loop
    continue;
}


/************************************************
3. Detect and capture the Primary Key
*/
$primary_key_pattern = "~PRIMARY\sKEY\s\(`([a-zA-Z_]+)`\)~";

if($table_opened && preg_match($primary_key_pattern, $line, $matches)){

    $keys["primary"] = strToLower(end($matches));

    foreach($columns as $key=>$column_array){
        if(!strcmp($columns[$key]["name"], $keys["primary"])){
            
            $columns[$key]["primary_key"]=true;
            $primary_key = array(
                "name"=>$columns[$key][name], 
                "varname"=>$columns[$key][varname]);
            
            echo "\nFound Primary Key: ".$keys["primary"]."\n";
            break;
        }
    }
    
    //skip the rest of the loop
    continue;
}


/************************************************
4. Detect end of table
*/

if($table_opened && preg_match("~;$~", $line)){

    //Create and open the file
    $file = $class_dir."db_".strtoLower($table_name).".php";
    $class_fptr = fopen($file, 'w');

    //check for successful open
    if($class_fptr == false){echo "Failed to open file: $file!"; return;}

    echo "\nOpening file: $file\n";


    //Generate the function names for the columns
    foreach($columns as $k=>$c){
        $name = explode("_", strToLower($c[name]));
        $new_name = "";

        foreach($name as $chunk){
            $new_name.=ucfirst($chunk);
        }

        $columns[$k][function_name]=$new_name;
    }
    

/************************************************
5. Write the class file header
*/

$class_header= '<?php

/**************************************************
*
*    '.$table_Fn_name.' Class
*
***************************************************/

/**************************************************
*
*   Table Description:
*
';

foreach($columns as $c){
    $class_header.= "*\t".$c[name]." - ".$c[type];
    if($c[primary_key]) $class_header.= " - PRIMARY KEY";
    $class_header.="\n";
}
$class_header.= '*
**************************************************/
require_once("query.php");

class '.$table_Fn_name.' {

//DB Interaction variables
private var $db=NULL;
private var $table="'.$table_name.'";

//Data storage variables
';

foreach($columns as $c){
    $class_header.="public var $".$c[name]."=NULL;\n";
}


$class_header .= '
//List of variables for sanitization
private var $varlist = array(';
foreach($columns as $c){
    if($c[primary_key]) continue;   //Skip the PK (id)

    $class_header.="\n\t\"".$c[name].'"=>"filter'.$c[fnName].'"';
    if($c != end($columns)) $class_header.=",";
}
$class_header.=');

/***************************************************

Constructor & Destructor

***************************************************/
public function __construct(){
    $this->db = Query::getInstance();
}

public function __destruct(){}

';

echo "Writing class header...\n";
fputs($class_fptr, $class_header);


/*************************************************
6. Write the commit function
*/

$commitFns = '
/**************************************************

Commit (Insert/Update) to DB Function(s)

**************************************************/
public function commit(){

    if($this->filterId($this->id)){
        return $this->updateRow();
    } else {
        return $this->insertRow();
    }
}

private function insertRow(){

    //Check for good data, first
    foreach($varlist as $vname=>$valFn){
        if(!$this->$valFn($this->$vname)) return false;
    }

    //Create the array of variables names and value calls
    $c_names = "";
    $v_calls = "";
    $values = array();
    foreach(array_keys($varlist) as $v){
        $c_names .= "$v";
        $v_calls .= ":$v";
        $values[":$v"] = $this->$v;

        if($v != end(array_keys($varlist)){
            $c_names .= ", ";
            $v_calls .= ", ";
        }
    }

    //Build the query
    $sql = "INSERT INTO $this->table ($c_names) VALUES ($v_calls)";

    return $this->db->insert($sql, $values);
}

private function updateRow(){

    //Check for good data, first
    foreach($varlist as $vname=>$valFn){
        if(!$this->$valFn($this->$vname)) return false;
    }

    //Create the array of variables names and value calls
    $c_str = "";
    $values = array(":id"=>$this->id);
    foreach(array_keys($varlist) as $v){
        $c_str .= "$v=:$v";
        $values[":$v"] = $this->$v;

        if($v != end(array_keys($varlist)){
            $c_str .= ", ";
        }
    }

    //Build the query
    $sql = "UPDATE $this->table SET $c_str WHERE id=:id";

    return $this->db->update($sql, $values);
}
';

echo "Writing commit (insert/update) function...\n";
fputs($class_fptr, $commitFns);

/**************************
* Delete function
**************************/
$deleteFn = '
/**************************************************

Delete Functions

**************************************************/
';
$deleteFn.= 'public function deleteByColumns($columns){

    //Create the values array
    $values = array();
    foreach($columns as $c=>$v){
        $values[":".$c]=$v;
    }

    //Create Query\n";
    $sql = "DELETE FROM $this->table WHERE ";
    $keys = array_keys($columns);
    foreach($keys as $column){
        $sql.= "$column=:$column";
        if(strcmp($column, end($keys))){
            $sql.= " AND ";
        }
    }

    return $this->db->delete($sql, $values);
}

public function deleteById($id){
    return $this->deleteByColumns(array("id"=>$id));
}

public function delete(){
    if($this->id) return $this->deleteById($this->id);

    return false;
}

';

echo "Writing delete function...\n";
fputs($class_fptr, $deleteFn);


/**************************
* Individual 'getBy' functions
**************************/
$allHeader = '
/**************************************************

Query Functions

**************************************************/
';
fputs($class_fptr, $allHeader);

$allFn='public function getAll(){

    //Generate the query
    $sql = "SELECT * FROM $this->table";

    return $this->db->query($sql, array());
}

';
fputs($class_fptr, $allFn);


/**************************
* Query by Columns function
**************************/
$masterQueryFn='public function queryByColumns($columns){

    //Values Array
    $values = array();
    foreach($columns as $column=>$value){
        $values[":".$column]=$value;
    }

    //Generate the query
    $sql = "SELECT * FROM $this->table WHERE ";
    $keys = array_keys($columns);
    foreach($keys as $column){
        $sql.= "$column=:$column";
        if(strcmp($column, end($keys))){
            $sql.= " AND ";
        }
    }

    return $this->db->query($sql, $values);
}';
$masterQueryFn.="\n\n";

echo "Writing master query function...\n";
fputs($class_fptr, $masterQueryFn);

foreach($columns as $column){

    $columnFn = '
public function getBy'.$column[fnName].'('.$column[varname].'){
	
    //Validate Inputs
    '.$column[validateFn].'

    return '.$table_Fn_name.'::fromArray($this->queryByColumns(array("'.$column[name].'"=>'.$column[varname].')));
}';
    $columnFn.="\n";

    echo "Writing column function for $column[name]...\n";
    fputs($class_fptr, $columnFn);

}

/*************************
*   From Array
*************************/
$arrayFn = '
public static function fromArray($array){

    $output = new array();

    foreach($array as $a){

        $new = new '.$table_Fn_name.'();
    
        if($array[id]) $new->id=$a[id];

        foreach($this->varlist as $v){
            $new->$v = $a[$v];
        }

        $output[] = $new;
    }

    return $output;
}'."\n\n";
echo "Writing column function for $column[name]...\n";
fputs($class_fptr, $arrayFn);


/*************************
* Exists by Column(s) Function
*************************/
$existsFnHeader = '
/**************************************************

Exists by Column(s) Function

**************************************************/
';
fputs($class_fptr, $existsFnHeader);

$existsFn = 'public function existsByColumns($columns){
    $results = $this->queryByColumns($columns);

    return count($results);
}';
$existsFn.="\n\n";

echo "Writing exists by column(s) function...\n";
fputs($class_fptr, $existsFn);



/*************************
* ValidationFunctions
*************************/
$validationFnHeader = '
/**************************************************
 
Column Validation Function(s)

**************************************************/
';
fputs($class_fptr, $validationFnHeader);
foreach($columns as $c){
    fputs($class_fptr, $c[selfValidation]."\n\n");
}


/**************************
*  Write the class footer
**************************/
//break this up so we can keep syntax highlighting working in vim...
$class_footer= "}//close class\n\n"."?".">\n";

echo "Writing class footer...\n";
fputs($class_fptr, $class_footer);
flush();

echo "Closing file\n";
fclose($class_fptr);

//turn off column detection
$table_opened=false;

} //close the end of table and write file detection clause

}//close the while($line) loop

/************************************************

Close the files

************************************************/
echo "\nClosing SQL file...\n";
fclose($sql_fptr);

?>
