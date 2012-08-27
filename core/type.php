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
 * @copyright  Copyright (c) 2011 - 2012, CodenDev.
 * @license		http://gnu.org/licenses/gpl.txt
 * @link		   http://arity.abideen.com
 * @since		Version 1.0
 * @filesource
 */
class Type extends Field {

    public $size;
    public $type;
    public $default;
    public $key;
    public $composite;
    public $required;

    /**
     *    Create a Type Field Object.
     *    @param string $name       Name of the field.
     *    @param string $description       Description of field.
     *    @param boolean $required       Boolean required field.
     *    @param integer $size        Size of field.
     *    @param string $type       Type of field(number,text,longtext,binary).
     *    @param mixed $default    Default value of field.
     *    @access public
     */
    function  __construct($name, $type, $size=null, $default=null, $required=ARITY_NOTREQUIRED, $key=null, $composite=null) {

        parent::__construct($this, $name);

        $this->size=$size;

        $this->type=$type;

        $this->default=$default;

        $this->key=$key;

        $this->composite=$composite;

        $this->required=$required;

    }

    public static function create($name, $type, $size=null) {

    	return new Type($name, $type, $size);

    }

    public function setDefault($default) {

    	$this->default=$default;

    	return $this;

    }

    public function composite() {

    	$this->composite=ARITY_COMPOSITE;	

    	return $this;

    }

    public function primaryKey() {

    	$this->key=ARITY_PRIMARY;

    	return $this;

    }
    
    public function uniqueKey() {

    	$this->key=ARITY_UNIQUE;

    	return $this;

    }
    
    public function required() {

    	$this->key=ARITY_REQUIRED;

    	return $this;

    }
}
