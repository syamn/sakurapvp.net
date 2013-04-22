<?php
App::uses('CakeEmail', 'Network/Email');
App::uses('Validation', 'Utility');

class ApiController extends AppController {
	var $uses = array('ServerData', 'User', 'UserData');
	var $args;

	public function beforeFilter(){
		parent::beforeFilter();

		$this->autoRender = false;
		$this->args = $this->params['url'];

		// Require id / key
		if (empty($this->args['sname']) || empty($this->args['skey'])){
			throw new BadRequestException('Server Name and Private Key are required.');
		}

		// Auth server
		$tmp = $this->ServerData->findByNameAndKey($this->args['sname'], $this->args['skey']);
		if (empty($tmp)){
			throw new BadRequestException('Server authentication failed.');
		}
	}

	/* 新規アカウントの登録用メール送信リクエスト */
	public function new_account() {
		if (!isset($this->args['name']) || !isset($this->args['mail']) || !isset($this->args['regkey'])){
			throw new BadRequestException('Invalid new account request.');
		}

		// Validate email addreess
		if(!Validation::email($this->args['mail'])){
			exit("Error,Invalid email address.");
		}
		
		$title = "Minecraftゲームサーバー SakuraPVPへようこそ！";
		$vars = array(
				'name' => $this->args['name'],
				'url' => 'https://sakurapvp.net/sessions/make_account/'.$this->args['name'].'?key='.$this->args['regkey'],
				'key' => $this->args['regkey'],
			);

		$mail = new CakeEmail('default');
		$check = $mail
			->template('regist_mail', 'default')
			->viewVars($vars)
			->to($this->args['mail'])
			->subject($title)
			->send();

		if($check){
			exit("OK,Success");
		}else{
			exit("Error,An internal error occured.");
		}
	}

	/* パスワードリセットリクエスト */
	public function reset_passwd() {
		if (!isset($this->args['pid']) || !isset($this->args['name'])){
			throw new BadRequestException('Invalid reset password request.');
		}
		$pid = (int) $this->args['pid'];
		$name = $this->args['name'];

		// validate name and pid
		if ($pid !== $this->User->getUserID($name)){
			exit("Error,Authenticate failed for user ".$name.".");
		}

		// generate new password
		$record = $this->UserData->findByPlayerId($pid);
		if (empty($record)){
			exit("Error,unregistered");
		}

		// Generate new password, don't use 'IL1 il O0o' characters
		$strArray = preg_split("//", "abcdefghjkmnpqrstuvwxABCDEFGHJKMNPQRSTUVWXYZ23456789", null, PREG_SPLIT_NO_EMPTY);
		$newPass = '';
		foreach (array_rand($strArray, 10) as $i) { // new password lenght = 10
			$newPass .= $strArray[$i];
		}

		// Update database
		$this->UserData->save(array('UserData' => array(
				'player_id' => $pid,
				'password' => $newPass,
				'lastUpdate' => time()
			)));

		// Send notify email
		try{
			$title = "重要なお知らせ: パスワードリセットリクエストを受け付けました";
			$vars = array(
					'name' => $name,
					'newPass' => $newPass
				);
			$mail = new CakeEmail('default');
			$mail
				->template('reset_passwd', 'default')
				->viewVars($vars)
				->to($record['UserData']['email'])
				->subject($title)
				->send();
		}catch(Exception $ignore){}

		exit("OK,".$newPass);
	}
}