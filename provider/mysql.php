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
 * @author		Shahrukh Hussain
 * @copyright           Copyright (c) 2011 - 2012, Abideen Business Consulting.
 * @license		http://www.gnu.org/licenses/gpl.txt
 * @link		http://arity.abideen.com
 * @since		Version 1.0
 * @filesource
 */

class Mysql extends Provider {


	public function  initialize() {

		//Initialize database variables
		$this->host      = MYSQL_HOST;
		$this->name          = MYSQL_DB_NAME;
		$this->username  = MYSQL_USERNAME;
		$this->password  = MYSQL_PASSWORD;


	}

	// Do we have a valid read-only database connection?
	public function isConnected() {
		return is_resource($this->DB) && get_resource_type($this->DB) == 'mysql link';
	}


	// Do we have a valid database connection and have we selected a database?
	public function databaseSelected() {
		if(!$this->isConnected()) return false;
		$result = mysql_list_tables($this->name, $this->DB);
		return is_resource($result);
	}

	public function connect() {
		$this->DB = mysql_connect($this->host, $this->username, $this->password) or $this->notify();
		if($this->DB === false) return false;

		if(!empty($this->name))
			mysql_select_db($this->name, $this->DB) or $this->notify();

		return $this->isConnected();
	}

	public function relationExists($relation) {

		$rs=$this->query("SHOW TABLES LIKE '".$relation."'");

		return $this->hasRows($rs);

	}
	public function createRelation($relation) {

		$rs=$this->query("SHOW TABLES LIKE '".$relation."'");

		if(!$this->relationExists($relation)) {

			return $this->execute("CREATE TABLE `".$relation."`"."(id int(11) NOT NULL AUTO_INCREMENT,  `created` datetime, `updated` datetime,PRIMARY KEY (id))");

		}
		else {

			return false;

		}

	}
	function delete($relation,$cColumnName,$cValue) {

		$this->db->query("DELETE FROM `$relation` WHERE `$cColumnName` =". $this->escape($cValue));
	}

	function makeQuery($entity,$table){

		$obj=$entity->obj;

		$load=$entity->load;

		$join=$entity->join;

		$condition=$entity->condition;

		if(count($load)==0) {

			foreach($obj as $item) {

				$load[]= $this->spQuote(strtolower($item)).".*";

			}
		}

		$fields=implode(',', $load);

		$query="SELECT $fields FROM ".$this->spQuote($table);

		foreach($join as $item ) {

			$query.=" ".$item." \n";
		}

		foreach($condition as $item ) {

			$query.=" ".$item." \n";
		}
		foreach($entity->groupby as $item){

			$query.=" ".$item." \n";

		}

		return $query;

	}
	function addLimit($start=0,$offset=1){

		return "LIMIT (".$this->escape($start)." , ". $this->escape($offset).")";

	}
	function createJoin($rObject,$table,$idColumnName,$type='onetoone') {
		
		if($type=='onetoone') {
			
			$this->join[]="LEFT JOIN `".$rObject->relation."` on ".$table.".".$rObject->name."=".$rObject->relation.".".$rObject->relationField;
		
		}
		elseif($type=='manytomany') {
			
			$this->join[]="LEFT JOIN `".$table."_".$rObject->relation."` on ".$table.".".$idColumnName."= ".$table."_".$rObject->relation.".".$rObject->relationField;

			$this->join[]="LEFT JOIN `".$rObject->relation."` on ".$table."_".$rObject->relation.".".$rObject->relation."=".$rObject->relation.".".$rObject->relationField;
		}
		
		elseif($type=='onetomany') {

			$this->join[]="LEFT JOIN `".$rObject->relation."` on ".$table.".".$idColumnName."=".$rObject->relation.".".$rObject->relationField;

		}
	}
	public function insert($table,$attributes) {

		$cmd = 'INSERT INTO';

		$data = array();

		foreach($attributes as $k => $v)

			if(!is_null($v))

			$data[$k] = $this->quote($v);

		$columns = '`' . implode('`, `', array_keys($data)) . '`';

		$values = implode(',', $data);

		$this->execute("$cmd `{$table}` ($columns) VALUES ($values)");

		$obj->id = $this->insertId();

	}
	
	function update($table,$attrib,$cColumnName,$cValue) {

		$sql = "UPDATE `{$table}` SET ";

		foreach($attrib as $k => $v)

			$sql .= "`$k`=" . $this->quote($v) . ',';

		$sql[strlen($sql) - 1] = ' ';

		$sql .= "WHERE `{$cColumnName}` = " . $this->quote($cValue);

		$this->execute($sql);

		return $this->affectedRows();

	}

	public function fieldExists($relation,$field) {

		$fields = $this->query("SHOW COLUMNS FROM `$relation`");

		$rs =  $this->getRows($fields);

		$columns =  $this->numRows($fields);

		for ($i = 0; $i < $columns; $i++) {

			$field_array[] = $rs[$i]['COLUMNS']["Field"];

		}

		return in_array($field, $field_array);



	}
	public function addField($relation,$fieldAttribute,$preceed) {

		$textSize=!isset($fieldAttribute->size)?TEXT_LENGTH:$fieldAttribute->size;

		$decimalSize=!isset($fieldAttribute->size)?DECIMAL_LENGTH:$fieldAttribute->size;

		$numberSize=!isset($fieldAttribute->size)?NUMBER_LENGTH:$fieldAttribute->size;


		if($fieldAttribute->required==ARITY_REQUIRED) {

			$required=' NOT NULL ';

		}

		if(isset($fieldAttribute->default)) {

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

				$type=" BINARY ";

				break;

		}

		if(empty($preceed)) {

			$preceed='id';
		}

		$sql="ALTER TABLE `".$relation."`"." ADD `$fieldAttribute->name` $type $required AFTER `".$preceed."`";

		$rs=$this->execute($sql);

		return $fieldAttribute->name;

	}

	public function query($sql) {

		$sql = trim($sql);
		if(preg_match('/^(INSERT|UPDATE|REPLACE|DELETE)/i', $sql) == 0) {
			if(!$this->isConnected())
				$this->connect();

			$the_db = $this->DB;
			$this->queries[] = $sql;
			$this->result = mysql_query($sql, $the_db) or $this->notify();
		}
		return $this->result;

	}
	public function execute( $sql ) {

		if(!$this->isConnected())
			$this->connect();
		$this->result = mysql_query($sql, $this->DB);

		if( $this->result ) {
			@mysql_free_result($this->result);
			return $this;
		}
		else {
			@mysql_free_result($this->result);
			return false;
		}
	}

	// Returns the number of rows.
	// You can pass in nothing, a string, or a db result
	public function numRows($arg = null) {
		$result = $this->resulter($arg);
		return ($result !== false) ? mysql_num_rows($result) : false;
	}

	// Returns true / false if the result has one or more rows
	public function hasRows($arg = null) {
		$result = $this->resulter($arg);
		return is_resource($result) && (mysql_num_rows($result) > 0);
	}

	// Returns the number of rows affected by the previous WRITE operation
	public function affectedRows() {
		if(!$this->isConnected()) return false;
		return mysql_affected_rows($this->DB);
	}

	// Returns the auto increment ID generated by the previous insert statement
	public function insertId() {
		if(!$this->isConnected()) return false;
		return mysql_insert_id($this->DB);
	}

	// Returns a single value.
	// You can pass in nothing, a string, or a db result
	public function getValue($arg = null) {
		$result = $this->resulter($arg);
		return $this->hasRows($result) ? mysql_result($result, 0, 0) : false;
	}

	// Returns an array of the first value in each row.
	// You can pass in nothing, a string, or a db result
	public function getValues($arg = null) {
		$result = $this->resulter($arg);
		if(!$this->hasRows($result)) return array();

		$values = array();
		mysql_data_seek($result, 0);
		while($row = mysql_fetch_array($result, MYSQL_ASSOC))
			$values[] = array_pop($row);
		return $values;
	}

	// Returns the first row.
	// You can pass in nothing, a string, or a db result
	public function getRow($arg = null) {
		$result = $this->resulter($arg);
		return $this->hasRows($result) ? mysql_fetch_array($result, MYSQL_ASSOC) : false;
	}

	// Returns an array of all the rows.
	// You can pass in nothing, a string, or a db result
	public function getRows($arg = null) {
		$resultRow=array();
		$result = $this->resulter($arg);
		$this->resultSet($result);
		if(!$this->hasRows($result)) return array();
		$rows = array();
		mysql_data_seek($result, 0);
		// var_dump($this->map[4]);exit;

		while($row = mysql_fetch_row($result)) {


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
			mysql_free_result($this->results);
		}
		$this->map = array();
		$numFields = mysql_num_fields($results);
		$index = 0;
		$j = 0;

		while ($j < $numFields) {

			$column = mysql_fetch_field($results,$j);

			if (!empty($column->table)) {
				$this->map[$index++] = array($column->table, $column->name);
			} else {
				$this->map[$index++] = array(0, $column->name);
			}
			$j++;

		}
		//var_dump($this->map);
	}


	// Escapes a value and wraps it in single quotes.
	public function quote($var) {
		return "'" . $this->escape($var) . "'";
	}

	// Escapes a value.
	public function escape($var) {
		if(!$this->isConnected()) $this->connect();
		return mysql_real_escape_string($var, $this->DB);
	}

	public function numQueries() {
		return count($this->queries);
	}

	public function lastQuery() {
		if($this->numQueries() > 0)
			return $this->queries[$this->numQueries() - 1];
		else
			return false;
	}

	// Takes nothing, a MySQL result, or a query string and returns
	// the correspsonding MySQL result resource or false if none available.
	public function resulter($arg = null) {
		if(is_null($arg) && is_resource($this->result))
			return $this->result;
		elseif(is_resource($arg))
		return $arg;
		elseif(is_string($arg)) {
			$this->query($arg);
			if(is_resource($this->result))
				return $this->result;
			else
				return false;
		}
		else
			return false;
	}

	private function spQuote($str){
		 
		return "`".$str."`";
		 
		 
	}
	private function notify(){
		 
		echo "Mysql database error";
		 
	}


}
