<?php
/**
 * Arity
 *
 * ORM Framework for PHP 5.2 or newer
 *
 * Licensed under BSD
 * 
 *
 * @package		Arity
 * @author		codendev
 * @copyright           Copyright (c) 2011 - 2012, CodenDev.
 * @license		http://www.gnu.org/licenses/gpl.txt
 * @link		http://www.codendev.com
 * @since		Version 1.0
 * @filesource
 */
class Group{
	 
	public static $meta;

	public $id;

	public $name;

	public $user;

	public $type_id;

	public $grouptype;

	public function  __construct() {

		self::$meta->id= new Type('id',ARITY_SERIAL,11,ARITY_EMPTY,ARITY_REQUIRED,ARITY_PRIMARY);

		self::$meta->name= new Type('name',ARITY_VARCHAR,50);

		self::$meta->type_id= new Type('type_id',ARITY_INT,11,ARITY_EMPTY,ARITY_REQUIRED);

		self::$meta->grouptype= new Reference('type_id','grouptype','id',ARITY_1M);

		self::$meta->user= new Reference('id','user','group_id',ARITY_1M,ARITY_PARENT);

	}

}
