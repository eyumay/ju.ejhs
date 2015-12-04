PDF export
----------

Requirements
------------
1.PHP 5.0 or above version
2.HARU library
	*) http://libharu.org/ (Download the library install it, required the libpng)
	*) http://pecl.php.net/package/haru (Install using 'pecl install haru' provide the path when installation. [--with-zlib-dir=/usr/local --with-png-dir=/usr/local --with-haru=/usr/local])

Installation
------------
1.Place the php files in the web root directory
2.Pass the 4 or 6 parameters. (If no need grouping,then don't pass the last two parameters out of 6)
	*) Associative array of records
	*) Comma separated column headers
	*) Associate array column names
	*) Title of the PDF
	*) Group column header
	*) Group column name on the associative array
3.Browse the exportPDF.php file

Tips
----
1. If you want to use windows server then edit the pdf file path on the exportPDF.class.php file. (Default linux path /tmp)
2. If you want to display any logo/image, provide the path and file on the exportPDF.class.php file.
