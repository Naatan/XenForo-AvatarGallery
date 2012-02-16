<?php

class AvatarGallery_ControllerPublic_Account extends XFCP_AvatarGallery_ControllerPublic_Account
{
	
	/**
	 * Override avatar upload to support avatar gallery selection
	 */
	public function actionAvatarUpload()
	{

		// Receive input data
		$inputData = $this->_input->filter(array(
			'delete' 		=> XenForo_Input::UINT,
			'avatar_crop_x' => XenForo_Input::UINT,
			'avatar_crop_y' => XenForo_Input::UINT,
			'gravatar' 		=> XenForo_Input::STRING,
			'chosen_avatar' => XenForo_Input::STRING,
			'use_gravatar' 	=> XenForo_Input::UINT
		));

		// No point in overriding the default behaviour if the user didn't use an avatar from the gallery
		if ($inputData['use_gravatar'] == 1 OR empty($inputData['chosen_avatar']))
		{
			return parent::actionAvatarUpload();
		}

		// Locate avatar 
		$options 	= XenForo_Application::get('options');
		$path 		= dirname(__FILE__) . '/../../..' . $options->avatarGalleryPath;
		$avatar		= $path . '/' . $inputData['chosen_avatar'];
		
		// Validate it exists and is readable
		if ( !file_exists($avatar) OR !is_readable($avatar))
		{
			throw new XenForo_Exception(new XenForo_Phrase('uploaded_file_is_not_valid_image'), true);
		}

		// Get image attributes
		list($width, $height, $type, $attr) = getimagesize($avatar);

		// Copy the image to a temp dir, since we're hooking into the avatar upload functionality
		$filename = sys_get_temp_dir() . '/' . uniqid();
		copy($avatar,$filename);

		$visitor = XenForo_Visitor::getInstance();

		// Apply the avatar to the user
		$avatarModel = $this->getModelFromCache('XenForo_Model_Avatar');
		$avatarData  = $avatarModel->applyAvatar($visitor['user_id'], $filename, $type, $width, $height, $visitor->getPermissions());

		// merge new data into $visitor, if there is any
		if (isset($avatarData) && is_array($avatarData))
		{
			foreach ($avatarData AS $key => $val)
			{
				$visitor[$key] = $val;
			}
		}

		$message = new XenForo_Phrase('upload_completed_successfully');

		// return a view if noredirect has been requested and we are not deleting
		if ($this->_noRedirect())
		{
			return $this->responseView(
				'XenForo_ViewPublic_Account_AvatarUpload',
				'account_avatar_upload',
				array(
					'user' 			=> $visitor->toArray(),
					'sizeCode' 		=> 'm',
					'maxWidth' 		=> XenForo_Model_Avatar::getSizeFromCode('m'),
					'maxDimension' 	=> ($visitor['avatar_width'] > $visitor['avatar_height'] ? 'height' : 'width'),
					'width' 		=> $visitor['avatar_width'],
					'height' 		=> $visitor['avatar_height'],
					'cropX' 		=> $visitor['avatar_crop_x'],
					'cropY' 		=> $visitor['avatar_crop_y'],
					'user_id' 		=> $visitor['user_id'],
					'avatar_date' 	=> $visitor['avatar_date'],
					'gravatar' 		=> $visitor['gravatar'],
					'message' 		=> $message
				)
			);
		}
			else
		{
			return $this->responseRedirect(
				XenForo_ControllerResponse_Redirect::SUCCESS,
				XenForo_Link::buildPublicLink('account/personal-details'),
				$message
			);
		}
	}

}