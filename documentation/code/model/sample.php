<?php
/**
 * Arity
 *
 * ORM Framework for PHP 5.2 or newer
 *
 * Licensed under GPL
 * Redistributions of files must retain the above copyright notice.
 *
 * @package		Arity
 * @author		codendev
 * @copyright           Copyright (c) 2011 - 2012, CodenDev.
 * @license		http://www.gnu.org/licenses/gpl.txt
 * @link		http://www.codendev.com
 * @since		Version 1.0
 * @filesource
 */
class Sample {
	 
	public static $meta;

	public $id;
	
	public $name;
	
	public $score;

	public function  __construct()  {

		self::$meta->id= new Type('id',ARITY_SERIAL,11,ARITY_EMPTY,ARITY_REQUIRED,ARITY_PRIMARY);
		 
		self::$meta->name= new Type('name',ARITY_VARCHAR,50);
		
		self::$meta->score= Type::create('score',ARITY_INT,11);

	}

}
