<?php

require '..\..\loader.php';

require 'model\user.php';

require 'model\group.php';

require 'model\grouptype.php';

require 'model\profile.php';



echo "/* Test 2 Intitialize User Object*/<br/>";

$userObj=new User();

$udbObj=Arity::addObject($userObj);

$udbObj->removeTable();

$udbObj->createTable();

echo "/* Test 2 Intitialize Group Object*/<br/>";

$groupObj= new Group();

$gdbObj=Arity::addObject($groupObj);

$gdbObj->removeTable();

$gdbObj->createTable();


echo "/* Test 2 Intitialize Group Object*/<br/>";

$grouptypeObj= new Grouptype();

$gtdbObj=Arity::addObject($grouptypeObj);

$gtdbObj->removeTable();

$gtdbObj->createTable();


echo "/* Test 2 Intitialize Profile Object*/<br/>";

$proObj= new Profile();

$proObj=Arity::addObject($proObj);

$proObj->removeTable();

$proObj->createTable();


echo "/* Test 2 Add Group Type */<br/>";

$grouptypeObj->name="admin";

$type=$gtdbObj->save();

echo "/* Test 2 Add Group Object*/<br/>";

$groupObj->name="admin";
$groupObj->type_id=$type->id;

$grp=$gdbObj->save();


echo "/* Test 2 Add User Object*/<br/>";

$userObj->username="sam";
$userObj->password="sam";
$userObj->group_id=$grp->id;

$udbObj->save();

echo "/* Test 2 Fetch User Object*/<br/>";

$rs=$gdbObj->fetch(3)->object();

foreach($rs as $item){
	
	var_dump($item);
	
};


?>
