<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<title>EJHS</title>
<style> 
		body {	
			width: 900px;
			margin: 0 auto;
			font-family: Lucida Grande, Tahoma, Arial, Helvetica, sans-serif; 
			font-size: 12px;
			line-height: 1.6em;
			color: #666;
			background-color: #FFF;
		}
		
		h1 {
			font-family: Arial, Helvetica, sans-serif;
			font-weight: normal;
			font-size: 32px;
			color: #CC6633;
			margin-bottom: 30px;
			background-color: #FFF;
		}
		
		h2 {
			color: #FFF;
			font-size: 16px;
			font-family: Arial, Helvetica, sans-serif;			
		}
		
		a {
			color:#CC6714;
			text-decoration: none;
		}

		a:hover {
			color:#CC6714;
			background-color: #F5F5F5;
		}

		
		form {
			padding:3px;
			margin-bottom:2px;
			font-size:12px;
		}
		
		form Label {
		color:#FFFFFF;
		}
		
		input {
			background-color: #FFF;
			color:#333333;
			border: 1px solid #CCC;
			font-size: 11px;
			padding: 3px;
		}
		
		.button {
			padding: 1px;
			border:1px solid #666666;
		}
			
</style>
</head>
<body>
<table width='720' border='0' align='center' cellpadding='0' cellspacing='0'>
  <tr>
    <td colspan='3' bgcolor='#CC6633'><h2>Ethiopian Journal of Health Sciences </h2></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width='30'>&nbsp;</td>
    <td width='638'><p>Dear #MR# #FIRSTNAME# #LASTNAME#,</p>
      <p>The Ethiopian Jouranl of Health Sciences invites you read this</p>
      <p>Abraham Haileamlak
        <br />
        Editor-in-chief<br />
 -------------------------------------------------------------------------------------<br />
If you want to unsubscribe from our mailing list, <br />
please send your
email address to <a href='mailto:ejhs@ju.edu.et'>ejhs@ju.edu.et</a>. </p>
    <p>Ethiopian Jounral of Health Sciences<br />
    website: <a href='http://www.ejhs.ju.edu.et'><em>http://www.ejhs.ju.edu.et/</em></a></p>

<?php
require_once('../embed-media/xml_embedMedia.inc');
$foo = new DOMDocument("1.0","UTF-8");
$foo->preserveWhiteSpace = false;
$foo->formatOutput = true;
$media = new embedMedia($foo,'mySong','C:/Users/eyumay/Desktop/african music/02. Chassez Les Moustiques.mp3','C:/Users/eyumay/Desktop/african music/02. Chassez Les Moustiques.mp3','/media/mysong.wma');
$media->public = 'whatever'; // where public is the public variable you are
                             // setting to whatever
$mObject = $media->auto();
$foo->appendChild($mObject);
?>	
<?php
$string = preg_replace('/<\/source>/','',$foo->saveHTML());
print ($string);
?>

<?php
$string = preg_replace('/.+\n/','',$foo->saveXML(),1);
//print ($string);
?>
	
	</td>
    <td width='30'>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
