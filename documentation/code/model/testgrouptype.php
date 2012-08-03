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
class TestGroupType{
	 
	public static $meta;

	public $id;

	public $name;

	public function  __construct() {

		self::$meta->id= new Type('id',ARITY_SERIAL,11,ARITY_EMPTY,ARITY_REQUIRED,ARITY_PRIMARY);
		self::$meta->name= new Type('name',ARITY_VARCHAR,50);
		 

	}





}
?>
