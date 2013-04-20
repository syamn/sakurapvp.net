<?php
App::uses('AppModel', 'Model');

class EmailRequest extends AppModel {
	public $name = 'EmailRequest';
	public $useTable = 'email_requests';
	public $primaryKey = 'player_id';
	public $validate = array(
			'email' => array(
					'emailRule-1' => array(
							'rule' => 'email',
							'message' => '有効なメールアドレスを入力してください'
						)
				),
		);

	public $hasOne = array(
			'User' => array(
					'className' => 'User',
					'foreignKey' => 'player_id'
				),
		);

	/* 変更先のメールアドレスを返す */
	public function getNewEmail($pid){
		$pid = (int) $pid;
		$record = $this->findByPlayerId($pid);

		// Pending record not found.
		if (empty($record)){
			return null; 
		}

		// Record expires check.
		$check = (int) $record['EmailRequest']['expired'];
		if ($check < time()){
			// Time expired, delete this record and returns null.
			$this->delete($record['EmailRequest']['player_id']);
			return null;
		}

		return $record['EmailRequest']['email'];
	}
}
?>