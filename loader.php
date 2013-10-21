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

require dirname(__FILE__).'/config/config.inc.php';

require_once(dirname(__FILE__).'/config/autoload.inc.php');

spl_autoload_register('__autoload');
