<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
 
class UserController extends AppController {
	var $uses = array('User', 'UserData', 'RegistKey');
	var $components = array('Session');

	public function make_account($username = null) {
		if (empty($username) || empty($this->params['url']['key'])){
			throw new NotFoundException();
		}

		// Get playerID by requested name.
		$pid = $this->User->getUserID($username);
		if(empty($pid) || $pid <= 0){
			throw new BadRequestException('User '.$username.' is not found!');
		}

		// Auth register key.
		$record = $this->RegistKey->find('first', array(
				'conditions' => array('RegistKey.key' => $this->params['url']['key'], 'RegistKey.player_id' => $pid),
				'recursive' => 2
			));

		if (empty($record)){
			$this->Session->setFlash('不正な登録キーです！', 'error');
			$this->redirect('/');
		}
		if (!empty($record['User']['Data'])){
			$this->Session->setFlash('あなたは既にアカウントを持っています！', 'error');
			$this->redirect('/');
		}

		// Check is requested?
		if (!empty($this->data)){
			$message = $this->_regist($record);
			if (empty($message)){
				$this->Session->setFlash('ユーザー登録を完了しました！', 'success');
				$this->redirect('/');
				exit;
			}else{
				$this->Session->setFlash($message, 'error');
			}
		}

		// Ser variables for View
		$this->set('name', $record['User']['player_name']);
		$this->set('mail', $record['RegistKey']['email']);
		$this->set('key', $record['RegistKey']['key']);
	}

	public function _regist($record){
		$data = $this->data;
		if (empty($data['pass1']) || empty($data['pass1']) || empty($data['pass1'])){
			return '不正なリクエストです。やり直してください。';
		}

		// Validate password
		$pass1 = $data['pass1'];
		$pass2 = $data['pass2'];

		if (mb_strlen($pass1) < 6) {
			return 'パスワードは6文字以上で入力してください';
		}
		if (mb_strlen($pass1) > 100){
			return 'パスワードが100文字以下で入力してください';
		}
		if ($pass1 !== $pass2){
			return '確認のパスワードが一致しませんでした';
		}

		$pid = $record['User']['player_id'];
		$now = time();

		// Registaration
		$data = array('UserData' => array(
			'player_id' => $pid,
			'password' => $pass1,
			'email' => $record['RegistKey']['email'],
			'regdate' => $now,
			'lastUpdate' => $now
		));
		$this->UserData->create();
		if (!$this->UserData->save($data)){
			return 'ユーザ登録に失敗しました。管理人にご連絡ください。';
		}

		$this->RegistKey->delete($record['RegistKey']['data_id']);

		return null;
	}
}