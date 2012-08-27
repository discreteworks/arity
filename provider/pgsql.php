<?php
/**
 * Arity
 *
 * Web Framework for PHP 5.2 or newer
 *
 * Licensed under GPL
 * Redistributions of files must retain the above copyright notice.
 *
 * @package		Arity
 * @author		codendev
 * @copyright           Copyright (c) 2011 - 2012, CodenDev.
 * @license		http://gnu.org/licenses/gpl.txt
 * @link		http://arity.abideen.com
 * @since		Version 1.0
 * @filesource
 */

class Pgsql extends Provider {

	// Join base entity table with other tables

	private $DB;

	private $host;

	private $name;

	private $username;

	private $password;

	private $join=array();

	public function  initialize() {

		// Initialize database variables
		$this->host      = PGSQL_HOST;

		$this->name      = PGSQL_DB_NAME;

		$this->username  = PGSQL_USERNAME;

		$this->password  = PGSQL_PASSWORD;

	}

	function __destruct() {

		$this->showDebug();

		if( $this->result ) {

			@pg_free_result($this->result);
			return $this;

		}

		@pg_free_result($this->result);
		return false;
	}

	// Do we have a valid read-only database connection?
	public function isConnected() {

		return is_resource($this->DB) && get_resource_type($this->DB) == 'pgsql link';

	}

	// Do we have a valid database connection and have we selected a database?
	public function databaseSelected() {

		if(!$this->isConnected()) return false;

		$result = pg_list_tables($this->name, $this->DB);

		return is_resource($result);
	}

	public function connect() {

		$this->DB = pg_connect("host=".$this->host." port=5432 dbname=".$this->name." user=".$this->username." password=".$this->password."");

		if($this->DB === false) return false;

		return $this->isConnected();
	}

	function delete($table,$columnName,$value) {

		$this->execute("DELETE FROM \"$table\" WHERE $columnName =". $this->escape($value));
	}

	function insert($table,$attributes) {

		$cmd = 'INSERT INTO';

		$data = array();

		foreach($attributes as $k => $v)

			if(!is_null($v))

				$data[$k] = $this->quote($v);

		$columns = '' . implode(', ', array_keys($data)) . '';

		$values = implode(',', $data);

		$sql="{$cmd} \"{$table}\" ({$columns}) VALUES ({$values})";

		$this->execute($sql);

		return $this->pg_last_inserted_id($table);
	}

	function update($table,$attrib,$cColumnName,$cValue) {

		$sql = "UPDATE \"{$table}\" SET ";

		foreach($attrib as $k => $v)

			$sql .= "$k=" . $this->quote($v) . ',';

		$sql[strlen($sql) - 1] = ' ';

		$sql .= "WHERE {$cColumnName} = " . $this->quote($cValue);

		$this->execute($sql);

		return $cValue;

	}

	public function tableExists($table) {

		$sql="SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'
		and table_name  LIKE '{$table}'";

		$rs=$this->query($sql);

		if($rs)

		   return $this->hasRows($rs);



		return false;
	}

	public function droptable($table) {

		$sql="DROP TABLE \"".$this->escape($table)."\"";

		$rs=$this->execute($sql);

	}

	public function truncate($table) {

		$sql="DELETE FROM \"".$table."\"";
			
		$rs=$this->execute($sql);

	}

	private function buildFieldConstraint($table,$fieldAttributes) {

		$constraints=array();

		$fields=array();

		foreach($fieldAttributes as $item) {

			$fields[]=$this->makeField($item);

			if($item->composite!=ARITY_COMPOSITE) {
					
				$constraint=$this->makeConstraint($table, $item);
					
				if($constraint)
				$constraints[]=$constraint;
			}
		}
			
		$primary=$this->makeCompositeConstraint($table,$fieldAttributes);
			
		if($primary) {


			$constraints[]=$primary;
		}
			
		$unique=$this->makeCompositeConstraint($table,$fieldAttributes,ARITY_UNIQUE);
			
			
		if($unique) {

			$constraints[]=$unique;
		}
		return array("fields"=>$fields,"constraints"=>$constraints);



	}


	public function createTable($table,$fieldAttributes) {

		if(!$this->tableExists($table)) {


			$result=$this->buildFieldConstraint($table, $fieldAttributes);

			if(count($result["constraints"])) {
				$cStr=",".implode(",",$result["constraints"]);
			}
			else {

				$cStr="";
			}

			$table="CREATE TABLE \"".$table."\" (".implode(",",$result["fields"]).$cStr." )";

			return $this->execute($table);

		}

		return false;

	}

	private function makeConstraint($table,$fieldAttribute) {

		if($fieldAttribute->key==ARITY_PRIMARY && $fieldAttribute->composite==ARITY_NULL)

		return " CONSTRAINT \"".$table."_".$fieldAttribute->name."_pk\" PRIMARY KEY (\"".$fieldAttribute->name."\")";

		if($fieldAttribute->key==ARITY_UNIQUE && $fieldAttribute->composite==ARITY_NULL)

		return " CONSTRAINT \"".$table."_".$fieldAttribute->name."_u\" UNIQUE (\"".$fieldAttribute->name."\")";

	}

	private function makeCompositeConstraint($table,$fieldAttributes,$type=ARITY_PRIMARY) {

		$compositePrimary=array();

		$compositeUnique=array();

		foreach($fieldAttributes as $item) {

			if($item->composite==ARITY_COMPOSITE)
			{

				if($item->key==ARITY_PRIMARY)

				$compositePrimary[]=$item->name;

				if($item->key==ARITY_UNIQUE)

				$compositeUnique[]=$item->name;
			}

		}
		if(count($compositePrimary)>0 && $type==ARITY_PRIMARY) {

			return "CONSTRAINT \"".$table."_".implode("_",$compositePrimary)."_pk\" PRIMARY KEY (\"".implode("\",\"",$compositePrimary)."\")";

		}
		elseif(count($compositeUnique)>0 && $type==ARITY_UNIQUE) {

			return "CONSTRAINT \"".$table."_".implode("_",$compositeUnique)."\" UNIQUE (\"".implode("\",\"",$compositeUnique)."\")";

		}

	}

	private function primaryKeyExist($table) {

		$sql="SELECT
		pg_attribute.attname,
		format_type(pg_attribute.atttypid, pg_attribute.atttypmod)
		FROM pg_index, pg_class, pg_attribute
		WHERE
		pg_class.oid = '".$table."'::regclass AND
		indrelid = pg_class.oid AND
		pg_attribute.attrelid = pg_class.oid AND
		pg_attribute.attnum = any(pg_index.indkey)
		AND indisprimary"	;

		$primaryKeys = $this->query("SELECT * FROM information_schema.columns WHERE table_name = '{$relation}'");

		if($primaryKeys) {

			if( $this->getRowCount($primaryKeys)>0) {
				return true;

			}

		}

		return false;

	}

	private function makeField($fieldAttribute) {

		$textSize=!isset($fieldAttribute->size)?TEXT_LENGTH:$fieldAttribute->size;

		$decimalSize=!isset($fieldAttribute->size)?DECIMAL_LENGTH:$fieldAttribute->size;

		$numberSize=!isset($fieldAttribute->size)?NUMBER_LENGTH:$fieldAttribute->size;

		$required="";

		if($fieldAttribute->required==ARITY_REQUIRED) {

			$required=' NOT NULL ';

		}

		if($fieldAttribute->default!=ARITY_EMPTY) {

			$required.= ' DEFAULT \''.$fieldAttribute->default.'\' ';

		}
		switch(strtoupper($fieldAttribute->type)) {

			case ARITY_VARCHAR:

				$type=" VARCHAR($textSize) ";
				break;

			case ARITY_SERIAL:

				$type=" SERIAL ";
				break;

			case ARITY_INT:

				$type=" INTEGER ";
				break;
					
			case ARITY_BIGINT:

				$type=" BIGINT ";
				break;
				
			case ARITY_SMALLINT:

				$type=" SMALLINT ";
				break;

			case ARITY_NUMERIC:

				$type=" NUMERIC($numberSize) ";
				break;

			case ARITY_DECIMAL:

				$type=" DECIMAL ($decimalSize) ";
				break;

			case ARITY_TEXT:

				$type=" TEXT ";
				break;

			case ARITY_DATE:

				$type=" DATE ";
				break;

			case ARITY_BINARY:
					
				$type=" BYTEA ";
				break;

		}

		return $field="\"".$fieldAttribute->name."\"" . $type . $required;
	}

	public function addField($table,$fieldAttributes) {

		$result=$this->buildFieldConstraint($table,$fieldAttributes);

		if(count($result["constraints"])) {

			$cStr=", ADD ".implode(", ADD ",$result["constraints"]);
		}
		else {

			$cStr="";

		}

		$fstr=implode(", ADD COLUMN ",$result["fields"]);

		$sql="ALTER TABLE \"".$table."\" ADD COLUMN ".$fstr. $cStr;

		$rs=$this->execute($sql);

	}

	public function fieldExists($relation,$field) {

		$fields = $this->query("SELECT * FROM information_schema.columns WHERE table_name = '{$relation}'");

		$field_array=array();

		if($fields) {

			$rs =  $this->getRows($fields);

			$columns =  $this->getRowCount($fields);

			for ($i = 0; $i < $columns; $i++) {

				$field_array[] = $rs[$i]['columns']['column_name'];

			}
		}

		return in_array($field, $field_array);

	}

	function setTable($table) {

		$this->table=$table;

	}

	/**
	 * Build the conditional statements.
	 * @param Associative Array $keyPairValues.
	 * @param String $type Login operator AND OR.
	 * @param String $compare condition.
	 * @return Boolean $prefix.
	 */

	public function setCondition($keyPairValues,$type,$compare) {

		$cond="";

		$msgArray=array();

		foreach($keyPairValues as $key=>$value) {

			$msgArray[]="$key $compare'".$value."'";
		}

		$cond=implode(" ".$type." ", $msgArray);

		if(count($this->condition)>0) {

			$cond= $type." ".$cond;
		}

		$this->condition[]=$cond;
	}

	public function getCondition() {

		return $this->condition;
	}

	public function setHavingCondition($keyPairValues,$type,$compare) {

		$cond="";

		$msgArray=array();

		foreach($keyPairValues as $key=>$value) {

			$msgArray[]="$key $compare'".$value."'";
		}

		$cond=implode(" ".$type." ", $msgArray);

		if(count($this->havingCondition)>0) {

			$cond= $type." ".$cond;
		}

		$this->havingCondition[]=$cond;

	}

	public function setGroupBy($column) {

		$selected= "";

		if(isset($column)) {

			$objColumnEx=explode(".", $column);

			if(count($objColumnEx)>1) {

				$selected= " \"".$objColumnEx[0]."\".".$objColumnEx[1];

			}
			else{

				$selected=$objColumnEx[0];
			}
		}

		$this->groupByItem[]=$selected;

	}


	public function setSelect($objColumn,$operator=null)
	{
		$selected= "";

		if(isset($objColumn)) {

			$objColumnEx=explode(".", $objColumn);

			if(count($objColumnEx)>1) {

				$selected= " \"".$objColumnEx[0]."\".".$objColumnEx[1];

			}
			else{

				$selected= " \"".$objColumnEx[0]."\".* ";
			}
		}

		if(isset($operator)) {

			$selected = $operator."(".$selected.")";

		}

		$this->load[]=$selected;

	}

	function buildQuery($table,$obj,$limit=TRUE) {

		if(count($this->load)==0) {

			foreach($obj as $item) {

				$this->load[]= "\"".strtolower($item)."\".*";

			}
		}

		$fields=implode(',', $this->load);

		$query="SELECT $fields FROM \"$table\"";

		foreach($this->join as $item ) {

			$query.=" ".$item." \n";
		}

		if(count($this->condition)>0) {

			$query.=" WHERE ";

			foreach($this->condition as $key=>$item ) {

				$query.=" ".$item." \n";

			}
		}

		if(count($this->groupByItem)>0) {

			$query.=" GROUP BY ".implode(",", $this->groupByItem)." \n";

		}

		if(count($this->havingCondition)>0) {

			$query.=" HAVING ";

			foreach($this->havingCondition as $item) {

				$query.=" ".$item." \n";

			}
		}

		if(isset($this->limit) && $limit)

			$query.=" ".$this->limit;

		// Clear joins

		$this->join=array();

		$this->load=array();

		$this->condition=array();

		$this->groupByItem= array();

		$this->havingCondition=array();

		$this->query($query);

		return $this->getRows();

	}

	function setLimit($start=0, $offset=1) {

		$this->start=$start;

		$this->offset=$offset;

		$this->limit= "LIMIT ".$this->escape($start)." OFFSET ". $this->escape($offset);

	}

	function createJoin($rObject,$table,$idColumnName,$type=ARITY_11) {

		if($type==ARITY_11) {

			$this->join[]="LEFT JOIN \"".$rObject->reference."\" on \"".$table."\".".$rObject->name."=\"".$rObject->reference."\".".$rObject->referenceField;

		}
		elseif($type==ARITY_MM) {

			$this->join[]="LEFT JOIN ".$table."_".$rObject->reference." on ".$table.".".$idColumnName."= ".$table."_".$rObject->reference.".".$rObject->referenceField;

			$this->join[]="LEFT JOIN ".$rObject->reference." on ".$table."_".$rObject->reference.".".$rObject->reference."=".$rObject->reference.".".$rObject->referenceField;

		}
		elseif($type==ARITY_1M) {

			$this->join[]="LEFT JOIN \"".$rObject->reference."\" on \"".$table."\".".$rObject->name."=\"".$rObject->reference."\".".$rObject->referenceField;

		}

	}

	public function query($sql) {

		$sql = trim($sql);

		if(preg_match('/^(INSERT|UPDATE|REPLACE|DELETE)/i', $sql) == 0) {

			if(!$this->isConnected())

				$this->connect();

			$the_db = $this->DB;

			$this->queries[] = $sql;
				
			$this->result = pg_query($the_db,$sql) or $this->notify();

		}

		return $this->result;

	}

	public function beginTransaction() {

		$sql="BEGIN";

		$this->execute($sql);

	}

	public function endTransaction() {

		$sql="COMMIT";

		$this->execute($sql);

	}

	public function execute( $sql ) {

		$this->queries[]=$sql;

		if(!$this->isConnected())

		$this->connect();

		try{

			$this->result = pg_query($this->DB,$sql);

		}
		catch(Exception $e) {

			return false;
		}

	}

	// Returns the number of rows.
	// You can pass in nothing, a string, or a db result
	public function getRowCount($arg = null) {

		$result = $this->resulter($arg);

		return ($this->result !== false) ? pg_num_rows($this->result) : false;

	}

	// Returns true / false if the result has one or more rows
	public function hasRows($arg = null) {

		$result = $this->resulter($arg);

		return is_resource($this->result) && (pg_num_rows($this->result) > 0);

	}

	// Returns the number of rows affected by the previous WRITE operation
	public function affectedRows() {

		if(!$this->isConnected()) return false;

		return pg_affected_rows($this->result);

	}

	// Returns the auto increment ID generated by the previous insert statement
	public function insertId() {

		if(!$this->isConnected()) return false;

		return pg_last_oid($this->result);

	}

	function pg_last_inserted_id($table) {

		if(!$this->isConnected()) return false;

		$sql = "SELECT * FROM  \"" . $table."\"";

		$ret = pg_query($this->DB, $sql);

		$sql = "SELECT currval('{$table}_".ARITY_IDENTITY."_seq')";

		$retorno =pg_query($this->DB, $sql);
			
		if(pg_num_rows($ret)>0) {

			$s_dados = pg_fetch_all($retorno);

			extract($s_dados[0],EXTR_OVERWRITE);

			return $currval;

		} 

		return false;
	}

	// Returns a single value.
	// You can pass in nothing, a string, or a db result
	public function getValue($arg = null) {

		$result = $this->resulter($arg);

		return $this->hasRows($this->result) ? pg_result($this->result, 0, 0) : false;

	}

	// Returns an array of the first value in each row.
	// You can pass in nothing, a string, or a db result
	public function getValues($arg = null) {

		$result = $this->resulter($arg);

		if(!$this->hasRows($result)) return array();

		$values = array();

		pg_data_seek($result, 0);

		while($row = pg_fetch_array($result, PGSQL_ASSOC))

		$values[] = array_pop($row);

		return $values;
	}

	// Returns the first row.
	// You can pass in nothing, a string, or a db result
	public function getRow($arg = null) {

		$result = $this->resulter($arg);

		return $this->hasRows($result) ? pg_fetch_array($result, PGSQL_ASSOC) : false;
	}

	// Returns an array of all the rows.
	// You can pass in nothing, a string, or a db result
	public function getRows($arg = null) {

		$resultRow=array();

		$result = $this->resulter($arg);

		$this->resultSet($result);

		if(!$this->hasRows($result)) return array();

		$rows = array();

		pg_result_seek($result, 0);

		// var_dump($this->map[4]);exit;

		while($row = pg_fetch_row($result)) {

			foreach ($row as $key=>$item) {

				list($table, $column) = $this->map[$key];

				$resultRow[$table][$column] = $item;

			}

			$rows[]=$resultRow;

		}

		return $rows;
	}

	public function resultSet($results) {

		if (isset($this->results) && is_resource($this->results) && $this->results != $results) {

			pg_free_result($this->results);
		}

		$this->map = array();

		$numFields = pg_num_fields($results);

		$index = 0;

		$j = 0;

		while ($j < $numFields) {

			$column =pg_field_name($results,$j);

			$table=pg_field_table($results, $j);

			if (!empty($table)) {

				$this->map[$index++] = array($table, $column);
			}
			else {

				$this->map[$index++] = array(0, $column[0]);

			}

			$j++;
			//var_dump($this->map);
		}
	}

	// Escapes a value and wraps it in single quotes.
	public function quote($var) {

		return "'" . $this->escape($var) . "'";

	}

	// Escapes a value.
	public function escape($var) {

		if(!$this->isConnected()) $this->connect();

		return pg_escape_string($this->DB,$var);

	}

	public function numQueries() {

		return count($this->queries);

	}

	public function lastQuery() {

		if($this->numQueries() > 0)

			return $this->queries[$this->numQueries() - 1];

		return false;

	}

	public function resulter($arg = null) {

		if(is_null($arg) && is_resource($this->result))

			return $this->result;

		elseif(is_resource($arg))

			return $arg;

		elseif(is_string($arg)) {

			$this->query($arg);

			if(is_resource($this->result))

				return $this->result;

			return false;
		}

		return false;

	}
}
