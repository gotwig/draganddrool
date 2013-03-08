<?php 
class Session
{
	const DEFAULT_SESSION_NAME = 'draganddrool';
	const COOKIE_EXPIRED_TIME  = 1209600; // 2 * 7* 24* 60 * 60
	
	public static function init()
	{
		ini_set('session_save_path', '/tmp');

		if(@session_start() == FALSE)
		{
			session_destroy();
			session_start();
		}
		
		session_name(self::DEFAULT_SESSION_NAME);
		session_set_cookie_params(self::COOKIE_EXPIRED_TIME);
	}
}