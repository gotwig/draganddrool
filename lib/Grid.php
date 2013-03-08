<?php 
class Grid
{
	public static function switchAction()
	{
		global $link;
		
		$_SESSION['gridid'] = $_POST['id']; // @todo SQL-INYECTION
		
		mysqli_query($link, 'UPDATE login SET lastusedgrid='.$_SESSION['gridid'].' WHERE id='.$_SESSION['id']);
	}
	
	public static function saveTimestamp()
	{
		global $link;

		mysqli_query($link, 'UPDATE grid SET lastchange="' . date("Y-m-d H:i:s") . '" WHERE id'.$_POST['id']); // @todo SQL-INYECTION
	}
}