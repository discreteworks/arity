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
 * @author		codendev
 * @copyright           Copyright (c) 2011 - 2012, CodenDev.
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
    
    public $parent;
   
   /**
    * 
    * 
    */
   public function  __construct($fieldName,$referedTable=null,$referedFieldName=null,$map=null,$parent=ARITY_CHILD,$onDelete='SET NULL') {
      

      $this->reference=$referedTable;

      $this->referenceField=$referedFieldName;
      
      $this->map=$map;
      
      $this->onDelete=$onDelete;
      
      $this->parent=$parent;

      parent::__construct($this, $fieldName);

    }

    public static function create($fieldName){
    	
    	return new Reference($fieldName);
    	
    	
    }
    
    public function referToTable($table){
    	
    	$this->reference=$table;
    	
    	return $this;
    }
    
    public function referFieldName($name){
    	
    	$this->referenceField=$name;
    	
    	return $this;
    }
    
    public function one2many(){
    	
    	$this->map=ARITY_1M;
    	
    	return $this;
    	
    }
    
    public function one2one(){
    	 
    	$this->map=ARITY_11;
    	 
    	return $this;
    	 
    }
    
    public function isParent(){
    	
    	$this->parent=ARITY_PARENT;
    	
    	return $this;
    	
    }
    
    public function isChild(){
    	 
    	$this->parent=ARITY_CHILD;
    	 
    	return $this;
    	 
    }
    
    public function setOnDelete($str){
    	
    	$this->onDelete=$str;
    	
    	return $this;
    	
    }

}