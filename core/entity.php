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

	//Provider instance
	public $provider;

	// Object array of entities
	private $obj=array();

	//Depth restriction of the joins 0 to n
	private $depth=0;

	//Current level of the join 0 to n
	private $level=0;

	//Name of entities mapped
	private $map=array();

	// Count of instances of entities object compile
	private $objectCount=0;

	/*
	 * Entity Constructor
	*/
	public function  __construct($baseIn,$providerInstance) {

		$this->base=$baseIn;

		$this->provider=$providerInstance;

	}

	/**
	 * Installs the entity in provider.
	 * Creates relation(table) in the provider.
	 */
	public function createTable() {

		$table=strtolower(get_class($this->base));

		if(!$this->provider->tableExists($table)){

			$this->createEntity($table);

		}
		else{

			$this->alterEntity($this);

		}

	}

	/**
	 * Truncate
	 * Removes all the row from the provider table
	 */
	public function truncateTable() {


		$table=strtolower(get_class($this->base));

		$this->provider->truncate($table);

	}

	/**
	 * Removes the provider table.
	 */
	public function removeTable() {


		$table=strtolower(get_class($this->base));

		if($this->provider->tableExists($table))

		$this->provider->dropTable($table);
	}



	/**
	 * Fetches the entities from provider with level of depth.
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

	public function loads($obj,$column=null) {

		$this->provider->select($obj,$column);

		return $this;

	}
	
	/**
	* sum of table column
	* @param String Entities names to load
	* @return Object
	*/
	public function sum($obj,$column=null) {
	
		$this->provider->select($obj,$column,ARITY_SUM);
	
		return $this;
	
	}
	
	/**
	* sum of table column
	* @param String Entities names to load
	* @return Object
	*/
	
	public function avg($obj,$column=null) {
	
		$this->provider->select($obj,$column,ARITY_AVG);
	
		return $this;
	
	}
	
	/**
	* sum of table column
	* @param String Entities names to load
	* @return Object
	*/
	
	public function min($obj,$column=null) {
	
		$this->provider->select($obj,$column,ARITY_MIN);
	
		return $this;
	
	}
	
	/**
	* sum of table column
	* @param String Entities names to load
	* @return Object
	*/
	
	public function max($obj,$column=null) {
	
		$this->provider->select($obj,$column,ARITY_MAX);
	
		return $this;
	
	}
	
	
	/**
	* Having conditions applied before compilation
	* @return Object Entitydecorator.
	*/
	public function having($filter,$type=ARITY_AND,$compare=ARITY_EQ) {
	
		
			if(is_array($filter)) {
	
				$filter=$this->provider->setHavingCondition($filter,$type,$compare);
			}
			
	
		return $this;
	
	
	}
	
	/**
	 * Group by
	 */
	public function groupBy($column) {
	
		$this->provider->setGroupBy($column);
	
		return $this;
	
	
	}

	/**
	 * Standard compile of the result set without mapping to entities
	 * @return Associative Array resultset of entities from provider.
	 */

	function arrayList() {

		$table=strtolower(get_class($this->base));

		if($this->obj==null){

			echo "<span>Please call the fetch method prior to native result";
			return;
		}

		$rows=$this->provider->

		buildQuery($table,$this->obj);
		


		return $rows;

	}

	/**
	 * Count the results from provider resultset.
	 * @return integer number of rows.
	 */

	function arrayListCount($statement=null) {

		$table=strtolower(get_class($this->base));
		
		if($this->obj==null){

			echo "Please call the fetch method prior to count";
			return;
		}

		$this->
		provider->
		buildQuery($table,$this->obj);

		return $this->provider->getRowCount();

	}




	/**
	 * Count the results from mapped entities resultset.
	 * @return integer count of entity instances or number of rows.
	 */

	function objectCount() {

		return $this->objectCount;
	}


	/**
	 * Object compile of provider resultset of entities with mapping.
	 * @return Associative array of mapped entity object instances.
	 */

	public function object() {

		$objs=array();

		$table=strtolower(get_class($this->base));

		if($this->obj==null){

			echo "Please call the fetch method prior to object";
			return;
		}

		$class_name=get_class($this->base);
		
		$rows=$this->
		provider->
		buildQuery($table,$this->obj);

		$objectArray=array();

		$obj=$this->mapping($this->base, $rows);

		$this->objectCount=sizeof($obj);

		if(isset($this->limit)) {

			$obj=array_slice($obj,$this->start,$this->offset);

		}

		//Clears the sets

		$this->load=array();

		$this->condition=array();

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


		if(is_array($filter)) {

			$this->provider->setCondition($filter,$type,$compare);
		}
			
		return $this;

	}

	/**
	 * Filter conditions applied before compilation
	 * @return Object Entitydecorator.
	 */
	public function limit($start=0,$offset=1) {

		$this->provider->setLimit($start, $offset);

		return $this;

	}

	/**
	 * Check Id set for the base object
	 * @return boolean true or false
	 */
	private function ok() {
		return !is_null($this->base->$this->identity);
	}

	/**
	 * Save entity instance in provider.
	 * @param Entity object $obj
	 * @return integer new insert id.
	 */

	public function save(){

		if(TRANSACTIONAL){

			$this->provider->beginTransaction();
		}

		$this->saveObject();

		if(TRANSACTIONAL){

			$this->provider->endTransaction();
		}

	}

	private function saveObject($obj=null) {

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
	 * Insert the entity instance in provider.
	 * @param Entity object $obj
	 * @return integer new insert id.
	 */
	private function insert($obj) {

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

		$obj->$id= $this->provider->insert($table,$attributes);

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

					//var_dump($item);
					$this->saveObject($item);


				}
			}
			else{
				$referedField=$obj::$meta->$k->name;

				$myField=$obj::$meta->$k->referenceField;

				$v->$myField=$obj->$referedField;

				$this->saveObject($v);
			}
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

		$this->provider->delete($table,$id,$this->base->$id);

		return $this->provider->affectedRows();

	}

	private function replace() {

		return $this->base->delete() && $this->base->insert();

	}

	/**
	 * Update the entity based on condition.
	 * @param Entity object $obj
	 * @return integer number of rows affected.
	 */
	private function update($obj) {

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


		$this->provider->update($table,$attrib,$id,$obj->$id);

		foreach($objectArray as $k=>$v){

			$referedField=$obj::$meta->$k->name;

			$myField=$obj::$meta->$k->referenceField;

			$v->$myField=$obj->$referedField;

			$this->saveObject($v);

		}
		return $obj;

	}

	private function alterEntity(){


		$fieldAttributes=array();

		$table=strtolower(get_class($this->base));

		$attributes=get_object_vars($this->base);

		foreach ($attributes as $key=>$item) {

			if(!$this->provider->fieldExists($table,$key)) {

				if(get_class($table::$meta->$key)==ARITY_TYPE) {

					$fieldAttributes[]=$table::$meta->$key;


				}

			}

		}

		if($fieldAttributes)

		$this->provider->addField($table,$fieldAttributes);



	}


	/**
	 * Create new entity table in provider. If entity table exists
	 * in provider drop and re-create in the provider.
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

			$this->provider->createTable($table,$fieldAttributes);
		}
			

	}

	/**
	 * Recursive method which form the query to get entities provider resultset.
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

				$this->provider->createJoin($table::$meta->$key,$table,$id,$table::$meta->$key->map);

				$ref=$table::$meta->$key->reference;

				$this->chain(new $ref);


			}

		}

		return $this;

	}

	/**
	 * Recursive method which maps the entities with provider resultset.
	 * @param Object Entity object
	 * @param Array resultset result returned from provider
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

		foreach($resultset as $key=>$item) {

			if(!array_key_exists($name, $resultset[$key])) {


				return null;

			}

			foreach($attrib as $vkey=>$var) {


				if(@get_class(@$c::$meta->$vkey)=="Type") {


					if($condition!=NULL) {


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
						if(isset($resultset[$key][$name][$vkey])){

							$c->$vkey=$resultset[$key][$name][$vkey];
						}
						else{

							$c->$vkey=null;
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

}


// 		$objectArray=array();

// 		foreach($rows as $key=>$item) {


// 			$ci=key($item);


// 			if(isset($ci)){

// 				$c=new $ci;

// 				$attrib=get_object_vars($c);

// 				foreach($attrib as $vkey=>$var) {

// 					if(isset($rows[$key][key($item)][$vkey])){

// 						$c->$vkey=$rows[$key][key($item)][$vkey];

// 					}

// 				}
// 			}

// 			$objectArray[md5(serialize($c))]=$c;
// 		}