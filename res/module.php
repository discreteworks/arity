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
class Module extends Base {
    //put your code here
    static $me;
    public $name;
    public $status;
   
    public function  __construct() {

        Module::$me['name']= new Type('name','',true,255,'text');
        Module::$me['status']= new Type('status','',true,1,'number',0);
        parent::__construct($this);

    }


    
   


}
?>
