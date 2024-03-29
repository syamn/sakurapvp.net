<?php
App::uses('AppModel', 'Model');

class EmailRequest extends AppModel {
	public $name = 'EmailRequest';
	public $useTable = 'email_requests';
	public $primaryKey = 'player_id';
	public $validate = array(
			'player_id' => array(
					'内部エラーです (PID 0)' => array('rule' => array('comparison', '>', 0)),
				),
			'email' => array(
					'有効なメールアドレスを入力してください' => array('rule' => 'email'),
					'100文字以下のメールアドレスしか設定できません' => array('rule' => array('maxLength', 100)),
				),
			'key' => array(
					'確認コードが空です' => array('rule' => 'notEmpty'),
				),
			'expired' => array(
					'有効期限が数値ではありません' => array('rule' => 'numeric'),
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
		$this->UserData = new UserData();
		$this->UserData->read(null, (int) $pid);
		$this->UserData->save(array('email' => $record['email']), true, array('email'));

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