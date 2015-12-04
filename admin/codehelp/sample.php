<?php

include ('codehelp.class.php');

$codehelp = new codehelp('Person');

//set the name of the database and a prefix
$codehelp->setdb('person','p_');

//set the fields, type, if its primary key, the string to display and type of form control
// by now it only uses text
$codehelp->addDetails('id', 'int(6)', 1, 'Id', 'no');
$codehelp->addDetails('name', 'char(8)', 0, 'Name', 'text');
$codehelp->addDetails('addr', 'text', 0, 'Address', 'text');
$codehelp->addDetails('country', 'text', 0, 'Country', 'text');
$codehelp->addDetails('status', 'char(1)', 0, 'Status', 'text');

// generate the code to be used
echo $codehelp->generatePageController();
echo $codehelp->generateModelClass();
echo $codehelp->generateViewClass();

// generate the schema of the database to use in MySQL
echo $codehelp->generateSQLSchema();

