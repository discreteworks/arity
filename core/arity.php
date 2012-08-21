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
class Arity {

	/**
	* Access provider methods
	*
	*/
	
    public static function provider($type) {

        if(empty($type)) {

            return  DEFAULT_PROVIDER::getProvider();

        }
        else {

            return  $type::getProvider();
        }

    }
   /**
    * Adds object to Arity Context
    * 
    */
    public static function addObject($object,$type=DEFAULT_PROVIDER) {

        $db=$type::getProvider($type);

        return new Entity($object,$db);

    }

}

?>
