<?php
require '..\..\loader.php';

require 'model\user.php';

require 'model\group.php';

require 'model\grouptype.php';

require 'model\profile.php';


echo "/* Test 4 Intitialize User Object*/<br/>";


$userObj=new User();

$udbObj=Arity::addObject($userObj);

$udbObj->removeTable();

$udbObj->createTable();


echo "/* Test 4 Intitialize User Object*/<br/>";

$pro=new Profile();

$pdbObj=Arity::addObject($pro);

$pdbObj->removeTable();

$pdbObj->createTable();


echo "/* Test 4 Add Parent with Child Objects*/<br/>";

$userObj->username="best";
$userObj->password="sample";
$userObj->group_id=1;


$pro->address="sam";

$p2=new Profile();

$p2->address="new address";

$pArray[]=$pro;
$pArray[]=$p2;


$userObj->profile=$pArray;

$udbObj=Arity::addObject($userObj);

$udbObj->save();


echo "/* Test 4 Fetch Inserted*/<br/>";

$rs=$udbObj->fetch(2)->object();

var_dump($rs);



?>
