<?php

require '..\..\loader.php';

require 'model\testtype.php';


echo "/* Case 0: Intitialize Arity Simple Persistent Object*/<br/>";

echo "/* Create PHP Object*/<br/>";

$testObj=new TestType();

echo "/* Add Object to Arity peristent manager*/<br/>";

$dbObj=Arity::addObject($testObj);

echo "Create Persistent Object Table<br/>";

$dbObj->createTable();

echo "/*Drop Persistent Object Table"."<br/>";;

$dbObj->removeTable();
