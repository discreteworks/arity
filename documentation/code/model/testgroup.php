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
class TestGroup{
   
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
	   
	   self::$meta->grouptype= new Reference('type_id','testgrouptype','id',ARITY_1M);
	   
	   self::$meta->user= new Reference('id','testuser','group_id',ARITY_1M);
	   

    }





}
?>
