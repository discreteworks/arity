<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


require '..\..\loader.php';

require 'model\sample.php';


echo "/* Test 3 Intitialize User Object*/<br/>";

$userObj=new User();

$udbObj=Arity::entity($userObj);

$udbObj->removeTable();

$udbObj->createTable();


echo "/* Test 3 Intitialize Group Object*/<br/>";

$groupObj= new Group();

$gdbObj=Arity::entity($groupObj);

$gdbObj->removeTable();

$gdbObj->createTable();


echo "/* Test 3 Add Parent with Child Objects*/<br/>";


$userObj=new User();

$userObj->username="test";
$userObj->password="sample";


$grp=new Group();

$grp->name=2;
$grp->type_id=1;

$userObj->group=$grp;

$udbObj=Arity::entity($userObj);

$udbObj->save();


echo "/* Test 4 Update Parent with Child Objects*/<br/>";


$userObj=new User();

$userObj->id=14;
$userObj->username="best";
$userObj->password="sample";


$grp=new Group();
$grp->id=17;
$grp->name="sampple";
$grp->type_id=1;

$userObj->group=$grp;

$udbObj=Arity::entity($userObj);

$udbObj->save();







?>
