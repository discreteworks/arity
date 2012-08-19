<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


require '..\..\loader.php';

require 'model\user.php';

require 'model\grouptype.php';



echo "/* Test 2 Intitialize User Object*/<br/>";

$userObj=new User();

$udbObj=Arity::entity($userObj);

$udbObj->removeTable();

$udbObj->createTable();

echo "/* Test 2 Intitialize Group Object*/<br/>";

$groupObj= new Group();

$gdbObj=Arity::entity($groupObj);

$gdbObj->removeTable();

$gdbObj->createTable();


echo "/* Test 2 Intitialize Group Object*/<br/>";

$grouptypeObj= new Grouptype();

$gtdbObj=Arity::entity($grouptypeObj);

$gtdbObj->removeTable();

$gtdbObj->createTable();


echo "/* Test 2 Add Group Type */<br/>";

$grouptypeObj->name="admin";

echo $type_id=$gtdbObj->save();

echo "/* Test 2 Add Group Object*/<br/>";

$groupObj->name="admin";
$groupObj->type_id=$type_id;

echo $group_id=$gdbObj->save();


echo "/* Test 2 Add User Object*/<br/>";

$userObj->username="sam";
$userObj->password="sam";
$userObj->group_id=$group_id;

$udbObj->save();

$rs=$gdbObj->fetch(3)->object();

foreach($rs as $item){
	
	var_dump($item);
	
};








?>
