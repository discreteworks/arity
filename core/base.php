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

abstract class Base {


    public static $meta;
    public $id;
 

    public function  __construct() {
        
         self::$meta->id= new Type('id','',true,11,'serial',true,true);
       
    }


    public static function  get($key) {

        return self::$meta->$key;
        
    }
    
    public static function set($key,$value) {

        self::$meta->$key=$value;
        
    }





}
?>
