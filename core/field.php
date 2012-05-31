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
class Field{

   public $name;
   public $description;
   public $required; 
   
   function  Field(Field $obj,$name,$description='',$required=false) {

      assert('$name!="" /* $name parameter must be an set */');
      
      $obj->name=$name;
      $obj->description=$description;
      $obj->required=$required;


   }

}
?>
