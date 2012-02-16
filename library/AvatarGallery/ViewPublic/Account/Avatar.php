<?php

class AvatarGallery_ViewPublic_Account_Avatar extends XFCP_AvatarGallery_ViewPublic_Account_Avatar
{

	/**
	 * Override Account_Avatar constructor to use our custom template
	 * 
	 * @param XenForo_ViewRenderer_Abstract $renderer     
	 * @param Zend_Controller_Response_Http $response     
	 * @param array                         $params       
	 * @param string                        $templateName 
	 */
	public function __construct(XenForo_ViewRenderer_Abstract $renderer, Zend_Controller_Response_Http $response, array $params = array(), $templateName = '')
	{
		$this->setParams(array('avatars' => AvatarGallery_AvatarGallery::get_avatars()));
		
		if ($templateName == 'account_avatar')
		{
			$templateName = 'account_avatar_gallery';
		}
		
		return parent::__construct($renderer,$response,$params,$templateName);
	}

	/**
	 * Overlay version
	 */
	public function renderJson()
	{
		$this->_templateName = 'account_avatar_gallery_overlay';
	}

}