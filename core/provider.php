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
abstract class Provider {
    // Singleton object. Leave $me alone.
   
    public $onError; 

    public $queries;
    
    public $result;

    public $obj;
    
    public $table;
        
    //Limit index
    public $start=0;
    
    //Limit offset
    public $offset=1;
        
    //Entities to be loaded prior to mapping
    public $load=array();
    
    //Filter conditions
    public $condition=array();
    
    //Group by base entity table with other tables
    public $groupByItem=array();
    
    //Having conditions
    public $havingCondition=array();
    

   

    //  Main Abstract methods
    
    abstract function initialize();
    
    abstract function createTable($tableName,$fieldAttributes);
    
    abstract function tableExists($table);
    
    abstract function dropTable($table);
    
    abstract function delete($table,$columnName,$value);
    
    abstract function fieldExists($table,$key);
    
    abstract function addField($table,$fieldAttributes);
    
    abstract function setCondition($keyPairValues,$type,$compare);
    
    abstract function setHavingCondition($keyPairValues,$type,$compare);
    
    abstract function getCondition();
    
    abstract function setSelect($objColumn,$operator=null);
    
    abstract function beginTransaction();
    
    abstract function endTransaction();
    
    
    //Provider Oriented methods
    
    abstract function query( $statement );
    
    abstract function execute(  $statement );
    
    abstract function getRowCount($arg = null);
   
    abstract function getValue($arg = null);
    
    abstract function getValues($arg = null);
    
    abstract function getRow($arg = null);
    
    abstract function getRows($arg = null);
  
           
   
    // Singleton constructor
    private function __construct() {
    
    	$this->initialize();
    
    	$this->queries = array();
    }
    
    // Get Singleton object
    public static function getProvider($provider) {
    	static $obj;
    	if(is_null($obj))
    	$obj = new $provider();
    	return $obj;
    }
    
    public  function showDebug(){
    
    	if(ARITY_DEBUG){
    
    		echo "<div class=\"debug\" style=\"background:#eee;color:#222;padding:5px;font: 90% Courier New,Courier,monospace\">";
    		echo "<h1 style=\"\">DEBUG</></h2>";
    		foreach($this->queries as $key=>$query){
    
    			echo "<p style=\"color:#555;\">".$query."</p>";
    		}
    		echo "</div>";
    
    
    	}
    }







}

?>
