# Arity PHP ORM

## Description

Project : Arity - Arity PHP ORM

## Features
- No XML required.
- Support nth level of relationships.
- Table defination exist within the mapping class.
- Can Supports sql independent providers.
- Supported Databases:Postgresql

## Installation procedure:

Setup the database connection in config/config.inc.php
Leave PGSQL_SCHEMA as public, if you havent created you own schema.


## HOW TO USE:

### Step1: Create model class. 

Create file: sample.php
```
class Sample {
	 
	public static $meta;

	public $id;
	
	public $name;
	
	public $score;

	public function  __construct()  {

		self::$meta->id= new Type('id',ARITY_SERIAL,11,ARITY_EMPTY,ARITY_REQUIRED,ARITY_PRIMARY);
		 
		self::$meta->name= new Type('name',ARITY_VARCHAR,50);
		
		self::$meta->score= Type::create('score',ARITY_INT,11);
	}

}
```

Step 2: Access the Arity Context methods. Create test.php for accessing the Arity Context methods
for data persistence.

Create file:test.php

```
require 'loader.php';

require 'sample.php';//If the the class is in sample file

echo "/* Create PHP Object*/<br/>";

$testObj=new Sample();

echo "Initialize Simple PHP Object"."<br/>";

$testObj->name="sam";

echo "/* Add Object to Arity peristent manager*/<br/>";

$dbObj=Arity::addObject($testObj);

echo "Create Persistent Object Table<br/>";

$dbObj->createTable();

echo "Save Simple Object or Persist Object"."<br/>";

$dbObj->save();

echo "Fetch Persisted PHP Object from DB"."<br/>";

$rs=$dbObj->fetch()->object();

var_dump($rs);

echo "Clean Simple Object Entries"."<br/>";;

$dbObj->truncateTable();

$rs=$dbObj->fetch()->object();

var_dump($rs);

echo "Drop Persistent Object Table"."<br/>";;

$dbObj->removeTable();
```
