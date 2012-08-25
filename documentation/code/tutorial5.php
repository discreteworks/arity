<?php

require '..\..\loader.php';

require 'model\sample.php';


echo "/* Tutorial 5: Intitialize Arity Simple Persistent Object*/<br/>";


echo "/* Create PHP Object*/<br/>";

$testObj1=new Sample();


echo "/* Add Object to Arity peristent manager*/<br/>";


$dbObj=Arity::addObject($testObj1);


echo "Create Persistent Object Table<br/>";


$dbObj->createTable();


echo "Initialize 1 Simple PHP Object"."<br/>";


$testObj1->name="sample1";
$testObj1->score="10";

$dbObj->save();


echo "/* Add Object 2 to Arity peristent manager*/<br/>";

$testObj2=new Sample();

$dbObj=Arity::addObject($testObj2);

echo "Initialize 2 Simple PHP Object"."<br/>";

$testObj2->name="sample2";
$testObj2->score="20";

echo "Persist PHP Object from DB"."<br/>";

$dbObj->save();


echo "/* Add Object 3 to Arity peristent manager*/<br/>";

$testObj3= new Sample();

$dbObj=Arity::addObject($testObj3);

echo "/*Initialize 3 Simple PHP Object*/"."<br/>";


$testObj3->name="sample2";
$testObj3->score="30";

echo "/*Persist PHP Object from DB*/"."<br/>";

$dbObj->save();

echo "/*Fetch Persisted PHP Object from DB*/"."<br/>";

$rs=$dbObj->fetch()->object();

var_dump($rs);

echo "/*Apply the where condition*/"."<br/>";

$rs=$dbObj->fetch()->where(array("name"=>"sam"))->object();

var_dump($rs);

echo "/*arrayList resultset methods*/"."<br/>";

echo "/*arrayList Resultset select method*/"."<br/>";

$rs=$dbObj->fetch()->select("sample.name")->arrayList();

var_dump($rs);

echo "/*arrayList Resultset min method*/"."<br/>";

$rs=$dbObj->fetch()->min('sample.score')->arrayList();

var_dump($rs);

echo "arrayList Resultset max method*/"."<br/>";

$rs=$dbObj->fetch()->max('sample.score')->arrayList();

var_dump($rs);

echo "/*arrayList Resultset avg method*/"."<br/>";

$rs=$dbObj->fetch()->avg('sample.score')->arrayList();

var_dump($rs);

echo "/*arrayList Resultset sum method*/"."<br/>";

$rs=$dbObj->fetch()->sum('sample.score')->arrayList();

var_dump($rs);

echo "/*arrayList Resultset groupBy method*/"."<br/>";

$rs=$dbObj->fetch()->select('sample.name')->sum('sample.score')->groupBy('sample.name')->arrayList();

var_dump($rs);

echo "/*arrayList Resultset sum groupBy having method*/"."<br/>";

$rs=$dbObj->fetch()->select('sample.name')->sum('sample.score')->groupBy('sample.name')->having(array('name'=>'sample2'))->arrayList();

var_dump($rs);


echo "/*Clean Simple Object Entries"."<br/>";;

$dbObj->truncateTable();

echo "/*Drop Persistent Object Table"."<br/>";;

$dbObj->removeTable();
