<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
App::uses('CakeEmail', 'Network/Email');
 
class UserController extends AppController {
	var $uses = array('User', 'UserData', 'RegistKey', 'LoginAttempt', 'EmailRequest');
	var $components = array('Cookie', 'Common');
	var $newEmailExpires = "24 hours"; // Login key expires

	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->deny();
	}

	public function index(){
		$this->autoRender = false;
		$this->redirect(array('controller' => 'user', 'action' => 'home'));
	}

	public function home(){
		$this->set('title_for_layout', 'ユーザーホーム');
	}

	/* Update user data */
	public function edit($page = null){
		$this->set('user', $this->Auth->user());

		switch ($page){
			case 'base': $this->_editData(); break;
			case 'profile': $this->_editProfile(); break;
			// Redirect to base edit page when missing page args.
			case null: $this->redirect(array('controller' => 'user', 'action' => 'edit', 'base')); break;
			default: throw new NotFoundException(); break;
		}
	}
	public function _editData(){
		// new pending email address
		$this->set('pendingEmail', $this->EmailRequest->getNewEmail($this->Auth->user('player_id')));

		// Get update request
		if ($this->request->is('post')){
			$this->UserData->set($this->data);

			// check new password
			if (!empty($this->data['UserData']['pass1'])){
				$newPass = $this->data['UserData']['pass1'];
				if (mb_strlen($newPass) < 6 || mb_strlen($newPass) > 100){
					$this->UserData->invalidate('pass1', 'パスワードは6～100文字で入力してください！');
				}
				elseif ($this->data['UserData']['pass1'] !== $this->data['UserData']['pass2']){
					$this->UserData->invalidate('pass2', '新しいパスワードが一致していません！');
				}
			}

			// check current password
			if (empty($this->data['UserData']['currentPass'])){
				$this->UserData->invalidate('currentPass', '現在のパスワードを入力してください！');
			}
			else if (!$this->UserData->isCorrectPassword($this->Auth->user('player_id'), $this->data['UserData']['currentPass'])){
				$this->UserData->invalidate('currentPass', '現在のパスワードが違います！');
			}
			
			if ($this->UserData->validates()){ // includes email check (format and unique rules)
				$this->UserData->read(null, $this->Auth->user('player_id'));
				$this->UserData->set('password', (empty($newPass)) ? $this->data['UserData']['currentPass'] : $newPass);
				$this->UserData->save();

				$newEmail = $this->data['UserData']['email'];
				if (!empty($newEmail) && $this->Auth->user('Data.email') !== $newEmail){
					$this->_updateEmail($newEmail);
					$this->Session->setFlash('ユーザー情報を更新しました。新しいメールアドレスにメールを送信しましたので、メールアドレスの確認手続きを行ってください。', 'success');
				}else{
					$this->Session->setFlash('ユーザー情報を更新しました！', 'success');
				}
				$this->redirect($this->here);
			}
		}

		// Don't keep password in the input form for security reasons.
		$this->request->data['UserData']['pass1'] = '';
		$this->request->data['UserData']['pass2'] = '';
		$this->request->data['UserData']['currentPass'] = '';

		$this->set('title_for_layout', 'ユーザー基本情報編集');
		$this->render('edit_data');
	}
	public function _editProfile(){
		$this->set('title_for_layout', 'プロフィール編集');
		$this->render('edit_profile');
	}

	/* Email confirmation */
	private function _updateEmail($email){
		$key = $this->Common->getRandomString(6);

		// Update database.
		$newRaw = array('EmailRequest' => array(
				'player_id' => $this->Auth->user('player_id'),
				'email' => $email,
				'key' => $key,
				'expired' => strtotime($this->newEmailExpires)
			));

		$this->EmailRequest->create();
		$this->EmailRequest->save($newRaw);

		// Send confirm email.
		$title = "SakuraPVP 新しいメールアドレスのご確認";
		$vars = array(
				'name' => $this->Auth->user('player_name'), 
				'newMail' => $email,
				'url' => 'https://sakurapvp.net/user/confirm_email/?key='.$key,
				'key' => $key,
				'ip' => env('REMOTE_ADDR')
			);

		$mail = new CakeEmail('default');
		$check = $mail
			->template('new_email', 'default')
			->viewVars($vars)
			->to($email)
			->subject($title)
			->send();
	}
	public function confirm_email() {
		$email = $this->EmailRequest->getNewEmail($this->Auth->user('player_id'));
		if (is_null($email)){
			$this->Session->setFlash('確認が必要なメールアドレスが無いか、確認コードの有効期限切れです。再度変更手続きを行ってください。', 'error');
			$this->redirect(array('controller' => 'user', 'action' => 'edit', 'base'));
			return;
		}

		// Verify
		if ($this->request->is('post') && !empty($this->data['key'])){
			$result = $this->EmailRequest->verify($this->Auth->user('player_id'), $this->data['key']);
			if ($result){
				$this->Session->write('Auth.User.Data.email', $email); // Update auth object
				$this->Session->setFlash('登録メールアドレスは正常に変更されました！', 'success');
				$this->redirect(array('controller' => 'user', 'action' => 'edit', 'base'));
				return;
			}else{
				$this->Session->setFlash('確認コードが間違っています。再度入力してください。', 'error');
			}
		}

		$this->set('title_for_layout', 'メールアドレスの確認');
		$this->set('name', $this->Auth->user('player_name'));
		$this->set('currentEmail', $this->Auth->user('Data.email'));
		$this->set('email', $email);
		$this->set('key', (empty($this->params['url']['key'])) ? '' : $this->params['url']['key']);
	}
}