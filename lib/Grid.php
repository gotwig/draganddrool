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
	
	public static function addUser()
	{
		global $link;
		
		// @todo SQL-INYECTION
		$sql1 = "INSERT INTO `login`(`id`, `name`, `pwd`, `email`, `ban`, `lastusedgrid`) VALUES ('','".$_POST['new_username']."','".md5($_POST['new_password'])."','".$_POST['new_email']."', '', NULL)";
		
		
		mysqli_query($link,$sql1);
		
		$sql2 =	"INSERT INTO `grid`(`id`, `name`, `ownerid`, `lastchange`) VALUES ('', 'Put new Gridname here', '".mysqli_insert_id($link)."', CURRENT_TIMESTAMP)";
		
		
		$account_id = mysqli_insert_id($link);
		
		mysqli_query($link,$sql2);
		
		$sql3 = 'UPDATE login SET lastusedgrid="' . mysqli_insert_id($link) .'" WHERE id='.$account_id ;
		
		$_SESSION['gridid'] = mysqli_insert_id($link);
		
		mysqli_query($link,$sql3);
		
		
		$_SESSION['id'] = $account_id;
		$_SESSION['usr'] = $_POST['new_username'];
		
		
		header("Location: ../project.php");
	}
	
	public static function remove()
	{
		// @todo SQL-INYECTION
		
		$id = $_POST['id'];
		$grid = $_SESSION['gridid'];

		$sql1 = "DELETE FROM `gridentries` WHERE `gridtable`=".$id.";";
		
		if($id==$grid){
			$sql1_2 = 'UPDATE login SET lastusedid=null WHERE id='.$_POST['id'];
			$t = mysqli_query($link,$sql1_2);
		}
		
		$sql2 .= "DELETE FROM `grid` WHERE `id`=".$id.";";
		
		$t = mysqli_query($link,$sql1);
		if ( !$t ) {
			die('Error DELETE: ' . mysqli_error());
		}
		
		$t = mysqli_query($link,$sql2);
		if ( !$t ) {
			die('Error DELETE: ' . mysqli_error());
		}
		
		echo '0';
	}
}