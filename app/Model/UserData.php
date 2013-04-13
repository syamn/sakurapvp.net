<?php
App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');
 
class UserData extends AppModel {
	public $name = 'UserData';
	public $useTable = 'user_data';
	public $primaryKey = 'player_id';

	public function beforeSave($options = array()){
		parent::beforeSave($options);

		// Encrypt password
		if (!empty($this->data['UserData']['password'])){
			$this->data['UserData']['password'] = AuthComponent::password($this->data['UserData']['password']);
		}

		return true;
	}
}
?>