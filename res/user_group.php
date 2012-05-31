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
class User_Group extends Base {
    //put your code here
    public static $me;
    public $user;
    public $group;
  

    public function  __construct() {

        User_Group::$me['group']= new Reference('group','','group','id','onetoone');
        User_Group::$me['user']= new Reference('user','','user','id','onetoone');
        
        parent::__construct($this);

    }





}
?>
