<?php

require '..\..\loader.php';

require 'model\sample.php';


echo "/* Tutorial 1: Intitialize Arity Simple Persistent Object*/<br/>";

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
