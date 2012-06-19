<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require '..\loader.php';




$userObj=new User();

$udbObj=Arity::entity($userObj);

$udbObj->removeTable();

$udbObj->createTable();

$pro=new Profile();

$pdbObj=Arity::entity($pro);

$pdbObj->removeTable();

$pdbObj->createTable();

$userObj->username="best";
$userObj->password="sample";
$userObj->group_id=1;


$pro->address="sam";

$p2=new Profile();

$p2->address="tech";

$pArray[]=$pro;
$pArray[]=$p2;



$userObj->profile=$pArray;

$udbObj=Arity::entity($userObj);

$udbObj->save();







?>
