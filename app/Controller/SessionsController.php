<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
App::uses('CakeEmail', 'Network/Email');
 
class SessionsController extends AppController {
	var $uses = array('User', 'UserData', 'RegistKey', 'LoginAttempt');
	var $components = array('Cookie');
	var $expires = "7 days"; // Login key expires

	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->deny('home', 'logout');
	}

	/* **** Login **** */
	public function login(){
		// User already logged in
		if ($this->Auth->loggedIn()){
			$this->Session->setFlash('あなたは既にログインしています！', 'error');
			$this->redirect('/');
			exit;
		}

		// Auto redirect to short url '/login'.
		if (strpos($this->here, '/sessions/login') === 0){ // Java string.startWith($str, $prefix);
			$this->redirect('/login');
		}

		$remain = $this->LoginAttempt->getRemain();
		if ($remain > 0){
			// Login attempt - Use cookie
			if (!is_null($this->Cookie->read('auth.pid')) && !is_null($this->Cookie->read('auth.key'))){
				$pid = (int) $this->Cookie->read('auth.pid');
				$key = $this->Cookie->read('auth.key');

				$userData = $this->UserData->findByPlayerIdAndLoginKey($pid, $key);
				if (!empty($userData) && strtotime($this->expires, (int) $userData['UserData']['lastWebLogin']) > time()){
					// Login successful
					$this->Auth->login($this->User->getUserAuthObject($pid)); // Actually　logged in as $pid user
					$this->_updateLoginKey($pid);

					$this->Session->setFlash('クッキーを使ってログインしました！', 'success');
					$this->redirect($this->Auth->redirect()); // Redirect
				}else{
					// Login failed
					$remain = $this->LoginAttempt->addRecord();
					$this->_deleteLoginKey($pid);
					$this->Session->setFlash('自動ログイン用のクッキーが不正、または期限切れです。手動でログインしてください。', 'default', array(), 'auth');
				}
			}
			// Login attempt - Post from login form
			elseif ($this->request->is('post') && !empty($this->data['User']['player_name']) && !empty($this->data['User']['password'])){
				if ($this->Auth->login()){
					// Login successful
					$pid = $this->Auth->user('player_id');
					$this->_onLoginSuccessful($pid);

					// Check and update login cookie
					if($this->request->data['User']['remember']){
						$this->_updateLoginKey($pid);
					}else{
						$this->_deleteLoginKey($pid);
					}

					$this->Session->setFlash('ログインに成功しました！', 'success');
					$this->redirect($this->Auth->redirect()); // Redirect
				}else{
					// Login failed
					$remain = $this->LoginAttempt->addRecord();
					$this->Session->setFlash('ログインに失敗しました。プレイヤー名またはパスワードをご確認ください。', 'default', array(), 'auth');
				}
			}
			elseif ($this->request->is('post')){
				$this->Session->setFlash('プレイヤー名またはパスワードが入力されていません！', 'default', array(), 'auth');
			}
		}

		// Don't keep password in the input form.
		$this->request->data['User']['password'] = '';

		// Set for view
		$this->set('remain', $remain);
		$this->set('title_for_layout', 'ログイン');
	}
	private function _onLoginSuccessful($pid){
		// Update last login date
		$this->UserData->save(array('UserData' => array('player_id' => $pid, 'lastWebLogin' => time())));
		// Remove login attempts
		$this->LoginAttempt->removeRecord();
	}
	public function _updateLoginKey($pid){
		$key = Security::generateAuthKey(); /*hash('SHA512', uniqid() . mt_rand(0, mt_getrandmax()) . time());*/
		$this->UserData->save(array('UserData' => array('player_id' => $pid, 'login_key' => $key)));
		$this->Cookie->write('auth.pid', $pid, true, $this->expires);
		$this->Cookie->write('auth.key', $key, true, $this->expires);
	}
	public function _deleteLoginKey($pid = null){
		if (!is_null($pid) && $this->UserData->find('count', array('conditions' => array('player_id' => 1, 'login_key !=' => null))) === 1){
			$this->UserData->save(array('UserData' => array('player_id' => $pid, 'login_key' => null)));
		}
		$this->Cookie->delete('auth.pid');
		$this->Cookie->delete('auth.key');
	}

	public function logout() {
		$this->autoRender = false;

		$this->_deleteLoginKey($this->Auth->user('player_id'));
		$this->Session->setFlash('正常にログアウトしました。', 'success');
		$this->redirect($this->Auth->logout());
	}
	
	/* **** Create a new account **** */
	public function make_account($username = null) {
		$this->set('title_for_layout', '新規アカウントの取得');

		// User already logged in
		if ($this->Auth->loggedIn()){
			$this->Session->setFlash('あなたは既にログインしています！', 'error');
			$this->redirect('/');
			return;
		}

		if (empty($username)){
			$this->setAction('_how_to_make');
			return;
		}

		// Get playerID by requested name.
		$pid = $this->User->getUserID($username);
		if(empty($pid) || $pid <= 0){
			$this->Session->setFlash('プレイヤー '.h($username).' が見つかりません！', 'error');
			$this->redirect(array('controller' => 'sessions', 'action' => 'make_account'));
			return;
		}

		// Auth register key.
		$record = $this->RegistKey->find('first', array(
				'conditions' => array(
						'BINARY RegistKey.key = "'.Sanitize::clean($this->params['url']['key'], 'default').'"',
						'RegistKey.player_id' => $pid
					),
				'recursive' => 2
			));
		if (empty($record)){
			$this->Session->setFlash('入力されたプレイヤー名または登録キーが間違っています！', 'error');
			$this->redirect(array('controller' => 'sessions', 'action' => 'make_account'));
			return;
		}
		if (!empty($record['User']['Data'])){
			$this->RegistKey->delete($record['RegistKey']['player_id']); 
			$this->Session->setFlash('あなたは既にアカウントを持っています！', 'error');
			$this->redirect(array('controller' => 'sessions', 'action' => 'login'));
			return;
		}
		if ((int)$record['RegistKey']['expired'] < time()){
			$this->Session->setFlash('この登録キーは有効期限が切れています！ゲーム内から再登録を行ってください！', 'error');
			$this->redirect(array('controller' => 'sessions', 'action' => 'make_account'));
			return;
		}

		// Check is requested?
		if ($this->request->is('post') && !empty($this->data)){
			$message = $this->_regist($record);
			if (empty($message)){
				$this->Session->setFlash('ユーザー登録が完了しました！あなたのアカウントにログインしてください！', 'success');
				$this->redirect(array('controller' => 'sessions', 'action' => 'login'));
				return;
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
		pr($this->data);
		// Check agreed box.
		if (!$this->data['agreed']){
			return 'ユーザー登録を行うためには、利用規約に同意していただく必要があります。';
		}

		// Validate password.
		$pass1 = $this->data['pass1'];
		if (mb_strlen($pass1) < 6) {
			return 'パスワードは6文字以上で入力してください。';
		}
		if (mb_strlen($pass1) > 100){
			return 'パスワードが100文字以下で入力してください。';
		}
		if ($pass1 !== $this->data['pass2']){
			return '確認のパスワードが一致しませんでした。';
		}

		// Validation passed. Update database.
		$pid = $record['User']['player_id'];
		$now = time();

		// Check email.
		$tmp = $this->UserData->findByEmail($record['RegistKey']['email']);
		if(!empty($tmp)){
			return '他のユーザーが既に使用しているメールアドレスです。数時間お待ちいただき、再度ゲーム内から登録してください。';
		}

		$newRecord = array('UserData' => array(
			'player_id' => $pid,
			'password' => $pass1,
			'email' => $record['RegistKey']['email'],
			'regDate' => $now,
			'lastUpdate' => $now
		));
		$this->UserData->create();
		if (!$this->UserData->save($newRecord)){
			return 'ユーザ登録に失敗しました。管理人にご連絡ください。';
		}

		$this->RegistKey->delete($record['RegistKey']['player_id']); // Delete registKey

		// Send welcome mail
		$title = "SakuraPVP ユーザー登録完了のお知らせ";
		$vars = array('name' => $record['User']['player_name'], 'mail' => $record['RegistKey']['email']);

		$mail = new CakeEmail('default');
		$check = $mail
			->template('registerd_mail', 'default')
			->viewVars($vars)
			->to($record['RegistKey']['email'])
			->subject($title)
			->send();

		return null;
	}
	public function _how_to_make(){
		$this->render('how_to_make');
	}

	/* **** Password reset **** */
	public function forgot_password(){
		$this->set('title_for_layout', 'パスワードを忘れた');
	}
}