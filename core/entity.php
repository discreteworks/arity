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
 * @copyright   Copyright (c) 2011 - 2012, Abideen Business Consulting.
 * @license		http://www.gnu.org/licenses/gpl.txt
 * @link		http://arity.abideen.com
 * @since		Version 1.0
 * @filesource
 */

class Entity {

	// Base entity on which operations are added
	protected $base;

	//Database connection instance
	public $db;

	//Filter conditionsp
	private $condition=array();



	//Group by base entity table with other tables
	private $groupby=array();

	//Having conditions
	private $having=array();

	// Object array of entities
	private $obj=array();

	//Entities to be loaded prior to mapping
	private $load=array();

	//Depth restriction of the joins 0 to n
	private $depth=0;

	//Current level of the join 0 to n
	private $level=0;

	//Name of entities mapped
	private $map=array();

	//Limit index
	private $start=0;

	//Limit offset
	private $offset=1;

	// Count of instances of entities object compile
	private $object_count=0;

	/*
	 * Entity Constructor
	*/
	public function  __construct($baseIn,$dbInstance) {

		$this->base=$baseIn;

		$this->db=$dbInstance;

	}

	/**
	 * Installs the entity in database.
	 * Creates relation(table) in the database.
	 */
	public function createTable() {

		$table=strtolower(get_class($this->base));

		if(!$this->db->tableExists($table)){

			$this->createEntity($table);

		}
		else{

			$this->alterEntity($this);

		}

	}

	/**
	 * Truncate
	 * Removes all the row from the relation
	 */
	public function truncateTable() {


		$table=strtolower(get_class($this->base));

		$this->db->truncate($table);

	}

	/**
	 * Installs the entity in database.
	 * Creates relation(table) in the database.
	 */
	public function removeTable() {


		$table=strtolower(get_class($this->base));

		if($this->db->tableExists($table))

			$this->db->dropTable($table);
	}


	private function alterEntity(){


		$fieldAttributes=array();

		$table=strtolower(get_class($this->base));

		$attributes=get_object_vars($this->base);

		foreach ($attributes as $key=>$item) {

			if(!$this->db->fieldExists($table,$key)) {

				if(get_class($table::$meta->$key)==ARITY_TYPE) {

					$fieldAttributes[]=$table::$meta->$key;


				}

			}

		}

		if($fieldAttributes)

			$this->db->addField($table,$fieldAttributes);



	}


	/**
	 * Create new entity table in database. If entity table exists
	 * in database drop and re-create in the database.
	 */
	private function createEntity($table) {


		$relationFlag=true;

		//Read attributes of the model

		$attributes=get_object_vars($this->base);

		if(count($attributes)>0){

			foreach ($attributes as $key=>$item) {
				if(get_class($table::$meta->$key)==ARITY_TYPE) {
					$fieldAttributes[]=$table::$meta->$key;

				}
			}

			$this->db->createTable($table,$fieldAttributes);
		}
			

	}

	/**
	 * Recursive method which form the query to get entities database resultset.
	 * @param Object Entity object
	 * @return Object Entitydecorator
	 */
	private function chain($obj=null) {

		$id= ARITY_IDENTITY;

		$this->level=$this->level+1;

		if($obj==null) {

			$ref=$this->base;
		}
		else {

			$ref=$obj;

		}

		$this->obj[strtolower(get_class($ref))]=get_class($ref);

		$attributes=get_object_vars($ref);

		$table=strtolower(get_class($ref));

		foreach($attributes as $key=>$name) {


			if(@get_class($table::$meta->$key)=='Reference'&&!isset($this->obj[strtolower($table::$meta->$key->reference)])&&$this->level<$this->depth) {

				$this->db->createJoin($table::$meta->$key,$table,$id,$table::$meta->$key->map);

				$ref=$table::$meta->$key->reference;

				$this->chain(new $ref);


			}

		}

		return $this;

	}

	/**
	 * Fetches the entities from database with level of depth.
	 * @param integer $depth the level of entities with respect to join
	 * @return Object Entitydecorator
	 */

	public function fetch($depth=null) {

		$this->depth=$depth;

		$this->chain();

		return $this;

	}

	/**
	 * Loads entities table
	 * @param String Entities names to load
	 * @return Object
	 */

	function loads($obj) {

		$this->load[]="`".$obj."`.*";

		return $this;

	}

	/**
	 * Recursive method which maps the entities with database resultset.
	 * @param Object Entity object
	 * @param Array resultset result returned from database
	 * @param Array $condition recursive call terminating condition
	 * @return Array Entity objects
	 */

	private function mapping($object,$resultset,$condition=null,$referenceField=null) {


		$id= ARITY_IDENTITY;

		$c= $object;

		$objectArray=array();

		$tempArray=array();

		$name=strtolower(get_class($object));

		$this->map[]=$name;

		$this->map=array_unique($this->map);

		$attrib=get_object_vars($c);
		//var_dump($attrib);
		//if($name=="group"){  var_dump($condition);return null;}

		foreach($resultset as $key=>$item) {

			if(!array_key_exists($name, $resultset[$key])) {


				return null;

			}

			foreach($attrib as $vkey=>$var) {


				if(@get_class(@$c::$meta->$vkey)=="Type") {


					if($condition!=NULL) {

						// 						if($name=="group") {
						// 							var_dump($resultset[$key][$name][$referenceField]);;exit;
						// 						}

						if($resultset[$key][$name][$referenceField]==$condition[key($condition)]) {

							$c->$vkey=$resultset[$key][$name][$vkey];

							if($name=="objectfield") {

							}

						}
						else {
							if($name=="objectfield" and $c->name==null) {
									
							}

						}

					}
					else {

						$c->$vkey=$resultset[$key][$name][$vkey];

						if($name=="user" ) {


						}
					}

				}
				elseif(@get_class(@$c::$meta->$vkey)=="Reference") {

					if($c::$meta->$vkey->map==ARITY_11&& (in_array($c::$meta->$vkey->reference, $this->map)==false || array_search($c::$meta->$vkey->reference, $this->map)>array_search($name, $this->map))) {

						$cond1=array($id=>$resultset[$key][$name][$vkey]);

						$ores=$this->mapping(new $vkey(), $resultset,$cond1);

						if(isset($ores[0])) {

							$c->$vkey=$ores[0];

						}
					}
					elseif($c::$meta->$vkey->map==ARITY_1M&&(in_array($c::$meta->$vkey->reference, $this->map)==false || array_search($c::$meta->$vkey->reference, $this->map)>array_search($name, $this->map))) {

						//var_dump($resultset[$key][$name]);

						$cond2=array($c::$meta->$vkey->name=>$resultset[$key][$name][$c::$meta->$vkey->name]
						);

						$or=$this->mapping(new $c::$meta->$vkey->reference, $resultset,$cond2,$c::$meta->$vkey->referenceField);

						$c->$vkey=$or;

					}
					else {

						unset($c->$vkey);

					}

				}

			}
			if($name=="objectfield" and $c->name==null) {
				// debug statements

			}


			$c_clone=clone $c;

			if(!array_key_exists(md5($c_clone->id), $tempArray)&&$c->$id!=null) {

				$tempArray[md5($c_clone->id)]=md5(serialize( $c_clone));

				$objectArray[]= $c_clone;


			}


		}

		return $objectArray;

	}

	/**
	 * Standard compile of the result set without mapping to entities
	 * @return Associative Array resultset of entities from database.
	 */

	function native($sql=null) {

		if($sql==null){

			$table=strtolower(get_class($this->base));

			if($this->obj==null){

				echo "<span>Please call the fetch method prior to native result";
				return;
			}

			$this->query=$this->
			db->
			makeQuery($table,$this->obj,
					$this->load,
					$this->join,
					$this->condition);

			if(isset($this->limit))

				$this->query.=" ".$this->limit;
			echo $this->query;
			$this->db->query($this->query);
		}
		else{

			$this->db->query($sql);

		}
		$rows=$this->db->getRows();

		$objectArray=array();

		foreach($rows as $key=>$item) {


			$ci=key($item);

			$c=new $ci;

			$attrib=get_object_vars($c);

			foreach($attrib as $vkey=>$var) {

				$c->$vkey=$rows[$key][key($item)][$vkey];

			}

			$objectArray[md5(serialize($c))]=$c;
		}



		return $rows;

	}

	/**
	 * Count the results from mapped entities resultset.
	 * @return integer count of entity instances or number of rows.
	 */

	function object_count() {

		return $this->object_count;
	}

	/**
	 * Count the results from database resultset.
	 * @return integer number of rows.
	 */

	function count() {

		$objs=array();

		$table=strtolower(get_class($this->base));

		if($this->obj==null){

			echo "Please call the fetch method prior to count";
			return;
		}

		$this->query=$this->
		db->
		makeQuery($table,$this->obj,
				$this->load,
				$this->join,
				$this->condition);

		$this->db->query($this->query);

		return $this->db->numRows();

	}

	/**
	 * Object compile of database resultset of entities with mapping.
	 * @return Associative array of mapped entity object instances.
	 */

	public function object() {

		$objs=array();

		$table=strtolower(get_class($this->base));

		if($this->obj==null){

			echo "Please call the fetch method prior to objecr";
			return;
		}

		$this->query=$this->
		db->
		makeQuery($table,$this->obj,
				$this->load,
				$this->condition,
				$this->groupby);

		$this->db->query($this->query);

		$class_name=get_class($this->base);

		$rows=$this->db->getRows();

		$objectArray=array();

		$obj=$this->mapping($this->base, $rows);

		$this->object_count=sizeof($obj);

		if(isset($this->limit)) {

			$obj=array_slice($obj,$this->start,$this->offset);

		}

		return $obj;


	}

	/**
	 * Filter conditions applied before compilation
	 * @param Array  $filter Key pair value.
	 * @param String $type  Logic operator.
	 * @param String $compare Operation operator.
	 * @return Object.
	 */
	function filter($filter,$type='AND',$compare=' = ') {

		if(count($this->condition)==0) {

			if(is_array($filter)) {

				$filter=$this->condition($filter,$type,$compare,FALSE);
			}

			$this->condition[]=" WHERE ". $filter;

		}
		else {

			if(is_array($filter)) {

				$filter=$this->condition($filter,$type,$compare);
			}

			$this->condition[]=" ".$filter;

		}

		return $this;

	}

	/**
	 * Having conditions applied before compilation
	 * @return Object Entitydecorator.
	 */
	public function having($filter,$type='AND',$compare=' = ') {


		if(count($this->having)==0) {

			if(is_array($filter)) {

				$filter=$this->condition($filter,$type,$compare,FALSE);
			}

			$this->condition[]=" HAVING ". $filter;
		}
		else {

			if(is_array($filter)) {

				$filter=$this->condition($filter,$type,$compare);
			}

			$this->condition[]=" ".$filter;

		}

		return $this;

	}

	/**
	 * Group by
	 */
	public function groupBy($columns) {


		$this->groupby="GROUP BY ".$columns;


	}

	/**
	 * Filter conditions applied before compilation
	 * @return Object Entitydecorator.
	 */
	public function limit($start=0,$offset=1) {

		$this->start=$start;

		$this->offset=$offset;

		$this->limit=$this->db->addLimit($start, $offset);

		return $this;

	}

	/**
	 * Build the conditional statements.
	 * @param Associative Array $value.
	 * @param String $type Login operator AND OR.
	 * @param String $compare condition.
	 * @return Boolean $prefix.
	 */
	private function condition($keyPairValues,$type,$compare,$prefix=TRUE) {

		$message="";

		$msgArray=array();

		foreach($keyPairValues as $key=>$value) {

			$msgArray[]="$key $compare'".$value."'";
		}

		$message=implode(" ".$type." ", $msgArray);

		if($prefix) {

			$message= $type." ".$message;
		}

		return $message;
	}

	/**
	 * Check Id set for the base object
	 * @return boolean true or false
	 */
	public function ok() {
		return !is_null($this->base->$this->identity);
	}

	/**
	 * Save entity instance in database.
	 * @param Entity object $obj
	 * @return integer new insert id.
	 */
	public function save($obj=null) {

		$id= ARITY_IDENTITY;

		if(isset($obj)) {

			if(is_null($obj->$id))

				$this->insert($obj);

			else
				$this->update($obj);

			return $obj->$id;
		}
		else {

			if(is_null($this->base->$id))

				$this->insert($this->base);

			else

				$this->update($this->base);

			return $this->base->$id;
		}
	}

	/**
	 * Insert the entity instance in database.
	 * @param Entity object $obj
	 * @return integer new insert id.
	 */
	public function insert($obj) {

		$id= ARITY_IDENTITY;

		$attributes=get_object_vars($obj);

		$table=strtolower(get_class($obj));

		$objectArray=array();

		if(count( $attributes) == 0) return false;

		foreach($attributes as $k => $v) {
			if($v==null) {

				unset ($attributes[$k]);
			}
			elseif(is_array($attributes[$k])) {

				// 				foreach($attributes[$k] as $item) {

				// 					$this->save($item);
				// 				}

				if($obj::$meta->$k->parent){
						
					$objectArray[$k]=$attributes[$k];

				}
				unset($attributes[$k]);
			}
			elseif(is_object($attributes[$k])) {

				if(!$obj::$meta->$k->parent){

					$out=$this->save($attributes[$k]);

					if($out)
					{
						$referedField=$obj::$meta->$k->name;
							
						$myField=$obj::$meta->$k->referenceField;

						$attributes[$referedField]=$out->$myField;

					}
				}
				else{

					$objectArray[$k]=$attributes[$k];

				}
				unset($attributes[$k]);
					

			}

		}

		$obj->$id= $this->db->insert($table,$attributes);

		// TODO Search for the reference field and map it the new objects.
		// 				foreach($attributes[$k] as $item) {
		//
		// 					$this->save($item);
		// 				}

		
		foreach($objectArray as $k=>$v){
				
				
			if(is_array($v)){

				foreach($v as $item){
						
					$referedField=$obj::$meta->$k->name;
						
					$myField=$obj::$meta->$k->referenceField;
						
					$item->$myField=$obj->$referedField;

					var_dump($item);
					$this->save($item);
						
						
				}
			}
			else{
				$referedField=$obj::$meta->$k->name;

				$myField=$obj::$meta->$k->referenceField;

				$v->$myField=$obj->$referedField;

				$this->save($v);
			}
		}

		return $obj;
	}

	public function replace() {

		return $this->base->delete() && $this->base->insert();

	}

	/**
	 * Update the entity based on condition.
	 * @param Entity object $obj
	 * @return integer number of rows affected.
	 */
	public function update($obj) {

		$id= ARITY_IDENTITY;
			
		$objectArray=array();
		
		if(is_null($obj->$id))

			return false;


		$attrib=get_object_vars($obj);

		if(count($attrib) == 0)

			return;

		foreach($attrib as $k => $v) {

			if($v==null) {
					
				unset ($attrib[$k]);

			}
			elseif(is_array($attrib[$k])) {

				foreach($attrib[$k] as $item) {

					$this->save($item);
				}

				unset($attrib[$k]);
			}
			elseif(is_object($attrib[$k])) {

				if(!$obj::$meta->$k->parent){

					$out=$this->save($attrib[$k]);

					if($out)
					{
						$referedField=$obj::$meta->$k->name;
							
						$myField=$obj::$meta->$k->referenceField;

						$attrib[$referedField]=$out->$myField;

					}
				}
				else{

					$objectArray[$k]=$attrib[$k];

				}
				unset($attrib[$k]);
					

			}

		}

		$table=strtolower(get_class($obj));


		$this->db->update($table,$attrib,$id,$obj->$id);

		foreach($objectArray as $k=>$v){

			$referedField=$obj::$meta->$k->name;

			$myField=$obj::$meta->$k->referenceField;

			$v->$myField=$obj->$referedField;

			$this->save($v);

		}
		return $obj;

	}

	/*
	 * Delete the entity based on condition.
	* @return Integer number of rows affected.
	*/
	public function delete() {

		$id= ARITY_IDENTITY;
			
		if(is_null($this->base->$id)) return false;

		$table=strtolower(get_class($this->base));

		$this->db->delete($table,$id,$this->base->$id);

		return $this->db->affectedRows();

	}


}