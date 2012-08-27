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
class Profile{
   
	public static $meta;
	
	public $id;
	
    public $address;
    
    public $user_id;
    
    public $user;
   
    public function  __construct() {

       self::$meta->id= new Type('id',ARITY_SERIAL,11,ARITY_EMPTY,ARITY_REQUIRED,ARITY_PRIMARY);
	   
       self::$meta->address= new Type('address',ARITY_VARCHAR,50);
	   
       self::$meta->user_id= new Type('user_id',ARITY_INT,11,ARITY_EMPTY,ARITY_REQUIRED);
	  
	   self::$meta->user= new Reference('user_id','user','id',ARITY_11);

    }

}
