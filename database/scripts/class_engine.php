<?php 

/******************************************************

	Class Engine

	Reads SQL files, creates table objects

******************************************************/


class Class_Engine{


    private $tables = array();
    private $sql_fptr;

    private $user_func_start = " ///////////////////////////////////////////////////////////\n".
                               " //\n".
                               " //     END OF AUTOMATED PORTION OF FILE\n".
                               " //     Put any custom functions below.\n".
                               " //     DO NOT DELETE THIS COMMENT\n".
                               " //\n".
                               " ///////////////////////////////////////////////////////////\n";

    private $user_func_stop  = " ///////////////////////////////////////////////////////////\n".
                               " //\n".
                               " //     END OF FILE.  ANYTHING AFTER THIS WILL BE LOST.\n".
                               " //     DO NOT DELETE THIS COMMENT\n".
                               " //\n".
                               " ///////////////////////////////////////////////////////////\n";

    private $create_table_pattern = "~`[a-z_]+`\.`([a-z_]+)`~";
    private $column_pattern = "~`([a-zA-Z_]+)`\s+([A-Z]+)~";
    private $primary_key_pattern = "~PRIMARY\sKEY\s\(`([a-zA-Z_]+)`\)~";
    private $foreign_key_pattern = "~FOREIGN\sKEY\s\(`([a-zA-Z_]+)`\)~";
    private $foreign_key_ref_pattern = "~REFERENCES\s\`[a-zA-Z_]+`\.`([a-zA-Z_]+)`\s\(`([a-zA-Z_]+)`\)~";
    private $table_close_pattern = "~(DEFAULT\sCHARACTER\sSET)|(ENGINE)~";


	public function __construct(){}

    /**********************************

    File & Dir Check Functions

    **********************************/

    public function isFileReadable($file){

        if(!file_exists($file)){
            throw new Exception("Unable to locate file: $file.");
            return false;
        }

        if(!is_readable($file)){
            throw new Exception("Unable to read file: $file.");
            return false;
        }

        return true;
    }


    public function isDirWriteable($dir){
    
        if(!is_dir($dir)){
            throw new Exception("Directory does not exist: $dir.");
            return false;
        }

        if(!is_writable($dir)){
            throw new Exception("Unable to write to output dir: $dir.");
            return false;
        }
 
        return true;
    }


    public function openSQL($file){
        try{
            if($this->isFileReadable($file)){
                $this->sql_fptr = fopen($file, "r");
            }
        } catch (Exception $e){
            echo $e->getMessage();
            exit;
        }
    }

	public function closeSQL(){
		fclose($this->sql_fptr);
	}


    /**********************************

    SQL Parse Functions

    **********************************/
    
	public function parseFile(){
		
		$line = " ";
        $table_open = false;

		while ($line){
			
			//Get a line from the file
			$line = fgets($this->sql_fptr);			

            //If the table hasn't been opened, look for a CREATE TABLE statement
            if(!$table_open){
                //Check for TABLE CREATE and yank table name
	    		if( preg_match("~CREATE TABLE~", $line) &&
		       	   !preg_match("~VIEW~"  , $line) &&
			    	preg_match($this->create_table_pattern, $line, $matches)){

                    //If we found a table, create one and set the 'table_open' flag
                    if(count($matches)){
			        	$table = new Table(end($matches));
                        $table_open=true;
                    }
			    }

            //Else, the table is open, and we should parse the line for a column
            } else {

                if(preg_match($this->column_pattern, $line)) {
                    //Parse the line, see if it's a column
                    $column = $this->parseColumn($line);
    
                    //If we got something, add it to the table
                    if($column){
                        $table->addColumn($column);
                    }

                //Look for a Primary Key
                } else if(preg_match($this->primary_key_pattern, $line, $pk_matches)) {
                    $table->setPrimaryKey(end($pk_matches));

                //Look for a Foreign Key
                } else if(preg_match($this->foreign_key_pattern, $line, $fk_matches)) {
                    $line = fgets($this->sql_fptr);
                    preg_match($this->foreign_key_ref_pattern, $line, $fk_ref_matches);
                    $table->setForeignKey(end($fk_matches), $fk_ref_matches[1], $fk_ref_matches[2]);

                //If we didn't, close the table, add it to the list
                } else if(preg_match($this->table_close_pattern, $line)) {
                    $table_open = false;

                    if(count($table->getColumns()) > 0){
                        $this->tables[] = $table;
                    }
                }
            }
		}
	}				

	private function parseColumn($line){
        $column = null;

        if(!preg_match("~INDEX~", $line) && 
            preg_match($this->column_pattern, $line, $matches)){

            if(count($matches)){
                $column = new Column($matches[1], $matches[2], preg_match("~NOT\sNULL~", $line));
            }
        }

        return $column;
	}

	/********************************************

	Data Abstraction Layer File Creation Functions

	********************************************/

    public function writeClasses($dir){

        echo "\n";

        foreach($this->tables as $t){

			echo "Writing class file for ".$t->getName()."\n";
            
			$user_fns = null;

            //Check to see if the class file already exists
			if(file_exists($dir.$t->getName().".php")){
				$user_fns = $this->getUserFns($dir."/".$t->getName().".php");
				system("mv ".$dir.$t->getName().".php ".$dir."archive/".$t->getName().".php.old");
			}

			//Write the new file
			$fptr = fopen($dir."/".$t->getName().".php", "w");
			
			fwrite($fptr, $this->getFileHeader());
			fwrite($fptr, $this->getTableDescription($t));
			fwrite($fptr, $this->openTableClass($t));
			fwrite($fptr, $this->getAccessFns($t));
			fwrite($fptr, $this->getCommitFns());
			fwrite($fptr, $this->getDeleteFns());	
			fwrite($fptr, $this->getQueryFns($t));
	
			fwrite($fptr, $this->user_func_start."\n\n\n");
			if(strlen($user_fns) > 0) {fwrite($fptr, $user_fns);}
			fwrite($fptr, "\n\n\n".$this->user_func_stop);

			fwrite($fptr, $this->closeTableClass());

			fclose($fptr);
		}

        echo "\nDone writing Class files.\n\n";
	}

	private function getFileHeader(){

        return 
            "<?php\n".
            "///////////////////////////////////////////////\n".
            "//\n".
            "//  FILE WRITTEN BY SCRIPT database/scripts/create_classes.php\n".
            "//\n".
            "///////////////////////////////////////////////\n\n".
            "require_once(\"query.php\");\n\n";
    }

    private function getTableDescription($table){

        $output = 
            "///////////////////////////////////////////////\n".
            "//\n".
            "//     Table Description\n".
            "//\n";

        foreach($table->getColumns() as $c){
            $pk = $c->isPrimaryKey();
            $fk = $c->getForeignKey();

            $output .= 
            "// ".$c->getName()." - ".$c->getType().
            ($pk ? " - PRIMARY KEY" : "").
            (is_array($fk) ? " - FK: ".$fk["table"].", ".$fk["column"] : "")."\n";
        }
        $output .= 
            "//\n".
            "///////////////////////////////////////////////\n\n";

        return $output;
    }

    private function openTableClass($table){

		$columns = $table->getColumns();

        $output = 
            "class ".$table->getClassName()." {\n\n".
	        "    private \$db;\n".
			"    private \$table = \"".$table->getName()."\";\n\n";

        //Write column variables
        foreach($columns as $c){
            $output .= 
            "    private ".$c->getVarname()." = null;\n";
        }
        $output .= "\n";

        //Write varlist
        $output .=
            "    private \$varlist = array(\n";
        foreach($columns as $c){
            if($c->isPrimaryKey()) continue;    //Skip if this is the PK
            $output .=
            "        \"".$c->getName()."\"";
            if($c != end($columns)){ $output .= ",\n"; }
        }
        $output .= ");\n\n";

        //Write constructor
        $output .= 
            "    public function __construct(\$id=null){\n".
            "        \$this->id = \$id;\n".
            "        \$this->db = Query::getInstance();\n".
            "    }\n\n";
        
        return $output;
    }

	private function closeTableClass(){
		return "}\n";
	}

    private function getAccessFns($table){

		$output = 
            "    ///////////////////////////////////////////////////\n".
            "    //\n".
            "    //  Data Access Functions (Setters & Getters)\n".
            "    //\n".
            "    ///////////////////////////////////////////////////\n\n";

        //Write setters & getters
        foreach($table->getColumns() as $c){
            $access_fn = 
            "    ///////////////////////////////////////////////\n".
			"    // Functions for ".$c->getName()."\n".
            "    ///////////////////////////////////////////////\n";

			$access_fn .= $this->getColumnCheckFn($table->getName(), $c);

			$access_fn .=
            "    public function set".$c->getFnName()."(".$c->getVarname()."){\n".
            "       if(\$this->check".$c->getFnName()."(".$c->getVarname()."){\n".
            "           \$this->".strtolower($c->getName())." = ".$c->getVarname().";\n".
            "       }\n".
            "    }\n\n";
            
            $access_fn .=
            "    public function get".$c->getFnName()."(".$c->getVarname()."){\n".
            "        return \$this->".strtolower($c->getName()).";\n".
            "    }\n\n\n";

			$output .= $access_fn;
        }

        return $output;
    }

	private function getColumnCheckFn($table_name, $col){
		$check_fn = 
			"	public function check".$col->getFnName()."(".$col->getVarname()."){\n";

		if($col->isNull()){
			$check_fn .=
			"	 	//Not allowed to be NULL\n".
			"		if(Check::isNull(".$col->getVarname().")){\n".
			"			echo \"$table_name->".$col->getName()." cannot be null!\";\n".
			"		}\n";
        } else {
            $check_fn .=
            "       //Allowed to be NULL\n".
            "       if(Check::isNull(".$col->getVarname().")){ return null; }\n";
        }

        switch($col->getType()){
        	case "INT":
        	case "BIGINT":
            	$fn = "notInt";
				$ret = "return intVal(".$col->getVarname().");";
            	break;
        	case "VARCHAR":
            	$fn = "notString";
				$ret = "return ".$col->getVarname().";";
            	break;
        	case "TINYINT":
        	case "BOOLEAN":
            	$fn = "notBool";
				$ret = "return intVal(".$col->getVarname().");";
            	break;
        	case "FLOAT":
				$fn = "notFloat";
				$ret = "return floatVal(".$col->getVarname().");";
				break;
        	case "DOUBLE":
            	$fn = "notFloat";
				$ret = "return doubleVal(".$col->getVarname().");";
            	break;
            case "DATETIME":
            case "TIMESTAMP":
                $fn = null;
                $ret = "return date(\"Y-m-d H:i:s\", ".$col->getVarname().");\n";
                break;
        	default :
            	$fn = "isNull";
                $ret = "return ".$col->getVarname().";";
		}

        if(!is_null($fn)){
            $check_fn .=
            "       //Check the value\n".
            "       if(Check::$fn(".$col->getVarname().")){\n".
            "           echo \"$table_name->".$col->getName()." is invalid!\";\n".
            "           return false;\n".
            "       }\n\n";
        }

        $check_fn .=
            "       $ret\n".
            "   }\n\n";

        return $check_fn;
    } 

    private function getCommitFns(){

        $output = 
'	///////////////////////////////////////////////////
	//
	//	Commit (Insert/Update) to DB Function(s)
	//
	///////////////////////////////////////////////////
	
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

		return $output;
	}

	private function getDeleteFns(){

		$output = 
'
    ///////////////////////////////////////////////////
    //
    //  Delete from DB Function(s)
    //
    ///////////////////////////////////////////////////

	public function deleteByColumns($columns){

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

	public function delete(){
    	if($this->id) return $this->deleteByColumns(array("id"=>$id));
    	return false;
	}
';
		return $output;
	}


	private function getQueryFns($table){
		$output = 
'
    ///////////////////////////////////////////////////
    //
    //  Query DB Function(s)
    //
    ///////////////////////////////////////////////////


	public static function getAll(){
    	//Generate the query
    	$sql = "SELECT * FROM $this->table";

    	$rows = $this->db->query($sql, array());

		$data = array();
		foreach($rows as $r){
			$data[] = '.$table->getClassName().'::parseRow($r);
		}

		return $data;
	}

	public static function getbyId($id){
		return '.$table->getClassName().'::queryByColumns(array("id"=>$id));
	}

    public static function queryByColumns($columns){

        //Create the values array
        $values = array();
        foreach($columns as $c=>$v){
            $values[":".$c]=$v;
        }

        //Create Query\n";
        $sql = "SELECT FROM $this->table WHERE ";
        $keys = array_keys($columns);
        foreach($keys as $column){
            $sql.= "$column=:$column";
            if(strcmp($column, end($keys))){
                $sql.= " AND ";
            }
        }

        $rows = $this->db->query($sql, $values);

		$data = array();
		foreach($rows as $r){
            $data[] = '.$table->getClassName().'::parseRow($r);
        }

        return $data;
    }

	private static function parseRow($row){
		$'.$table->getName().' = new '.$table->getClassName().'();

';
		foreach($table->getColumns() as $c){
			$output .=
"	    \$".$table->getName()."->set".$c->getFnName()."(\$row[\"".$c->getName()."\"]);\n";
		}
		$output .=
'	
		return $'.$table->getName().';
	}
';

		return $output;
    }
    

	/********************************************

    File Creation Helper Functions

    ********************************************/

    private function getUserFns($file){

        if($this->isFileReadable($file)){
            $contents = file_get_contents($file);
            
            $user_start = strpos($contents, $this->user_func_start)+strlen($this->user_func_start);
            $user_stop = strpos($contents, $this->user_func_stop)-1;

			if(($user_start == false) || ($user_stop == false)) return null;

            $user_funcs = substr($contents, $user_start, $user_stop-$user_start);

            unset($contents);
            return trim($user_funcs);
        }
    }

    private function archiveFile($file){
        system("mv $file archive/$file.old");
    }

    public static function getFnName($name){
        $str = "";
        $parts = explode("_", $name);
        foreach($parts as $p){
            $str .= ucfirst($p);
        }
        return $str; 
	}
}


/********************************************

Helper Classes

********************************************/


class Table{

    private $table_name;
    private $class_name;
	private $columns;

    public function __construct($t_name){
        $this->table_name = $t_name;
		$this->class_name = Class_Engine::getFnName($t_name);
        $this->columns = array();
    }

    public function getName(){
        return $this->table_name;
    }

    public function getClassName(){
        return $this->class_name;
    }

    public function addColumn($col){
        $this->columns[] = $col;
    }

    public function getColumns(){
        return $this->columns;
    }

    public function setPrimaryKey($column_name){
        foreach($this->columns as &$c){
            if(!strcmp($c->getName(), $column_name)){
                $c->setPrimaryKey(true);
                break;
            }
        }
    }

    public function setForeignKey($column_name, $ref_table, $ref_col){
        foreach($this->columns as &$c){
            if(!strcmp($c->getName(), $column_name)){
                $c->setForeignKey($ref_table, $ref_col);
                break;
            }
        }
    }
}

class Column {

    private $col_name = null;
    private $col_fn_name = null;
    private $varname = null;
    private $col_type = null;
    private $is_null = null;
    private $is_primary_key = null;
    private $foreign_key = null;

    public function __construct($c_name, $c_type, $is_null){
        $this->col_name = $c_name;
        $this->varname = "$".strtolower($c_name);
        $this->col_fn_name = Class_Engine::getFnName($c_name);
        $this->col_type = $c_type;
        $this->is_null = $is_null;
    }

    public function getName(){
        return $this->col_name;
    }

    public function getVarname(){
        return $this->varname;
    }

    public function getFnName(){
        return $this->col_fn_name;
    }

    public function getType(){
        return $this->col_type;
    }

    public function isNull(){
        return $this->is_null;
    }

    public function setPrimaryKey($bool){
        $this->is_primary_key = $bool;
    }

    public function isPrimaryKey(){
        return $this->is_primary_key;
    }

    public function setForeignKey($table, $column){
        $this->foreign_key = array("table"=>$table, "column"=>$column);
    }

    public function getForeignKey(){
        return $this->foreign_key;
    }
}

?>
