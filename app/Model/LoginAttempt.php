<?php
App::uses('AppModel', 'Model');
 
class LoginAttempt extends AppModel {
	public $name = 'LoginAttempt';
	public $useTable = 'login_attempts';
	public $primaryKey = 'ip';

	var $count = 10; // ロックアウト閾値 少なくとも2回以上で指定すること
	var $expires = "10 hours"; // ロックアウトする時間 (strtotimeを使う)

	/* ログイン試行が可能な残り回数を返す */
	public function getRemain($ip = null){
		if (is_null($ip)) $ip = env('REMOTE_ADDR');
		$record = $this->findByIp($ip);

		if (empty($record)){
			return $this->count; // No record found.
		}

		$expired = $this->_checkExpired($record);
		if ($expired){
			$this->delete($record['LoginAttempt']['ip']);
			return $this->count; // Record removed.
		}

		$now = (int)$record['LoginAttempt']['login_count'];
		return ($this->count - $now);
	}
	/* ログイン失敗の記録を追加する */
	public function addRecord($ip = null){
		if (is_null($ip)) $ip = env('REMOTE_ADDR');
		$record = $this->findByIp($ip);

		if (empty($record)){
			$now = $this->_newRecord($ip);
		}else{
			$now = $this->_addRecord($record);
		}

		return ($this->count - $now);
	}
	/* ログイン試行履歴を削除する */
	public function removeRecord($ip = null){
		if (is_null($ip)) $ip = env('REMOTE_ADDR');
		$record = $this->findByIp($ip);
		if (!empty($record)){
			$this->delete($record['LoginAttempt']['ip']);
		}
	}

	/* 引数のレコードが期限切れかチェックを行う */
	private function _checkExpired($record){
		if (!is_null($record['LoginAttempt']['expiration_time'])){
			$check = (int) $record['LoginAttempt']['expiration_time'];
			return ($check < time());
		}
	}
	/* レコード追加・更新系メソッド */
	private function _newRecord($ip){
		$data = array('LoginAttempt' => array(
				'ip' => $ip,
				'login_count' => 1,
				'create_time' => time()
			));
		$this->create();
		$this->save($data);
		return 1;
	}
	private function _addRecord($record){
		$now = ((int) $record['LoginAttempt']['login_count']) + 1;

		if ($now >= $this->count){
			// Lockout IP address.
			$data = array('LoginAttempt' => array(
				'ip' => $record['LoginAttempt']['ip'],
				'login_count' => $now,
				'expiration_time' => strtotime($this->expires, time())
			));
		}else{
			$data = array('LoginAttempt' => array(
				'ip' => $record['LoginAttempt']['ip'],
				'login_count' => $now
			));
		}

		$this->save($data);
		return $now;
	}
}
?>