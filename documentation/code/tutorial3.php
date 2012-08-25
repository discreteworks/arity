<?php

require '..\..\loader.php';

require 'model\user.php';

require 'model\group.php';

require 'model\grouptype.php';

require 'model\profile.php';



echo "/* Test 3 Intitialize User Object*/<br/>";

$userObj=new User();

$udbObj=Arity::addObject($userObj);

$udbObj->removeTable();

$udbObj->createTable();


echo "/* Test 3 Intitialize Group Object*/<br/>";

$groupObj= new Group();

$gdbObj=Arity::addObject($groupObj);

$gdbObj->removeTable();

$gdbObj->createTable();


echo "/* Test 3 Add Parent with Child Objects*/<br/>";


$userObj=new User();

$userObj->username="test";
$userObj->password="sample";


$grp=new Group();

$grp->name="administrator";
$grp->type_id=1;

$userObj->group=$grp;

$udbObj=Arity::addObject($userObj);

$udbObj->save();


echo "/* Test 3 Fetch Inserted*/<br/>";

$rs=$udbObj->fetch(2)->object();

var_dump($rs);

echo "/* Test 3 Update Parent with Child Objects*/<br/>";


$userObj=new User();

$userObj->id=1;
$userObj->username="best";
$userObj->password="sample";


$grp=new Group();
$grp->id=1;
$grp->name="sampple";
$grp->type_id=1;

$userObj->group=$grp;

$udbObj=Arity::addObject($userObj);

$udbObj->save();

echo "/* Test 3 Fetch Updated*/<br/>";

$rs=$udbObj->fetch(2)->object();

var_dump($rs);
