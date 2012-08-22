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
 * @license		http://www.gnu.org/licenses/gpl.txt
 * @link		http://arity.abideen.com
 * @since		Version 1.0
 * @filesource
 */
class TestType {
	 
	public static $meta;

	public $id;
	
	public $arityint;
	
	public $aritysmallint;
	
	public $aritybigint;
	
	public $aritydecimal;

	public $arityvarchar;
	
	public $aritytext;

	public $aritydate;
	
	public $aritybinary;
	 
	 
	public function  __construct()  {

		self::$meta->id= new Type('id',ARITY_SERIAL,11,ARITY_EMPTY,ARITY_REQUIRED,ARITY_PRIMARY);
		 
		self::$meta->arityint= new Type('arityint',ARITY_INT);
		
		self::$meta->aritysmallint= Type::create('aritysmallint',ARITY_SMALLINT);
		
		self::$meta->aritybigint= Type::create('aritybigint',ARITY_BIGINT);
		
		self::$meta->aritydecimal= Type::create('aritydecimal',ARITY_DECIMAL);
		
		self::$meta->arityvarchar= Type::create('arityvarchar',ARITY_VARCHAR);
		
		self::$meta->aritytext= Type::create('aritytext',ARITY_TEXT);
		
		self::$meta->aritydate= Type::create('aritydate',ARITY_DATE);
		
		self::$meta->aritybinary= Type::create('aritybinary',ARITY_BINARY);
		
	
		

	}

}
?>
