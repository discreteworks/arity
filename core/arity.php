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

    public static function native($type) {

        if(empty($type)) {

            return  DEFAULT_DB::getDb();

        }
        else {

            return  $type::getDb();
        }



    }
    public static function entity($object,$type=DEFAULT_DB) {

        $db=$type::getDb($type);


        return new Entity($object,$db);

    }

}

?>
