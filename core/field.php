<?php
/**
 * Arity
 *
 * ORM Framework for PHP 5.2 or newer
 *
 * Licensed under BSD
 * 
 *
 * @package		Arity
 * @author		codendev
 * @copyright           Copyright (c) 2011 - 2012, CodenDev.
  * @license		BSD
 * @link		http://www.codendev.com
 * @since		Version 1.0
 * @filesource
 */
abstract class Field {

	public $name;

	function  __construct(Field $obj, $name) {

		$obj->name = $name;

	}

}
