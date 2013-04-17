<?php
App::uses('CakeEmail', 'Network/Email');
App::uses('Validation', 'Utility');

class ApiController extends AppController {
	var $uses = array('ServerData');
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

	public function new_account() {
		if (empty($this->args['name']) || empty($this->args['mail']) || empty($this->args['regkey'])){
			throw new BadRequestException('Invalid new account request.');
		}

		// Validate email addreess
		if(!Validation::email($this->args['mail'])){
			exit("Error, Invalid email address.");
		}
		
		$title = "Minecraftゲームサーバー SakuraPVPへようこそ！";
		$vars = array(
				'name' => $this->args['name'],
				'url' => 'http://sakurapvp.net/user/make_account/'.$this->args['name'].'?key='.$this->args['regkey'],
			);

		$mail = new CakeEmail('default');
		$check = $mail
			->template('regist_mail', 'default')
			->viewVars($vars)
			->to($this->args['mail'])
			->subject($title)
			->send();

		if($check){
			exit("OK, Success");
		}else{
			exit("Error, An internal error occured.");
		}
	}
}