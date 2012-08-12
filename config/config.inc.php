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

/**************
 *PHP SETTINGS*
***************/

ini_set('display_errors', '1');

ini_set('error_reporting', E_ALL);


define('ARITY_DEBUG',1);



/*********************
 *ARITY ORM DEFAULTS *
**********************/

define("DEFAULT_PROVIDER","pgsql");

define("NUMBER_LENGTH","11");

define("TEXT_LENGTH","255");

define("DECIMAL_LENGTH","6,2");


/*************************
 *ORM FIELD MAPPINGS*
**************************/

define("ARITY_IDENTITY","id"); //Change to your choice of table identity field

define("ARITY_INT","INT");

define("ARITY_SERIAL","'SERIAL'");

define("ARITY_SMALLINT","'SMALLINT'");

define("ARITY_NUMERIC","NUMERIC");

define("ARITY_DECIMAL","DECIMAL");

define("ARITY_VARCHAR","VARCHAR");

define("ARITY_TEXT","TEXT");

define("ARITY_DATE","DATE");

define("ARITY_BINARY","BINARY");

define("ARITY_PRIMARY","PRIMARY");

define("ARITY_UNIQUE","UNIQUE");

define("ARITY_COMPOSITE","COMPOSITE");

define("ARITY_REQUIRED","REQUIRED");

define("ARITY_NOTREQUIRED","NOTREQUIRED");

define("ARITY_11","ONETOONE");

define("ARITY_1M","ONETOMANY");

define("ARITY_MM","MANYTOMANY");//Proposed multilevel 

define("ARITY_TYPE","Type");

define("ARITY_NULL",null);

define("ARITY_EMPTY","");

define("ARITY_MASTER","MASTER");

define("ARITY_CHILD","CHILD");

define("ARITY_MAX","MAX");

define("ARITY_MIN","MIN");

define("ARITY_AVG","AVG");

define("ARITY_SUM","SUM");

define("ARITY_AND","AND");

define("ARITY_OR","OR");

define("ARITY_EQ","=");

define("ARITY_NEQ","!=");

define("ARITY_GTEQ",">=");

define("ARITY_LTEQ","<=");



/*********************
*Providers*
**********************/


/*********************
*MYSQL DB CONNECTION*
**********************/

define("MYSQL_HOST", 'localhost');

define("MYSQL_DB_NAME"    , 'arity');

define("MYSQL_USERNAME", 'root');

define("MYSQL_PASSWORD", '');



/*********************
 *PGSQL DB CONNECTION*
**********************/

define("PGSQL_HOST", 'localhost');

define("PGSQL_DB_NAME"    , 'arity');

define("PGSQL_SCHEMA", 'public');

define("PGSQL_USERNAME", 'postgres');

define("PGSQL_PASSWORD", 'sasa');

define("TRANSACTIONAL", 1);


