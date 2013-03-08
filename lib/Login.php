<?php
class Login
{
	public static function isLogged()
	{
		return isset($_SESSION['id']);
	}
	
	public static function checkLoginRedirect()
	{
		if(!self::isLogged())
		{
			header('Location: index.php');
			exit();
		}
	}
}