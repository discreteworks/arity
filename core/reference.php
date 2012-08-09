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
    
    public $role;
   
   /**
    * 
    * 
    */
   function  __construct($fieldName,$referedTable=null,$referedFieldName=null,$map=null,$role=false,$onDelete='SET NULL') {
      

      $this->reference=$referedTable;

      $this->referenceField=$referedFieldName;
      
      $this->map=$map;
      
      $this->onDelete=$onDelete;
      
      $this->role=$role;

      parent::__construct($this, $fieldName);

    }
    
  
    
    static function create($fieldName){
    	
    	return new Reference($fieldName);
    	
    	
    }
    
    function referToTable($table){
    	
    	$this->reference=$table;
    	
    	return $this;
    }
    
    function referFieldName($name){
    	
    	$this->referenceField=$name;
    	
    	return $this;
    }
    
    function one2many(){
    	
    	$this->map=ARITY_1M;
    	
    	return $this;
    	
    }
    
    function one2one(){
    	 
    	$this->map=ARITY_11;
    	 
    	return $this;
    	 
    }
    
    function isMaster(){
    	
    	$this->role=ARITY_MASTER;
    	
    	return $this;
    	
    }
    
    function isChild(){
    	 
    	$this->role=ARITY_CHILD;
    	 
    	return $this;
    	 
    }
    
    function setOnDelete($str){
    	
    	$this->onDelete=$str;
    	
    	return $this;
    	
    }

  
}
?>
