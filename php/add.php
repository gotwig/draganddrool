<?php
require_once 'connect_inc.php';
require_once '../lib/Session.php';

Session::init();

	$actualgrid = $_SESSION['gridid']; // get posted data

	
	$type = $_POST['type']; // get the right type of entry
	
	switch($type){

	case 'text':
    	$sql = "INSERT INTO `gridentries` (`id`, `type`, `content`, `gridtable`, `datarow`, `datacolumn`) VALUES
('', 'text', 'Click, to add your text content', ".$actualgrid.", 1, 1)";
	break;
	
	case 'quote':
    	$sql = "INSERT INTO `gridentries` (`id`, `type`, `content`, `gridtable`, `datarow`, `datacolumn`) VALUES
('', 'quote', 'Click, to enter a quote', ".$actualgrid.", 1, 1)";

	break;

	case 'image':
    	$sql = "INSERT INTO `gridentries` (`id`, `type`, `content`, `gridtable`, `datarow`, `datacolumn`) VALUES
('', 'image', 'http://c.dart-examples.com/_/rsrc/1338345094325/config/customLogo.gif', ".$actualgrid.", 1, 1)";
	    break;
	}
	$t = mysqli_query($link,$sql);

    	if ( !$t ) {
       		die('Fehler beim INSERT: ' . mysqli_error($link)); }

	echo (mysqli_insert_id($link));

/*


try {
	include("connect_inc.php");
	//DON't ever forget validation
	if(empty($_POST['type']) || empty($_POST['id'])) {
			throw new Exception("type and id are required");
	}
	
	$sql = "INSERT INTO `gridentries` (`id`, `type`, `content`, `gridtable`, `datarow`, `datacolumn`) VALUES('', :type, :content, :id, 1, 1)";
	
	switch($_POST['type']) {
		case 'text':
			$content = 'Auswählen, zum Eingeben von neuem Text';
			break;
		case 'quote':
			$content = 'Auswählen, zum Eingeben von neuem Zitat';
			break;
		case 'image':
			$content = 'http://c.dart-examples.com/_/rsrc/1338345094325/config/customLogo.gif';
			break;
		default:
			throw new Exception("type is needed");
	}
	
	$stmt = $db->prepare($sql);
	$stmt->execute(array(':type' => $_POST['type'], ':content' => $content, ':id' => $_POST['id']));
				   
	echo json_encode("OK");
} catch(Exception $e) {
	$message = array('error' => $e->getMessage());
	echo json_encode($message);
}

*/

?>
