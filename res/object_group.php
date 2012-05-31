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
class Object_Group extends Base {
    //put your code here
    public static $me;
    public $object;
    public $group;
    public $read;
    public $write;
    
    public function  __construct() {
        
        Object_Group::$me['group']= new Reference('group','','group','id','onetoone');
        Object_Group::$me['object']= new Reference('object','','object','id','onetoone');
        Object_Group::$me['read']= new Type('read','',false,1,'text');
        Object_Group::$me['write']= new Type('write','',false,1,'text');
        parent::__construct($this);

    }





}
?>
