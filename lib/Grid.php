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
	
	public static function savePosition()
	{
		global $link;
		
		$boxes = $_POST['data'];
		foreach ($boxes as $box)
		{
			$box = array_map('intval', $box);
			$sql = "UPDATE gridentries SET datarow='{$box['row']}' , datacolumn='{$box['col']}' WHERE id='{$box['id']}'"; // @todo SQL-INYECTION
			mysqli_query($link,$sql);
		
		}
		
		echo $sql;
	}
	
	public static function add()
	{
		global $link;
		
		$t = mysqli_query($link, "INSERT INTO `grid`(`id`, `name`, `ownerid`, `lastchange`) 
				VALUES ('', 'Put new Gridname here', '".$_SESSION['id']."', CURRENT_TIMESTAMP)"); // @todo SQL-INYECTION
		
		if ( !$t )
		{
			die('Error INSERT: ' . mysqli_error($link));
		}
		
		echo mysqli_insert_id($link);
	}
}