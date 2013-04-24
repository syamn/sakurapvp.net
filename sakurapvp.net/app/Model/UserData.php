<?php
App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');
App::uses('Sanitize', 'Utility');
 
class UserData extends AppModel {
	public $name = 'UserData';
	public $useTable = 'user_data';
	public $primaryKey = 'player_id';
	public $validate = array(
			'player_id' => array(
					'内部エラーです (PID 0)' => array('rule' => array('comparison', '>', 0)),
				),
			'email' => array(
					'有効なメールアドレスを入力してください' => array('rule' => 'email'),
					'このメールアドレスは既に使用されています' => array('rule' => 'isUnique'),
					'100文字以下のメールアドレスしか設定できません' => array('rule' => array('maxLength', 100)),
				),
		);

	public function beforeSave($options = array()){
		parent::beforeSave($options);

		// Encrypt password
		if (!empty($this->data['UserData']['password'])){
			$this->data['UserData']['password'] = AuthComponent::password($this->data['UserData']['password']);
		}

		return true;
	}

	/* プレイヤーIDとパスワードから、正当なリクエストか判断する */
	public function isCorrectPassword($pid = null, $password = null){
		if (is_null($pid) || is_null($password)){
			return false;
		}

		$pid = (int) $pid;
		if ($pid <= 0){
			return false;
		}

		$password = AuthComponent::password($password);

		// Search by player_id and encrypted password.
		$result = $this->find('first', array(
				'conditions' => array(
						'player_id' => $pid,
						'BINARY `password` = "'.Sanitize::clean($password, 'default').'"'
					),
				'recursive' => -1
			));

		// Record not found, auth failed
		if (empty($result) || empty($result['UserData'])) {
			return false;
		}
		return true; // Passed.
	}
}
?>