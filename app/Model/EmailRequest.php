<?php
App::uses('AppModel', 'Model');
App::import('Model', 'UserData');

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
		$record = $this->_findByPlayerId($pid);
		if (is_null($record)){
			return null;
		}

		return $record['email'];
	}

	/* メールアドレスの検証を行う 成功なら関連レコードの更新を行いtrueを返す */
	public function verify($pid, $key){
		$record = $this->_findByPlayerId($pid);
		if (is_null($record)){
			return false; // Valid revord not found.
		}

		// Failed verify, returns false.
		if ($record['key'] !== $key){
			return false;
		}

		// Update some records.
		$userData = new UserData();
		$userData->read(null, (int) $pid);
		$userData->save(array('email' => $record['email']), true, array('email'));

		$this->delete($record['player_id']); // delete request record.
		
		return true;
	}

	private function _findByPlayerId($pid){
		$pid = (int) $pid;
		$record = $this->findByPlayerId($pid);

		// Pending record not found.
		if (empty($record)){
			return null; 
		}
		$record = $record['EmailRequest'];

		// Record expires check.
		$check = (int) $record['expired'];
		if ($check < time()){
			// Time expired, delete this record and returns null.
			$this->delete($record['player_id']);
			return null;
		}

		return $record;
	}
}
?>