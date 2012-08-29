<?php
/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

function __autoload($class_name) {

	if (file_exists(dirname(__FILE__)."/../model/" . strtolower($class_name) . ".php")) {

		require_once dirname(__FILE__)."/../model/" . strtolower($class_name) . ".php";
	}

	if (file_exists(dirname(__FILE__)."/../provider/" . strtolower($class_name) . ".php")) {

		require_once dirname(__FILE__)."/../provider/" . strtolower($class_name) . ".php";
	}

	if (file_exists(dirname(__FILE__)."/../core/" . strtolower($class_name) . ".php")) {

		require_once dirname(__FILE__)."/../core/" . strtolower($class_name) . ".php";
	}
	
	/* Comment to disable test case object loading in production */
	if (file_exists(dirname(__FILE__)."/../res/" . strtolower($class_name) . ".php")) {
	
		require_once dirname(__FILE__)."/../res/" . strtolower($class_name) . ".php";
	}

}
