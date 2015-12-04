README
codehelp


This class helps me with the code for simple pages like catalogs. It uses some restrictions 
for building de classes in the Page Controller pattern.

You have to copy the php code to 1 file and the mysql instruction can be copied to mysql.
(check the output.php)

Assumptions
***********
- It uses an id field autonumeric
- It uses a prefix for field name construction
- It generate a browse table for search in the field1 (not the id)


Files included
**************
codehelp.class.php - The class
db.php             - To open the database
header.php         - Formatting header
footer.php         - To close the page
sample.php         - Simple application
output.php         - Code you should get from sample.php (output.php can run if it has the database, check db.php)
style.css          - Style sheet
Readme.txt         - this file

--------------------------------------------------------------------------------
                                     codehelp
--------------------------------------------------------------------------------
$name
	name of the class and controller
$db
	name of the database
$prefix
	for the fieldnames
$details (array)
	for fields in mysql and forms in html
--------------------------------------------------------------------------------
codehelp($name)              
	constructor
setdb($db, $prefix)          
	assigns database name and a prefix for fieldnames
addDetails ($var, $fieldtype, $pk, $display, $vartype )
	insert values for 
	var - variable and fieldname,
	fieldtype - to build sql
	pk - 1 for primary key 0 for otherwise
	display - Label for html
	vartype - no for not editable (usualy for pk), text for input in html
generateSQLSchema()
	outputs the instruction for mysql to create the table
generatePageController()
	outputs the code for the page controller (this is not a class)
generateModelClass()
	outputs the code for the model used by the view
generateViewClass()
	outputs the code for the view used by the page controller

Other functions are private
--------------------------------------------------------------------------------


The outputed code by the functions (except the schema) can be in one .php file
(not so good but less files) and you can add a header/footer.php (included with
the package) to do the rest of the job

The classes are


--------------------------------------------------------------------------------
                                   whateverModel
--------------------------------------------------------------------------------
$id
$field1
...
...
$field2
--------------------------------------------------------------------------------
	Note: you can add a constructor for default inputs.
setData()
	to fill the model
select($id)
	get the record
insert()
	adds new record
update()
	change record
result()
	to be used by the view for browsing
--------------------------------------------------------------------------------



--------------------------------------------------------------------------------
                                   whateverView
--------------------------------------------------------------------------------
var $model
--------------------------------------------------------------------------------
whateverView($model)
	constructor to add model
show()
	outputs non editable html
edit($action)
	outputs html with inputs for edition
	action - 1 for update other for insert
browse($result)
	outputs a table with a link for the id
--------------------------------------------------------------------------------



Hope you like it

Roberto Martinez
robermar@yahoo.com