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
class Group1 extends Base {
   
    public $name;
    public $user_group;
    public $object_group;
  

    public function  __construct() {

        self::$meta['name']= new Type('name','',true,255,'text');
        self::$meta['user_group']= new Reference('user_group','','user_group','group','onetomany');
        self::$meta['object_group']= new Reference('object_group','','object_group','group','onetomany');
        
        parent::__construct($this);

    }





}
?>
