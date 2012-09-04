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
class User {

	public static $meta;

	public $id;

	public $username;

	public $password;

	public $group_id;

	public $group;

	public $profile;

	public function  __construct() {

		self::$meta->id= new Type('id',ARITY_SERIAL,11,ARITY_EMPTY,ARITY_REQUIRED,ARITY_PRIMARY);

		self::$meta->username= new Type('username',ARITY_VARCHAR,50);

		self::$meta->password= new Type('password',ARITY_VARCHAR,50);

		self::$meta->group_id= new Type('group_id',ARITY_INT,11,ARITY_EMPTY,ARITY_REQUIRED);

		self::$meta->group= new Reference('group_id','group','id',ARITY_1M);

		self::$meta->profile=new Reference('id','profile','user_id',ARITY_11,ARITY_PARENT);

	}

}