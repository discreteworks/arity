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
class Object extends Base {
    //put your code here
    static $me;
    var $name;
    var $objectfield;
    var $object_group;

 

    function  __construct() {

        Object::$me['name']= new Type('name','',true,255,'text');
        Object::$me['objectfield']= new Reference('objectfield','','objectfield','object','onetomany');
        Object::$me['object_group']= new Reference('object_group','','object_group','object','onetomany');
        parent::__construct($this);
    }

}
?>
