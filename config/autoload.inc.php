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
 \* @license		BSD
 * @link		http://www.codendev.com
 * @since		Version 1.0
 * @filesource
 */

function __autoload($class_name) {

	if (file_exists(dirname(__FILE__).'/../model/' . strtolower($class_name) . '.php')) {

		require_once dirname(__FILE__).'/../model/' . strtolower($class_name) . '.php';
	}

	if (file_exists(dirname(__FILE__).'/../provider/' . strtolower($class_name) . '.php')) {

		require_once dirname(__FILE__).'/../provider/' . strtolower($class_name) . '.php';
	}

	if (file_exists(dirname(__FILE__).'/../core/' . strtolower($class_name) . '.php')) {

		require_once dirname(__FILE__).'/../core/' . strtolower($class_name) . '.php';
	}

	/* Comment to disable test case object loading in production */
	if (file_exists(dirname(__FILE__).'/../res/' . strtolower($class_name) . '.php')) {

		require_once dirname(__FILE__).'/../res/' . strtolower($class_name) . '.php';
	}

}
