<?php 
class Grid
{
	public static function switchAction()
	{
		global $link;
		
		session_start();
		session_name('draganddrool');
		
		$_SESSION['gridid'] = $_POST['id']; // @todo SQL-INYECTION
		
		mysqli_query($link, 'UPDATE login SET lastusedgrid='.$_SESSION['gridid'].' WHERE id='.$_SESSION['id']);
	}
	
}