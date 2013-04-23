<?php
App::uses('AppModel', 'Model');
 
class LoginAttempt extends AppModel {
	public $name = 'LoginAttempt';
	public $useTable = 'login_attempts';
	public $primaryKey = 'ip';

	var $count = 10; // ロックアウト閾値 少なくとも2回以上で指定すること
	var $expires = "10 hours"; // ロックアウトする時間 (strtotimeを使う)
	var $resetCycle = "48 minute"; // レコード作成からレコードを削除する時間 (ロックアウトされていればexpiresの時間を優先する)

	/* ログイン試行が可能な残り回数を返す */
	public function getRemain($ip = null){
		if (is_null($ip)) $ip = env('REMOTE_ADDR');
		$record = $this->findByIp($ip);

		if (empty($record)){
			return $this->count; // No record found.
		}
		$record = $record[$this->name];

		// IP Blocked, returns null.
		if (!is_null($record['expiration_time']) && (int) $record['expiration_time'] === 0){
			return null;
		}

		// Check is expired.
		$expired = $this->_checkExpired($record);
		if ($expired){
			$this->delete($record['ip']);
			return $this->count; // Record removed.
		}

		$now = (int)$record['login_count'];
		return ($this->count - $now);
	}
	/* ログイン失敗の記録を追加する */
	public function addRecord($ip = null){
		if (is_null($ip)) $ip = env('REMOTE_ADDR');
		$record = $this->findByIp($ip);

		if (empty($record)){
			$now = $this->_newRecord($ip);
		}else{
			$record = $record[$this->name];
			$now = $this->_addRecord($record);
		}

		return ($this->count - $now);
	}
	/* ログイン試行履歴を削除する */
	public function removeRecord($ip = null){
		if (is_null($ip)) $ip = env('REMOTE_ADDR');
		$record = $this->findByIp($ip);

		if (!empty($record)){
			$this->delete($record[$this->name]['ip']);
		}
	}

	/* 引数のレコードが期限切れかチェックを行う */
	private function _checkExpired($record){
		$check = $record['expiration_time'];
		// Invalid state. Delete that record.
		if (is_null($check) && (int) $record['login_count'] >= $this->count){
			return true;
		}

		// Check IP locked out or not.		
		if (!is_null($check)){
			return ((int) $check < time());
		}

		// Not locked out IP. Reset attempt count every cycle times.
		$check = $record['create_time'];
		if (time() >= strtotime($this->resetCycle, (int) $check)){
			return true;
		}

		return false;			
	}
	/* レコード追加・更新系メソッド */
	private function _newRecord($ip = null){
		if (is_null($ip)) $ip = env('REMOTE_ADDR');

		$data = array(
			'LoginAttempt' => array(
				'ip' => $ip,
				'login_count' => 1,
				'create_time' => time()
			));

		$this->create();
		$this->save($data);
		return 1;
	}
	private function _addRecord($record){
		$now = ((int) $record['login_count']) + 1;

		if ($now >= $this->count){
			// Lockout IP address.
			$data = array('LoginAttempt' => array(
				'ip' => $record['ip'],
				'login_count' => $now,
				'expiration_time' => strtotime($this->expires, time())
			));
		}else{
			$data = array('LoginAttempt' => array(
				'ip' => $record['ip'],
				'login_count' => $now
			));
		}

		$this->save($data);
		return $now;
	}
}
?>