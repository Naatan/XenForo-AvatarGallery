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
			if ( ! is_file($path . '/' . $filename) OR ! in_array(exif_imagetype($path . '/' . $filename), array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG)))
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
		return (is_dir($base . $value) AND is_readable($base . $value));
	}

}