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
class Reference extends Field {
    //put your code here
  
    public $reference;
    public $map;
    public $referenceField;
    public $cascade;
   
  
   function  Reference($myFieldName,$referedTable,$referedFieldName,$map,$parent=false,$onDelete='SET NULL',$description=null) {
      

      $this->reference=$referedTable;
      $this->referenceField=$referedFieldName;
      $this->map=$map;
      $this->onDelete=$onDelete;
      $this->parent=$parent;


      parent::Field($this, $myFieldName, $description,true);

    }

  
}
?>
