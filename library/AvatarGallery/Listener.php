<?php

class AvatarGallery_Listener
{
	
	/**
	 * Singleton class, no constructor
	 */
	private final function __construct() {}

    public static function load_class_controller($class, array &$extend)
    {
		if ($class == 'XenForo_ControllerPublic_Account')
		{
			$extend[] = 'AvatarGallery_ControllerPublic_Account';
		}
    }

    public static function load_class_view($class, array &$extend)
    {
		if ($class == 'XenForo_ViewPublic_Account_Avatar')
		{
			$extend[] = 'AvatarGallery_ViewPublic_Account_Avatar';
		}
    }

}