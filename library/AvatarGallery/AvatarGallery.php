<?php

class AvatarGallery_AvatarGallery
{
	
	/**
	 * Receive array of avatars available
	 * @return array
	 */
	public static function get_avatars() 
	{
		$options 	= XenForo_Application::get('options');
		$path 		= dirname(__FILE__) . '/../..' . $options->avatarGalleryPath;
		$avatars 	= array();
		
		if (!is_dir($path) OR !is_readable($path))
		{
			return $avatars;
		}

		$files = scandir($path);
		foreach ($files AS $filename)
		{
			$ext = strtolower(substr($filename, strrpos($filename, '.')+1));

			if ( ! is_file($path . '/' . $filename) OR ! in_array( $ext, array('jpg', 'jpeg', 'gif', 'png')))
			{
				continue;
			}

			array_push($avatars, $filename);
		}

		return $avatars;
	}

	/**
	 * Validate avatar path option
	 * 
	 * @param  string $value 
	 * 
	 * @return bool
	 */
	public static function validate_avatar_path($value)
	{
		$base = dirname(__FILE__) . '/../..';

		if ( ! is_dir($base . $value) AND ! mkdir($base . $value))
		{
			return false;
		}

		return (is_readable($base . $value));
	}

}