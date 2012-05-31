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
class User1 extends Base {
    //put your code here
    static $me;
    public $username;
    public $password;
    public $user_group;

    public function  __construct() {

        User::$me['username']= new Type('username','',true,255,'text');
        User::$me['password']= new Type('password','',true,255,'sha');
        User::$me['user_group']= new Reference('user_group','','user_group','user','onetomany');

        parent::__construct($this);

    }


    
   


}
?>
