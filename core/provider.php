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
abstract class Provider {
    // Singleton object. Leave $me alone.
   
    public $onError; // Can be '', 'die', or 'redirect'

    public $queries;
    
    public $result;

    public $map;

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
    
    protected  function showQuery(){
    
    	if(QUERY_DEBUG){
    
    		echo "<div class=\"debug\" style=\"background:#eee;color:#222;padding:5px;font: 90% Courier New,Courier,monospace\">";
    		echo "<h1 style=\"\">DEBUG</></h2>";
    		foreach($this->queries as $key=>$query){
    
    			echo "<p style=\"color:#555;\">".$query."</p>";
    		}
    		echo "</div>";
    
    
    	}
    }

    //  Abstract methods
    abstract function initialize();
    
    abstract function isConnected();
    
    abstract function databaseSelected();
    
    abstract function createTable($tableName,$fieldAttributes);
    
    abstract function query( $sql );
    
    abstract function execute( $sql );
    
    abstract function numRows($arg = null);
    
    abstract function hasRows($arg = null);
    
    abstract function affectedRows();
    
    abstract function insertId();
    
    abstract function getValue($arg = null);
    
    abstract function getValues($arg = null);
    
    abstract function getRow($arg = null);
    
    abstract function getRows($arg = null);
    
    abstract function resultSet($results);
    
    abstract function quote($var);
    
    abstract function escape($var);
    
    abstract function numQueries();
    
    abstract function lastQuery();
    
    abstract function resulter($arg = null);

    abstract function buildCondition($keyPairValues,$type,$compare,$prefix=TRUE);
    
    abstract function select($obj,$column);








}

?>
