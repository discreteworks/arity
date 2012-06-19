<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require '..\loader.php';

// // echo "/* Test1 Intitialize the Arity Simple Object*/<br/>";

// // $testObj=new Test();

// // $dbObj=Arity::entity($testObj);


// // echo "Create Object to DB Entities<br/>";


// // //$dbObj->removeTable();

// // $dbObj->createTable();



// // echo "Create Object to DB Entities:complete<br/>";


// // echo "Initialize sample test object"."<br/>";

// // $testObj->name="sam";



// // echo "Initialize sample test object:complete"."<br/>";


// // echo "Save sample test object"."<br/>";


// // $dbObj->save();


// // echo "Save sample test object:complete"."<br/>";


// // echo "Fetch test object"."<br/>";


// // $rs=$dbObj->fetch()->object();

// // var_dump($rs);

// // echo "Fetch test object:complete"."<br/>";


// echo "/* Test 2 Arity Relational Object*/<br/>";


// echo "/* Test 2 Intitialize User Object*/<br/>";

// $userObj=new User();

// $udbObj=Arity::entity($userObj);

// $udbObj->removeTable();

// $udbObj->createTable();

// echo "/* Test 2 Intitialize Group Object*/<br/>";

// $groupObj= new Group();

// $gdbObj=Arity::entity($groupObj);

// $gdbObj->removeTable();

// $gdbObj->createTable();


// echo "/* Test 2 Intitialize Group Object*/<br/>";

// $grouptypeObj= new Grouptype();

// $gtdbObj=Arity::entity($grouptypeObj);

// $gtdbObj->removeTable();

// $gtdbObj->createTable();


// echo "/* Test 2 Add Group Type */<br/>";

// $grouptypeObj->name="admin";

// echo $type_id=$gtdbObj->save();

// echo "/* Test 2 Add Group Object*/<br/>";

// $groupObj->name="admin";
// $groupObj->type_id=$type_id;

// echo $group_id=$gdbObj->save();


// echo "/* Test 2 Add User Object*/<br/>";

// $userObj->username="sam";
// $userObj->password="sam";
// $userObj->group_id=$group_id;

// $udbObj->save();

// $rs=$gdbObj->fetch(3)->object();

// foreach($rs as $item){
	
// 	var_dump($item);
	
// };


// echo "/* Test 3 Add Multipe Objects*/<br/>";


// $userObj=new User();

// $userObj->username="test";
// $userObj->password="sample";


// $grp=new Group();

// $grp->name=2;
// $grp->type_id=1;

// $userObj->group=$grp;

// $udbObj=Arity::entity($userObj);

// $udbObj->save();


echo "/* Test 4 Update Multipe Objects*/<br/>";


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
