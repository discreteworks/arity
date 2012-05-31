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
class Type extends Field {

    public $size;
    public $type;
    public $default;
    public $key;
    public $composite;


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
    function  Type($name,$type,$size=null,$default=null,$required=null,$key=null,$composite=null,$description=null) {

        parent::Field($this, $name, $description, $required );
        $this->size=$size;
        $this->type=$type;
        $this->default=$default;
        $this->key=$key;
        $this->composite=$composite;

    }


}
?>
